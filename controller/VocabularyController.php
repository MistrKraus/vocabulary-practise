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

        $_SESSION['fromUrl'] = 'vocabulary';

        $ws = Vocabulary::getAllWords();
        $langs = Langs::getAllLangs();
        $translations = Dictionary::getAllTraslations();


        $this->prepareData($ws, $langs, $translations);

        if ($_POST) {
            $this->processMain('vocabulary');

//            if (isset($_POST['logout'])) {
//                $this->logout();
//            }

            if (isset($_SESSION['user_id'])) {
                if (isset($_POST['add'])) {
                    MyVoc::addToMyVoc($_POST['add'], $_SESSION['user_id']);
                    return;
                }

                if (isset($_POST['remove'])) {
                    MyVoc::removeFromMyVoc($_POST['remove'], $_SESSION['user_id']);
                    return;
                }

                if (isset($_POST['removeTrans']) && $_SESSION['user_position']) {
                    Dictionary::removeTranslation($_POST['removeTrans']);
                    return;
                }
            }
        }

//        $i = 0;
//        $continue = false;
//        foreach ($translations as $translation) : {
//            $word_id1 = $translation['word_id1'];
//            $word_id2 = $translation['word_id2'];
//
//            foreach ($trans as $tran) : {
//                foreach ($langs as $lang) : {
//                    $lang_id = $lang['id_lang'];
//
//                    if ($words[$word_id1][$lang_id] == $tran[$lang_id]) {
//                        $tran[$words[$word_id2]['lang_id']] = $words[$word_id2]['word'];
//
//                        $continue = true;
//                        break;
//                    }
//
//                    if ($words[$word_id2][$lang_id] == $tran[$lang_id]) {
//                        $tran[$words[$word_id1]['lang_id']] = $words[$word_id1]['word'];
//
//                        $continue = true;
//                        break;
//                    }
//                } endforeach;
//
//                if ($continue) $continue;
//            } endforeach;
//
////            $i++;
//        } endforeach;
//        foreach ($translations as $translation) : {
//            $trans[$translation['id_trans']][$words[$translation['word_id1']]['lang_id']] =
//                $words[$translation['word_id1']]['word'];
//            $trans[$translation['id_trans']][$words[$translation['word_id2']]['lang_id']] =
//                $words[$translation['word_id2']]['word'];
//        } endforeach;

//  WORKS:
//        SELECT * FROM `translation`
//        INNER JOIN (word INNER JOIN language ON language.id_lang=word.lang_id) ON translation.word_id1=word.id_word;

    }

    function prepareData($ws, $langs, $translations) {
        $words = array(array());
        $trans = array(array());

        foreach ($ws as $word) : {
            $words[$word['id_word']] = $word;
        } endforeach;

        //var_dump($words);
//        var_dump($translations);
//        echo $translations[0]['id_trans']."\r\n";
//        echo $words[$translations[0]['word_id1']]['lang_id']."\r\n";
//        echo $words[$translations[0]['word_id1']]['word'];
        //echo $words[0]["id_word"];

        foreach ($translations as $translation) : {
            $word_id1 = $translation['word_id1'];
            $word_id2 = $translation['word_id2'];

            foreach ($langs as $lang) : {
                $lang_id = $lang['id_lang'];

                if ($words[$word_id1]['lang_id'] == $lang_id) {
                    $trans[$translation['id_trans']][$lang_id] = $words[$word_id1]['word'];
                    continue;
                }

                if ($words[$word_id2]['lang_id'] == $lang_id) {
                    $trans[$translation['id_trans']][$lang_id] = $words[$word_id2]['word'];
                    continue;
                }

                $trans[$translation['id_trans']][$lang_id] = "-";
            } endforeach;
        } endforeach;

        $this->data['trans'] = $trans;
        $this->data['langs'] = $langs;
    }
}