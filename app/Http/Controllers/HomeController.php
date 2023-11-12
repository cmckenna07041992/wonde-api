<?php

namespace App\Http\Controllers;

use App\DTOs\PaginationDTO;
use App\Http\Integrations\Wonde\Requests\GetSchoolClassesRequest;
use App\Http\Integrations\Wonde\Requests\GetSchoolClassRequest;
use App\Http\Integrations\Wonde\Requests\GetSchoolCountsRequest;
use App\Http\Integrations\Wonde\Requests\GetSchoolEmployeeRequest;
use App\Http\Integrations\Wonde\Requests\GetSchoolEmployeesRequest;
use App\Http\Integrations\Wonde\Requests\GetSchoolLessonsRequest;
use App\Http\Integrations\Wonde\Requests\GetSchoolRequest;
use App\Http\Integrations\Wonde\WondeConnector;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class HomeController extends Controller
{
    private const SCHOOL_ID = 'A1930499544';

    public function __construct(private WondeConnector $wonde_connector)
    {
    }

    public function getSchool()
    {
        $school = $this->wonde_connector->send(
            new GetSchoolRequest(self::SCHOOL_ID)
        );
        $counts = $this->getSchoolCount();

        return response([
            'school' => $school->object()->data,
            'counts' => $counts->object()->data,
            'cached' => [
                'school' => $school->isCached(),
                'counts' => $counts->isCached(),
            ],
        ]);
    }

    private function getSchoolCount(?string $types = 'students,employees,classes,lessons')
    {
        $request = new GetSchoolCountsRequest(self::SCHOOL_ID);
        $request->query()->add('include', $types);
        $response = $this->wonde_connector->send($request);

        return $response;
    }

    public function getSchoolTeachers(Request $incomingRequest)
    {
        $page = $incomingRequest->query('page', 1);
        $request = new GetSchoolEmployeesRequest(self::SCHOOL_ID, $page);
        $request->query()->add('include', 'classes');
        $request->query()->add('has_class', 'true');
        $response = $this->wonde_connector->send($request);

        $teachers = $response->object()->data;
        foreach ($teachers as $teacher) {
            $teacher->classes_count = collect($teacher->classes->data)->count();
        }

        return response([
            'teachers' => $teachers,
            'pagination' => $this->buildPagination(
                $response->object()->meta->pagination
            ),
        ]);
    }

    public function getSchoolTeacherProfile(string $id, Request $incomingRequest)
    {
        $date = $incomingRequest->query('date', null);
        $request = new GetSchoolEmployeeRequest(self::SCHOOL_ID, $id);
        $request->query()->add('include', 'classes');
        $response = $this->wonde_connector->send($request);

        $teacher = $response->object()->data;
        $currentClasses = $teacher->classes->data ?? [];
        $teacher->classes = null;
        $allLessons = [];
        foreach ($currentClasses as $class) {
            $loadClass = self::getSchoolClass($class->id);
            if ($loadClass->lessons !== null) {
                foreach ($loadClass->lessons as $lesson) {
                    $lesson->students = $loadClass->students ?? [];
                    $lesson->student_count = count($lesson->students);
                    $lesson->start_at_date = Carbon::parse($lesson->start_at->date)->format('d/m/Y');
                    $lesson->time_from_to = Carbon::parse($lesson->start_at->date)->format('H:i').' - '.Carbon::parse($lesson->end_at->date)->format('H:i');
                    $lesson->end_at_date = Carbon::parse($lesson->end_at->date)->format('d/m/Y');
                    $lesson->class_name = $loadClass->name;
                    $lesson->class_subject = $loadClass->subject;
                    $lesson->class_description = $loadClass->description;
                    $allLessons[] = $lesson;
                }
            }
        }

        return response([
            'teacher' => $teacher,
            'lessons' => $allLessons,
        ]);
    }

    // start of allowing the teach to filter by date
    // public function getSchoolTeacherProfile(string $id, Request $incomingRequest)
    // {
    //     $date = $incomingRequest->query('date', null);
    //     $request = new GetSchoolEmployeeRequest(self::SCHOOL_ID, $id);
    //     $response = $this->wonde_connector->send($request);

    //     $teacher = $response->object()->data;
    //     $page = 1;
    //     $allLessons = collect();
    //     while ($page !== null && $page < 15) {
    //         $lessonResponse = self::getLessonsForDate($date, $page);
    //         $allLessons = $allLessons->merge($lessonResponse->data);
    //         $page = $lessonResponse->meta->pagination->more ? $page + 1 : null;
    //     }
    //     $teacher->lessons = collect($allLessons)
    //     ->filter(function ($lesson) use ($id) {
    //         if ($lesson->employee === null) {
    //             return false;
    //         }
    //         return $lesson->employee->data->id === $id;
    //     })->map(function ($lesson) {
    //         $class = self::getSchoolClass($lesson->class->data->id);
    //         $lesson->students = $class->students ?? [];
    //         return $lesson;
    //     });

    //     return response([
    //         'teacher' => $teacher
    //     ]);
    // }

    // private function getLessonsForDate(?string $date = null, int $page = 1)
    // {
    //     $request = new GetSchoolLessonsRequest(self::SCHOOL_ID);
    //     $to = '2023-11-10 23:59:59';//Carbon::now()->format('Y-m-d 23:59:59');
    //     $from = '2023-11-10 00:00:00'; //Carbon::now()->format('Y-m-d 00:00:00');
    //     if ($date) {
    //         $to = Carbon::parse($date)->format('Y-m-d 23:59:59');
    //         $from = Carbon::parse($date)->format('Y-m-d 00:00:00');
    //     }
    //     $request->query()->add('per_page', '200');
    //     $request->query()->add('employee_id', 'A921160679');
    //     $request->query()->add('include', 'class,employee');
    //     $request->query()->add('lessons_start_after', $from);
    //     $request->query()->add('lessons_start_before', $to);
    //     $response = $this->wonde_connector->send($request);
    //     return $response->object();
    // }

    public function getSchoolClasses()
    {
        $response = $this->wonde_connector->send(
            new GetSchoolClassesRequest(self::SCHOOL_ID)
        );

        return $response->object()->data;
    }

    public function getSchoolClass(string $class_id)
    {
        $classRequest = new GetSchoolClassRequest(self::SCHOOL_ID, $class_id);
        $classRequest->query()->add('include', 'students,lessons');
        $response = $this->wonde_connector->send($classRequest);
        $class = $response->object()->data;
        $class->students = collect($class->students->data)
            ->map(function ($student) {
                return $student;
            });
        $class->lessons = collect($class->lessons->data);

        return $class;
    }

    private function buildPagination(object $meta): PaginationDTO
    {
        $currentPage = (int) $meta->current_page;
        $more = (bool) $meta->more;

        return new PaginationDTO(
            $currentPage,
            $more ? $currentPage + 1 : null,
            $currentPage > 1 ? $currentPage - 1 : null,
            $meta->per_page
        );
    }
}
