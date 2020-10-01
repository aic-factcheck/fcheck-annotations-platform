<?php

use kartik\datecontrol\Module;

return [
    'bsVersion' => '4.x',
    'adminEmail' => 'meloun.jack@gmail.com',
    'contactEmail' => 'ja@bertik.net',
    'senderEmail' => 'svobodova@bertik.net',
    'senderName' => 'LucieSvobodova.works mailer',
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
    'tinymce' => require __DIR__ . '/tinymce.php'
];
