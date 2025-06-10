<?php

declare(strict_types=1);

namespace App\Module\Company\Application\QueryHandler\Employee;

use App\Module\Company\Application\Query\Employee\GetEmployeeByUUIDQuery;
use App\Module\Company\Application\Transformer\Employee\EmployeeDataTransformer;
use App\Module\Company\Domain\Entity\Employee;
use App\Module\Company\Domain\Interface\Employee\EmployeeReaderInterface;

final readonly class GetEmployeeByUUIDQueryHandler
{
    public function __construct(private EmployeeReaderInterface $employeeReaderRepository)
    {
    }

    public function __invoke(GetEmployeeByUUIDQuery $query): array
    {
        $employee = $this->employeeReaderRepository->getEmployeeByUUID($query->uuid);
        $transformer = new EmployeeDataTransformer();

      return $transformer->transformToArray(
          $employee,
          [Employee::RELATION_COMPANY, Employee::RELATION_ADDRESS, Employee::RELATION_CONTACTS, Employee::RELATION_ROLE,]
      );
    }
}
