<?php

declare(strict_types=1);

namespace App\Module\Company\Application\Command\Employee;

use App\Common\Domain\DTO\AddressDTO;
use App\Common\Domain\Interface\CommandInterface;
use App\Module\Company\Domain\Entity\Employee;

class UpdateEmployeeCommand implements CommandInterface
{
    public function __construct(
        public Employee $employee,
        public string $companyUUID,
        public string $roleUUID,
        public string $firstName,
        public string $lastName,
        public string $email,
        public ?array $phones,
        public ?AddressDTO $address,
    ) {}
}
