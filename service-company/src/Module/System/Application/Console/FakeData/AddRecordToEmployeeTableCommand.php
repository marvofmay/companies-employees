<?php

declare(strict_types=1);

namespace App\Module\System\Application\Console\FakeData;

use App\Module\Company\Domain\Entity\Employee;
use App\Module\Company\Domain\Enum\ContactTypeEnum;
use App\Module\Company\Domain\Interface\Company\CompanyReaderInterface;
use App\Module\Company\Domain\Interface\Role\RoleReaderInterface;
use App\Module\Company\Domain\Interface\User\UserReaderInterface;
use App\Module\Company\Domain\Service\User\UserFactory;
use App\Module\System\Application\Console\FakeData\Data\Employee as EmployeeFakeData;
use App\Module\Company\Domain\Entity\Address;
use App\Module\Company\Domain\Entity\Contact;
use App\Module\System\Domain\Entity\RoleAccess;
use App\Module\System\Domain\Entity\RoleAccessPermission;
use App\Module\System\Domain\Interface\Access\AccessReaderInterface;
use App\Module\System\Domain\Interface\Permission\PermissionReaderInterface;
use App\Module\System\Domain\Interface\RoleAccess\RoleAccessInterface;
use App\Module\System\Domain\Interface\RoleAccessPermission\RoleAccessPermissionInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'app:add-record-to-employee-table')]
class AddRecordToEmployeeTableCommand extends Command
{
    private const string DESCRIPTION         = 'Add default employee if not exists';
    private const string HELP                = 'This command adds a default employee based on predefined data if it does not already exist';
    private const string SUCCESS_MESSAGE     = 'Company added successfully!';
    private const string INFO_ALREADY_EXISTS = 'Employee with EMAIL already exists. No action taken.';

    public function __construct(
        private readonly EntityManagerInterface        $entityManager,
        private readonly CompanyReaderInterface        $companyReaderRepository,
        private readonly UserReaderInterface           $userReaderRepository,
        private readonly RoleReaderInterface           $roleReaderRepository,
        private readonly AccessReaderInterface         $accessReaderRepository,
        private readonly PermissionReaderInterface     $permissionReaderRepository,
        private readonly RoleAccessInterface           $roleAccessRepository,
        private readonly RoleAccessPermissionInterface $roleAccessPermissionRepository,
        private readonly EmployeeFakeData              $employeeFakeData,
        private readonly UserFactory                   $userFactory,
    )
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setDescription(self::DESCRIPTION)
            ->setHelp(self::HELP);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $items = $this->employeeFakeData->getFakeData();
        foreach ($items as $data) {
            $iseUserExists = $this->userReaderRepository->isUserWithEmailExists($data['email']);
            if ($iseUserExists) {
                $output->writeln(sprintf('<comment>%s: EMAIL: %s</comment>', self::INFO_ALREADY_EXISTS, $data['email']));

                continue;
            }

            $company = $this->companyReaderRepository->getCompanyByName($data['companyName']);
            $role = $this->roleReaderRepository->getRoleByName($data['roleName']);
            $access = $this->accessReaderRepository->getAccessByName($data['accessName']);
            $permission = $this->permissionReaderRepository->getPermissionByName($data['permissionName']);
            $user = $this->userFactory->create($data['email'], $data['email']);

            $employee = new Employee();
            $employee->setCompany($company);
            $employee->setRole($role);
            $employee->setUser($user);
            $employee->setFirstName($data['firstName']);
            $employee->setLastName($data['lastName']);

            $isRoleHasAccess = $this->roleAccessRepository->isRoleHasAccess($access, $role);
            if (!$isRoleHasAccess) {
                $roleAccess = new RoleAccess($role, $access);
                $this->entityManager->persist($roleAccess);
            }

            $isRoleHasAccessAndPermission = $this->roleAccessPermissionRepository->isRoleHasAccessAndPermission($permission, $access, $role);
            if (!$isRoleHasAccessAndPermission) {
                $roleAccessPermission = new RoleAccessPermission($role, $access, $permission);
                $this->entityManager->persist($roleAccessPermission);
            }

            foreach ($data['phones'] as $phoneNumber) {
                $contact = new Contact();
                $contact->setType(ContactTypeEnum::PHONE->value);
                $contact->setData($phoneNumber);
                $contact->setEmployee($employee);
                $this->entityManager->persist($contact);
            }

            $contact = new Contact();
            $contact->setType(ContactTypeEnum::EMAIL->value);
            $contact->setData($data['email']);
            $contact->setEmployee($employee);
            $this->entityManager->persist($contact);

            if (isset($data['address'])) {
                $addressData = $data['address'];

                $address = new Address();
                $address->setStreet($addressData['street']);
                $address->setPostcode($addressData['postcode']);
                $address->setCity($addressData['city']);
                $address->setCountry($addressData['country']);
                $address->setEmployee($employee);

                $this->entityManager->persist($address);
            }

            $this->entityManager->persist($employee);
            $this->entityManager->flush();
        }

        $output->writeln(sprintf('<info>%s</info>', self::SUCCESS_MESSAGE));

        return Command::SUCCESS;
    }
}
