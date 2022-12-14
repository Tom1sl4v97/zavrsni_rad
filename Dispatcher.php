<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Dispatcher
 *
 * @author franj
 */
class Dispatcher {

    private $_zahtjev;

    public function dispatch() {
        $this->_zahtjev = new Request();
        Router::parse($this->_zahtjev->url, $this->_zahtjev);
        $controller = $this->load_controller();
        call_user_func_array([$controller, $this->_zahtjev->akcija], $this->_zahtjev->parametar);
    }

    public function load_controller() {
        $name = $this->_zahtjev->kontroler . "_controller";
        $file = ROOT . 'Controllers/' . $name . '.php';
        require($file);
        $controller = new $name();
        return $controller;
    }

}
