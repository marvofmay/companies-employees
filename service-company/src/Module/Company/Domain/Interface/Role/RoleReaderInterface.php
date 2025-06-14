<?php

declare(strict_types=1);

namespace App\Module\Company\Domain\Interface\Role;

use App\Module\Company\Domain\Entity\Role;

interface RoleReaderInterface
{
    public function getRoleByUUID(string $uuid): ?Role;

    public function getRoleByName(string $name, ?string $uuid): ?Role;
    public function isRoleWithUUIDExists(string $uuid): bool;
    public function isRoleExists(string $name, ?string $uuid = null): bool;
}
