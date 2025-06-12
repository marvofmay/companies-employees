<?php

declare(strict_types=1);

namespace App\Module\Company\Presentation\API\Action\Company;

use App\Module\Company\Application\Command\Company\UpdateCompanyCommand;
use App\Module\Company\Application\Validator\Company\CompanyValidator;
use App\Module\Company\Domain\DTO\Company\UpdateDTO;
use App\Module\Company\Domain\Interface\Company\CompanyReaderInterface;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

final readonly class UpdateCompanyAction
{
    public function __construct(
        private MessageBusInterface $commandBus,
        private CompanyReaderInterface $companyReaderRepository,
        private TranslatorInterface $translator,
        private CompanyValidator $companyValidator,
    )
    {
    }

    public function execute(string $uuid, UpdateDTO $updateDTO): void
    {
        $company = $this->companyReaderRepository->getCompanyByUUID($uuid);
        if (null === $company) {
            throw new Exception( $this->translator->trans('company.uuid.notExists', [], 'companies'), Response::HTTP_NOT_FOUND);
        }

        $this->companyValidator->validateCompanyExists($updateDTO->getNIP(), $updateDTO->getName(), $uuid);

        $this->commandBus->dispatch(
            new UpdateCompanyCommand(
                $company,
                $updateDTO->getName(),
                $updateDTO->getNip(),
                $updateDTO->getPhones(),
                $updateDTO->getEmails(),
                $updateDTO->getAddress()
            )
        );
    }
}
