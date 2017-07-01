<?php

use Parsclick\Sessions\AutoLogin;
class UserController extends MyClasses\components\Controller
{

    /*
     * @var array Array errors
     * */
    public $errors = [];

    /*
     * @var The variable passed to the template to hide some elements.
     * */
    public $reg;

    /*
     * @var int Variable initialization successful registration (for the template).
     * */
    private $registered = null;


    /*
     * @var string Unique key user
     * */
    private $user_key = null;


    public $user_name = null;
    /*
     *
     * */
    public function actionIndex()
    {
        return true;
    }


    /**
     * @return bool
     */
    public function actionLogin()
    {

        if (isset($_POST['login'])) {

            $login = isset($_POST['username']) ? $_POST['username'] : null;
            $password = isset($_POST['password']) ? $_POST['password'] : null;


            $auth = new MyClasses\components\AuthFilter($login, $password);

            $auth->emptyField($auth->login, $auth->password, 'Please, fill in the empty field.');
            $auth->issetUser($auth->login, 'Incorrect username or password');
            $auth->passVerify($auth->password, $auth->passwordV, 'Incorrect username or password');

            if (empty($auth->getLogErrors())) {
                session_regenerate_id(true);

                $user = \Users::find('first',['conditions' => ['username = ? AND password = ?', $auth->login, $auth->passwordV]]);

                $_SESSION['username'] = $user->username;
                $_SESSION['authenticated'] = true;
                $_SESSION['id'] = $user->id;

                if (isset($_POST['remember'])) {
                    // create persistent login
                    $autologin = new AutoLogin($this->db);
                    $autologin->persistentLogin();
                }
                header("Location: /");

            } else {
                $this->errors = $auth->getLogErrors();
            }


        }

        $this->loadTwig();

        echo $this->twig->display('products.html', ['error_log' => $this->errors]);
        return true;
    }


    /**
     * @return bool
     */
    public function actionRegister()
    {
        if (isset($_POST['reg'])) {

            $login = isset($_POST['username']) ? $_POST['username'] : null;
            $password = isset($_POST['password']) ? $_POST['password'] : null;
            $confirm_password = isset($_POST['cpassword']) ? $_POST['cpassword'] : null;
            $email = isset($_POST['email']) ? $_POST['email'] : null;


            if (! empty($login) && ! empty($password) && ! empty($confirm_password) && ! empty($email)) {


                $reg = new \MyClasses\components\Reg($login, $password, $confirm_password, $email);

                $reg->regexLogin(MyClasses\components\Reg::LOGIN_PATTERN, $reg->login, 'Incorrect characters for login');
                $reg->regexEmail(MyClasses\components\Reg::EMAIL_PATTERN, $reg->email, 'Not real email');
                $reg->uniqueLogin($reg->login, 'This login is already used');
                $reg->uniqueEmail($reg->email, 'This email is already used');
                $reg->quality($reg->password, $reg->confirm_password, 'Passwords do not match.');

                if (empty($reg->getErrors())) {

                    $reg->generateHash();

                    $user_key = hash('crc32', microtime(true) . mt_rand() . $reg->login);
                    $this->user_key = $reg->resetUkey($user_key);

                    $attributes = ['user_key' => $this->user_key, 'username' => $reg->login, 'password' => $reg->password, 'email' => $reg->email];

                    $user = \Users::create($attributes);
                    $user->is_valid();
                    $user->save();
                    $this->registered = 1;

                } else {
                    $this->errors = $reg->getErrors();
                }

            }
        }

        $this->loadTwig();
        if (is_null($this->registered)) {
            echo $this->twig->display('register.html', ['errors' => $this->errors, 'reg' => $this->reg]);
        } else {
            echo $this->twig->display('success.html');
        }

        return true;
    }


    /**
     *
     */
    public function actionLogout()
    {
        if (isset($_POST['cancel'])) {
            header('Location: /');
            exit;
        }
        if (isset($_POST['logout'], $_SESSION['remember']) || isset($_POST['logout'], $_SESSION['parsclick_auth'])) {

            $this->loadTwig();
            echo $this->twig->display('logout.html', ['out' => 'logout']);
        } elseif (isset($_POST['single']) || isset($_POST['all'])) {
            $autologin = new AutoLogin($this->db);
            if (isset($_POST['single'])) {
                $autologin->logout(false);
            } else {
                $autologin->logout(true);
            }
            $this->logout_sess();
        } elseif (isset($_POST['logout'])) {
            $this->logout_sess();
        }
        return true;
    }


}