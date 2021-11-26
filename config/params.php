<?php

use kartik\datecontrol\Module;

return [
    'bsVersion' => '4.x',
    'adminEmail' => 'ullriher@fel.cvut.cz',
    'contactEmail' => 'ullriher@fel.cvut.cz',
    'senderEmail' => 'ullriher@fel.cvut.cz',
    'senderName' => 'Fcheck Anotace mailer',
    'dateControlDisplay' => [
        Module::FORMAT_DATE => 'dd.MM.yyyy',
        Module::FORMAT_TIME => 'hh:mm:ss a',
        Module::FORMAT_DATETIME => 'dd-MM-yyyy hh:mm:ss a',
    ],
    'dateControlSave' => [
        Module::FORMAT_DATE => 'php:Y-m-d',
        Module::FORMAT_TIME => 'php:H:i:s',
        Module::FORMAT_DATETIME => 'php:Y-m-d H:i:s',
    ],
    //'sandbox' => json_decode(file_get_contents(__DIR__ . '/datasets/sandbox.json'),true),
    //'live' => json_decode(file_get_contents(__DIR__ . '/datasets/live.json'),true),
    //'entities' => json_decode(file_get_contents(__DIR__ . '/datasets/entities.json'),true),
];
