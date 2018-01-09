<?php
/**
 * Created by PhpStorm.
 * User: kraus
 * Date: 06.11.2017
 * Time: 18:53
 */

class VocabularyController extends Controller {

    function process($params)
    {
        // Hlavička stránky
        $this->header['title'] = 'Slovník';
        // Nastavení šablony
        $this->view = 'vocabulary';

        $_SESSION['description'] = "VocabularyController";
        $_SESSION['fromUrl'] = 'vocabulary';

        $this->checkLogin();

        // načte data
        $this->prepareData();

        // zpracuje vstup
        if ($_POST) {
            $this->processMain();

            if (isset($_SESSION['user_id'])) {
                // přidá překlad do "Můj Slovník"
                if (isset($_POST['add'])) {
                    MyVoc::addToMyVoc($_POST['add'], $_SESSION['user_id']);
                    return;
                }

                // odebere překlad z "Můj Slovník"
                if (isset($_POST['remove'])) {
                    MyVoc::removeFromMyVoc($_POST['remove'], $_SESSION['user_id']);
                    return;
                }

                // odebere překlad z databáze
                if (isset($_POST['removeTrans']) && $_SESSION['user_position']) {
                    $tranId = $_POST['removeTrans'];
                    Dictionary::removeTranslation($tranId);
                    $_SESSION['trans'] = Dictionary::getAllTraslations();
                }
            }
        }
    }

    // načte data
    function prepareData() {
        //$ws = Vocabulary::getAllWords();
        $langs = Langs::getAllLangs();
        //$categories = Categories::getAllCategories();
        $translations = Dictionary::getAllTraslations();

        $this->data['langs'] = $langs;
        $_SESSION['trans'] = $translations;

//        $words = array(array());
//        $trans = array(array());
//
//        foreach ($ws as $word) : {
//            $words[$word['id_word']] = $word;
//        } endforeach;
//
//        //var_dump($words);
////        var_dump($translations);
////        echo $translations[0]['id_trans']."\r\n";
////        echo $words[$translations[0]['word_id1']]['lang_id']."\r\n";
////        echo $words[$translations[0]['word_id1']]['word'];
//        //echo $words[0]["id_word"];
//
//        foreach ($translations as $translation) : {
//            $word_id1 = $translation['word_id1'];
//            $word_id2 = $translation['word_id2'];
//
//            foreach ($langs as $lang) : {
//                $lang_id = $lang['id_lang'];
//
//                if ($words[$word_id1]['lang_id'] == $lang_id) {
//                    $trans[$translation['id_trans']][$lang_id] = $words[$word_id1]['word'];
//                    continue;
//                }
//
//                if ($words[$word_id2]['lang_id'] == $lang_id) {
//                    $trans[$translation['id_trans']][$lang_id] = $words[$word_id2]['word'];
//                    continue;
//                }
//
//                $trans[$translation['id_trans']][$lang_id] = "-";
//            } endforeach;
//        } endforeach;
//
//        //$this->data['trans'] = $trans;
//        $_SESSION['trans'] = $trans;
//        $this->data['langs'] = $langs;
    }

    function clearController() {
        unset($_SESSION['trans']);
    }
}