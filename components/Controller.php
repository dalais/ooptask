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

        $this->getUserID();
    }



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

    public function logout_sess() {
        $_SESSION = [];
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 86400, $params['path'], $params['domain'],
            $params['secure'], $params['httponly']);
        session_destroy();
        header('Location: /');
        exit;
    }

    public function getUserID()
    {
        if (isset($_COOKIE['parsclick_auth'])) {
            $parts = explode('|', $_COOKIE['parsclick_auth']);
            $uname = $parts[0];
            $user = \Users::first(['conditions' => ['username = ?', $uname]]);
            $_SESSION['id'] = $user->id;
            $_SESSION['username'] = $user->username;
        } elseif (isset($_SESSION['authenticated'])) {
            $username = $_SESSION['username'];
            $user = \Users::first(['conditions' => ['username = ?', $username]]);
            $_SESSION['id'] = $user->id;

        }
    }

}