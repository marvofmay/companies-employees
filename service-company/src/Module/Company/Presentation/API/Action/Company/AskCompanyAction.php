<?php

declare(strict_types=1);

namespace App\Module\Company\Presentation\API\Action\Company;

use App\Module\Company\Application\Query\Company\GetCompanyByUUIDQuery;
use Symfony\Component\Messenger\Exception\ExceptionInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;

final readonly class AskCompanyAction
{
    public function __construct(private MessageBusInterface $queryBus,)
    {
    }

    public function ask(string $uuid): array
    {
        try {
            $handledStamp = $this->queryBus->dispatch(new GetCompanyByUUIDQuery($uuid));
        } catch (ExceptionInterface $e) {
            throw $e->getPrevious();
        }

        return $handledStamp->last(HandledStamp::class)->getResult();
    }
}
