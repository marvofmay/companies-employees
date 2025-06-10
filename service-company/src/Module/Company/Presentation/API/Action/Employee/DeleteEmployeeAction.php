<?php

declare(strict_types=1);

namespace App\Module\Company\Presentation\API\Action\Employee;

use App\Common\Domain\Exception\NotFoundByUUIDException;
use App\Module\Company\Application\Command\Employee\DeleteEmployeeCommand;
use App\Module\Company\Domain\Interface\Employee\EmployeeReaderInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

final readonly class DeleteEmployeeAction
{
    public function __construct(
        private MessageBusInterface $commandBus,
        private EmployeeReaderInterface $employeeReaderRepository,
        private TranslatorInterface $translator,
    ) {}

    public function execute(string $uuid): void
    {
        $employee = $this->employeeReaderRepository->getEmployeeByUUID($uuid);
        if (null === $employee) {
            throw new NotFoundByUUIDException($this->translator->trans('employee.uuid.notExists', [':uuid' => $uuid], 'employees'));
        }

        $this->commandBus->dispatch(new DeleteEmployeeCommand($employee));
    }
}
