<?php

declare(strict_types=1);

namespace App\Module\Company\Application\Query\Employee;

final readonly class GetEmployeeByUUIDQuery
{
    public function __construct(public string $uuid)
    {
    }
}
