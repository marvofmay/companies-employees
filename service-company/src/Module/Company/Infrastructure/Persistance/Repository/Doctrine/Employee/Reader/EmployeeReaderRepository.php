<?php

declare(strict_types=1);

namespace App\Module\Company\Infrastructure\Persistance\Repository\Doctrine\Employee\Reader;

use App\Module\Company\Domain\Entity\Employee;
use App\Module\Company\Domain\Entity\User;
use App\Module\Company\Domain\Interface\Employee\EmployeeReaderInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class EmployeeReaderRepository extends ServiceEntityRepository implements EmployeeReaderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Employee::class);
    }

    public function getEmployeeByUUID(string $uuid): ?Employee
    {
        return $this->getEntityManager()
            ->createQuery('SELECT e FROM ' . Employee::class . ' e WHERE e.' . Employee::COLUMN_UUID . ' = :uuid')
            ->setParameter('uuid', $uuid)
            ->getOneOrNullResult();
    }


    public function isEmployeeWithUUIDExists(string $uuid): bool
    {
        $qb = $this->getEntityManager()->createQueryBuilder();

        $qb->select('e')
            ->from(Employee::class, 'e')
            ->where('e.' . Employee::COLUMN_UUID . ' = :uuid')
            ->setParameter('uuid', $uuid);

        return null !== $qb->getQuery()->getOneOrNullResult();
    }
}
