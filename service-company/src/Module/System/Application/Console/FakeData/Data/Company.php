<?php

declare(strict_types=1);

namespace App\Module\System\Application\Console\FakeData\Data;

final readonly class Company
{

    public function getDefaultData(): array
    {
        return [
            'name' => 'Feture Technology',
            'nip' => '9316831327',
            'phones' => [
                '155-555-555',
                '255-555-555',
                '355-555-555',
            ],
            'emails' => [
                'futuretechnnology_1@example.com',
                'futuretechnnology_2@example.com',
                'futuretechnnology_3@example.com',
            ],
            'address' => [
                'street' => 'Wiejska 1',
                'postcode' => '11-111',
                'city' => 'Gdańsk',
                'country' => 'Polska',
                'active' => true,
            ],
        ];
    }
}
