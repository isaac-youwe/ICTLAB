<?php

/**
 * Author Isaac de Cuba
 * Editor Serhildan Akdeniz
 *
 * Class Bootstrap
 */
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
        $this->_view->headScript()->appendFile('https://maps.googleapis.com/maps/api/js?v=3.exp');
        $this->_view->headScript()->appendFile('js/bootstrap.min.js');
        $this->_view->headScript()->appendFile('js/jquery.js');
        $this->_view->headScript()->appendFile('js/filter.js');
        $this->_view->headScript()->appendFile('js/jquery.sidr.min.js');

        // JavaScript files for Typeahead Bloodhound [check if it can go to the bottom of the page]
//        $this->_view->headScript()->appendFile('https://code.jquery.com/jquery-1.11.0.min.js');
        $this->_view->headScript()->appendFile('https://cdn.rawgit.com/twitter/typeahead.js/gh-pages/releases/0.10.5/typeahead.bundle.js');
        $this->_view->headScript()->appendFile('https://cdnjs.cloudflare.com/ajax/libs/handlebars.js/3.0.3/handlebars.min.js');
        $this->_view->headScript()->appendFile('js/funda/zoekbalk.js');
    }
}

