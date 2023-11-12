<?php

namespace App\Http\Integrations\Wonde\Requests;

use Saloon\Enums\Method;
use Saloon\Http\Request;

class GetSchoolEmployeesRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        protected string $school_id,
        protected int $page = 1
    ) {
    }

    public function resolveEndpoint(): string
    {
        return "/schools/{$this->school_id}/employees";
    }

    protected function defaultQuery(): array
    {
        return [
            'page' => $this->page,
        ];
    }
}
