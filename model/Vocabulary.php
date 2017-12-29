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

    public static function getWordsWithLangs() {
        return Db::getAll("SELECT word.id_word AS id_word, word.word AS word, lang.lang AS lang FROM word
                            JOIN language AS lang on word.lang_id=lang.id_lang", null);
    }

//    public static function getLangsWithoutWords() {
//        $query = "SELECT l.id_lang AS id_lang, l.lang AS lang FROM word AS w
//                    JOIN LANGUAGE AS l on w.lang_id";
//        echo var_dump(Db::query($query, null));
//
//        return array(array());
//    }

    public static function getWordID($word, $langID) {
        $fromDb = Db::getFirstRow("SELECT id_word FROM word WHERE (lang_id=:lang_id AND word=:word)",
            array(':lang_id'=>$langID, ':word'=>$word));

        return $fromDb['id_word'];
    }

    public static function addWord($word, $langID) {
//        $fromDb = self::getAllWords();
//
//        foreach ($fromDb as $item) {
//            if ($item["word"] == $word && $item["lang_id"] == $langID)
//                return $item["id_word"];
//        }
        $id = self::getWordID($word, $langID);
        if (empty($id)) {
            Db::insert("word", array('lang_id' => $langID, 'word' => $word));
        }

        return self::getWordID($word, $langID);
        //return Db::insertGetId("word", "id_word", array('lang_id'=>$langID, 'word'=>$word));
    }

    public static function removeWord($wordId) {
        Db::query("DELETE FROM word WHERE id_word=:id", array(':id'=>$wordId));
    }
}