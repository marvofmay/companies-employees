<?php

declare(strict_types=1);

namespace App\Common\Application\Factory;

use App\Common\Domain\Exception\TransformerNotFoundException;
use App\Module\Company\Application\QueryHandler\Company\ListCompaniesQueryHandler;
use App\Module\Company\Application\QueryHandler\Employee\ListEmployeesQueryHandler;
use App\Module\Company\Application\Transformer\Company\CompanyDataTransformer;
use App\Module\Company\Application\Transformer\Employee\EmployeeDataTransformer;

class TransformerFactory
{
    public static function createForHandler(string $handlerClass): object
    {
        return match ($handlerClass) {
            ListCompaniesQueryHandler::class => new CompanyDataTransformer(),
            ListEmployeesQueryHandler::class => new EmployeeDataTransformer(),
            default => throw new TransformerNotFoundException("No transformer found for handler: {$handlerClass}")
        };
    }
}
