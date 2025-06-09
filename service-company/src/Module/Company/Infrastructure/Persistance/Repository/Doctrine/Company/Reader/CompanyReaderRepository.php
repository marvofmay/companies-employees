<?php

declare(strict_types=1);

namespace App\Module\Company\Infrastructure\Persistance\Repository\Doctrine\Company\Reader;

use App\Module\Company\Domain\Entity\Company;
use App\Module\Company\Domain\Interface\Company\CompanyReaderInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class CompanyReaderRepository extends ServiceEntityRepository implements CompanyReaderInterface
{
    public function __construct(ManagerRegistry $registry,)
    {
        parent::__construct($registry, Company::class);
    }

    public function getCompanyByUUID(string $uuid): ?Company
    {
        return $this->findOneBy([Company::COLUMN_UUID => $uuid]);
    }


    public function getCompanyByName(string $name, ?string $uuid = null): ?Company
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select(Company::ALIAS)
            ->from(Company::class, Company::ALIAS)
            ->where(Company::ALIAS . '.' . Company::COLUMN_NAME . ' = :name')
            ->setParameter('name', $name);

        if (null !== $uuid) {
            $qb->andWhere(Company::ALIAS . '.' . Company::COLUMN_UUID . ' != :uuid')
                ->setParameter('uuid', $uuid);
        }

        return $qb->getQuery()->getOneOrNullResult();
    }

    public function getCompanyByNIP(string $nip, ?string $uuid = null): ?Company
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select(Company::ALIAS)
            ->from(Company::class, Company::ALIAS)
            ->where(Company::ALIAS . '.' . Company::COLUMN_NIP . ' = :nip')
            ->setParameter('nip', $nip);

        if (null !== $uuid) {
            $qb->andWhere(Company::ALIAS . '.' . Company::COLUMN_UUID . ' != :uuid')
                ->setParameter('uuid', $uuid);
        }

        return $qb->getQuery()->getOneOrNullResult();
    }

    public function isCompanyExistsWithName(string $name, ?string $uuid = null): bool
    {
        return !is_null($this->getCompanyByName($name, $uuid));
    }

    public function isCompanyExistsWithNIP(string $nip, ?string $uuid = null): bool
    {
        return !is_null($this->getCompanyByNIP($nip, $uuid));
    }

    public function isCompanyExistsWithUUID(string $uuid): bool
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select(Company::ALIAS)
            ->from(Company::class, Company::ALIAS)
            ->where(Company::ALIAS . '.' . Company::COLUMN_UUID . ' = :uuid')
            ->setParameter('uuid', $uuid);

        return null !== $qb->getQuery()->getOneOrNullResult();
    }

    public function isCompanyExists(string $nip, string $name, ?string $companyUUID = null): bool
    {
        $qb = $this->getEntityManager()->createQueryBuilder();

        $qb->select(Company::ALIAS)
            ->from(Company::class, Company::ALIAS)
            ->where(sprintf('%s.%s = :nip OR %s.%s = :name', Company::ALIAS, Company::COLUMN_NIP, Company::ALIAS, Company::COLUMN_NAME))
            ->setParameter('nip', $nip)
            ->setParameter('name', $name);

        if ($companyUUID !== null) {
            $qb->andWhere(sprintf('%s.uuid != :uuid', Company::ALIAS))
                ->setParameter('uuid', $companyUUID);
        }

        return null !== $qb->getQuery()->getOneOrNullResult();
    }
}
