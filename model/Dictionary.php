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
            INNER JOIN (word INNER JOIN language ON language.id_lang=word.lang_id)
             ON translation.word_id1=word.id_word AND approved>0", null);
            //INNER JOIN language ON word.lang_id = language.id_lang", null);
    }

    public static function getDictionary($lang1Id, $lang2Id) {
        if ($lang1Id == $lang2Id)
            return -1;

        return Db::getAll("SELECT w1.word AS w1, l1.lang AS L1,w2.word AS w2, l2.lang AS L2 FROM translation AS t 
            JOIN word AS w1 on w1.id_word=t.word_id1 
            JOIN word AS w2 on w2.id_word=t.word_id2
            JOIN language AS L1 on w1.lang_id=L1.id_lang
            JOIN language AS L2 on w2.lang_id=L2.id_lang
            WHERE (l2.id_lang=:lang2 OR l2.id_lang=:lang1) 
            AND (l1.id_lang=:lang2 OR l1.id_lang=:lang1)
            AND approved>0",
            array('lang1'=>$lang1Id, 'lang2'=>$lang2Id));
    }

    public static function getNotapproved() {
        return Db::getAll("SELECT w1.word AS w1, l1.lang AS L1,w2.word AS w2, l2.lang AS L2 FROM translation AS t 
            JOIN word AS w1 on w1.id_word=t.word_id1 
            JOIN word AS w2 on w2.id_word=t.word_id2
            JOIN language AS L1 on w1.lang_id=L1.id_lang
            JOIN language AS L2 on w2.lang_id=L2.id_lang
            WHERE approved=0");
    }

    public static function addTranslation($word1Id, $word2Id, $user, $userPos) {
        if ($userPos == 1) {
            return $word1Id == $word2Id ? -1 : Db::insert("translation",
                array('word_id1'=>$word1Id, 'word_id2'=>$word2Id, 'user_id'=>$user, 'approved'=>1));
        }
        return $word1Id == $word2Id ? -1 : Db::insert("translation",
            array('word_id1'=>$word1Id, 'word_id2'=>$word2Id, 'user_id'=>$user));
    }

    public static function removeTranslation($transId) {
        return Db::query("DELETE * FROM translation WHERE id_trans=:id_trans", array('id_trans'=>$transId));
    }
}