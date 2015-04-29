<?php

class ResultController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        $this->view->assign('search', $this->getRequest()->getParam('search'));

    }


}

