<?php
return [
    'default' => 'rangine',
    'sandbox' => env('SMS_SANDBOX', true),
    'gateway' => [
        'melipayamak'   => [
            'website'    => 'http://melipayamak.ir',
            'webService' => 'http://melipayamak.ir/post/send.asmx?wsdl',
            'username'   => '',
            'password'   => '',
            'from'       => '',
        ],
        'rangine'   => [
            'website'    => 'https://sms.rangine.ir/',
            'webService' => 'http://ippanel.com/class/sms/wsdlservice/server.php?wsdl',
            'username'   => env('SMS_USERNAME','username '),
            'password'   => env('SMS_PASSWORD','password '),
            'from'       => env('SMS_FROM','5000 '),
        ],
    ],

];