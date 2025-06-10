<?php

declare(strict_types=1);

namespace App\Module\Company\Presentation\API\Action\Employee;

use App\Module\Company\Application\Command\Employee\CreateEmployeeCommand;
use App\Module\Company\Domain\DTO\Employee\CreateDTO;
use Symfony\Component\Messenger\MessageBusInterface;

readonly class CreateEmployeeAction
{
    public function __construct(private MessageBusInterface $commandBus)
    {
    }

    public function execute(CreateDTO $createDTO): void
    {
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
