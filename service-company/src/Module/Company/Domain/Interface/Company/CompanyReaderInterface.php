<?php

declare(strict_types=1);

namespace App\Module\Company\Domain\Interface\Company;

use App\Module\Company\Domain\Entity\Company;

interface CompanyReaderInterface
{
    public function getCompanyByUUID(string $uuid): ?Company;
    public function getCompanyByName(string $name, ?string $uuid): ?Company;
    public function getCompanyByNIP(string $nip, ?string $uuid): ?Company;
    public function isCompanyExistsWithName(string $name, ?string $uuid = null): bool;
    public function isCompanyExistsWithNIP(string $nip, ?string $uuid = null): bool;
    public function isCompanyExistsWithUUID(string $uuid): bool;
    public function isCompanyExists(string $nip, string $name, ?string $companyUUID = null): bool;
}
