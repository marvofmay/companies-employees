<?php

declare(strict_types=1);

namespace App\Module\Company\Application\Command\Company;

use App\Common\Domain\DTO\AddressDTO;
use App\Common\Domain\Interface\CommandInterface;
use App\Module\Company\Domain\Entity\Company;

class UpdateCompanyCommand implements CommandInterface
{
    public function __construct(
        public Company $company,
        public string $name,
        public string $nip,
        public ?array $phones,
        public ?array $emails,
        public AddressDTO $address,
    ) {}
}
