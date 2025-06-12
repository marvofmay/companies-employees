<?php

declare(strict_types=1);

namespace App\Module\Company\Presentation\API\Action\Employee;

use App\Module\Company\Application\Query\Employee\GetEmployeeByUUIDQuery;
use Symfony\Component\Messenger\Exception\ExceptionInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;

final readonly class AskEmployeeAction
{
    public function __construct(
        private MessageBusInterface $queryBus,
    )
    {
    }

    public function ask(string $uuid): array
    {
        try {
            $handledStamp = $this->queryBus->dispatch(new GetEmployeeByUUIDQuery($uuid));
        } catch (ExceptionInterface $e) {
            throw $e->getPrevious();
        }

        return $handledStamp->last(HandledStamp::class)->getResult();
    }
}
