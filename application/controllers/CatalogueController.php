<?php

class CatalogueController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        // set title
        $this->view->headTitle('Catalogue');

        // init DB
        $products = new Application_Model_DbTable_Products();
        $categories = new Application_Model_DbTable_Categories();

        // get GET parameter "sort"
        $sortType = $this->_getParam('sort');
        $sortType = intval($sortType);

        // get GET parameter "page"
        $page = $this->_getParam('page');
        !isset($page) ? $page = 1: $page = intval($page);

        // get GET parameter "category"
        $category = $this->_getParam('category');
        !isset($category) ? $category = null: $category = intval($category);

        // get GET parameter "brand"
        $brand = $this->_getParam('brand');
        !isset($brand) ? $brand = null: $brand = strval($brand);


        // switch parameters for view
        switch($sortType)
        {
            case 1:
                $this->view->sortType = 'Popularity';
                break;
            case 2:
                $this->view->sortType = 'Price (high-low)';
                break;
            case 3:
                $this->view->sortType = 'Price (low-high)';
                break;
            default:
                $this->view->sortType = 'Popularity';
                break;
        }

        $productsArray = array();

        // if sort parameter is set
        if (isset($sortType))
            // get sorted products for catalogue view
            $productsArray  = $products->getProductsByPage($page, $sortType);
        else
            // get all products for catalogue view
            $productsArray = $products->getProductsByPage($page, 0);

        if ($brand != null)
            $productsArray = $products->getProductsByBrand($page, $brand);

        if ($category != null)
            $productsArray = $products->getProductsByCategory($page, $category);

        $this->view->products = $productsArray;
        $this->view->categories = $categories->getCategoriesList();
        $this->view->debug = $brand;

        #$productsCount = $products->getProductsCount()[0]['count(*)'];
        #$this->view->pagesCount = ceil($productsCount/Zend_Registry::get('limit'));
        isset($page) ? $this->view->activePage = $page: $this->view->activePage = 1;

    }

    public function cartAction()
    {
        // set title
        $this->view->headTitle('Cart');

        // init DB
        $table = new Application_Model_DbTable_Products();

        // get items in cart array from session
        $cart = new Zend_Session_Namespace('Cart');
        $cartList = array();
        $cartList = json_decode($cart->content);

        // get info about items in cart
        $cartItems = array();
        foreach ($cartList as $itemId)
        {
            $cartItems[] = $table->getProductById($itemId);
        }

        // set total price of items in cart
        $totalPrice = 0;
        foreach ($cartItems as $item)
        {
            $totalPrice += $item['price'];
        }

        // set values (count, items info, total price) to view
        $this->view->count = count($cartList);
        $this->view->cartItems = $cartItems;
        $this->view->totalPrice = $totalPrice;
    }

    public function viewAction()
    {
        // init DB
        $table = new Application_Model_DbTable_Products();

        // get STRING parameter id
        $id = $this->_getParam('id');
        $id = intval($id);

        // set values (id, product info, recommended) to view
        $this->view->id = $id;
        $this->view->info = $table->getProductById($id);
        $this->view->products = $table->getRecommended();

        // set title
        $this->view->headTitle($this->view->info['title']);
    }

    public function addtocartAction()
    {
        // disable layout for AJAX actions
        $this->_helper->layout()->disableLayout();

        // init cart session namespace
        $cart = new Zend_Session_Namespace('Cart');
        $cartList = array();
        $cartList = json_decode($cart->content);

        // get GET parameter id
        $id = $this->_getParam('id');
        $id = intval($id);

        // add received ID to cart array
        $cartList[] = $id;
        // set count of items in cart
        $count = count($cartList);
        $cart->content = json_encode($cartList);

        // print count of items in cart
        echo $count;
    }

    public function removecartAction()
    {
        // disable layout for AJAX actions
        $this->_helper->layout()->disableLayout();

        // init Cart session namespace
        $cart = new Zend_Session_Namespace('Cart');
        $cartList = array();
        $cartList = json_decode($cart->content);

        // get GET parameter id
        $id = $this->_getParam('id');
        $id = intval($id);

        // delete received id from cart array
        $index = array_search($id, $cartList);
        unset($cartList[$index]);
        // set count of items in cart
        $count = count($cartList);
        $cart->content = json_encode($cartList);

        // print count of items in cart
        echo $count;
    }

    public function checkoutAction()
    {
        // set title
        $this->view->headTitle('Checkout');

        // init register form and put it to view
        $form = new Application_Form_Checkout();
        $this->view->form = $form;

        // init products DB
        $table = new Application_Model_DbTable_Products();

        // get items in cart array from session
        $cart = new Zend_Session_Namespace('Cart');
        $cartList = array();
        $cartList = json_decode($cart->content);

        // get info about items in cart
        $cartItems = array();
        foreach ($cartList as $itemId)
        {
            $cartItems[] = $table->getProductById($itemId);
        }

        // set total price of items in cart
        $totalPrice = 0;
        foreach ($cartItems as $item)
        {
            $totalPrice += $item['price'];
        }

        // set values (count, items info, total price) to view
        $this->view->count = count($cartList);
        $this->view->totalPrice = $totalPrice;


        // if we have POST request
        if ($this->getRequest()->isPost())
        {
            // get data from request
            $formData = $this->getRequest()->getPost();

            // if form is filled in properly
            if ($form->isValid($formData))
            {
                // get fields from request
                $firstName = $this->getRequest()->getPost('first-name');
                $lastName = $this->getRequest()->getPost('last-name');
                $phone = $this->getRequest()->getPost('phone');
                $email = $this->getRequest()->getPost('email');
                $comment = $this->getRequest()->getPost('comment');
                $items = json_decode($cartList);


                /*$this->view->items = $items;
                $this->view->firstName = $firstName;
                $this->view->lastName = $lastName;
                $this->view->phone = $phone;
                $this->view->email = $email;
                $this->view->comment = $comment;*/

                $this->view->debug = $cart->content;

                // init DB
                $orders = new Application_Model_DbTable_Orders();
                // put new order info to DB
                $orders->addOrder($firstName, $lastName, $phone, $email, $comment, $items);

                // redirect to account page
                #$this->_helper->redirector->gotoRoute(array('controller' => 'catalogue', 'action' => 'index'));
            }
        }
    }
}
