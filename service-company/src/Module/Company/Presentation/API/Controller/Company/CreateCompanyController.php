<?php

declare(strict_types=1);

namespace App\Module\Company\Presentation\API\Controller\Company;

use App\Module\Company\Domain\DTO\Company\CreateDTO;
use App\Module\Company\Presentation\API\Action\Company\CreateCompanyAction;
use App\Module\System\Domain\Enum\AccessEnum;
use App\Module\System\Domain\Enum\PermissionEnum;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

final class CreateCompanyController extends AbstractController
{
    public function __construct(private readonly LoggerInterface $logger, private readonly TranslatorInterface $translator)
    {
    }

    #[Route('/api/companies', name: 'api.companies.create', methods: ['POST'])]
    public function create(#[MapRequestPayload] CreateDTO $createDTO, CreateCompanyAction $createCompanyAction): JsonResponse
    {
        try {
            if (!$this->isGranted(PermissionEnum::CREATE, AccessEnum::COMPANY)) {
                throw new \Exception($this->translator->trans('accessDenied', [], 'messages'), Response::HTTP_FORBIDDEN);
            }

            $createCompanyAction->execute($createDTO);

            return new JsonResponse(['message' => $this->translator->trans('company.add.success', [], 'companies')], Response::HTTP_CREATED);
        } catch (\Exception $error) {
            $message = sprintf('%s. %s', $this->translator->trans('company.add.error', [], 'companies'), $error->getMessage());
            $this->logger->error($message);

            return new JsonResponse(['message' => $message], $error->getCode());
        }
    }
}
