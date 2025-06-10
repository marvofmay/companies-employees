<?php

declare(strict_types=1);

namespace App\Module\System\Application\Console\FakeData\Data;

final readonly class Company
{

    public function getFakeData(): array
    {
        return [
            [
                'name'    => 'Future Technology',
                'nip'     => '7965645023',
                'phones'  => [
                    '111-555-555',
                    '112-555-555',
                    '113-555-555',
                ],
                'emails'  => [
                    'futuretechnnology_1@example.com',
                    'futuretechnnology_2@example.com',
                    'futuretechnnology_3@example.com',
                ],
                'address' => [
                    'street'   => 'Wiejska 1',
                    'postcode' => '11-111',
                    'city'     => 'Gdańsk',
                    'country'  => 'Polska',
                ],
            ],
            [
                'name'    => 'NCBR',
                'nip'     => '9711228630',
                'phones'  => [
                    '221-555-555',
                ],
                'emails'  => [
                    'ncbr_1@example.com',
                    'ncbr_2@example.com',
                ],
                'address' => [
                    'street'   => 'Polna 21/3c',
                    'postcode' => '22-222',
                    'city'     => 'Gdańsk',
                    'country'  => 'Polska',
                ],
            ],
            [
                'name'    => 'CPK',
                'nip'     => '7629966558',
                'phones'  => [
                    '331-555-555',
                    '332-555-555'
                ],
                'emails'  => [
                    'cpk_1@example.com',
                ],
                'address' => [
                    'street'   => 'Niewidoczna 3',
                    'postcode' => '33-333',
                    'city'     => 'Warszawa',
                    'country'  => 'Polska',
                ],
            ],
        ];
    }
}
