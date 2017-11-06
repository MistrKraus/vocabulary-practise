<?php
/**
 * Created by PhpStorm.
 * User: kraus
 * Date: 06.11.2017
 * Time: 18:53
 */

class VocabularyController extends Controller {

    function process($params)
    {
        // Hlavička stránky
        $this->header['title'] = 'Slovník';
        // Nastavení šablony
        $this->view = 'vocabulary';
    }
}