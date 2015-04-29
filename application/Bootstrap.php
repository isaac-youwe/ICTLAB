<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
    protected $_layout;

    /**
     * View of layout template.
     *
     * @var Zend_View
     */
    protected $_view;

    protected function _initDoctype()
    {
        $this->bootstrap('view');
        $this->_view = $this->getResource('view');
        $this->_view->doctype('XHTML1_STRICT');
    }

    protected function _initPopulateLayout()
    {
        // Set a starting title
        $this->_view->headTitle('Funda.ga');

        // CSS Files
        $this->_view->headLink()->appendStylesheet('css/bootstrap.min.css');
        //$this->_view->headLink()->appendStylesheet('css/custom.css');
        $this->_view->headLink()->appendStylesheet('css/style.css');
        $this->_view->headLink()->appendStylesheet('js/stylesheets/jquery.sidr.dark.css');

        // JavaScript Files bottom of page
        // $this->view->inlineScript()->appendFile('https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js');

        // JavaScript Files top of page
        $this->_view->headScript()->appendFile('https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js');
        $this->_view->headScript()->appendFile('https://maps.googleapis.com/maps/api/js?v=3.exp');//Google Map API
        $this->_view->headScript()->appendFile('js/bootstrap.min.js');
        $this->_view->headScript()->appendFile('js/jquery.js');
        $this->_view->headScript()->appendFile('js/filter.js');
        $this->_view->headScript()->appendFile('js/jquery.sidr.min.js');
        $this->_view->headScript()->appendFile('js/map.js');
    }
}

