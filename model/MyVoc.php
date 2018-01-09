<?php
/**
 * Created by PhpStorm.
 * User: kraus
 * Date: 22.12.2017
 * Time: 18:47
 */

class MyVoc {

    // vrátí všechny překlady z "Můj Slovník"
    public static function getMyVoc($userId) {
        return Db::getAll("SELECT pv.id_pers_voc AS id, w1.word AS w1, L1.id_lang AS L1, w2.word AS w2, L2.id_lang AS L2,
            cat.name AS category, t.id_trans AS tran_id, pv.strike AS strike, pv.user_id AS user_id
            FROM personal_vocabulary AS pv
            JOIN translation AS t on t.id_trans=pv.trans_id
            JOIN category AS cat on cat.id_category=t.category_id
            JOIN word AS w1 on w1.id_word=t.word_id1
            JOIN word AS w2 on w2.id_word=t.word_id2
            JOIN language AS L1 on w1.lang_id=L1.id_lang
            JOIN language AS L2 on w2.lang_id=L2.id_lang
            WHERE pv.user_id=:user_id", array(':user_id'=>$userId));
    }

    // vrátí konkrétní překlad
    public static function getTransFromMyVoc($transId, $userId) {
        return Db::getAll("SELECT user_id FROM personal_vocabulary WHERE trans_id=:trans_id AND user_id=:user_id", array('trans_id'=>$transId, 'user_id'=>$userId));//
            //array('trans_id'=>$transId));
    }

    // varati id uzivatele, ktery ma preklad ulozeny
    public static function getSomebodiesTranIdById($transId) {
        return Db::getAll("SELECT user_id FROM personal_vocabulary WHERE trans_id=:trans_id", array('trans_id'=>$transId));
    }

    // vrátí náhodný překlad
    public static function getRandomTrans($transCount, $lang1Id, $lang2Id, $userId) {
        return Db::getAll("SELECT pv.id_pers_voc AS id, w1.word AS w1, l1.id_lang AS L1,w2.word AS w2, l2.id_lang AS L2,
            strike FROM personal_vocabulary AS pv
            JOIN translation AS t on t.id_trans=pv.trans_id
            JOIN word AS w1 on w1.id_word=t.word_id1 
            JOIN word AS w2 on w2.id_word=t.word_id2
            JOIN language AS L1 on w1.lang_id=L1.id_lang
            JOIN language AS L2 on w2.lang_id=L2.id_lang
            WHERE (l2.id_lang=:lang1 OR l2.id_lang=:lang2)
            AND (l1.id_lang=:lang3 OR l1.id_lang=:lang4)
            AND pv.user_id=:user_id
            AND strike < 5
            ORDER BY RAND() LIMIT $transCount",
            array('lang1'=>$lang1Id, 'lang2'=>$lang2Id, 'lang3'=>$lang1Id, 'lang4'=>$lang2Id, 'user_id'=>$userId));
    }

    // přidá překlad do "Můj Slovník"
    public static function addToMyVoc($transId, $userId) {
//        echo $transId . "<br>";
        Db::insert("personal_vocabulary", array('user_id'=>$userId, 'trans_id'=>$transId));
    }

    // odstraní překlad z "Můj Slovník"
    public static function removeFromMyVoc($transId, $userId) {
        return Db::query("DELETE FROM personal_vocabulary WHERE trans_id=:trans_id AND user_id=:user_id",
            array('trans_id'=>$transId, 'user_id'=>$userId));
    }

    // podle id prekladu a uzivatele prida hodnotu 'plus' ke striku, pokud 'plus' je rovno 0 vynuluje strike
    public static function addStrike($transId, $user_id, $plus) {
        if ($plus == 0) {
            Db::query("UPDATE personal_vocabulary SET strike=0 WHERE id_pers_voc=:id AND user_id=:userId", array('id'=>$transId, 'userId'=>$user_id));
            return;
        }

        for ($i = 0; $i<$plus; $i++) {
            Db::query("UPDATE personal_vocabulary SET strike=strike + 1 WHERE id_pers_voc=:id AND user_id=:userId", array('id'=>$transId, 'userId'=>$user_id));
        }
    }

    // upraví hodnotu Striku
    public static function updateStrike($tranId, $strike) {
        //Db::update("personal_vocabulary", array('strike'=>$strike), " WHERE id_pers_voc = ?", array('id_pers_voc'=>$tranId));
        Db::query("UPDATE personal_vocabulary SET strike=:strike WHERE id_pers_voc=:id", array(':strike'=>$strike, ':id'=>$tranId));
    }
}