<?php
/**
 * Created by PhpStorm.
 * User: kraus
 * Date: 28.12.2017
 * Time: 20:39
 */

class Categories {

    public static function getAllCategories() {
        return Db::getAll("SELECT * FROM category");
    }

    public static function getCategoryId($category) {
        $fromDb = Db::getFirstRow("SELECT id_category FROM category WHERE name=:name", array(':name'=>$category));
        return $fromDb['id_category'];
    }

    public static function addCategory($category) {
        if (empty(self::getCategoryId($category))) {
            Db::insert("category", array('name'=>$category));
        }

        return self::getCategoryId($category);
    }
}