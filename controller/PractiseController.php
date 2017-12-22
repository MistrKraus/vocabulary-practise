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

        $this->data['langs'] = Langs::getAllLangs();

        $this->data['testGenerated'] = false;

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


        }
    }

    function generateTest() {
        if (!(isset($_POST['langs1']) && $_POST['langs1'] &&
            isset($_POST['langs2']) && $_POST['langs2'] &&
            isset($_POST['transCount'])) && $_POST['transCount'] &&
            $_POST['$lang1'] != $_POST['langs2']) {
            $this->addMessage("Zkontrolujte vyplnění formuláře.");

            return;
        }

        echo $_POST['customVoc'];

        $this->data['langFromId'] = $_POST['langs1'];



        $lang2 = $_POST['langs2'];



//        $lang1;
//        $lang2;
//        $transCount;
//        $useMyVoc;

        $this->data['testGenerated'] = true;
    }

    function checkTest() {

    }
}