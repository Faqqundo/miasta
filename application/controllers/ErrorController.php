<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

include_once 'BaseController.php';

/**
 * Description of IndexController
 *
 * @author mario
 */
class ErrorController extends BaseController {
    
    public function indexAction() {
        //todo!
    }

    public function errorAction() {
        $this->getResponse()->setHttpResponseCode(500);
        //todo zapisz bład do bazy
        
        if (APPLICATION_ENV === 'production') {
            $this->odp->info = 'Wystąpił błąd aplikacji, żądanie nie zostało obsłużone poprawnie.';            
        } elseif($this->hasParam('error_handler') && $this->getParam('error_handler')->exception) {
            $this->odp->info = $this->getParam('error_handler')->exception->getMessage();
        } else {
            $this->odp->info = 'Wystąpił błąd aplikacji, żądanie nie zostało obsłużone poprawnie.';
        }
    }

}
