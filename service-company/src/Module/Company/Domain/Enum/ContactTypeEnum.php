<?php

declare(strict_types=1);

namespace App\Module\Company\Domain\Enum;

use App\Common\Domain\Interface\EnumInterface;

enum ContactTypeEnum: string implements EnumInterface
{
    case PHONE = 'phone';
    case EMAIL = 'email';

    public function label(): string
    {
        return $this->value;
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
