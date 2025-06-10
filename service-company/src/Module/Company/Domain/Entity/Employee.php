<?php

declare(strict_types=1);

namespace App\Module\Company\Domain\Entity;

use App\Common\Domain\Trait\AttributesEntityTrait;
use App\Common\Domain\Trait\RelationsEntityTrait;
use App\Common\Domain\Trait\TimestampableTrait;
use App\Module\Company\Domain\Enum\ContactTypeEnum;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Ramsey\Uuid\Doctrine\UuidGenerator;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity]
#[ORM\Table(name: 'employee')]
#[ORM\Index(name: 'index_first_name', columns: ['first_name'])]
#[ORM\Index(name: 'index_last_name', columns: ['last_name'])]
#[ORM\HasLifecycleCallbacks]
#[Gedmo\SoftDeleteable(fieldName: 'deletedAt', timeAware: false, hardDelete: true)]
class Employee
{
    use TimestampableTrait;
    use AttributesEntityTrait;
    use RelationsEntityTrait;

    public const string COLUMN_UUID = 'uuid';
    public const string COLUMN_COMPANY_UUID = 'companyUUID';
    public const string COLUMN_FIRST_NAME = 'firstName';
    public const string COLUMN_LAST_NAME = 'lastName';
    public const string COLUMN_CREATED_AT = 'createdAt';
    public const string COLUMN_UPDATED_AT = 'updatedAt';
    public const string COLUMN_DELETED_AT = 'deletedAt';
    public const string RELATION_COMPANY = 'company';
    public const string RELATION_ROLE = 'role';
    public const string RELATION_ADDRESS = 'address';
    public const string RELATION_CONTACTS = 'contacts';

    public const string ALIAS = 'employee';
    public const SOFT_DELETED_AT = 'soft';
    public const HARD_DELETED_AT = 'hard';

    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: UuidGenerator::class)]
    private UuidInterface $uuid;

    #[ORM\ManyToOne(targetEntity: Company::class, inversedBy: 'employees')]
    #[ORM\JoinColumn(name: 'company_uuid', referencedColumnName: 'uuid', nullable: false, onDelete: 'CASCADE')]
    private ?Company $company;

    #[ORM\ManyToOne(targetEntity: Role::class, inversedBy: 'employees')]
    #[ORM\JoinColumn(name: 'role_uuid', referencedColumnName: 'uuid', nullable: false, onDelete: 'CASCADE')]
    private Role $role;

    #[ORM\Column(type: Types::STRING, length: 50, nullable: false)]
    #[Assert\NotBlank()]
    private string $firstName;

    #[ORM\Column(type: Types::STRING, length: 50, nullable: false)]
    #[Assert\NotBlank()]
    private string $lastName;

    #[ORM\OneToOne(targetEntity: User::class, mappedBy: 'employee', cascade: ['persist', 'remove'])]
    private ?User $user = null;

    #[ORM\OneToMany(targetEntity: Contact::class, mappedBy: 'employee', cascade: ['persist', 'remove'])]
    private Collection $contacts;

    #[ORM\OneToOne(targetEntity: Address::class, mappedBy: 'employee', cascade: ['persist', 'remove'])]
    private ?Address $address = null;

    public function __construct()
    {
        $this->contacts = new ArrayCollection();
    }

    public function getUUID(): UuidInterface
    {
        return $this->{self::COLUMN_UUID};
    }

    public function setUuid(UuidInterface $uuid): void
    {
        $this->{self::COLUMN_UUID} = $uuid;
    }

    public function getFirstName(): string
    {
        return $this->{self::COLUMN_FIRST_NAME};
    }

    public function setFirstName(string $firstName): void
    {
        $this->{self::COLUMN_FIRST_NAME} = $firstName;
    }

    public function getLastName(): string
    {
        return $this->{self::COLUMN_LAST_NAME};
    }

    public function setLastName(string $lastName): void
    {
        $this->{self::COLUMN_LAST_NAME} = $lastName;
    }

    public function getContacts(?ContactTypeEnum $type = null): Collection
    {
        if ($type === null) {
            return $this->contacts;
        }

        return $this->contacts->filter(fn(Contact $contact) => $contact->getType() === $type->value);
    }

    public function addContact(Contact $contact): void
    {
        if (!$this->contacts->contains($contact)) {
            $this->contacts[] = $contact;
            $contact->setEmployee($this);
        }
    }

    public function getAddress(): ?Address
    {
        return $this->address;
    }

    public function setAddress(?Address $address): void
    {
        $this->address = $address;
        $address->setEmployee($this);
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user): void
    {
        $this->user = $user;
        if ($user && $user->getEmployee() !== $this) {
            $user->setEmployee($this);
        }
    }

    public function getCompany(): Company
    {
        return $this->company;
    }

    public function setCompany(Company $company): void
    {
        $this->company = $company;
    }

    public function getRole(): Role
    {
        return $this->role;
    }

    public function setRole(Role $role): void
    {
        $this->role = $role;
    }
}
