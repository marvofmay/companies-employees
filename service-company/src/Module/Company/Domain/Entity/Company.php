<?php

declare(strict_types=1);

namespace App\Module\Company\Domain\Entity;

use App\Common\Domain\Trait\AttributesEntityTrait;
use App\Common\Domain\Trait\RelationsEntityTrait;
use App\Common\Domain\Trait\TimestampableTrait;
use App\Module\Company\Domain\Enum\ContactTypeEnum;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Ramsey\Uuid\Doctrine\UuidGenerator;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;

#[ORM\Entity]
#[ORM\Table(name: 'company')]
#[ORM\Index(name: 'idx_nip', columns: ['nip'])]
#[ORM\HasLifecycleCallbacks]
#[Gedmo\SoftDeleteable(fieldName: 'deletedAt', timeAware: false, hardDelete: true)]
class Company
{
    use TimestampableTrait;
    use AttributesEntityTrait;
    use RelationsEntityTrait;

    public const COLUMN_UUID = 'uuid';

    public const COLUMN_NAME = 'name';
    public const COLUMN_NIP = 'nip';
    public const COLUMN_CREATED_AT = 'createdAt';
    public const COLUMN_UPDATED_AT = 'updatedAt';
    public const COLUMN_DELETED_AT = 'deletedAt';
    public const ALIAS = 'company';
    public const SOFT_DELETED_AT = 'soft';
    public const HARD_DELETED_AT = 'hard';

    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: UuidGenerator::class)]
    private UuidInterface $uuid;

    #[ORM\OneToMany(targetEntity: Employee::class, mappedBy: 'company', cascade: ['persist', 'remove'])]
    private Collection $employees;

    #[ORM\OneToMany(targetEntity: Contact::class, mappedBy: 'company', cascade: ['persist', 'remove'])]
    private Collection $contacts;

    #[ORM\OneToOne(targetEntity: Address::class, mappedBy: 'company', cascade: ['persist', 'remove'])]
    private Address $address;

    #[ORM\Column(type: Types::STRING, length: 1000)]
    #[Assert\NotBlank]
    private string $name;

    #[ORM\Column(type: Types::STRING, length: 20, nullable: false)]
    private string $nip;

    public function __construct()
    {
        $this->employees = new ArrayCollection();
        $this->contacts = new ArrayCollection();
    }

    public function getUUID(): UuidInterface
    {
        return $this->{self::COLUMN_UUID};
    }

    public function getAddress(): Address
    {
        return $this->address;
    }

    public function setAddress(Address $address): void
    {
        $this->address = $address;
        $address->setCompany($this);
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
            $contact->setCompany($this);
        }
    }

    public function getName(): string
    {
        return $this->{self::COLUMN_NAME};
    }

    public function setName(string $name): void
    {
        $this->{self::COLUMN_NAME} = $name;
    }

    public function getNIP(): string
    {
        return $this->nip;
    }

    public function setNIP(string $nip): void
    {
        $this->nip = $nip;
    }

}
