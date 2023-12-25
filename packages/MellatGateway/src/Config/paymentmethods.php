<?php

use MellatGateway\Payment\Mellat;

return [
    'mellat' => [
        'code' => 'mellat',
        'title' => 'Mellat',
        'description' => 'Mellat bank payment gateway',
        'class' => Mellat::class,
        'active' => true,
        'sort' => 5,
    ],
];