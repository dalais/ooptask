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

    /*
     * @var object Takes an instance of the Twig Environment
     * */
    public $twig = null;


    public function __construct()
    {
        $this->connectPDO();
        $this->initAR();
        $this->getUserID();
    }

    /*
     * Configuring the database connection for PDO
     * */
    public function connectPDO()
    {
        try {
            $db = new \PDO('mysql:host=localhost;dbname=apptest', 'root', '');
            $db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            $this->db = $db;

        } catch (\PDOException $e) {
            $this->error = $e->getMessage();
        }
    }

    /*
     * Configuring ActiveRecord
     *
     * */
    public function initAR()
    {
        \ActiveRecord\Config::initialize(function ($cfg) {
            $cfg->set_model_directory(ROOT . '/models');
            $cfg->set_connections(array(
                'development' => 'mysql://root:@localhost/apptest?charset=utf8'));
        });
    }


    /*
     * Configuring Twig to load templates
     * */
    public function loadTwig()
    {
        $loader = new \Twig_Loader_Filesystem(ROOT . '/views');

        $this->twig = new \Twig_Environment($loader);


        if (isset($_SESSION['authenticated']) || isset($_SESSION['parsclick_auth'])) {

            $username = isset($_SESSION['username']) ? $_SESSION['username'] : null;
            $user_id = isset($_SESSION['id']) ? $_SESSION['id'] : null;

            $this->twig->addGlobal('auth', $username);
            $this->twig->addGlobal('user_id', $user_id);

        } else {
            $autologin = new AutoLogin($this->db);
            $autologin->checkCredentials();
            if (! isset($_SESSION['parsclick_auth'])) {
                $this->twig->addGlobal('auth', null);
                $this->twig->addGlobal('user_id', null);
            }
        }
    }


    /*
     * Logout sessions
     * */
    public function logout_sess() {
        $_SESSION = [];
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 86400, $params['path'], $params['domain'],
            $params['secure'], $params['httponly']);
        session_destroy();
        header('Location: /');
        exit;
    }


    /*
     * Get the ID of an authorized user
     * */
    public function getUserID()
    {
        if (isset($_COOKIE['parsclick_auth'])) {
            $parts = explode('|', $_COOKIE['parsclick_auth']);
            $uname = $parts[0];
            $user = \Users::first(['conditions' => ['username = ?', $uname]]);
            $_SESSION['id'] = $user->id;
            $_SESSION['username'] = $user->username;
        }
    }

}