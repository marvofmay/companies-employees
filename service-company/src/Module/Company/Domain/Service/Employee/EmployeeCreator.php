<?php

declare(strict_types=1);

namespace App\Module\Company\Domain\Service\Employee;

use App\Common\Domain\DTO\AddressDTO;
use App\Common\Domain\Interface\CommandInterface;
use App\Module\Company\Application\Command\Employee\CreateEmployeeCommand;
use App\Module\Company\Domain\Entity\Address;
use App\Module\Company\Domain\Entity\Company;
use App\Module\Company\Domain\Entity\Contact;
use App\Module\Company\Domain\Entity\Employee;
use App\Module\Company\Domain\Entity\Role;
use App\Module\Company\Domain\Entity\User;
use App\Module\Company\Domain\Enum\ContactTypeEnum;
use App\Module\Company\Domain\Interface\Company\CompanyReaderInterface;
use App\Module\Company\Domain\Interface\Employee\EmployeeReaderInterface;
use App\Module\Company\Domain\Interface\Employee\EmployeeWriterInterface;
use App\Module\Company\Domain\Interface\Role\RoleReaderInterface;
use App\Module\Company\Domain\Interface\User\UserWriterInterface;
use App\Module\Company\Domain\Service\User\UserFactory;
use Doctrine\Common\Collections\ArrayCollection;

class EmployeeCreator
{
    protected ArrayCollection $contacts;

    public function __construct(
        protected Company                 $company,
        protected Employee                $employee,
        protected Role                    $role,
        protected User                    $user,
        protected Address                 $address,
        protected EmployeeWriterInterface $employeeWriterRepository,
        protected CompanyReaderInterface  $companyReaderRepository,
        protected EmployeeReaderInterface $employeeReaderRepository,
        protected RoleReaderInterface     $roleReaderRepository,
        protected UserWriterInterface     $userWriterRepository,
        protected UserFactory             $userFactory,
    )
    {
        $this->contacts = new ArrayCollection();
    }

    public function create(CreateEmployeeCommand $command): void
    {
        $this->setEmployee($command);
        $this->employeeWriterRepository->saveEmployeeInDB($this->employee);
    }

    protected function setEmployee(CommandInterface $command): void
    {
        $this->setCompany($command->companyUUID);
        $this->setRole($command->roleUUID);
        if (null !== $command->address) {
            $this->setAddress($command->address);
        }
        $this->setContacts($command->phones, [$command->email]);
        $this->setUser($command->email, $command->firstName);

        $this->setEmployeeMainData($command);
        $this->setEmployeeRelations($command);
    }

    protected function setEmployeeMainData(CommandInterface $command): void
    {
        $this->employee->setFirstName($command->firstName);
        $this->employee->setLastName($command->lastName);
    }

    protected function setEmployeeRelations(CommandInterface $command): void
    {
        $this->employee->setCompany($this->company);
        $this->employee->setRole($this->role);
        $this->employee->setUser($this->user);

        foreach ($this->contacts as $contact) {
            $this->employee->addContact($contact);
        }

        if (null !== $command->address) {
            $this->employee->setAddress($this->address);
        }
    }

    protected function setCompany(string $companyUUID): void
    {
        $this->company = $this->companyReaderRepository->getCompanyByUUID($companyUUID);
    }

    protected function setRole(string $roleUUID): void
    {
        $this->role = $this->roleReaderRepository->getRoleByUUID($roleUUID);
    }

    protected function setContacts(array $phones, array $emails = []): void
    {
        $dataSets = [
            ContactTypeEnum::PHONE->value => $phones,
            ContactTypeEnum::EMAIL->value => $emails,
        ];

        foreach ($dataSets as $type => $values) {
            foreach ($values as $value) {
                $contact = new Contact();
                $contact->setType($type);
                $contact->setData($value);

                $this->contacts[] = $contact;
            }
        }
    }

    protected function setAddress(AddressDTO $addressDTO): void
    {
        $this->address->setStreet($addressDTO->street);
        $this->address->setPostcode($addressDTO->postcode);
        $this->address->setCity($addressDTO->city);
        $this->address->setCountry($addressDTO->country);
    }

    protected function setUser(string $email, string $firstName): void
    {
        $password = sprintf('%s-%s', $email, $firstName);
        $user = $this->userFactory->create($email, $password);
        $this->user = $user;
    }
}
