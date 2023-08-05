<?php

use ACECRGateway\Payment\ACECR;

return [
    'acecr' => [
        'code' => 'acecr',
        'title' => 'ACECR',
        'description' => 'ACECR bank payment gateway',
        'class' => ACECR::class,
        'active' => true,
        'sort' => 1,
    ],
];