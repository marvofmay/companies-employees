<?php

declare(strict_types=1);

namespace App\Module\Company\Infrastructure\Persistance\Repository\Doctrine\User\Reader;

use App\Module\Company\Domain\Entity\User;
use App\Module\Company\Domain\Interface\User\UserReaderInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class UserReaderRepository extends ServiceEntityRepository implements UserReaderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function getUserByEmail(string $email, ?string $uuid = null): ?User
    {
        $qb = $this->getEntityManager()->createQueryBuilder();

        $qb->select('u')
            ->from(User::class, 'u')
            ->where('u.' . User::COLUMN_EMAIL . ' = :email')
            ->setParameter('email', $email);

        if (null !== $uuid) {
            $qb->andWhere('u.' . User::COLUMN_EMPLOYEE_UUID . ' != :uuid')
                ->setParameter('uuid', $uuid);
        }

        return $qb->getQuery()->getOneOrNullResult();
    }

    public function isUserWithEmailExists(string $email, ?string $uuid = null): bool
    {
        return !is_null($this->getUserByEmail($email, $uuid));
    }
}
