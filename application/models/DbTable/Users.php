<?php

class Application_Model_DbTable_Users extends Zend_Db_Table_Abstract
{

    protected $_name = 'users';

    public function registerUser($name, $email, $password)
    {
        $data = array(
            'name' => $name,
            'email' => $email,
            'password' => $password,
            'role' => 'user'
        );

        $this->insert($data);
    }
}

