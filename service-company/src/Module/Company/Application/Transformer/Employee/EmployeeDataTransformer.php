<?php

declare(strict_types=1);

namespace App\Module\Company\Application\Transformer\Employee;

use App\Module\Company\Domain\Entity\Address;
use App\Module\Company\Domain\Entity\Company;
use App\Module\Company\Domain\Entity\Contact;
use App\Module\Company\Domain\Entity\Employee;
use App\Module\Company\Domain\Entity\Role;

class EmployeeDataTransformer
{
    public function transformToArray(Employee $employee, array $includes = []): array
    {
        $data = [
            Employee::COLUMN_UUID => $employee->getUUID()->toString(),
            Employee::COLUMN_FIRST_NAME => $employee->getFirstName(),
            Employee::COLUMN_LAST_NAME => $employee->getLastName(),
            Employee::COLUMN_CREATED_AT => $employee->getCreatedAt()?->format('Y-m-d H:i:s'),
            Employee::COLUMN_UPDATED_AT => $employee->getUpdatedAt()?->format('Y-m-d H:i:s'),
            Employee::COLUMN_DELETED_AT => $employee->getDeletedAt()?->format('Y-m-d H:i:s'),
        ];

        foreach ($includes as $relation) {
            if (in_array($relation, Employee::getRelations(), true)) {
                $data[$relation] = $this->transformRelation($employee, $relation);
            }
        }

        return $data;
    }

    private function transformRelation(Employee $employee, string $relation): ?array
    {
        return match ($relation) {
            Employee::RELATION_COMPANY => $this->transformCompany($employee->getCompany()),
            Employee::RELATION_ADDRESS => $this->transformAddress($employee->getAddress()),
            Employee::RELATION_CONTACTS => $this->transformContacts($employee->getContacts()),
            Employee::RELATION_ROLE => $this->transformRole($employee->getRole()),
            default => null,
        };
    }

    private function transformCompany(Company $company): ?array
    {
        return [
            Company::COLUMN_UUID => $company->getUUID()->toString(),
            Company::COLUMN_NAME => $company->getName(),
            Company::COLUMN_NIP => $company->getNIP(),
        ];
    }

    private function transformAddress($address): ?array
    {
        if ($address === null) {
            return null;
        }

        return [
            Address::COLUMN_STREET   => $address->getStreet(),
            Address::COLUMN_POSTCODE => $address->getPostcode(),
            Address::COLUMN_CITY     => $address->getCity(),
            Address::COLUMN_COUNTRY  => $address->getCountry(),
        ];
    }

    private function transformContacts($contacts): ?array
    {
        if ($contacts === null || $contacts->isEmpty()) {
            return null;
        }

        return array_map(
            fn(Contact $contact) => [
                Contact::COLUMN_TYPE => $contact->getType(),
                Contact::COLUMN_DATA => $contact->getData(),
            ],
            $contacts->toArray()
        );
    }

    private function transformRole(Role $role): ?array
    {
        return [
            Role::COLUMN_UUID => $role->getUUID()->toString(),
            Role::COLUMN_NAME => $role->getName(),
            Role::COLUMN_DESCRIPTION => $role->getDescription(),
        ];
    }
}
