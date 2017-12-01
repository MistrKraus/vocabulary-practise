<?php
/**
 * Created by PhpStorm.
 * User: kraus
 * Date: 18.11.2017
 * Time: 22:24
 */

class Dictionary {

    public static function getAllWords() {
        $fromDb = Db::getAll("SELECT * FROM word", null);

        return $fromDb;
    }

    public static function getWordID($word, $langID) {
        $fromDb = Db::getFirst("SELECT FROM word WHERE word=? AND lang_id=?",
            array('word'=>$word, 'lang_id'=>$langID));

        return $fromDb;
    }

    public static function addWord($word, $langID) {


        Db::insert("word", array('lang_id'=>$langID, 'word'=>$word));
    }
}