<?php
/**
 * Created by PhpStorm.
 * User: kraus
 * Date: 22.12.2017
 * Time: 18:47
 */

class MyVoc {

    public static function getMyVoc($userId) {
        return Db::getAll("SELECT * FROM personal_vocabulary WHERE user_id=:user_id", array('user_id'=>$userId));
    }

    public static function getTransFromMyVoc($transId) {
        return Db::getAll("SELECT user_id FROM personal_vocabulary", null);// WHERE id_pers_voc=:id_pers_voc",
            //array('trans_id'=>$transId));
    }

    public static function addToMyVoc($transId, $userId) {
        return Db::insert("personal_vocabulary", array('trans_id'=>$transId, 'user_id'=>$userId));
    }

    public static function removeFromMyVoc($transId, $userId) {
        return Db::query("DELETE * FROM personal_vocabulary WHERE trans_id=:trans_id AND user_id=:user_id",
            array('trans_id'=>$transId, 'user_id'=>$userId));
    }
}