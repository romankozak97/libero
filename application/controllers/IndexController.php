<?php

class IndexController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        // init DB
        $table = new Application_Model_DbTable_Products();
        // get recommended products and put it to view
        $this->view->products = $table->getRecommended();
    }

    public function searchAction()
    {
        // init DB
        $table = new Application_Model_DbTable_Products();

        // get GET parameter q
        $query = $this->_getParam('q');
        // put search query and results of search to view
        $this->view->query = $query;
        $this->view->results = $table->searchForQuery($query);

        // set title
        $this->view->headTitle("Search results for \"$query\"");
    }

    public function contactAction()
    {
        // set title
        $this->view->headTitle('Contact us');
        // TODO: proccesing of contact form
    }
}


