<?php

return [
    'digipay' => [
        'code' => 'digipay',
        'title' => 'دیجی‌پی (درگاه بانکی)',
        'description' => 'پرداخت امن از طریق درگاه بانکی دیجی‌پی',
        'class' => \DigipayGateway\Payment\Digipay::class,
        'active' => false,
        'sort' => 5,
    ],
];
