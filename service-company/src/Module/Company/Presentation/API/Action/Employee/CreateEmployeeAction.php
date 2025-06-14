<?php

declare(strict_types=1);

namespace App\Module\Company\Presentation\API\Action\Employee;

use App\Module\Company\Application\Command\Employee\CreateEmployeeCommand;
use App\Module\Company\Application\Validator\Employee\EmployeeValidator;
use App\Module\Company\Domain\DTO\Employee\CreateDTO;
use Symfony\Component\Messenger\MessageBusInterface;

final readonly class CreateEmployeeAction
{
    public function __construct(
        private MessageBusInterface $commandBus,
        private EmployeeValidator $employeeValidator,
    )
    {
    }

    public function execute(CreateDTO $createDTO): void
    {
        $this->employeeValidator->validateCompanyExists($createDTO->getCompanyUUID());
        $this->employeeValidator->validateRoleExists($createDTO->getRoleUUID());
        $this->employeeValidator->validateEmailIsUnique($createDTO->getEmail());

        $this->commandBus->dispatch(
            new CreateEmployeeCommand(
                $createDTO->getCompanyUUID(),
                $createDTO->getRoleUUID(),
                $createDTO->getFirstName(),
                $createDTO->getLastName(),
                $createDTO->getEmail(),
                $createDTO->getPhones(),
                $createDTO->getAddress()
            )
        );
    }
}
