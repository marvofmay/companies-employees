<?php

declare(strict_types=1);

namespace App\Module\Company\Application\Command\Company;

use App\Common\Domain\Interface\CommandInterface;
use App\Common\Domain\DTO\AddressDTO;

final class CreateCompanyCommand implements CommandInterface
{
    public function __construct(
        public string $name,
        public string $nip,
        public ?array $phones,
        public ?array $emails,
        public AddressDTO $address,
    ) {}
}
