<?php

declare(strict_types=1);

namespace App\Module\Company\Application\Transformer\Company;

use App\Module\Company\Domain\Entity\Address;
use App\Module\Company\Domain\Entity\Company;
use App\Module\Company\Domain\Entity\Contact;
use App\Module\Company\Domain\Entity\Employee;

class CompanyDataTransformer
{
    public function transformToArray(Company $company, array $includes = []): array
    {
        $data = [
            Company::COLUMN_UUID       => $company->getUUID()->toString(),
            Company::COLUMN_NAME       => $company->getName(),
            Company::COLUMN_NIP        => $company->getNIP(),
            Company::COLUMN_CREATED_AT => $company->getCreatedAt()?->format('Y-m-d H:i:s'),
            Company::COLUMN_UPDATED_AT => $company->getUpdatedAt()?->format('Y-m-d H:i:s'),
            Company::COLUMN_DELETED_AT => $company->getDeletedAt()?->format('Y-m-d H:i:s'),
        ];

        foreach ($includes as $relation) {
            if (in_array($relation, Company::getRelations(), true)) {
                $data[$relation] = $this->transformRelation($company, $relation);
            }
        }

        return $data;
    }

    private function transformRelation(Company $company, string $relation): ?array
    {
        return match ($relation) {
            Company::RELATION_EMPLOYEES => $this->transformEmployees($company->getEmployees()),
            Company::RELATION_ADDRESS => $this->transformAddress($company->getAddress()),
            Company::RELATION_CONTACTS => $this->transformContacts($company->getContacts()),
            default => null,
        };
    }

    private function transformEmployees($employees): ?array
    {
        if ($employees === null || $employees->isEmpty()) {
            return null;
        }

        return array_map(
            fn(Employee $employee) => [
                Employee::COLUMN_UUID       => $employee->getUUID()->toString(),
                Employee::COLUMN_FIRST_NAME => $employee->getFirstName(),
                Employee::COLUMN_LAST_NAME  => $employee->getLastName(),
            ],
            $employees->toArray()
        );
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
}
