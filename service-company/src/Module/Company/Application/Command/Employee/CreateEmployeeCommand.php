<?php

declare(strict_types=1);

namespace App\Module\Company\Application\Command\Employee;

use App\Common\Domain\DTO\AddressDTO;
use App\Common\Domain\Interface\CommandInterface;

class CreateEmployeeCommand implements CommandInterface
{
    public function __construct(
        public string $companyUUID,
        public string $roleUUID,
        public string $firstName,
        public string $lastName,
        public string $email,
        public ?array $phones,
        public ?AddressDTO $address,
    ) {}
}
