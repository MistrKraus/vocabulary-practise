<?php
/**
 * Created by PhpStorm.
 * User: kraus
 * Date: 01.12.2017
 * Time: 23:15
 */

class Dictionary {

    public static function getAllTraslations() {
        return Db::getAll("SELECT * FROM `translation`
INNER JOIN (word INNER JOIN language ON language.id_lang=word.lang_id) ON translation.word_id1=word.id_word", null);
            //INNER JOIN language ON word.lang_id = language.id_lang", null);
    }

    public static function getDictionary($lang1Id, $lang2Id) {
        if ($lang1Id == $lang2Id)
            return -1;

        if ($lang1Id > $lang2Id) {
            $temp = $lang1Id;
            $lang1Id = $lang2Id;
            $lang2Id = $temp;
        }

        return Db::getAll("SELECT * word.word, language.lang FROM 'translatin' WHERE word_id1=? AND word_id2=?",
            array('word_id1'=>$lang1Id, 'word_id2'=>$lang2Id));
    }



    public static function addTranslation($word1Id, $lang1Id, $word2Id, $lang2Id, $user) {
        return $word1Id == $word2Id ? -1 : Db::insert("translation",
            array('word_id1'=>$word1Id, 'word_id2'=>$word2Id, 'user_id'=>$user));
    }
}