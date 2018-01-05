<?php
/**
 * Created by PhpStorm.
 * User: kraus
 * Date: 07.11.2017
 * Time: 12:00
 */

class PractiseController extends Controller {

    function process($params)
    {
        // Hlavička stránky
        $this->header['title'] = 'Procvičování';
        // Nastavení šablony
        $this->view = 'practise';

        $_SESSION['description'] = "PractiseController";
        $_SESSION['fromUrl'] = 'practise';

        $this->checkLogin();

        $this->data['langs'] = Langs::getAllLangs();

//        echo var_dump($this->header);

        if ($_POST) {
            $this->processMain('vocabulary');

            if (isset($_POST['practise'])) {
                $this->generateTest();
                return;
            }

            if (isset($_POST['check'])) {
                $this->checkTest();
                return;
            }

            if (isset($_POST['new'])) {
                unset($_SESSION['testState']);
                $this->clearController();
                return;
            }

            $this->clearController();
        }
    }

    function generateTest() {
        if (!(isset($_POST['langs1']) && $_POST['langs1'] &&
            isset($_POST['langs2']) && $_POST['langs2'] &&
            isset($_POST['transCount']) && $_POST['transCount'] &&
            $_POST['langs1'] != $_POST['langs2'])) {

            $this->addMessage("Zkontrolujte vyplnění formuláře.");

            return;
        }

        $lang1 = $_POST['langs1'];
        $lang2 = $_POST['langs2'];
//        $_SESSION['transCount'] = $_POST['transCount'];
        $transCount = $_POST['transCount'];

        //$this->data['langFrom'] = Langs::getLang($lang1)['lang'];
        $_SESSION['langFrom'] = Langs::getLang($lang1)['lang'];
        $_SESSION['langFromId'] = $lang1;

        //$this->data['langTo'] = Langs::getLang($lang2)['lang'];
        //$this->data['langToId'] = $lang2;
        $_SESSION['langTo'] = Langs::getLang($lang2)['lang'];
        $_SESSION['langToId'] = $lang2;

        if (isset($_POST['customVoc'])) {
            $_SESSION['cutomVoc'] = true;
            $trans = MyVoc::getRandomTrans($transCount, $lang1, $lang2, $_SESSION['user_id']);
        } else {
            $trans = Dictionary::getRandomTrans($transCount, $lang1, $lang2);
        }

        if ($trans == -1 ) {
            $this->addMessage("Chyba pri vytvareni testu");
            return;
        }

        if (sizeof($trans) < $transCount && sizeof($trans) != 0) {
            $this->addMessage("Ve slovníku není dostatečný počet překladů - Do testu bylo zařazeno "
                . sizeof($trans) . " z $transCount překladů");
        }

        $_SESSION['trans'] = $trans;
        $_SESSION['testState'] = 1;
    }

    function checkTest() {
        $mistakesId = array();

        $trans = $_SESSION['trans'];
        $fromLangId = $_SESSION['langFromId'];
        $_SESSION['userTrans'] = array();

//        echo var_dump($_POST);
//        echo var_dump($trans);

        foreach ($trans as $tran) {
//            echo "userTran" . $tran['id'];
            $word = ucfirst($_POST["userTran" . $tran['id']]);

            echo $word;

            if ($tran['L1'] != $fromLangId) {
                $leven = levenshtein(strtolower($tran['w1']), strtolower($word));
                $_SESSION['userTrans'][$tran['id']] = $word;

                if (isset($_SESSION['cutomVoc']) && $_SESSION['cutomVoc']) {
                    MyVoc::addStrike([$tran['id']], $_SESSION['user_id'], 0);
                }
            } else {
                $leven = levenshtein(strtolower($tran['w2']), strtolower($word));
                $_SESSION['userTrans'][$tran['id']] = $word;

                if (isset($_SESSION['cutomVoc']) && $_SESSION['cutomVoc']) {
                    //echo $tran['id'] . " " . $_SESSION['user_id'];
                    MyVoc::addStrike($tran['id'], $_SESSION['user_id'], 1);
                }
            }

            //echo $leven;

            $mistakesId[$tran['id']] = $leven;
        }

        $_SESSION['mistakesId'] = $mistakesId;
        $_SESSION['testState'] = 2;
    }

    function clearController() {
        unset($_SESSION['langFrom']);
        unset($_SESSION['langFromId']);

        unset($_SESSION['langTo']);
        unset($_SESSION['langToId']);

        unset($_SESSION['cutomVoc']);
        unset($_SESSION['trans']);
        unset($_SESSION['testState']);
        unset($_SESSION['userTrans']);
        unset($_SESSION['mistakesId']);
        unset($_SESSION['testGenerated']);
    }
}