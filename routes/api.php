<?php

use Illuminate\Support\Facades\Route;

Route::controller(App\Http\Controllers\HomeController::class)->group(function () {
    Route::get('school', 'getSchool');
    Route::get('school/teachers', 'getSchoolTeachers');
    Route::get('school/teachers/{id}', 'getSchoolTeacherProfile');
});
