<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
    protected function _initRoutes()
    {
        $router = Zend_Controller_Front::getInstance()->getRouter();
        /*$router->addRoute('login', new Zend_Controller_Router_Route('login', array('controller' => 'auth', 'action' => 'login')));
        $router->addRoute('logout', new Zend_Controller_Router_Route('logout', array('controller' => 'auth', 'action' => 'logout')));
        $router->addRoute('register', new Zend_Controller_Router_Route('register', array('controller' => 'auth', 'action' => 'register')));
        $router->addRoute('account', new Zend_Controller_Router_Route('account', array('controller' => 'auth', 'action' => 'index')));
        $router->addRoute('product', new Zend_Controller_Router_Route('catalogue/:id', array('controller' => 'catalogue', 'action' => 'view')));
        $router->addRoute('search', new Zend_Controller_Router_Route('search', array('controller' => 'index', 'action' => 'search')));
        $router->addRoute('contact', new Zend_Controller_Router_Route('contact', array('controller' => 'index', 'action' => 'contact')));
        $router->addRoute('showProducts', new Zend_Controller_Router_Route('admin/products/show', array('controller' => 'admin', 'action' => 'showproducts')));
        $router->addRoute('showCategories', new Zend_Controller_Router_Route('admin/categories/show', array('controller' => 'admin', 'action' => 'showcategories')));
        $router->addRoute('showOrders', new Zend_Controller_Router_Route('admin/orders/show', array('controller' => 'admin', 'action' => 'showorders')));
        $router->addRoute('editProduct', new Zend_Controller_Router_Route('admin/products/show/:id', array('controller' => 'admin', 'action' => 'editproduct')));
        $router->addRoute('editCategory', new Zend_Controller_Router_Route('admin/categories/show/:id', array('controller' => 'admin', 'action' => 'editcategory')));
        $router->addRoute('editOrder', new Zend_Controller_Router_Route('admin/orders/show/:id', array('controller' => 'admin', 'action' => 'editorder')));*/
    }

    protected function _initVars()
    {
        Zend_Registry::set('limit', 9);
    }
}

