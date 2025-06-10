<?php

declare(strict_types=1);

namespace App\Common\Domain\DTO;

use App\Common\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints as Assert;

class AddressDTO
{
    public function __construct(
        #[NotBlank(message: [
            'text' => 'company.address.street.required',
            'domain' => 'companies',
        ])]
        #[Assert\Type('string')]
        public ?string $street = null,

        #[NotBlank(message: [
            'text' => 'company.address.postcode.required',
            'domain' => 'companies',
        ])]
        #[Assert\Type('string')]
        public ?string $postcode = null,

        #[NotBlank(message: [
            'text' => 'company.address.city.required',
            'domain' => 'companies',
        ])]
        #[Assert\Type('string')]
        public ?string $city = null,

        #[NotBlank(message: [
            'text' => 'company.address.country.required',
            'domain' => 'companies',
        ])]
        #[Assert\Type('string')]
        public ?string $country = null,
    ) {}
}
