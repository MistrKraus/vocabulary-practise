<?php
/**
 * Created by PhpStorm.
 * User: kraus
 * Date: 07.11.2017
 * Time: 13:51
 */

class Langs {

    public static function getAllLangs() {
        $fromDb = Db::getAll("SELECT * FROM language");

        // works
//        foreach ($fromDb as $item) :
//            echo $item['id_lang'] . " - " . $item['lang'];
//        endforeach;

        return $fromDb;
    }

    public static function getLangID($lang) {
        $fromDb = Db::getFirst("SELECT FROM LANGUAGE WHERE lang = ?", array($lang));

        return $fromDb;
    }

    public static function addLang($lang) {
        $fromDb = self::getAllLangs();

        foreach ($fromDb as $item) {
            if ($item["lang"] == $lang)
                return -1;
        }

        return Db::insert("language", array('lang'=>$lang));
    }
}