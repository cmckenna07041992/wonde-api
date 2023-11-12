<?php

namespace App\DTOs;

class PaginationDTO
{
    public function __construct(
        public int $currentPage,
        public ?int $next,
        public ?int $previous,
        public int $perPage
    ) {
    }
}
