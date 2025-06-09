<?php

declare(strict_types=1);

namespace App\Module\System\Domain\Enum;

use App\Common\Domain\Interface\EnumInterface;

enum AccessEnum: string implements EnumInterface
{
    case COMPANY       = ModuleEnum::COMPANY->value . '.company';
    case EMPLOYEE      = ModuleEnum::COMPANY->value . '.employee';
    case ACCESS        = ModuleEnum::SYSTEM->value . '.access';
    case PERMISSION    = ModuleEnum::SYSTEM->value . '.permission';

    public function label(): string
    {
        return match ($this) {
            self::COMPANY       => 'Company',
            self::EMPLOYEE      => 'Employee',
            self::ACCESS        => 'Access',
            self::PERMISSION    => 'Permission',
        };
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
