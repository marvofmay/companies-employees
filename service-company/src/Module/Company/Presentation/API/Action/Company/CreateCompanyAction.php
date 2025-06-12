<?php

declare(strict_types=1);

namespace App\Module\Company\Presentation\API\Action\Company;

use App\Module\Company\Application\Command\Company\CreateCompanyCommand;
use App\Module\Company\Application\Validator\Company\CompanyValidator;
use App\Module\Company\Domain\DTO\Company\CreateDTO;
use App\Module\Company\Domain\Interface\Company\CompanyReaderInterface;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

final readonly class CreateCompanyAction
{
    public function __construct(
        private MessageBusInterface $commandBus,
        private CompanyValidator $companyValidator,
    )
    {
    }

    public function execute(CreateDTO $createDTO): void
    {
        $this->companyValidator->validateCompanyExists($createDTO->getNIP(), $createDTO->getName());
        $this->commandBus->dispatch(
            new CreateCompanyCommand(
                $createDTO->getName(),
                $createDTO->getNip(),
                $createDTO->getPhones(),
                $createDTO->getEmails(),
                $createDTO->getAddress()
            )
        );
    }
}
