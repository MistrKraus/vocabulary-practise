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

        if (isset($_SESSION['user_id'])) {
            $this->redirect('intro');
        }

        if ($_POST) {
            $this->processMain('vocabulary');
//            if (isset($_POST['logout'])) {
//                $this->logout();
//            }

            if (!$this->testPost()) {
                return;
            }

            $userName = $_POST['userName'];
            $passW = $_POST['passW'];

            $password = User::getUserPassword($userName)['password'];

            if (strlen($password) == 0) {
                $this->addMessage("Chybné uživatelské jméno!");
                return;
            }

            if (password_verify($passW, $password)) {
                $user = User::logIn($userName);

                $_SESSION['user_id'] = $user['id_user'];
                $_SESSION['user_position'] = $user['position_id'];
                $_SESSION['user_name'] = $user['nickname'];

                $this->redirectBack();
            } else {
                //$_SESSION['user_id'] = 0;
                $this->addMessage("Chybné heslo");
            }
        }
    }

    function testPost() {
        if (isset($_SESSION['user_id'])) {
            return false;
        }

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