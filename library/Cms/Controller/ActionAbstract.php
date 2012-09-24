<?php

abstract class Cms_Controller_ActionAbstract extends Zend_Controller_Action
{

    protected $_session;
    protected $_config;
    protected $_translate;
    protected $_language;
    protected $_metaLanguage;

    public function init()
    {
        parent::init();
        
        //session
        $this->_session = new Zend_Session_Namespace('cms');
        
        //config
        $config = $this->getInvokeArg('bootstrap')->getOptions();
        $this->_config = $config['cms'];

        //language
        $this->_language();
        $this->view->assign('language', $this->_language);
        $this->view->assign('metaLanguage', $this->_metaLanguage);

        //translate
        if (is_file('../application/languages/lang.' . $this->_metaLanguage . '.csv')) {
            $this->_translate = new Zend_Translate('csv', '../application/languages/lang.' . $this->_metaLanguage . '.csv', $this->_metaLanguage);
            Zend_Registry::set('Zend_Translate', $this->_translate);
        }
        
    }

    //translating text
    protected function _translate($text)
    {
        return $this->_translate->translate($text);
    }

    //get or set language
    protected function _language()
    {
        if (sizeof($this->_config['language']) > 1) {
            $language = $this->getRequest()->getParam('language');
            if (!empty($language)) {
                $this->_language = $language . '/';
                $this->_metaLanguage = $language;
            } else if (!empty($this->_session->language)) {
                $this->_language = $this->_session->language . '/';
                $this->_metaLanguage = $this->_session->language;
            } else {
                $browserLanguage = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
                if (in_array($browserLanguage, $this->_config['language'])) {
                    $this->_session->language = $browserLanguage;
                    $this->_language = $browserLanguage . '/';
                    $this->_metaLanguage = $browserLanguage;
                } else {
                    $this->_language = $this->_config['language'][0] . '/';
                    $this->_metaLanguage = $this->_config['language'][0];
                }
            }
        } else {
            $this->_language = '';
            $this->_metaLanguage = $this->_config['language'][0];
        }
    }
    
}
