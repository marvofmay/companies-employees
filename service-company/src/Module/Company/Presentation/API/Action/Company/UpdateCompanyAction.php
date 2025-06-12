<?php

declare(strict_types=1);

namespace App\Module\Company\Presentation\API\Action\Company;

use App\Module\Company\Application\Command\Company\UpdateCompanyCommand;
use App\Module\Company\Domain\DTO\Company\UpdateDTO;
use App\Module\Company\Domain\Interface\Company\CompanyReaderInterface;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

readonly class UpdateCompanyAction
{
    public function __construct(private MessageBusInterface $commandBus, private CompanyReaderInterface $companyReaderRepository, private readonly TranslatorInterface $translator,)
    {
    }

    public function execute(string $uuid, UpdateDTO $updateDTO): void
    {
        $company = $this->companyReaderRepository->getCompanyByUUID($uuid);
        if (null === $company) {
            throw new Exception( $this->translator->trans('company.uuid.notExists', [], 'companies'), Response::HTTP_NOT_FOUND);
        }

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
