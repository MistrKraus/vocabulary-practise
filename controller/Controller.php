<?php
/**
 * Created by PhpStorm.
 * User: kraus
 * Date: 05.11.2017
 * Time: 14:00
 */

abstract class Controller {

    // Pole, jehož indexy jsou poté viditelné v šabloně jako běžné proměnné
    protected $data = array();
    // Název šablony bez přípony
    protected $view = "";
    // Hlavička HTML stránky
    protected $header = array('title' => '', 'key_words' => '', 'description' => '');

    // Ošetří proměnnou pro výpis do HTML stránky
    private function avoidError($x = null) {
        if (!isset($x))
            return null;
        elseif (is_string($x))
            return htmlspecialchars($x, ENT_QUOTES);
        elseif (is_array($x)) {
            foreach($x as $k => $v) {
                $x[$k] = $this->avoidError($v);
            }
            return $x;
        }
        else
            return $x;
    }

    // Vyrenderuje pohled
    public function buildView() {
        if ($this->view) {
            extract($this->avoidError($this->data));
            extract($this->data, EXTR_PREFIX_ALL, "");
            require("view/" . $this->view . ".phtml");
        }
    }

    // Přidá zprávu pro uživatele
    public function addMessage($message) {
        if (isset($_SESSION['messages']))
            $_SESSION['messages'][] = $message;
        else
            $_SESSION['messages'] = array($message);
    }

    // Vrátí zprávy pro uživatele
    public static function getMessages() {
        if (isset($_SESSION['messages'])) {
            $messages = $_SESSION['messages'];
            unset($_SESSION['messages']);
            return $messages;
        }
        else
            return array();
    }

    // Přesměruje na dané URL
    public function redirect($url) {
        header("Location: /semestralniPrace/$url");
        header("Connection: close");
        exit;
    }

    // Hlavní metoda controlleru
    abstract function process($params);

}