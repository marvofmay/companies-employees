<?php

declare(strict_types=1);

namespace App\Module\Company\Presentation\API\Action\Employee;

use App\Module\Company\Application\Command\Employee\UpdateEmployeeCommand;
use App\Module\Company\Domain\DTO\Employee\UpdateDTO;
use App\Module\Company\Domain\Interface\Employee\EmployeeReaderInterface;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

final readonly class UpdateEmployeeAction
{
    public function __construct(
        private MessageBusInterface $commandBus,
        private EmployeeReaderInterface $employeeReaderRepository,
        private TranslatorInterface $translator,
    )
    {
    }

    public function execute(string $uuid, UpdateDTO $updateDTO): void
    {
        $employee = $this->employeeReaderRepository->getEmployeeByUUID($uuid);
        if (null === $employee) {
            throw new Exception( $this->translator->trans('employee.uuid.notExists', [], 'employees'), Response::HTTP_NOT_FOUND);
        }

        $this->commandBus->dispatch(
            new UpdateEmployeeCommand(
                $employee,
                $updateDTO->getCompanyUUID(),
                $updateDTO->getRoleUUID(),
                $updateDTO->getFirstName(),
                $updateDTO->getLastName(),
                $updateDTO->getEmail(),
                $updateDTO->getPhones(),
                $updateDTO->getAddress()
            )
        );
    }
}
