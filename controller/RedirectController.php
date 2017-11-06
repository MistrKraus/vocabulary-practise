<?php
/**
 * Created by PhpStorm.
 * User: kraus
 * Date: 05.11.2017
 * Time: 15:50
 */

class RedirectController extends Controller {

    /**
     * @var Kontroler
     */
    protected $controller;

    // Metoda převede pomlčkovou variantu controlleru na název třídy
    private function dashToCamelNotation($text) {
        $sentence = str_replace('-', ' ', $text);
        $sentence = ucwords($sentence);
        $sentence = str_replace(' ', '', $sentence);
        return $sentence;
    }

    // Naparsuje URL adresu podle lomítek a vrátí pole parametrů
    private function parseURL($url) {
        // Naparsuje jednotlivé části URL adresy do asociativního pole
        $parsedURL = parse_url($url);
        // Odstranění počátečního lomítka
        $parsedURL["path"] = ltrim($parsedURL["path"], "/");
        // Odstranění bílých znaků kolem adresy
        $parsedURL["path"] = trim($parsedURL["path"]);
        // Rozbití řetězce podle lomítek
        $splitedPath = explode("/", $parsedURL["path"]);
        array_shift($splitedPath);
        return $splitedPath;
    }

    function process($params) {
        $parsedURL = $this->parseURL($params[0]);

        if(empty($parsedURL[0]))
            $this->redirect("intro");

        $ControllerClass = $this->dashToCamelNotation(array_shift($parsedURL)) . 'Controller';

        if (file_exists('controller/' . $ControllerClass . '.php')) {
            $this->controller = new $ControllerClass;
        } else $this->redirect('error');

        $this->controller->process($parsedURL);

        // Nastavení proměnných pro šablonu
        $this->data['title'] = $this->controller->header['title'];
        $this->data['description'] = $this->controller->header['description'];
        $this->data['key_words'] = $this->controller->header['key_words'];
        $this->data['messages'] = $this->getMessages();
        // Nastavení hlavní šablony
        $this->view = 'main';
    }
}