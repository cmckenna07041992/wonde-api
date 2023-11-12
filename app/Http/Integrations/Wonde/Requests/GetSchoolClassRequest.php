<?php

namespace App\Http\Integrations\Wonde\Requests;

use Saloon\Enums\Method;
use Saloon\Http\Request;

class GetSchoolClassRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        protected string $school_id,
        protected string $class_id
    ) {
    }

    public function resolveEndpoint(): string
    {
        return "/schools/{$this->school_id}/classes/{$this->class_id}";
    }
}
