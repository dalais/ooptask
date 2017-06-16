<?php

class UserController extends MyClasses\components\Controller {

    public $errors;
    public $reg;

    public function actionIndex()
    {
        return true;
    }


    public function actionLogin()
    {
        if (isset($_POST['submit'])) {
            $login = isset($_POST['username']) ? $_POST['username']:null;
            $password = isset($_POST['password']) ? $_POST['password']:null;

            if (! empty($login) || ! empty($password)) {

                $auth = new MyClasses\components\AuthFilter($login, $password);

                $auth->emptyField($auth->login, $auth->password, 'Please, fill in the empty field.');
                $auth->issetUser($auth->login, 'Incorrect username or password');
                $auth->passVerify($auth->password, $auth->passwordV, 'Incorrect username or password');

                if (empty($auth->getLogErrors())) {
                    $userData = \Users::find_by_sql("SELECT username, email FROM `users`
                              WHERE username = '$auth->login' AND password = '$auth->passwordV'");
                    $userData = (array) $userData[0];

                    $_SESSION['user'] = $userData;

                } else {
                    $this->errors = $auth->getLogErrors();
                }

            }
        }

        $this->loadTwig();

        echo $this->twig->display('products.html',['error_log' => $this->errors]);
        return true;
    }


    public function actionRegister()
    {
        if (isset($_POST['submit'])) {

            $login = isset($_POST['username']) ? $_POST['username']:null;
            $password = isset($_POST['password']) ? $_POST['password']:null;
            $confirm_password = isset($_POST['cpassword']) ? $_POST['cpassword']:null;
            $email = isset($_POST['email']) ? $_POST['email'] : null;

            if (! empty($login) || ! empty($password) || ! empty($confirm_password) || ! empty($email)) {

                $login = trim($login);
                $password = trim($password);
                $confirm_password = trim($confirm_password);
                $email = trim($email);

                $reg = new \MyClasses\components\Reg($login, $password, $confirm_password, $email);

                $reg->regexLogin(MyClasses\components\Reg::LOGIN_PATTERN, $reg->login, 'Incorrect characters for login');
                $reg->regexEmail(MyClasses\components\Reg::EMAIL_PATTERN, $reg->email, 'Not real email');
                $reg->uniqueLogin($reg->login,'This login is already used');
                $reg->uniqueEmail($reg->email,'This email is already used');
                $reg->quality($reg->password, $reg->confirm_password, 'Passwords do not match.');

                if (empty($reg->getErrors())) {
                    $reg->generateHash();

                    $attributes = ['username' => $reg->login, 'password' => $reg->password, 'email' => $reg->email];

                    $user = \Users::create($attributes);
                    $user->is_valid();
                    $user->save();

                } else {
                    $this->errors = $reg->getErrors();
                }

            }
        }
        if ($_SERVER['REQUEST_URI'] = 'register') {
            $this->reg = 1;
        }
        $this->loadTwig();
        echo $this->twig->display('register.html', ['errors' => $this->errors, 'reg' => $this->reg]);
        return true;
    }


    public function actionLogout()
    {
        if(isset($_POST['logout'])) {
            unset($_SESSION['user']);
        }
        header('Location: /');
    }


}