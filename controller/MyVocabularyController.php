<?php
/**
 * Created by PhpStorm.
 * User: kraus
 * Date: 07.11.2017
 * Time: 12:01
 */

class MyVocabularyController extends Controller {

    function process($params)
    {
        // Hlavička stránky
        $this->header['title'] = 'Můj Slovník';
        // Nastavení šablony
        $this->view = 'myVocabulary';
    }
}