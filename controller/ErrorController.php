<?php
/**
 * Created by PhpStorm.
 * User: kraus
 * Date: 05.11.2017
 * Time: 14:06
 */

class ErrorController extends Controller {
    function process($params) {
        // Hlavička požadavku
        header("HTTP/1.0 404 Not Found");
        // Hlavička stránky
        $this->header['title'] = 'Chyba 404';
        // Nastavení šablony
        $this->view = 'error';

        $this->checkLogin();

        $_SESSION['description'] = "ErrorController";
    }

    function clearController() {
    }
}