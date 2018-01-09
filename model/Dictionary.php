<?php
/**
 * Created by PhpStorm.
 * User: kraus
 * Date: 01.12.2017
 * Time: 23:15
 */

class Dictionary {

    // Vrati vsechny schvalene preklady
    public static function getAllTraslations() {
        return Db::getAll("SELECT t.id_trans AS id, w1.word AS w1, l1.id_lang AS L1,
                w2.word AS w2, l2.id_lang AS L2, cat.name AS category, t.user_id FROM translation AS t 
            JOIN category AS cat on cat.id_category=t.category_id
            JOIN word AS w1 on w1.id_word=t.word_id1 
            JOIN word AS w2 on w2.id_word=t.word_id2
            JOIN language AS L1 on w1.lang_id=L1.id_lang
            JOIN language AS L2 on w2.lang_id=L2.id_lang
        AND approved=1",null);
    }

    // vrátí konkrétní překlad
    public static function getTranslation($wordId1, $wordId2) {
        return Db::getAll("SELECT id_trans FROM translation 
              WHERE (word_id1=:id1 AND word_id2=:id2) OR (word_id1=:id3 AND word_id2=:id4)",
            array(':id1'=>$wordId1, ':id2'=>$wordId2, ':id3'=>$wordId2, ':id4'=>$wordId1));
    }

    // Vrati vsechny neschvalene preklady
    public static function getUnappTranslations() {
        return Db::getAll("SELECT t.id_trans AS id, w1.word AS w1, l1.id_lang AS L1,w2.word AS w2, l2.id_lang AS L2 FROM translation AS t 
            JOIN word AS w1 on w1.id_word=t.word_id1 
            JOIN word AS w2 on w2.id_word=t.word_id2
            JOIN language AS L1 on w1.lang_id=L1.id_lang
            JOIN language AS L2 on w2.lang_id=L2.id_lang
            AND approved=0",null);
    }

    // Vratio vsechna slova bez prekladu
    public static function getUntransWords() {
        $words = Vocabulary::getWordsWithLangs();

        $untransW = array(array());

        foreach ($words as $word) : {
            $id = $word['id_word'];
            $translation = Db::getAll("SELECT * FROM translation WHERE 
              word_id1=:id1 OR word_id2=:id2", array(':id1'=>$id, ':id2'=>$id));
            if (sizeof($translation) == 0) {
                $untransW[$id] = $word;
            }
        } endforeach;

        return $untransW;
    }

    // vrati vsechna preklady mezi dvema jazyky
    public static function getDictionary($lang1Id, $lang2Id) {
        if ($lang1Id == $lang2Id)
            return -1;

        return Db::getAll("SELECT w1.word AS w1, l1.lang AS L1,w2.word AS w2, l2.lang AS L2 FROM translation AS t 
            JOIN word AS w1 on w1.id_word=t.word_id1 
            JOIN word AS w2 on w2.id_word=t.word_id2
            JOIN language AS L1 on w1.lang_id=L1.id_lang
            JOIN language AS L2 on w2.lang_id=L2.id_lang
            WHERE (l2.id_lang=:lang1 OR l2.id_lang=:lang2) 
            AND (l1.id_lang=:lang3 OR l1.id_lang=:lang4)
            AND approved>0",
            array('lang1'=>$lang1Id, 'lang2'=>$lang2Id, 'lang3'=>$lang1Id, 'lang4'=>$lang2Id));
    }

    // vrati náhodné překlady prekladu mezi dvema jazyky
    public static function getRandomTrans($transCount, $lang1Id, $lang2Id) {
        if ($lang1Id == $lang2Id) {
            return -1;
        }

        return Db::getAll("SELECT t.id_trans AS id, w1.word AS w1, l1.id_lang AS L1,w2.word AS w2, l2.id_lang AS L2
            FROM translation AS t 
            JOIN word AS w1 on w1.id_word=t.word_id1 
            JOIN word AS w2 on w2.id_word=t.word_id2
            JOIN language AS L1 on w1.lang_id=L1.id_lang
            JOIN language AS L2 on w2.lang_id=L2.id_lang
            WHERE (w1.lang_id=:lang1 OR w1.lang_id=:lang2) 
            AND (w2.lang_id=:lang3 OR w2.lang_id=:lang4)
            AND approved>0
            ORDER BY RAND() LIMIT $transCount",
            array('lang2'=>$lang1Id, 'lang1'=>$lang2Id, 'lang3'=>$lang1Id, 'lang4'=>$lang2Id));
    }

    // vrati vsechny neschvalene preklady
    public static function getNotapproved() {
        return Db::getAll("SELECT w1.word AS w1, l1.lang AS L1,w2.word AS w2, l2.lang AS L2 FROM translation AS t 
            JOIN word AS w1 on w1.id_word=t.word_id1 
            JOIN word AS w2 on w2.id_word=t.word_id2
            JOIN language AS L1 on w1.lang_id=L1.id_lang
            JOIN language AS L2 on w2.lang_id=L2.id_lang
            WHERE approved=0");
    }

    // prida preklad - pokud jej pridava administrator, je pridan automaticky
    public static function addTranslation($word1Id, $word2Id, $user, $catId, $userPos) {
        if ($userPos == 1) {
            $word1Id == $word2Id ? -1 : Db::insert("translation",
                array('word_id1'=>$word1Id, 'word_id2'=>$word2Id, 'user_id'=>$user, 'category_id'=>$catId, 'approved'=>1));
            return;
        }
        $word1Id == $word2Id ? -1 : Db::insert("translation",
            array('word_id1'=>$word1Id, 'word_id2'=>$word2Id, 'user_id'=>$user, 'category_id'=>$catId));
        return;
    }

    // zapíše schválení překladu
    public static function approveTranslation($transId) {
//        Db::update("translation", array('approved'=>1), "WHERE `id_trans`= ?", array('id_trans'=>$transId));
        Db::query("UPDATE translation SET approved=:app WHERE id_trans=:id", array(':app'=>1, ':id'=>$transId));
    }

    // odstraní překlad
    public static function removeTranslation($transId) {
        return Db::query("DELETE FROM translation WHERE id_trans=:id_trans", array(':id_trans'=>$transId));
    }

    // vrátí počet překladů
    public static function getTranslationsCount() {
        return Db::count("translation");
    }
}