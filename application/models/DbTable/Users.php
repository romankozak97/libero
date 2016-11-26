<?php

class Application_Model_DbTable_Users extends Zend_Db_Table_Abstract
{

    protected $_name = 'users';

    /**
     * Get user
     * @param $id int User ID
     * @return array User row assoc array
     */
    public function getUserById($id)
    {
        $id = intval($id);
        $row = $this->fetchRow("id = $id");

        return $row->toArray();
    }

    /**
     * Add user
     * @param $name string User name
     * @param $email string User email
     * @param $password string User password
     */
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

    public function editUser($id, $name, $password)
    {
        $data = array(
            'name' => $name,
            'password' => $password
        );

        $this->update($data, "id=$id");
    }
}

