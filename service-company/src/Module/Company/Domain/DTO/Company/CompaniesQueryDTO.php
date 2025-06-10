<?php

declare(strict_types=1);

namespace App\Module\Company\Domain\DTO\Company;

use App\Common\Domain\Abstract\QueryDTOAbstract;

class CompaniesQueryDTO extends QueryDTOAbstract
{
    public ?string $name = null;

    public ?string $nip = null;
}
