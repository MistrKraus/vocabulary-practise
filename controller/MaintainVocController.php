<?php
/**
 * Created by PhpStorm.
 * User: kraus
 * Date: 14.12.2017
 * Time: 15:44
 */

class maintainVocController extends Controller {

    function process($params)
    {
        // Hlavička stránky
        $this->header['title'] = 'Sravovat Slovník';
        // Nastavení šablony
        $this->view = 'maintainVoc';

        if ($_SESSION['user_position'] != 1) {
            $this->addMessage("To se nedělá! Fuj!");
            $this->redirect('intro');
        }

        if ($_POST) {
            $this->processMain('vocabulary');

//            if (isset($_POST['logout'])) {
//                $this->logout();
//            }


        }
    }
}