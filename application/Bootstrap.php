<?php

/**
 * Starter aplikacji
 * 
 */
class Bootstrap extends Zend_Application_Bootstrap_Bootstrap {

    /**
     * Inicjacja konfiguracji
     *
     * @return array
     */
    protected function _initConfig()
    {
        $config = $this->getOptions();
        Zend_Registry::set('config', $config);//konfig w formie tablicy - raz czytany, wielokrotnie odtwarzany:)
        return $config;
    }
  
    /**
     * Inicjalizacja i konfiguracja autoloaderów w nowych namespacach
     *
     * @return Zend_Loader_Autoloader 
     */
    protected function _initAutoloader() {
        //die("autoloader");                
        
        require 'Zend/Loader/AutoloaderFactory.php';
        
        Zend_Loader_AutoloaderFactory::factory(array(
            'Zend_Loader_StandardAutoloader' => array(
                'namespaces' => array(
                    'Model' => APPLICATION_PATH . DIRECTORY_SEPARATOR . 'models'                    
                ))            
        ));
        
        $autoloader = Zend_Loader_Autoloader::getInstance();                
        $autoloader->unregisterNamespace('ZendX_');        
       
        return $autoloader;
    }

    /**
     * Inicjalizacja mapera adresów na kontrolery
     *
     * @return Zend_Controller_Router_Interface 
     */
    protected function _initRouter() {
        $this->bootstrap('FrontController');
        $front = $this->getResource('FrontController');

        $routesConfig = new Zend_Config_Ini(CONFIGS_PATH . DIRECTORY_SEPARATOR . 'routes.ini', APPLICATION_ENV);

        $router = $front->getRouter();

        $router->addConfig($routesConfig);        
                
        return $router;
    }

}