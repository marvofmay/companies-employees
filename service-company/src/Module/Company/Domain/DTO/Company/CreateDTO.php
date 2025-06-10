<?php

declare(strict_types=1);

namespace App\Module\Company\Domain\DTO\Company;

use App\Common\Domain\DTO\AddressDTO;
use App\Common\Validator\Constraints\MinMaxLength;
use App\Common\Validator\Constraints\NotBlank;
use App\Common\Validator\Constraints\NIP;
use App\Module\Company\Structure\Validator\Constraints\Company\UniqueCompanyName;
use App\Module\Company\Structure\Validator\Constraints\Company\UniqueCompanyNIP;
use Symfony\Component\Validator\Constraints as Assert;

class CreateDTO
{
    #[NotBlank(message: [
        'text' => 'company.name.required',
        'domain' => 'companies',
    ])]
    #[MinMaxLength(min: 3, max: 500, message: [
        'tooShort' => 'company.name.minimumLength',
        'tooLong' => 'company.name.maximumLength',
        'domain' => 'companies',
    ])]
    #[UniqueCompanyName]
    public string $name = '';

    #[NIP(message: [
        'invalidNIP' => 'company.nip.invalid',
        'domain' => 'companies',
    ])]
    #[UniqueCompanyNIP]
    public ?string $nip = null;

    #[Assert\All([
        new Assert\Type(type: 'string')
    ])]
    #[Assert\Type('array')]
    #[Assert\Count(
        max: 3,
        maxMessage: 'phones.max'
    )]
    public ?array $phones = [];

    #[Assert\All([
        new Assert\Type(type: 'string')
    ])]
    #[Assert\Type('array')]
    #[Assert\Count(
        max: 3,
        maxMessage: 'emails.max'
    )]
    public ?array $emails = [];

    #[Assert\NotBlank]
    #[Assert\Valid]
    public AddressDTO $address;

    public function getName(): string
    {
        return $this->name;
    }


    public function getNIP(): ?string
    {
        return $this->nip;
    }

    public function getPhones(): ?array
    {
        return $this->phones;
    }

    public function getEmails(): ?array
    {
        return $this->emails;
    }

    public function getAddress(): AddressDTO
    {
        return $this->address;
    }
}
