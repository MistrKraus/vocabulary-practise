<?php
/**
 * Created by PhpStorm.
 * User: kraus
 * Date: 07.11.2017
 * Time: 12:04
 */

class ExtendVocController extends Controller {

    function process($params) {
        // Hlavička stránky
        $this->header['title'] = 'Rozšířit Slovník';
        // Nastavení šablony
        $this->view = 'extendVoc';

        $this->data['langs'] = Langs::getAllLangs();

        // works
//        foreach ($this->data['langs'] as $item) :
//            echo $item['id_lang'] . " - " . $item['lang'];
//        endforeach;

        if($_POST) {
            if (isset($_POST['lang1']) && $_POST['lang1'] &&
                isset($_POST['lang2']) && $_POST['lang2']) {

                $lang1 = $_POST['lang1'];
                $lang2 = $_POST['lang2'];

                if ($lang1 == -1) {
                    if (isset($_POST['newLang1']) && $_POST['newLang1']) {
                        $lang1 = $_POST['newLang1'];
                        $succes1 = Langs::addLang($lang1);
                    }
                    else
                        $this->addMessage('Pokud volíte "Jiný" jazyk, musíte napsat který!');

                    $lang1 = $succes1 == -1 ? -1 : Langs::getLangID($lang1);
                }

                if ($lang2 == -1) {
                    if (isset($_POST['newLang2']) && $_POST['newLang2']) {
                        $lang2 = $_POST['newLang2'];
                        $succes2 = Langs::addLang($lang2);
                    }
                    else
                        $this->addMessage('Pokud volíte "Jiný" jazyk, musíte napsat který!');

                    $lang2 = $succes2 == -1 ? -1 : Langs::getLangID($lang2);
                }

                if ($succes1 == $succes2) {
                    $this->addMessage('Vkládáte nový překlad - jazyky NESMÍ být SHODNÉ!');

                    return;
                }

                if (isset($_POST['word1']) && $_POST['word1'] &&
                    isset($_POST['word2']) && $_POST['word2']) {


                } else {
                    $this->addMessage('Pole "Slovo" musí být vyplněné!');
                }
            }
        }
    }
}