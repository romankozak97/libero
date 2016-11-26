<?php

class AuthController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        // set title
        $this->view->headTitle('Account');

        // if user is not authorized then redirect to login page
        if (!Zend_Auth::getInstance()->hasIdentity())
            $this->_helper->redirector->gotoRoute(array('controller' => 'auth', 'action' => 'login'));

        // get user info from storage
        $identity = Zend_Auth::getInstance()->getStorage()->read();

        // set values (id, name, email, role) to view
        $this->view->userId = $identity->id;
        $this->view->userName = $identity->name;
        $this->view->userEmail = $identity->email;
        $this->view->userRole = $identity->role;
    }

    public function loginAction()
    {
        // set title
        $this->view->headTitle('Login');

        if ($error = $this->_getParam('0'))
            $this->view->errMessage = "You should login as admin to access admin section";

        // init form and put it to view
        $form = new Application_Form_Login();
        $this->view->form = $form;

        // if we have POST request
        if ($this->getRequest()->isPost())
        {
            // get data from request
            $formData = $this->getRequest()->getPost();

            // if form is filled in properly
            if ($form->isValid($formData))
            {
                // get DB adapter and init DB
                $authAdapter = new Zend_Auth_Adapter_DbTable(Zend_Db_Table::getDefaultAdapter());
                $authAdapter->setTableName('users')
                    ->setIdentityColumn('email')
                    ->setCredentialColumn('password');

                // get credentials from request
                $email = $this->getRequest()->getPost('email');
                $password = $this->getRequest()->getPost('password');

                // set credentials to DB adapter
                $authAdapter->setIdentity($email)
                    ->setCredential(md5($password));

                // authenticate user
                $auth = Zend_Auth::getInstance();
                $result = $auth->authenticate($authAdapter);

                // if credentials are true
                if ($result->isValid())
                {
                    // get user info and put it to storage
                    $identity = $authAdapter->getResultRowObject();
                    $authStorage = $auth->getStorage();
                    $authStorage->write($identity);
                    // redirect to account page
                    $this->_helper->redirector->gotoRoute(array('controller' => 'auth', 'action' => 'index'));
                }
                else {
                    // print error message
                    $this->view->errMessage = 'Invalid username or password';
                }
            }
        }
    }

    public function logoutAction()
    {
        // clear storage data about authorization
        Zend_Auth::getInstance()->clearIdentity();
        // redirect to home page
        $this->_helper->redirector->gotoRoute(array('controller' => 'index', 'action' => 'index'));
    }

    public function registerAction()
    {
        // if user is authorized then redirect to account page
        if (Zend_Auth::getInstance()->hasIdentity())
            $this->_helper->redirector->gotoRoute(array('controller' => 'auth', 'action' => 'index'));

        // set title
        $this->view->headTitle('Sign up');

        // init register form and put it to view
        $form = new Application_Form_Register();
        $this->view->form = $form;

        // if we have POST request
        if ($this->getRequest()->isPost())
        {
            // get data from request
            $formData = $this->getRequest()->getPost();

            // if form is filled in properly
            if ($form->isValid($formData))
            {
                // get fields from request
                $name = $this->getRequest()->getPost('name');
                $email = $this->getRequest()->getPost('email');
                $password = $this->getRequest()->getPost('password');

                // init DB
                $users = new Application_Model_DbTable_Users();
                // put new user info to DB
                $users->registerUser($name, $email, md5($password));

                // redirect to account page
                $this->_helper->redirector->gotoRoute(array('controller' => 'auth', 'action' => 'index'));
            }
        }
    }

    public function editAction()
    {
        // get user data from storage
        $identity = Zend_Auth::getInstance()->getStorage()->read();
        $this->view->userId = $identity->id;
        $this->view->userName = $identity->name;
        $this->view->userEmail = $identity->email;
        $this->view->userRole = $identity->role;

        // init DB
        $table = new Application_Model_DbTable_Users();

        // get product row by ID
        $product = $table->getUserById($identity->id);

        // init form with values and put it to view
        $form = new Application_Form_Edituser();
        $form->name->setValue($identity->name);
        $form->email->setValue($identity->email);
        $this->view->form = $form;

        // if we have POST request
        if ($this->getRequest()->isPost())
        {
            // get data from request
            $formData = $this->getRequest()->getPost();

            // if form is filled in properly
            if ($form->isValid($formData))
            {
                // get fields from request
                $name = $this->getRequest()->getPost('name');
                $curPassword = $this->getRequest()->getPost('currentpassword');
                $newPassword = $this->getRequest()->getPost('newpassword');

                // init DB
                $users = new Application_Model_DbTable_Users();

                $dbPassword = $users->getUserById($identity->id)['password'];
                if (md5($curPassword) === $dbPassword)
                {
                    if ($newPassword === "")
                        $newPassword = $dbPassword;
                    // put new user info to DB
                    $users->editUser($identity->id, $name, md5($newPassword));

                    // redirect to account page
                    $this->_helper->redirector->gotoRoute(array('controller' => 'auth', 'action' => 'index'));
                }
                else {
                    $this->view->errMessage = "Invalid current password. Try one more time";
                }

            }
        }
    }

    public function ordersAction()
    {
        // get user data from storage
        $identity = Zend_Auth::getInstance()->getStorage()->read();
        $this->view->userId = $identity->id;
        $this->view->userName = $identity->name;
        $this->view->userEmail = $identity->email;
        $this->view->userRole = $identity->role;

        // init DB
        $table = new Application_Model_DbTable_Orders();

        // get product row by ID
        $this->view->orders = $table->getOrdersByUserId($identity->id);
    }
}


