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
        $_SESSION['fromUrl'] = 'extendVoc';

        $this->checkLogin();

        $this->data['langs'] = Langs::getAllLangs();
        $this->data['categories'] = Categories::getAllCategories();

        if ($_POST) {
            $this->processMain();

            // konstrola vstupu jazyků
            if (isset($_POST['langs1']) && $_POST['langs1'] &&
                isset($_POST['langs2']) && $_POST['langs2']) {

                $lang1 = $_POST['langs1'];
                $lang2 = $_POST['langs2'];

                $succes1 = $lang1;
                $succes2 = $lang2;

                // prida jazyk, ktery jeste neni v databazi
                if ($lang1 == -1) {
                    if (isset($_POST['newLang1']) && $_POST['newLang1'] && strlen($_POST['newLang1']) > 1) {
                        $lang1 = $this->processText($_POST['newLang1']);
                        $succes1 = Langs::addLang($lang1);

                        if ($succes1 == -1)
                            Langs::addLang($lang1);

                        //$this->addMessage('S1='.$succes2);
                        //$this->addMessage(strlen($lang1));
                    }
                    else {
                        $succes1 = -1;
//                        $this->addMessage('Pokud volíte "Jiný" jazyk, musíte napsat který!');
//                        $this->addMessage('(Pokud se vám pole pro vyplnění nezobrazuje, povolte JavaScript)');
                        $this->data['error'][0] = 'Povinné pole';
                    }

                    //echo $lang1 . " přidan<br>";
                    $lang1 = $succes1 == -1 ? -1 : Langs::getLangID($lang1);
                }

                // prida jazyk, ktery neni v databazi
                if ($lang2 == -1) {
                    if (isset($_POST['newLang2']) && $_POST['newLang2'] && strlen($_POST['newLang2']) > 1) {
                        $lang2 = $this->processText($_POST['newLang2']);
                        $succes2 = Langs::addLang($lang2);

                        //$this->addMessage('S2='.$succes2);
                    } else {
                        $succes2 = -1;
//                        $this->addMessage('Pokud volíte "Jiný" jazyk, musíte napsat který!');
//                        $this->addMessage('Pokud se vám pole pro vyplnění nezobrazuje, povolte JavaScript)');
                        $this->data['error'][0] = 'Povinné pole';
                    }

                    //echo $lang2 . " přidan<br>";
                    $lang2 = $succes2 == -1 ? -1 : Langs::getLangID($lang2);
                }

                // kontrola jazyku
                if ($lang1 == $lang2) {
//                    $this->addMessage('Vkládáte nový překlad - jazyky NESMÍ být SHODNÉ!');
                    $this->data['error'][0] = "Jazyky nesmí být shodné";

                    return;
                }

                // kontrola
                if ($succes1 == -1 || $succes2 == -1) {
                    $this->addMessage('Chyba při práci s databází');

                    return;
                }

                // kontrola slov, pridani slov a prekladu
                if (isset($_POST['word1']) && $_POST['word1'] &&
                    isset($_POST['word2']) && $_POST['word2']) {
                    $word1 = $this->processText($_POST['word1']);
                    $word2 = $this->processText($_POST['word2']);

                    $return = false;

                    if (strlen($word1) < 1) {
//                        $this->addMessage('Pole "Slovo" musí být vyplněné');
                        $this->data['error'][1] = "Povinné pole";

                        $return = true;
                    }

                    if (strlen($word2) < 1) {
                        $this->data['error'][2] = "Povinné pole";

                        $return = true;
                    }

                    if ($return) {
                        return;
                    }

                    $category = $_POST['category'];

                    $data = array();
                    $data['lang1'] = $lang1;
                    $data['word1'] = $word1;
                    $data['lang1'] = $lang1;
                    $data['word2'] = $word2;
                    $data['category'] = $category;

                    // pri obnoveni stranky zabrani znovu uložení do databáze
                    if (isset($_SESSION['addedTrans']) && ($_SESSION['addedTrans'] == $data)) {
                        unset($_SESSION['addedTrans']);
                        return;
                    }
                    $_SESSION['addedTrans'] = $data;

                    // přidá novou kategorii do databáze
                    if ($category == 1 && isset($_POST['newCat']) && $_POST['newCat']) {
                        $category = $_POST['newCat'];

                        if (Categories::getCategoryId($category) == NULL) {
                            $category = Categories::addCategory($category);
                        }
                    }

                    $id1 = Vocabulary::addWord($word1, $lang1); //Vocabulary::getWordID($word1, $lang1);
                    if ($id1 == 0) $id1 = Vocabulary::getWordID($word1, $lang1);
                    //echo $word1 . " přidan<br>";

                    $id2 = Vocabulary::addWord($word2, $lang2); //Vocabulary::getWordID($word2, $lang2);
                    if ($id2 == 0) $id2 = Vocabulary::getWordID($word2, $lang2);
                    //echo $word2 . " přidan<br>";

                    // přidá překlad do databáze
                    if ($id1 != $id2 && ($id1 != -1 || $id2 != -1) && $lang1 != $lang2) {
                        Dictionary::addTranslation($id1,$id2, $_SESSION['user_id'], $category, $_SESSION['user_position']);

//                        echo "$id1 $id2 . $category";

                        if (isset($_POST['addToMyVoc'])) {
                            $newWordId = Dictionary::getTranslation($id1, $id2);
//                            var_dump($newWordId);
                            foreach ($newWordId as $id) {
//                                echo "Id: " . $id['id_trans'];
                                MyVoc::addToMyVoc($id['id_trans'], $_SESSION['user_id']);
                            }
                        }

                        $this->clearController();
                    }
                } else {
//                    $this->addMessage('Pole "Slovo" musí být vyplněné!');
                    $this->data['error'][1] = 'Pole "Slovo" musí být vyplněná';
                }
            }

            $this->data['langs'] = Langs::getAllLangs();
            $this->data['categories'] = Categories::getAllCategories();
        }
    }

    // Vyčistí Session
    function clearController() {
        unset($_SESSION['addedTrans']);
    }
}