<?php

return [
    'name'              => 'Telephone FieldType',
    'description'       => 'Adds Telephone specific functionality to ExpressionEngine',
    'version'           => '1.0.0',
    'author'            => 'mithra62',
    'author_url'        => 'https://mithra62.com/',
    'namespace'         => 'Mithra62\Tel',
    'settings_exist'    => false,
    'fieldtypes'        => [
        'tel' => [
            'name' => 'Telephone',
            'compatibility' => 'text',
        ],
    ], 
];
