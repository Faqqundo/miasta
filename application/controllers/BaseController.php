<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of BaseController
 *
 * @author mario
 */
abstract class BaseController extends Zend_Rest_Controller {

    /**
     * Kontener na dane odpowiedzi żądań REST
     *
     * @var stdClass
     */
    protected $odp;

    /**
     * Inicjacja kontrolera
     *
     */
    public function init() {
        parent::init();

        $this->odp = new stdClass();
    }

    /**
     * Wykonywane po przetworzeniu akcji - zapewnia zwrócenie odpowiedzi jsonowej
     *
     */
    public function postDispatch() {
        parent::postDispatch();

//        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);

        $this->getResponse()
            ->setBody(Zend_Json::encode($this->odp))
            ->setHeader('Content-Type', 'application/json', true);
    }

    //zaślepki, aby niepotrzebnie nie powielać w każdym kontrolerze

    public function deleteAction() {
        throw new Zend_Rest_Server_Exception('Próba wykonania nieznanej operacji');
    }

    public function getAction() {
        throw new Zend_Rest_Server_Exception('Próba wykonania nieznanej operacji');
    }

    public function headAction() {
        throw new Zend_Rest_Server_Exception('Próba wykonania nieznanej operacji');
    }

    public function indexAction() {
        throw new Zend_Rest_Server_Exception('Próba wykonania nieznanej operacji');
    }

    public function postAction() {
        throw new Zend_Rest_Server_Exception('Próba wykonania nieznanej operacji');
    }

    public function putAction() {
        throw new Zend_Rest_Server_Exception('Próba wykonania nieznanej operacji');
    }

}
