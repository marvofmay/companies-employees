<?php

declare(strict_types=1);

namespace App\Module\Company\Application\Validator\Employee;

use App\Module\Company\Domain\Interface\Company\CompanyReaderInterface;
use App\Module\Company\Domain\Interface\Role\RoleReaderInterface;
use App\Module\Company\Domain\Interface\User\UserReaderInterface;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\Translation\TranslatorInterface;

final readonly class EmployeeValidator
{
    public function __construct(
        private CompanyReaderInterface $companyReaderRepository,
        private RoleReaderInterface $roleReaderRepository,
        private UserReaderInterface $userReaderRepository,
        private TranslatorInterface $translator,
    ) {
    }

    public function validateCompanyExists(string $uuid): void
    {
        if (!$this->companyReaderRepository->isCompanyExistsWithUUID($uuid)) {
            throw new Exception(
                $this->translator->trans('company.uuid.notExists', [':uuid' => $uuid], 'companies'),
                Response::HTTP_NOT_FOUND
            );
        }
    }

    public function validateRoleExists(string $uuid): void
    {
        if (!$this->roleReaderRepository->isRoleWithUUIDExists($uuid)) {
            throw new Exception(
                $this->translator->trans('role.uuid.notExists', [':uuid' => $uuid], 'roles'),
                Response::HTTP_NOT_FOUND
            );
        }
    }

    public function validateEmailIsUnique(string $email, ?string $excludeUuid = null): void
    {
        if ($this->userReaderRepository->isUserWithEmailExists($email, $excludeUuid)) {
            throw new Exception(
                $this->translator->trans('employee.email.alreadyExists', [':email' => $email], 'employees'),
                Response::HTTP_CONFLICT
            );
        }
    }
}
