<?php

declare(strict_types=1);

namespace App\Module\System\Application\Console\FakeData\Data;

use App\Module\System\Application\Console\DefaultData\Data\RoleEnum;
use App\Module\System\Domain\Enum\AccessEnum;
use App\Module\System\Domain\Enum\PermissionEnum;
use Symfony\Contracts\Translation\TranslatorInterface;

final readonly class Employee
{
    public function __construct(private readonly TranslatorInterface $translator)
    {
    }

    public function getFakeData(): array
    {
        $access = explode('.', AccessEnum::EMPLOYEE->value);
        $accessName = $access[1];

        return [
            [
                'companyName'    => 'Future Technology',
                'roleName'       => $this->translator->trans(sprintf('role.defaultData.name.%s', RoleEnum::EMPLOYEE->value), [], 'roles'),
                'accessName'     => $accessName,
                'permissionName' => PermissionEnum::LIST->value,
                'firstName'      => 'Jan',
                'lastName'       => 'Nowakowski',
                'phones'         => [
                    '111-555-555',
                    '112-555-555',
                    '113-555-555',
                ],
                'email'          => 'jan.nowakowski@example.com',
                'address'        => [
                    'street'   => 'Miejska 12/4',
                    'postcode' => '11-111',
                    'city'     => 'Gdańsk',
                    'country'  => 'Polska',
                ],
            ],
            [
                'companyName'    => 'NCBR',
                'roleName'       => $this->translator->trans(sprintf('role.defaultData.name.%s', RoleEnum::EMPLOYEE->value), [], 'roles'),
                'accessName'     => $accessName,
                'permissionName' => PermissionEnum::LIST->value,
                'firstName'      => 'Anna',
                'lastName'       => 'Nowak',
                'phones'         => [
                    '111-555-555',
                    '112-555-555',
                    '113-555-555',
                ],
                'email'          => 'anna.nowak@example.com',
                'address'        => [
                    'street'   => 'Spokojna 12/4',
                    'postcode' => '22-444',
                    'city'     => 'Sopot',
                    'country'  => 'Polska',
                ],
            ],
            [
                'companyName'    => 'NCBR',
                'roleName'       => $this->translator->trans(sprintf('role.defaultData.name.%s', RoleEnum::EMPLOYEE->value), [], 'roles'),
                'accessName'     => $accessName,
                'permissionName' => PermissionEnum::LIST->value,
                'firstName'      => 'Michał',
                'lastName'       => 'Kowal',
                'phones'         => [
                    '111-555-555',
                    '112-555-555',
                    '113-555-555',
                ],
                'email'          => 'michal.kowal@example.com',
                'address'        => [
                    'street'   => 'Za morzem 23',
                    'postcode' => '33-444',
                    'city'     => 'Gdynia',
                    'country'  => 'Polska',
                ],
            ],
        ];
    }
}
