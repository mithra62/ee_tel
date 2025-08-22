<?php

use Mithra62\Tel\Services\FormatService;

const TEL_FIELDTYPE_VERSION = '1.0.1';

return [
    'name'              => 'Telephone FieldType',
    'description'       => 'Adds Telephone specific functionality to ExpressionEngine',
    'version'           => TEL_FIELDTYPE_VERSION,
    'author'            => 'mithra62',
    'author_url'        => 'https://mithra62.com/',
    'namespace'         => 'Mithra62\Tel',
    'settings_exist'    => false,
    'fieldtypes'        => [
        'tel' => [
            'name' => 'Telephone',
            'compatibility' => 'text',
            'use' => [
                'MemberField',
            ],
        ],
    ],
    'services' => [
        'FormatService' => function ($addon) {
            return new FormatService();
        },
    ]
];
