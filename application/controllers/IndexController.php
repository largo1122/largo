<?php

class IndexController extends Cms_Controller_ActionAbstract
{

    public function init()
    {
        parent::init();
    }

    public function indexAction()
    {
        // action body
        $this->view->breadcrumb($this->_translate('LANG_MENU_CONTACT'));
    }

    public function kontaktAction()
    {
         $this->view->breadcrumb($this->_translate('LANG_MENU_CONTACT'));
    }

    public function languageAction()
    {
        // Added comment
        $this->_helper->layout()->disableLayout();
	$this->_helper->viewRenderer->setNoRender();
        $this->_session->language = $this->getRequest()->getParam('language');
        $this->_redirect('/');
    }


}

