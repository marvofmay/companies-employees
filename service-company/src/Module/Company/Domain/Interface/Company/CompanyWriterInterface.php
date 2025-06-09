<?php

declare(strict_types=1);

namespace App\Module\Company\Domain\Interface\Company;

use App\Module\Company\Domain\Entity\Company;

interface CompanyWriterInterface
{
    public function saveCompanyInDB(Company $company): void;
    public function deleteCompanyInDB(Company $company): void;
}
