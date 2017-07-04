<?php

return [

    'product/([0-9]+)' => 'product/view/$1',

    'comrat/addcomm' => 'comrat/addcomm',
    'comrat/addrat' => 'comrat/addrat',
    'comrat/delete' => 'comrat/delete',

    'register' => 'user/register',
    'login'    => 'user/login',
    'logout' => 'user/logout',

    'index.php/page-([0-9]+)' => 'product/index/$1',
    'index.php' => 'product/index',

    'page-([0-9]+)' => 'product/index/$1',
    '' => 'product/index'
];