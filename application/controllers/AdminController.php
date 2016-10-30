<?php

class AdminController extends Zend_Controller_Action
{

    public function init()
    {
        // change layout for admin section
        $this->_helper->layout()->setLayout('admin_layout');
        $products = new Application_Model_DbTable_Orders();
        $this->view->activeOrdersCount = $products->getActiveOrdersCount()[0]['count(*)'];
    }

    public function indexAction()
    {
        // get user info from storage
        $identity = Zend_Auth::getInstance()->getStorage()->read();

        // if user is not authorized then redirect to login page
        if (!Zend_Auth::getInstance()->hasIdentity() || $identity->role != 'admin')
            $this->_helper->redirector->gotoRoute(array('controller' => 'auth', 'action' => 'login', array('error' => '1')));

        $this->view->headTitle("Admin Home");
    }

    public function showproductsAction()
    {
        $this->view->headTitle("Products list");
        $products = new Application_Model_DbTable_Products();
        $this->view->products = $products->getProductsList();
    }

    public function showcategoriesAction()
    {
        $this->view->headTitle("Categories list");
        $categories = new Application_Model_DbTable_Categories();
        $this->view->categories = $categories->getCategoriesList();
    }

    public function showordersAction()
    {
        $this->view->headTitle("Orders list");
        $orders = new Application_Model_DbTable_Orders();
        $this->view->orders = $orders->getOrdersList();
    }

    public function addproductAction()
    {
        $this->view->headTitle("Add product");
        $form = new Application_Form_Addproduct();
        $this->view->form = $form;
    }

    public function addcategoryAction()
    {
        $this->view->headTitle("Add category");
        $form = new Application_Form_Addcategory();
        $this->view->form = $form;
    }

    public function editproductAction()
    {
        $this->view->headTitle("Edit product");
        $form = new Application_Form_Editproduct();
        $this->view->form = $form;
    }

    public function editcategoryAction()
    {
        $this->view->headTitle("Edit category");
        $form = new Application_Form_Editcategory();
        $this->view->form = $form;
    }

    public function editorderAction()
    {
        $this->view->headTitle("Order info");

        $table = new Application_Model_DbTable_Orders();
        $products = new Application_Model_DbTable_Products();

        // get GET parameter id
        $id = $this->_getParam('id');
        $id = intval($id);

        $orderInfo = $table->getOrderById($id);
        $this->view->order = $orderInfo;

        $this->view->items = array();
        foreach (json_decode($orderInfo['items']) as $itemId)
        {
            $this->view->items[] = $products->getProductById($itemId);
        }
    }

    public function deleteproductAction()
    {
        // disable layout for AJAX actions
        $this->_helper->layout()->disableLayout();

        $table = new Application_Model_DbTable_Products();

        // get GET parameter id
        $id = $this->_getParam('id');
        $id = intval($id);

        $table->deleteProductById($id);
    }

    public function deletecategoryAction()
    {
        // disable layout for AJAX actions
        $this->_helper->layout()->disableLayout();

        $table = new Application_Model_DbTable_Categories();

        // get GET parameter id
        $id = $this->_getParam('id');
        $id = intval($id);

        $table->deleteCategoryById($id);
    }

    public function deleteorderAction()
    {
        // disable layout for AJAX actions
        $this->_helper->layout()->disableLayout();

        $table = new Application_Model_DbTable_Orders();

        // get GET parameter id
        $id = $this->_getParam('id');
        $id = intval($id);

        $table->deleteOrderById($id);
    }

    public function editstatusAction()
    {
        // disable layout for AJAX actions
        $this->_helper->layout()->disableLayout();

        $table = new Application_Model_DbTable_Orders();

        // get GET parameter id
        $id = $this->_getParam('id');
        $id = intval($id);

        // get GET parameter status
        $status = $this->_getParam('status');
        $status = intval($status);

        $table->editStatusById($id, $status);
    }


}


























