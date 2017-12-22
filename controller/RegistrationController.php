<?php
/**
 * Created by PhpStorm.
 * User: kraus
 * Date: 12.12.2017
 * Time: 19:24
 */

class RegistrationController extends Controller
{

    function process($params)
    {
        // Hlavička stránky
        $this->header['title'] = 'Registrace';
        // Nastavení šablony
        $this->view = 'registration';

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
            $options = ['cost' => 12,];
            $passW = password_hash($passW, PASSWORD_BCRYPT, $options);

            $passWord = User::getUserPassword($userName);

            if (strlen($passWord["password"]) > 0) {
                $this->addMessage("Toto uživatelské jméno je bohužel obsazené.");
                return;
            }

            User::registerUser($userName, $passW);

            $_SESSION['user_id'] = Db::getLastId();
            $_SESSION['user_position'] = 2;
            $_SESSION['user_name'] = $userName;

            $this->addMessage("Uživatel $userName úspěšně zaregistrován!");

            $this->redirectBack();
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
            $isOk = false;
        }

        if (!(isset($_POST['passWA']) && !empty($_POST['passWA']))) {
            $this->addMessage("'Heslo znovu' není vyplněné");
            $isOk = false;
        }

        if ($isOk) {
            if ($_POST['passW'] != $_POST['passWA']) {
                $this->addMessage("Hesla se neshodují!");
                return false;
            }
        }

        return $isOk;
    }
}