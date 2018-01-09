<?php
/**
 * Created by PhpStorm.
 * User: kraus
 * Date: 18.11.2017
 * Time: 22:24
 */

class Vocabulary {

    // vrátí všechna slova
    public static function getAllWords() {
        $fromDb = Db::getAll("SELECT * FROM word", null);

        return $fromDb;
    }

    // vrátí všechna slova a jejich jazyky
    public static function getWordsWithLangs() {
        return Db::getAll("SELECT word.id_word AS id_word, word.word AS word, lang.lang AS lang FROM word
                            JOIN language AS lang on word.lang_id=lang.id_lang", null);
    }

    // vrátí id slova podle slova a id jazyka
    public static function getWordID($word, $langID) {
        $fromDb = Db::getFirstRow("SELECT id_word FROM word WHERE (lang_id=:lang_id AND word=:word)",
            array(':lang_id'=>$langID, ':word'=>$word));

        return $fromDb['id_word'];
    }

    // zapíše slovo
    public static function addWord($word, $langID) {
        $id = self::getWordID($word, $langID);
        if (empty($id)) {
            Db::insert("word", array('lang_id' => $langID, 'word' => $word));
        }

        return self::getWordID($word, $langID);
        //return Db::insertGetId("word", "id_word", array('lang_id'=>$langID, 'word'=>$word));
    }

    // odstraní slovo z databáze
    public static function removeWord($wordId) {
        Db::query("DELETE FROM word WHERE id_word=:id", array(':id'=>$wordId));
    }
}