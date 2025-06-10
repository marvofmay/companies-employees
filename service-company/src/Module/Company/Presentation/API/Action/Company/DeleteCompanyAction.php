<?php

declare(strict_types=1);

namespace App\Module\Company\Presentation\API\Action\Company;

use App\Common\Domain\Exception\NotFoundByUUIDException;
use App\Module\Company\Application\Command\Company\DeleteCompanyCommand;
use App\Module\Company\Domain\Interface\Company\CompanyReaderInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

readonly class DeleteCompanyAction
{
    public function __construct(
        private MessageBusInterface $commandBus,
        private CompanyReaderInterface $companyReaderRepository,
        private TranslatorInterface $translator,
    ) {}

    public function execute(string $uuid): void
    {
        $company = $this->companyReaderRepository->getCompanyByUUID($uuid);
        if ($company === null) {
            throw new NotFoundByUUIDException($this->translator->trans('company.uuid.notExists', [':uuid' => $uuid], 'companies'));
        }

        $this->commandBus->dispatch(new DeleteCompanyCommand($company));
    }
}
