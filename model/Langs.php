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
        $fromDb = Db::getFirstRow("SELECT id_lang FROM language WHERE lang=:lang",
            array(':lang'=>$lang));

//        foreach ($fromDb as $item) :
//            return $item['id_lang'];
//        endforeach;

        return $fromDb['id_lang'];
    }

    public static function addLang($lang) {
        $fromDb = self::getAllLangs();

        foreach ($fromDb as $item) {
            if ($item["lang"] == $lang)
                return $item["id_lang"];
        }

        return Db::insert("language", array('lang'=>$lang));
    }
}