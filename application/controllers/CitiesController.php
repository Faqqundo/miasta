<?php

/**
 *  Moduł ogólny
 *
 *
 */

include_once 'BaseController.php';

use Model\Cities;

/**
 * Kontroler do obsługi miejscowości
 *
 * PHP version 7.0
 *
 *
 * @category  PHP
 * @package   Default
 * @author    Mariusz Wintoch <biuro@informatio.pl>
 * @copyright 2016 (c) Informatio, Mariusz Wintoch
 */
class CitiesController extends BaseController
{
    /**
     * Lista miast
     *
     */
    public function indexAction()
    {
        $this->odp->cities = Cities::getInstance()->pobierzListe(
            $this->getParam('page', 1)
        );
    }

    /**
     * Szczegóły wybranego miasta
     * 
     */
    public function getAction()
    {        
        $this->odp->city = Cities::getInstance()->pobierzSzczegoly(
            $this->getParam('ID')
        );
    }
}
