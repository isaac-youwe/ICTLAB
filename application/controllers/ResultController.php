<?php

class ResultController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
//        $this->view->assign('baseUrl', Zend_Controller_Front::getBaseUrl() . Zend_Controller_Front::getInstance()->getRequest()->getRequestUri());
        $this->view->assign('search', $this->getRequest()->getParam('search'));
        $this->view->fundaAanbod = new Application_Model_Funda_Aanbod();
    }


}

