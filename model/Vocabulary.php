<?php
/**
 * Created by PhpStorm.
 * User: kraus
 * Date: 18.11.2017
 * Time: 22:24
 */

class Vocabulary {

    public static function getAllWords() {
        $fromDb = Db::getAll("SELECT * FROM word", null);

        return $fromDb;
    }

    public static function getWordID($word, $langID) {
        $fromDb = Db::getFirstRow("SELECT id_word FROM word WHERE (lang_id=:lang_id AND word=:word)",
            array(':lang_id'=>$langID, ':word'=>$word));

        return $fromDb['id_word'];
    }

    public static function addWord($word, $langID) {
        $fromDb = self::getAllWords();

        foreach ($fromDb as $item) {
            if ($item["word"] == $word && $item["lang_id"] == $langID)
                return $item["id_word"];
        }
        Db::insert("word", array('lang_id'=>$langID, 'word'=>$word));

        return self::getWordID($word, $langID);
        //return Db::insertGetId("word", "id_word", array('lang_id'=>$langID, 'word'=>$word));
    }
}