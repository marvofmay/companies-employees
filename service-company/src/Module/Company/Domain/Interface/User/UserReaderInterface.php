<?php

declare(strict_types=1);

namespace App\Module\Company\Domain\Interface\User;

use App\Module\Company\Domain\Entity\User;

interface UserReaderInterface
{
    public function getUserByEmail(string $email, ?string $uuid = null): ?User;
    public function isUserWithEmailExists(string $email, ?string $uuid = null): bool;
}
