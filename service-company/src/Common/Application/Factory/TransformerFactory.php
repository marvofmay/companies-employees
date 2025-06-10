<?php

declare(strict_types=1);

namespace App\Common\Application\Factory;

use App\Module\Company\Application\QueryHandler\Company\ListCompaniesQueryHandler;
use App\Module\Company\Application\Transformer\Company\CompanyDataTransformer;

class TransformerFactory
{
    public static function createForHandler(string $handlerClass): object
    {
        return match ($handlerClass) {
            ListCompaniesQueryHandler::class => new CompanyDataTransformer(),
            default => throw new \RuntimeException("No transformer found for handler: {$handlerClass}")
        };
    }
}
