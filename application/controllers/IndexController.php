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
class IndexController extends BaseController {
 
    public function indexAction() {
        $this->odp->nazwaAplikacji = 'miasta';
        $this->odp->czasSerwera = date(DATE_RFC3339);        
    }

}
