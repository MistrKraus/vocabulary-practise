<?php
/**
 * Created by PhpStorm.
 * User: kraus
 * Date: 07.11.2017
 * Time: 12:00
 */

class PractiseController extends Controller {

    function process($params)
    {
        // Hlavička stránky
        $this->header['title'] = 'Procvičování';
        // Nastavení šablony
        $this->view = 'practise';
    }
}