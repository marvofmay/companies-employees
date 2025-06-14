<?php

declare(strict_types=1);

namespace App\Module\Company\Domain\Interface\Employee;

use App\Module\Company\Domain\Entity\Employee;

interface EmployeeWriterInterface
{
    public function saveEmployeeInDB(Employee $employee): void;
    public function deleteEmployeeInDB(Employee $employee): void;
}
