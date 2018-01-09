<?php
/**
 * Created by PhpStorm.
 * User: kraus
 * Date: 07.11.2017
 * Time: 13:51
 */

class Langs {

    // vrátí všechny překlady
    public static function getAllLangs() {
        $fromDb = Db::getAll("SELECT * FROM language");

        return $fromDb;
    }

    // vrátí id jazyka
    public static function getLangID($lang) {
        $fromDb = Db::getFirstRow("SELECT id_lang FROM language WHERE lang=:lang",
            array(':lang'=>$lang));

        return $fromDb['id_lang'];
    }

    // vrátí název jazyka v asociativním poli
    public static function getLang($langId) {
        return Db::getFirstRow("SELECT lang FROM language WHERE id_lang=:lang",
            array(':lang'=>$langId));
    }

    // zapíše jazyk
    public static function addLang($lang) {
        $fromDb = self::getAllLangs();

        foreach ($fromDb as $item) {
            if ($item["lang"] == $lang)
                return $item["id_lang"];
        }

        return Db::insert("language", array('lang'=>$lang));
    }

    // odstraní jazyk
    public static function removeLang($langId) {
        Db::query("DELETE FROM language WHERE id_lang=:id", array(':id'=>$langId));
    }
}