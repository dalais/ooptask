<?php

namespace MyClasses\components;

use Parsclick\Sessions\AutoLogin;

class Controller
{
    /*
     * @var object MySQL connections via PDO
     * */
    public $db;

    /*
     * @var object Exeption errors
     * */
    public $error;

    public $auth;
    public $twig = null;

    public function __construct()
    {

        try {
            // Configuring the database connection for PDO
            $db = new \PDO('mysql:host=localhost;dbname=sesstest', 'root', '');
            $db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            $this->db = $db;


            // Configuring database connections for ActiveRecord
            \ActiveRecord\Config::initialize(function ($cfg) {
                $cfg->set_model_directory(ROOT . '/models');
                $cfg->set_connections(array(
                    'development' => 'mysql://root:@localhost/sesstest?charset=utf8'));
            });
        } catch (\PDOException $e) {
            $this->error = $e->getMessage();
        }
    }

    public function loadTwig()
    {
        \Twig_Autoloader::register();
        $loader = new \Twig_Loader_Filesystem(ROOT . '/views');


        $this->twig = new \Twig_Environment($loader, array('debug' => true));
        if (isset($_SESSION['authenticated']) || isset($_SESSION['parsclick_auth'])) {
            // we're OK
            $this->auth = $_SESSION['username'];
        } else {
            $autologin = new AutoLogin($this->db);
            $autologin->checkCredentials();
            if (! isset($_SESSION['parsclick_auth'])) {
                $this->auth = null;
            }
        }

        $this->twig->addGlobal('auth', $this->auth);
    }
}