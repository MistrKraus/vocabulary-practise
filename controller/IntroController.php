<?php
/**
 * Created by PhpStorm.
 * User: kraus
 * Date: 05.11.2017
 * Time: 15:45
 */

class IntroController extends Controller {

    function process($params) {
        // Hlavička stránky
        $this->header['title'] = 'Úvod';
        // Nastavení šablony
        $this->view = 'intro';

        $_SESSION['description'] = "IntroController";

        $this->checkLogin();

        if ($_POST) {
            $this->processMain('vocabulary');
        }
    }

    function clearController()
    {
        //echo "cistim intro";
        // TODO: Implement clearController() method.
    }
}