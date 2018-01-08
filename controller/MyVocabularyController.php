<?php
/**
 * Created by PhpStorm.
 * User: kraus
 * Date: 07.11.2017
 * Time: 12:01
 */

class MyVocabularyController extends Controller {

    function process($params)
    {
        // Hlavička stránky
        $this->header['title'] = 'Můj Slovník';
        // Nastavení šablony
        $this->view = 'myVocabulary';

        $_SESSION['description'] = 'MyVocabularyController';
        $_SESSION['fromUrl'] = 'myVocabulary';

        $this->checkLogin();

        if (isset($_SESSION['user_id'])) {
            $userId = $_SESSION['user_id'];

            $_SESSION['fromUrl'] = 'myVocabulary';
            $_SESSION['trans'] = MyVoc::getMyVoc($userId);
            $this->data['langs'] = Langs::getAllLangs();//$this->getLangs();
        }

        if ($_POST) {
            $this->processMain('myVocabulary');

            if(!isset($_SESSION['user_id'])) {
                return;
            }

            if (isset($_POST['strike'])) {
                $tranId = $_POST['strike'];
                MyVoc::updateStrike($tranId, $_POST[$tranId]);
                //var_dump($_SESSION['trans']);
                $_SESSION['trans'] = MyVoc::getMyVoc($_SESSION['user_id']);
                $this->data['saved'][$tranId] = true;
            }

            if (isset($_POST['remove'])) {
                MyVoc::removeFromMyVoc($_POST['remove'], $_SESSION['user_id']);
                $_SESSION['trans'] = MyVoc::getMyVoc($_SESSION['user_id']);
            }
        }
    }

    function getLangs() {
        $langs = Langs::getAllLangs();
        $trans = $_SESSION['trans'];
        $noLangs = array(array());

        foreach ($langs as $lang) : {
            $id = $lang['id_lang'];
            $noWord = true;

            foreach ($trans as $tran) : {
                if ($tran['L1'] == $id || $tran['L2'] == $id) {
                    $noWord = false;
                    break;
                }
            } endforeach;

            if ($noWord) {
                $noLangs[$id] = $lang;
            }
        } endforeach;

        foreach ($noLangs as $noLang) : {
            unset($noLang, $langs);
        } endforeach;

        if (!isset($langs)) {
            $langs = array(array());
        }

        return $langs;
    }

    function clearController() {
        unset($_SESSION['trans']);
    }
}