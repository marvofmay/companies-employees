<?php

declare(strict_types=1);

namespace App\Module\Company\Domain\DTO\Company;

use Symfony\Component\Validator\Constraints as Assert;

class UpdateDTO extends CreateDTO
{
    #[Assert\NotBlank]
    public string $uuid;

    public function getUUID(): string
    {
        return $this->uuid;
    }
}
