<?php

declare(strict_types=1);

namespace App\Module\Company\Domain\Entity;

use App\Common\Domain\Trait\AttributesEntityTrait;
use App\Common\Domain\Trait\RelationsEntityTrait;
use App\Common\Domain\Trait\TimestampableTrait;
use App\Module\System\Domain\Entity\Access;
use App\Module\System\Domain\Entity\Permission;
use App\Module\System\Domain\Entity\RoleAccess;
use App\Module\System\Domain\Entity\RoleAccessPermission;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Ramsey\Uuid\Doctrine\UuidGenerator;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity]
#[ORM\Table(name: 'role')]
#[ORM\HasLifecycleCallbacks]
#[Gedmo\SoftDeleteable(fieldName: 'deletedAt', timeAware: false, hardDelete: false)]
class Role
{
    use TimestampableTrait;
    use AttributesEntityTrait;
    use RelationsEntityTrait;

    public const string COLUMN_UUID = 'uuid';
    public const string COLUMN_NAME = 'name';
    public const string COLUMN_DESCRIPTION = 'description';
    public const string ALIAS = 'role';

    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: UuidGenerator::class)]
    private UuidInterface $uuid;

    #[ORM\Column(type: Types::STRING, length: 100, unique: true)]
    #[Assert\NotBlank()]
    private string $name;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\OneToMany(mappedBy: 'role', targetEntity: RoleAccess::class, cascade: ['persist', 'remove'], orphanRemoval: true)]
    private Collection $roleAccesses;

    #[ORM\OneToMany(mappedBy: 'role', targetEntity: RoleAccessPermission::class, cascade: ['persist', 'remove'])]
    private Collection $accessPermissions;

    #[ORM\OneToMany(mappedBy: 'role', targetEntity: Employee::class)]
    private Collection $employees;

    public function __construct()
    {
        $this->roleAccesses = new ArrayCollection();
        $this->accessPermissions = new ArrayCollection();
        $this->employees = new ArrayCollection();
    }

    public function getUUID(): UuidInterface
    {
        return $this->uuid;
    }

    public function setUuid(UuidInterface $uuid): void
    {
        $this->uuid = $uuid;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    public function getRoleAccesses(): Collection
    {
        return $this->roleAccesses;
    }

    public function addAccess(Access $access): void
    {
        foreach ($this->roleAccesses as $roleAccess) {
            if ($roleAccess->getAccess() === $access) {
                return;
            }
        }

        $roleAccess = new RoleAccess($this, $access);
        $this->roleAccesses->add($roleAccess);
    }

    public function removeAccess(Access $access): void
    {
        foreach ($this->roleAccesses as $roleAccess) {
            if ($roleAccess->getAccess() === $access) {
                $this->roleAccesses->removeElement($roleAccess);
                break;
            }
        }
    }

    public function getAccessPermissions(): Collection
    {
        return $this->accessPermissions;
    }

    public function addAccessPermission(Access $access, Permission $permission): void
    {
        $relation = new RoleAccessPermission($this, $access, $permission);
        $this->accessPermissions->add($relation);
    }

    public function getEmployees(): Collection
    {
        return $this->employees;
    }
}
