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

    /**
     * Admin home page
     */
    public function indexAction()
    {
        // get user info from storage
        $identity = Zend_Auth::getInstance()->getStorage()->read();

        // if user is not authorized then redirect to login page
        if (!Zend_Auth::getInstance()->hasIdentity() || $identity->role != 'admin')
            $this->_helper->redirector->gotoRoute(array('controller' => 'auth', 'action' => 'login', array('error' => '1')));

        $this->view->headTitle("Home");
    }

    /**
     * Show products list
     */
    public function showproductsAction()
    {
        $this->view->headTitle("Products list");
        $products = new Application_Model_DbTable_Products();
        $this->view->products = $products->getProductsList();
    }

    /**
     * Show categories list
     */
    public function showcategoriesAction()
    {
        $this->view->headTitle("Categories list");
        $categories = new Application_Model_DbTable_Categories();
        $this->view->categories = $categories->getCategoriesList();
    }

    /**
     * Show orders list
     */
    public function showordersAction()
    {
        $this->view->headTitle("Orders list");
        $orders = new Application_Model_DbTable_Orders();
        $this->view->orders = $orders->getOrdersList();
    }

    /**
     * Add product
     * @throws Zend_Form_Exception
     */
    public function addproductAction()
    {
        $this->view->headTitle("Add product");
        $form = new Application_Form_Addproduct();
        $this->view->form = $form;

        // if we have POST request
        if ($this->getRequest()->isPost()) {
            // get data from request
            $formData = $this->getRequest()->getPost();

            // if form is filled in properly
            if ($form->isValid($formData)) {
                // get fields from request
                $title = $this->getRequest()->getPost('title');
                $price = $this->getRequest()->getPost('price');
                $desc = $this->getRequest()->getPost('description');
                $size = $this->getRequest()->getPost('size');
                $brand = $this->getRequest()->getPost('brand');
                $category = $this->getRequest()->getPost('category');

                try
                {
                    $adapter = new Zend_File_Transfer_Adapter_Http();
                    $adapter->addValidator('Count', false, array('min' => 1, 'max' => 1));
                    $adapter->setDestination('c:\\wamp\\www\\libero\\public\\img\\products');

                    $file = $adapter->getFileInfo();
                    $name = $adapter->getFileName();
                    if (($adapter->isUploaded()) && ($adapter->isValid()))
                    {
                        $adapter->receive();
                        $length = strlen($name);
                        $extension = substr($name, $length-3, $length);
                        $filename = 'product_'.date('Ymdhs').'.'.$extension;
                        #rename($file, 'c:\\wamp\\www\\libero\\public\\img\\temp\\'.$filename);
                        $adapter->addFilter('Rename', array(
                            'target' => 'c:\\wamp\\www\\libero\\public\\img\\products\\'.$filename,
                            'overwrite' => true
                        ));
                    }
                }
                catch (Exception $e)
                 {
                    echo $e->getMessage();
                }

                // init DB
                $table = new Application_Model_DbTable_Products();
                // put new user info to DB
                $table->addProduct($title, $price, $desc, $size, $brand, $category, basename($name));

                // redirect to account page
                $this->_helper->redirector->gotoRoute(array('controller' => 'admin', 'action' => 'showproducts'));
            }
        }
    }

    /**
     * Add category
     * @throws Zend_Form_Exception
     */
    public function addcategoryAction()
    {
        $this->view->headTitle("Add category");

        $form = new Application_Form_Addcategory();

        $table = new Application_Model_DbTable_Products();
        $products = $table->getProductsList();

        $options = array();
        $ids = array();
        for ($i = 0; $i < count($products); $i++)
        {
            $options["$i"] = "{$products[$i]['title']} (ID: {$products[$i]['id']})";
            $ids[$i] = $products[$i]['id'];
        }
        $form->items->setMultiOptions($options);
        $this->view->form = $form;

        // if we have POST request
        if ($this->getRequest()->isPost()) {
            // get data from request
            $formData = $this->getRequest()->getPost();

            // if form is filled in properly
            if ($form->isValid($formData)) {
                // get fields from request
                $title = $this->getRequest()->getPost('title');
                $rawItems = $this->getRequest()->getPost('items');
                $items = array();
                foreach ($rawItems as $item)
                {
                    $items[] = $ids[$item];
                }

                // init DB
                $users = new Application_Model_DbTable_Categories();
                #$this->view->debug = json_encode($items);
                // put new user info to DB
                $users->addCategory($title, json_encode($items));

                // redirect to account page
                $this->_helper->redirector->gotoRoute(array('controller' => 'admin', 'action' => 'showcategories'));
            }
        }
    }

    /**
     * Edit product
     * @throws Zend_Form_Exception
     */
    public function editproductAction()
    {
        $this->view->headTitle("Edit product");

        // init DB
        $table = new Application_Model_DbTable_Products();

        // get GET parameter id
        $id = $this->_getParam('id');
        $id = intval($id);

        // get product row by ID
        $product = $table->getProductById($id);

        // init form with values and put it to view
        $form = new Application_Form_Editproduct();
        $form->title->setValue($product['title']);
        $form->price->setValue($product['price']);
        $form->description->setValue($product['description']);
        $form->size->setValue($product['size']);
        $form->brand->setValue($product['brand']);
        $form->category->setValue($product['category']);
        $this->view->form = $form;
        $this->view->id = $id;

        // if we have POST request
        if ($this->getRequest()->isPost()) {
            // get data from request
            $formData = $this->getRequest()->getPost();

            // if form is filled in properly
            if ($form->isValid($formData)) {
                // get fields from request
                $title = $this->getRequest()->getPost('title');
                $price = $this->getRequest()->getPost('price');
                $desc = $this->getRequest()->getPost('description');
                $size = $this->getRequest()->getPost('size');
                $brand = $this->getRequest()->getPost('brand');
                $category = $this->getRequest()->getPost('category');

                try
                {
                    $adapter = new Zend_File_Transfer_Adapter_Http();
                    $adapter->addValidator('Count', false, array('min' => 1, 'max' => 1));
                    $adapter->setDestination('c:\\wamp\\www\\libero\\public\\img\\products');

                    $file = $adapter->getFileInfo();
                    $name = $adapter->getFileName();
                    if (($adapter->isUploaded()) && ($adapter->isValid()))
                    {
                        $adapter->receive();
                        $length = strlen($name);
                        $extension = substr($name, $length-3, $length);
                        $filename = 'product_'.date('Ymdhs').'.'.$extension;
                        #rename($file, 'c:\\wamp\\www\\libero\\public\\img\\temp\\'.$filename);
                        $adapter->addFilter('Rename', array(
                            'target' => 'c:\\wamp\\www\\libero\\public\\img\\products\\'.$filename,
                            'overwrite' => true
                        ));
                    }
                }
                catch (Exception $e)
                {
                    echo $e->getMessage();
                }

                // init DB
                $table = new Application_Model_DbTable_Products();
                // put new user info to DB
                $table->editProduct($id, $title, $price, $desc, $size, $brand, $category, basename($name));

                // redirect to account page
                $this->_helper->redirector->gotoRoute(array('controller' => 'admin', 'action' => 'showproducts'));
            }
        }
    }

    /**
     * Edit category
     * @throws Zend_Form_Exception
     */
    public function editcategoryAction()
    {
        $this->view->headTitle("Edit category");

        // init DB
        $table = new Application_Model_DbTable_Categories();

        $tableProd = new Application_Model_DbTable_Products();
        $products = $tableProd->getProductsList();

        $options = array();
        $ids = array();
        for ($i = 0; $i < count($products); $i++)
        {
            $options["$i"] = "{$products[$i]['title']} (ID: {$products[$i]['id']})";
            $ids[$i] = $products[$i]['id'];
        }

        // get GET parameter id
        $id = $this->_getParam('id');
        $id = intval($id);

        // get product row by ID
        $product = $table->getCategoryById($id);

        // init form with values and put it to view
        $form = new Application_Form_Editcategory();
        $form->title->setValue($product['title']);
        $form->items->setValue($product['items']);
        $form->items->setMultiOptions($options);
        $this->view->form = $form;

        // if we have POST request
        if ($this->getRequest()->isPost()) {
            // get data from request
            $formData = $this->getRequest()->getPost();

            // if form is filled in properly
            if ($form->isValid($formData)) {
                // get fields from request
                $title = $this->getRequest()->getPost('title');
                $rawItems = $this->getRequest()->getPost('items');
                $items = array();
                foreach ($rawItems as $item)
                {
                    $items[] = $ids[$item];
                }

                // init DB
                $categories = new Application_Model_DbTable_Categories();
                #$this->view->debug = json_encode($items);
                // put new user info to DB
                $categories->editCategory($id, $title, json_encode($items));

                // redirect to account page
                $this->_helper->redirector->gotoRoute(array('controller' => 'admin', 'action' => 'showcategories'));
            }
        }
    }

    /**
     * Edit order
     */
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
        $totalPrice = 0;
        foreach (json_decode($orderInfo['items']) as $itemId)
        {
            $product = $products->getProductById($itemId);
            $this->view->items[] = $product;
            $totalPrice += $product['price'];
        }

        $this->view->totalPrice = $totalPrice;
    }

    /**
     * Delete product
     */
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

    /**
     * Delete category
     */
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

    /**
     * Delete order
     */
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

    /**
     * Edit order status
     */
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


























