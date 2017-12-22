<?php
/**
 * Created by PhpStorm.
 * User: kraus
 * Date: 12.12.2017
 * Time: 21:43
 */

class User {

    public static function registerUser($userName, $passWord) {
        Db::insert("user", array('nickname'=>$userName, 'password'=>$passWord, 'position_id'=>2));
    }

    public static function logIn($userName) {
        return Db::getFirstRow("SELECT * FROM user WHERE nickname=:nickname", array(':nickname'=>$userName));
    }

    public static function getUserPassword($userName) {
        return Db::getFirstRow("SELECT password FROM user WHERE nickname=:nickname", array(':nickname'=>$userName));

        //return true;
    }
}