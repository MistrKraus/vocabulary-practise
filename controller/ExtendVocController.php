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

        //$this->addMessage(Vocabulary::getWordID("Ahoj", 2));
        //$this->addMessage(Langs::getLangID("Čeština));

        // works
//        foreach ($this->data['langs'] as $item) :
//            echo $item['id_lang'] . " - " . $item['lang'];
//        endforeach;

        if($_POST) {
            $this->processMain('extendVoc');
//            if (isset($_POST['logout'])) {
//                $this->logout();
//            }
//
//            if (isset($_POST['login'])) {
//                $this->redirectToLogin('extendVoc');
//            }

            if (isset($_POST['langs1']) && $_POST['langs1'] &&
                isset($_POST['langs2']) && $_POST['langs2']) {

                $lang1 = $_POST['langs1'];
                $lang2 = $_POST['langs2'];

                $succes1 = $lang1;
                $succes2 = $lang2;

                //$this->addMessage("is it workin' and shit?");

                if ($lang1 == -1) {
                    if (isset($_POST['newLang1']) && $_POST['newLang1'] && strlen($_POST['newLang1']) > 1) {
                        $lang1 = $_POST['newLang1'];
                        $succes1 = Langs::addLang($lang1);

                        $this->addMessage('S1='.$succes2);
                        //$this->addMessage(strlen($lang1));
                    }
                    else {
                        $succes1 = 0;
                        $this->addMessage('Pokud volíte "Jiný" jazyk, musíte napsat který!');
                        $this->addMessage('(Pokud se vám pole pro vyplnění nezobrazuje, povolte JavaScript)');
                    }

                    $lang1 = $succes1 == 0 ? 0 : Langs::getLangID($lang1);
                }

                if ($lang2 == -1) {
                    if (isset($_POST['newLang2']) && $_POST['newLang2'] && strlen($_POST['newLang2']) > 1) {
                        $lang2 = $_POST['newLang2'];
                        $succes2 = Langs::addLang($lang2);

                        $this->addMessage('S2='.$succes2);
                    }
                    else {
                        $succes2 = 0;
                        $this->addMessage('Pokud volíte "Jiný" jazyk, musíte napsat který!');
                        $this->addMessage('Pokud se vám pole pro vyplnění nezobrazuje, povolte JavaScript)');
                    }

                    $lang2 = $succes2 == 0 ? 0 : Langs::getLangID($lang2);
                }

                if ($succes1 == $succes2) {
                    $this->addMessage('Vkládáte nový překlad - jazyky NESMÍ být SHODNÉ!');

                    return;
                }

                if ($succes1 == 0 || $succes2 == 0) {
                    $this->addMessage('Chyba při práci s databází');

                    return;
                }

                if (isset($_POST['word1']) && $_POST['word1'] &&
                    isset($_POST['word2']) && $_POST['word2']) {
                    $word1 = $_POST['word1'];
                    $word2 = $_POST['word2'];

                    if (strlen($word1) < 1 || strlen($word2) < 1) {
                        $this->addMessage('Pole "Slovo" musí být vyplněné');

                        return;
                    }

                    $id1 = Vocabulary::addWord($word1, $lang1); //Vocabulary::getWordID($word1, $lang1);
                    if ($id1 == 0) Vocabulary::getWordID($word1, $lang1);

                    $id2 = Vocabulary::addWord($word2, $lang2); //Vocabulary::getWordID($word2, $lang2);
                    if ($id2 == 0) Vocabulary::getWordID($word2, $lang2);


                    //TODO zmenit "1" na prihlaseneho uzivatele
                    if ($id1 != $id2 && ($id1 != -1 || $id2 != -1) && $lang1 != $lang2) {
                        Dictionary::addTranslation($id1, $lang1, $id2, $lang2, $_SESSION['user_id'], $_SESSION['user_position']);
                        //$this
                    }
                } else {
                    $this->addMessage('Pole "Slovo" musí být vyplněné!');
                }
            }
        }
    }
}