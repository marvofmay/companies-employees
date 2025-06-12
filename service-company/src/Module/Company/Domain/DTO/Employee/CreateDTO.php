<?php

declare(strict_types=1);

namespace App\Module\Company\Domain\DTO\Employee;

use App\Common\Domain\DTO\AddressDTO;
use App\Common\Validator\Constraints\MinMaxLength;
use App\Common\Validator\Constraints\NotBlank;
use App\Module\Company\Structure\Validator\Constraints\Role\ExistingRoleUUID;
use Symfony\Component\Validator\Constraints as Assert;

class CreateDTO
{
    #[NotBlank(message: [
        'text'   => 'company.uuid.required',
        'domain' => 'companies',
    ])]
    #[Assert\Uuid(message: 'uuid.invalid.company')]
    public string $companyUUID;

    #[Assert\Uuid(message: 'uuid.invalid.role')]
    #[NotBlank(message: [
        'text'   => 'role.uuid.required',
        'domain' => 'roles',
    ])]
    public string $roleUUID;

    #[NotBlank(message: [
        'text'   => 'employee.email.required',
        'domain' => 'employees',
    ])]
    #[Assert\Email(message: 'email.invalid')]
    public string $email;

    #[NotBlank(message: [
        'text'   => 'employee.firstName.required',
        'domain' => 'employees',
    ])]
    #[MinMaxLength(min: 3, max: 50, message: [
        'tooShort' => 'employee.firstName.minimumLength',
        'tooLong'  => 'employee.firstName.maximumLength',
        'domain'   => 'employees',
    ])]
    public string $firstName;

    #[NotBlank(message: [
        'text'   => 'employee.lastName.required',
        'domain' => 'employees',
    ])]
    #[MinMaxLength(min: 3, max: 50, message: [
        'tooShort' => 'employee.lastName.minimumLength',
        'tooLong'  => 'employee.lastName.maximumLength',
        'domain'   => 'employees',
    ])]
    public string $lastName;

    #[Assert\All([
        new Assert\Type(type: 'string'),
    ])]
    #[Assert\Type('array')]
    #[Assert\Count(
        min: 0,
        max: 3,
        minMessage: 'phones.min',
        maxMessage: 'phones.max'
    )]
    public ?array $phones = [];

    #[Assert\Valid]
    public ?AddressDTO $address = null;

    public function getCompanyUUID(): ?string
    {
        return $this->companyUUID;
    }

    public function getRoleUUID(): ?string
    {
        return $this->roleUUID;
    }


    public function getEmail(): string
    {
        return $this->email;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function getPhones(): ?array
    {
        return $this->phones;
    }

    public function getAddress(): ?AddressDTO
    {
        return $this->address;
    }
}
