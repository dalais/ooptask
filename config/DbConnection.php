<?php

ActiveRecord\Config::initialize(function ($cfg)
{
    $cfg->set_model_directory(ROOT.'/models');
    $cfg->set_connections(array(
        'development' => 'mysql://root:@localhost/ooptask?charset=utf8'));
});