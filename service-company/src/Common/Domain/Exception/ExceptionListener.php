<?php

declare(strict_types=1);

namespace App\Common\Domain\Exception;

use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Contracts\Translation\TranslatorInterface;

class ExceptionListener
{
    public function __construct(private TranslatorInterface $translator, private readonly LoggerInterface $logger)
    {
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        if ($exception instanceof MethodNotAllowedHttpException) {
            $response = new JsonResponse([
                'message' => $this->translator->trans('notCorrectMethodHTTP'),
            ]);
            $event->setResponse($response);
        }

        if ($exception instanceof NotFoundHttpException) {
            $response = new JsonResponse([
                'message' => $this->translator->trans('notEndpointFound'),
            ]);
            $event->setResponse($response);
        }

        $this->logger->error($this->translator->trans($exception->getMessage()));
    }
}
