<?php

return [

    'country_code' => '234', //Country code to be added
    'default' => env('SMS_API_DEFAULT_GATEWAY', 'bulksmsnigeria'), //Choose default gateway

    'bulksmsnigeria' => [
        'method' => 'GET', //Choose Request Method (GET/POST) Default:GET
        'url' => 'https://www.bulksmsnigeria.com/api/v1/sms/create?', //Base URL
        'params' => [
            'send_to_param_name' => 'to', //Send to Parameter Name
            'msg_param_name' => 'body', //Message Parameter Name
            'others' => [
                'api_token' => 'zDSs5NW0kzQdQSiRpaNU2CylEgn9HA73adT0GnVgF8xcuKuIRrKyWAjpphbi',
                'from' => 'QuickQart',
                'dnd' => 5
            ],
        ],
        'headers' => [],
        'add_code' => false, //Include Country Code (true/false)
    ],
];
