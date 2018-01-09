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
        $_SESSION['fromUrl'] = 'intro';

        $this->checkLogin();

        if ($_POST) {
            $this->processMain();
        }
    }

    function clearController() {
        //echo "cistim intro";
    }
}