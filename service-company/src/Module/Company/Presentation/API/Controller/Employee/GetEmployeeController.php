<?php

declare(strict_types=1);

namespace App\Module\Company\Presentation\API\Controller\Employee;

use App\Module\Company\Presentation\API\Action\Employee\AskEmployeeAction;
use App\Module\System\Domain\Enum\AccessEnum;
use App\Module\System\Domain\Enum\PermissionEnum;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

final class GetEmployeeController extends AbstractController
{
    public function __construct(
        private readonly LoggerInterface         $logger,
        private readonly TranslatorInterface     $translator,
    )
    {
    }

    #[Route('/api/employees/{uuid}', name: 'api.employees.get', methods: ['GET'])]
    public function get(string $uuid, AskEmployeeAction $askEmployeeAction): JsonResponse
    {
        try {
            if (!$this->isGranted(PermissionEnum::VIEW, AccessEnum::EMPLOYEE)) {
                throw new \Exception($this->translator->trans('accessDenied', [], 'messages'), Response::HTTP_FORBIDDEN);
            }

            return new JsonResponse(['data' => $askEmployeeAction->ask($uuid),], Response::HTTP_OK);
        } catch (\Exception $error) {
            $message = sprintf('%s. %s', $this->translator->trans('employee.view.error', [], 'employees'), $error->getMessage());
            $this->logger->error($message);

            return new JsonResponse(['message' => $message], $error->getCode());
        }
    }
}
