<?php
/**
 * Created by PhpStorm.
 * User: kraus
 * Date: 12.12.2017
 * Time: 21:43
 */

class User {

    public static function registerUser($userName, $passWord) {
        Db::insert("user", array('nickname'=>$userName, 'password'=>$passWord));
    }

    public static function logIn($userName, $passWord) {
        return Db::getFirstRow("SELECT * FROM user WHERE ", array('nickname'=>$userName, 'password'=>$userName));
    }

    public static function getUserPassword($userName) {
        var_dump(Db::getAll("SELECT * FROM 'user' WHERE ", array('nickname'=>$userName)));

        return true;
    }
}