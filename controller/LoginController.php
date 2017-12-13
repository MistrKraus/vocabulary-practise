<?php
/**
 * Created by PhpStorm.
 * User: kraus
 * Date: 12.12.2017
 * Time: 19:24
 */

class LoginController extends Controller
{

    function process($params)
    {
        // Hlavička stránky
        $this->header['title'] = 'Přihlášení';
        // Nastavení šablony
        $this->view = 'login';

        if ($_POST) {
            if (!$this->testPost()) {
                return;
            }

            $userName = $_POST['userName'];
            $passW = $_POST['passW'];
            $options = ['cost' => 12,];
            $passW = password_hash($passW, PASSWORD_BCRYPT, $options);

            $user = User::logIn($userName, $passW);
            if ($user) {
                $_SESSION['user_id'] = $user['id_user'];
                $_SESSION['user_name'] = $user['nickname'];
            } else {
                $_SESSION['user_id'] = 0;
                $this->addMessage("Chybné jméno nebo heslo.");
            }
        }
    }

    function testPost() {
        $isOk = true;

        if (!(isset($_POST['userName']) && !empty($_POST['userName']))) {
            $this->addMessage("'Uživatelské jméno' není vyplněné!");
            $isOk = false;
        }

        if (!(isset($_POST['passW']) && !empty($_POST['passW']))) {
            $this->addMessage("'Heslo' není vyplněné!");
            return false;
        }

        return $isOk;
    }
}