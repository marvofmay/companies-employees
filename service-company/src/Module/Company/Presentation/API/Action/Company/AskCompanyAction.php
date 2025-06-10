<?php

declare(strict_types=1);

namespace App\Module\Company\Presentation\API\Action\Company;

use App\Common\Domain\Exception\NotFoundByUUIDException;
use App\Module\Company\Application\Query\Company\GetCompanyByUUIDQuery;
use App\Module\Company\Domain\Interface\Company\CompanyReaderInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;
use Symfony\Contracts\Translation\TranslatorInterface;

final readonly class AskCompanyAction
{
    public function __construct(
        private MessageBusInterface $queryBus,
        private CompanyReaderInterface $companyReaderRepository,
        private TranslatorInterface $translator,
    )
    {
    }

    public function ask(string $uuid): array
    {
        $company = $this->companyReaderRepository->getCompanyByUUID($uuid);
        if ($company === null) {
            throw new NotFoundByUUIDException($this->translator->trans('company.uuid.notExists', [':uuid' => $uuid], 'companies'));
        }

        $handledStamp = $this->queryBus->dispatch(new GetCompanyByUUIDQuery($uuid));

        return $handledStamp->last(HandledStamp::class)->getResult();
    }
}
