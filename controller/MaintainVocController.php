<?php
/**
 * Created by PhpStorm.
 * User: kraus
 * Date: 14.12.2017
 * Time: 15:44
 */

class maintainVocController extends Controller {

    function process($params)
    {
        // Hlavička stránky
        $this->header['title'] = 'Spravovat Slovník';
        // Nastavení šablony
        $this->view = 'maintainVoc';

        $_SESSION['description'] = "MaintainVocController";

        $this->checkLogin();

        // zkonstroluje, zda je uživatel administrátor
        if ($_SESSION['user_position'] != 1) {
            $this->addMessage("To se nedělá! Fuj!");
            $this->redirect('intro');
        }

        $this->prepareData();

        if ($_POST) {
            $this->processMain();

            if (isset($_POST['addT'])) {
                Dictionary::approveTranslation($_POST['addT']);
                unset($_SESSION['trans'][$_POST['addT']]);

                return;
            }

            if (isset($_POST['removeT'])) {
                Dictionary::removeTranslation($_POST['removeT']);
                unset($_SESSION['trans'][$_POST['removeT']]);

                return;
            }

            if (isset($_POST['removeW'])) {
                Vocabulary::removeWord($_POST['removeW']);
                unset($_SESSION['words'][$_POST['removeW']]);

                return;
            }

            if (isset($_POST['removeL'])) {
                Langs::removeLang($_POST['removeL']);
                unset($_SESSION['noLangs'][$_POST['removeL']]);

                return;
            }
        }
    }

    // načte potřebná data z databáze
    function prepareData() {
        $unappTranslations = Dictionary::getUnappTranslations();
        $langs = Langs::getAllLangs();
        $_SESSION['words'] = Dictionary::getUntransWords();

        $this->data['langs'] = $langs;

        $_SESSION['noLangs'] = $this->getLangsWithnoutWords($langs);

        if (sizeof($unappTranslations) == 0) {
            return;
        }

        $_SESSION['trans'] = array(array());

        foreach ($unappTranslations as $trans) : {
            $_SESSION['trans'][$trans['id']]['id'] = $trans['id'];

            foreach ($langs as $lang) : {
                $langId = $lang['id_lang'];

                if ($trans['L1'] == $langId) {
                    $_SESSION['trans'][$trans['id']][$langId] = $trans['w1'];
                    continue;
                }

                if ($trans['L2'] == $langId) {
                    $_SESSION['trans'][$trans['id']][$langId] = $trans['w2'];
                    continue;
                }

                $_SESSION['trans'][$trans['id']][$langId] = '-';
            } endforeach;
        } endforeach;
    }

    // získá jazyky bez slov
    function getLangsWithnoutWords($langs) {
        $noLangs = array(array());

        $words = Vocabulary::getAllWords();

        foreach ($langs as $lang) : {
            $id = $lang['id_lang'];
            $noWord = true;

            foreach ($words as $word) {
                if ($word['lang_id'] == $id) {
                    $noWord = false;
                    break;
                }
            }

            if ($noWord) {
                $noLangs[$id] = $lang;
            }
        } endforeach;;

        return $noLangs;
    }

    // vyčistí session
    function clearController() {
        unset($_SESSION['trans']);
        unset($_SESSION['words']);
    }
}