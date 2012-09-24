<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{

    protected function _initRoutes()
    {
        $config = new Zend_Config_Ini(APPLICATION_PATH . '\configs\routes.ini', 'production');
        $front = Zend_Controller_Front::getInstance();
        $router = $front->getRouter();
        $router->addConfig($config, 'routes');
    }

    protected function _initDbDefaults()
    {
        $resource = $this->getPluginResource('db');
        $db = $resource->getDbAdapter();
        $db->getProfiler()->setEnabled(true);
        $db->query("SET CHARSET utf8");

        Zend_Db_Table_Abstract::setDefaultAdapter($db);
        Zend_Registry::set('dbAdapter', $db);
    }
    
}

