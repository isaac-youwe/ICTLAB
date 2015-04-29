<?php

class ApiController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        /**
         * author isaac
         * NIET VERWIJDEREN!!
         */
        $this->view->fundaApiConnector = new Application_Model_FundaApiConnector();
    }


}

