<?php

namespace MyClasses\components;

class Reg
{
    public $confirm_password;

    private $err = [];


    const LOGIN_PATTERN='/^[a-zA-Z][a-zA-Z0-9-_\.]{1,20}$/';
    const EMAIL_PATTERN='/^[^0-9][A-z0-9_]+([.][A-z0-9_]+)*[@][A-z0-9_]+([.][A-z0-9_]+)*[.][A-z]{2,4}$/';

    public function __construct($login, $password, $confirm_password, $email)
    {
        $this->login = (string) trim($login);
        $this->password = (string) str_replace(" ", "_",$password);
        $this->confirm_password = (string) str_replace(" ", "_",$confirm_password);
        $this->email = (string) trim($email);
    }


    public function quality($one, $two, $err)
    {
        $one == $two?:$this->err['cpassword'] = $err;
    }

    public function uniqueLogin($login, $err)
    {
        empty(\Users::all(['conditions' => ['username = ?', $login]]))?:$this->err['username'] = $err;
    }

    public function uniqueEmail($email, $err)
    {
        empty(\Users::all(['conditions' => ['email = ?', $email]]))?:$this->err['email'] = $err;
    }



    public function regexLogin($pattern, $field, $err)
    {
        preg_match($pattern, $field) ? : $this->err['regex_login'] = $err;
    }

    public function regexEmail($pattern, $field, $err)
    {
        preg_match($pattern, $field) ? : $this->err['regex_email'] = $err;
    }

    public function resetUkey($ukey)
    {
        $find_ukey = \Users::find(['conditions' => ['user_key = ?', $ukey]]);

        if (! empty($find_ukey)) {

            $ukey = hash('crc32', microtime(true) . mt_rand() . $this->login);

        }
        return $ukey;
    }


    public function getErrors()
    {
        return $this->err;
    }


    public function generateSalt($int)
    {
        $chars = 'qwertyuiopasdfghjklzxcvbnm0123456789QWERTYUIOPASDFGHJKLZXCVBNM<>?;][\/-=)(+';
        $size = strlen($chars);
        $salt = '';
        while($int--){
            $salt.=$chars[rand(0,$size)];
        }
        return $salt;

    }


    public function generateHash($algo = PASSWORD_DEFAULT, array $options = null)
    {

        !is_null($options)?:$options = [
            'salt' => $this->generateSalt(22),
            'cost' => 10
        ];

        $this->password = password_hash($this->password, $algo, $options);
    }
}