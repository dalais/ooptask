<?php

namespace MyClasses\components;

class Controller
{

    public $twig = null;


    public function loadTwig()
    {
        \Twig_Autoloader::register();
        $loader = new \Twig_Loader_Filesystem(ROOT.'/views');


        $this->twig = new \Twig_Environment($loader, array('debug' => true));
        if (isset($_SESSION['user'])) {
            $this->twig->addGlobal('user', $_SESSION['user']);
        }
    }
}