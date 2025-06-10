<?php

declare(strict_types=1);

namespace App\Module\Company\Application\Query\Company;

final readonly class GetCompanyByUUIDQuery
{
    public function __construct(public string $uuid)
    {
    }
}
