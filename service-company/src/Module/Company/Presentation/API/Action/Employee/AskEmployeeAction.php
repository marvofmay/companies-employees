<?php

declare(strict_types=1);

namespace App\Module\Company\Presentation\API\Action\Employee;

use App\Common\Domain\Exception\NotFoundByUUIDException;
use App\Module\Company\Application\Query\Employee\GetEmployeeByUUIDQuery;
use App\Module\Company\Domain\Interface\Employee\EmployeeReaderInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;
use Symfony\Contracts\Translation\TranslatorInterface;

final readonly class AskEmployeeAction
{
    public function __construct(
        private MessageBusInterface $queryBus,
        private EmployeeReaderInterface $employeeReaderRepository,
        private TranslatorInterface $translator,
    )
    {
    }

    public function ask(string $uuid): array
    {
        $employee = $this->employeeReaderRepository->getEmployeeByUUID($uuid);
        if ($employee === null) {
            throw new NotFoundByUUIDException($this->translator->trans('employee.uuid.notExists', [':uuid' => $uuid], 'employees'));
        }

        $handledStamp = $this->queryBus->dispatch(new GetEmployeeByUUIDQuery($uuid));

        return $handledStamp->last(HandledStamp::class)->getResult();
    }
}
