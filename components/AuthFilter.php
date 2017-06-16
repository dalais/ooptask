<?php

namespace MyClasses\components;

class AuthFilter
{

    public $passwordV;
    private $err = [];


    public function __construct($login, $password)
    {
        $this->login = trim($login);
        $this->password = trim($password);
    }


    public function emptyField($login, $password, $err)
    {
        (! empty($login) && ! empty($password))?:$this->err['empty'] = $err;
    }


    public function issetUser($login, $err)
    {
        if (isset($login)) {
            $user = \Users::find(['conditions' => ['username = ?', $login]]);

        }
        isset($user) ? $this->passwordV = $user->password : $this->err['user'] = $err;
    }

    public function passVerify($password1, $password2, $err)
    {
        password_verify($password1, $password2) ? : $this->err['user'] = $err;
    }

    public function getLogErrors()
    {
        return $this->err;
    }
}