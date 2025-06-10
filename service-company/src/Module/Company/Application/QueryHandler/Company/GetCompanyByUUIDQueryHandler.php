<?php

declare(strict_types=1);

namespace App\Module\Company\Application\QueryHandler\Company;

use App\Module\Company\Application\Query\Company\GetCompanyByUUIDQuery;
use App\Module\Company\Application\Transformer\Company\CompanyDataTransformer;
use App\Module\Company\Domain\Entity\Company;
use App\Module\Company\Domain\Interface\Company\CompanyReaderInterface;

final readonly class GetCompanyByUUIDQueryHandler
{
    public function __construct(private CompanyReaderInterface $companyReaderRepository)
    {
    }

    public function __invoke(GetCompanyByUUIDQuery $query): array
    {
        $company = $this->companyReaderRepository->getCompanyByUUID($query->uuid);
        $transformer = new CompanyDataTransformer();

      return $transformer->transformToArray($company, [Company::RELATION_EMPLOYEES, Company::RELATION_ADDRESS, Company::RELATION_CONTACTS]);
    }
}
