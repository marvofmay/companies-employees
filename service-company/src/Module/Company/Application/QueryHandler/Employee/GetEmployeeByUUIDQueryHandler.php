<?php

declare(strict_types=1);

namespace App\Module\Company\Application\QueryHandler\Employee;

use App\Common\Domain\Exception\NotFoundByUUIDException;
use App\Module\Company\Application\Query\Employee\GetEmployeeByUUIDQuery;
use App\Module\Company\Application\Transformer\Employee\EmployeeDataTransformer;
use App\Module\Company\Domain\Entity\Employee;
use App\Module\Company\Domain\Interface\Employee\EmployeeReaderInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

final readonly class GetEmployeeByUUIDQueryHandler
{
    public function __construct(private EmployeeReaderInterface $employeeReaderRepository, private TranslatorInterface $translator)
    {
    }

    public function __invoke(GetEmployeeByUUIDQuery $query): array
    {
        $employee = $this->employeeReaderRepository->getEmployeeByUUID($query->uuid);
        if ($employee === null) {
            throw new NotFoundByUUIDException($this->translator->trans('employee.uuid.notExists', [':uuid' => $query->uuid], 'employees'));
        }

        $transformer = new EmployeeDataTransformer();

        return $transformer->transformToArray(
            $employee,
            [Employee::RELATION_COMPANY, Employee::RELATION_ADDRESS, Employee::RELATION_CONTACTS, Employee::RELATION_ROLE,]
        );
    }
}
