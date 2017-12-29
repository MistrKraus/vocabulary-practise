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

        $_SESSION['description'] = "ExtendVocController";

        $this->checkLogin();

        $this->data['langs'] = Langs::getAllLangs();
        $this->data['categories'] = Categories::getAllCategories();

        if ($_POST) {
            $this->processMain('extendVoc');

            if (isset($_POST['langs1']) && $_POST['langs1'] &&
                isset($_POST['langs2']) && $_POST['langs2']) {

                $lang1 = $_POST['langs1'];
                $lang2 = $_POST['langs2'];

                $succes1 = $lang1;
                $succes2 = $lang2;

                // prida jazyk, ktery jeste neni v databazi
                if ($lang1 == -1) {
                    if (isset($_POST['newLang1']) && $_POST['newLang1'] && strlen($_POST['newLang1']) > 1) {
                        $lang1 = ucfirst($_POST['newLang1']);
                        $succes1 = Langs::addLang($lang1);

                        if ($succes1 == -1)
                            Langs::addLang($lang1);

                        //$this->addMessage('S1='.$succes2);
                        //$this->addMessage(strlen($lang1));
                    }
                    else {
                        $succes1 = -1;
                        $this->addMessage('Pokud volíte "Jiný" jazyk, musíte napsat který!');
                        $this->addMessage('(Pokud se vám pole pro vyplnění nezobrazuje, povolte JavaScript)');
                    }

                    //echo $lang1 . " přidan<br>";
                    $lang1 = $succes1 == -1 ? -1 : Langs::getLangID($lang1);
                }

                // prida jazyk, ktery neni v databazi
                if ($lang2 == -1) {
                    if (isset($_POST['newLang2']) && $_POST['newLang2'] && strlen($_POST['newLang2']) > 1) {
                        $lang2 = ucfirst($_POST['newLang2']);
                        $succes2 = Langs::addLang($lang2);

                        //$this->addMessage('S2='.$succes2);
                    }
                    else {
                        $succes2 = -1;
                        $this->addMessage('Pokud volíte "Jiný" jazyk, musíte napsat který!');
                        $this->addMessage('Pokud se vám pole pro vyplnění nezobrazuje, povolte JavaScript)');
                    }

                    //echo $lang2 . " přidan<br>";
                    $lang2 = $succes2 == -1 ? -1 : Langs::getLangID($lang2);
                }

                // kontrola jazyku
                if ($lang1 == $lang2) {
                    $this->addMessage('Vkládáte nový překlad - jazyky NESMÍ být SHODNÉ!');

                    return;
                }

                // kontrola
                if ($succes1 == -1 || $succes2 == -1) {
                    $this->addMessage('Chyba při práci s databází');

                    return;
                }

                // pridani slov a prekladu
                if (isset($_POST['word1']) && $_POST['word1'] &&
                    isset($_POST['word2']) && $_POST['word2']) {
                    $word1 = ucfirst($_POST['word1']);
                    $word2 = ucfirst($_POST['word2']);

                    if (strlen($word1) < 1 || strlen($word2) < 1) {
                        $this->addMessage('Pole "Slovo" musí být vyplněné');

                        return;
                    }

                    $category = ucfirst($_POST['category']);

                    $data = array();
                    $data['lang1'] = $lang1;
                    $data['word1'] = $word1;
                    $data['lang1'] = $lang1;
                    $data['word2'] = $word2;
                    $data['category'] = $category;

                    if (isset($_SESSION['addedTrans']) && ($_SESSION['addedTrans'] == $data)) {
                        unset($_SESSION['addedTrans']);
                        return;
                    }
                    $_SESSION['addedTrans'] = $data;

                    //echo $_POST['category'] . " - ";
                    if ($_POST['category'] == 1 && isset($_POST['newCat']) && $_POST['newCat']) {
                        $category = $_POST['newCat'];

                        $category = Categories::addCategory($category);
                        //echo $category . "<br>";
                    }

                    $id1 = Vocabulary::addWord($word1, $lang1); //Vocabulary::getWordID($word1, $lang1);
                    if ($id1 == 0) Vocabulary::getWordID($word1, $lang1);
                    //echo $word1 . " přidan<br>";

                    $id2 = Vocabulary::addWord($word2, $lang2); //Vocabulary::getWordID($word2, $lang2);
                    if ($id2 == 0) Vocabulary::getWordID($word2, $lang2);
                    //echo $word2 . " přidan<br>";

                    if ($id1 != $id2 && ($id1 != -1 || $id2 != -1) && $lang1 != $lang2) {
                        Dictionary::addTranslation($id1,$id2, $_SESSION['user_id'], $category, $_SESSION['user_position']);
                        //echo "preklad přidan<br>";
                    }
                } else {
                    $this->addMessage('Pole "Slovo" musí být vyplněné!');
                }
            }

            $this->data['langs'] = Langs::getAllLangs();
            $this->data['categories'] = Categories::getAllCategories();
        }
    }

    function clearController() {
        //echo "cistim muhaha";
    }
}