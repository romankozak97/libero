<?php

class Application_Model_DbTable_Orders extends Zend_Db_Table_Abstract
{

    protected $_name = 'Orders';

    public function getOrdersList()
    {
        $sql = $this->select()->from($this->_name, array('id', 'first_name', 'last_name', 'date', 'phone', 'email', 'comment', 'items', 'status'));

        return $this->fetchAll($sql)->toArray();
    }

    public function getOrderById($id)
    {
        $id = intval($id);
        $row = $this->fetchRow("id = $id");

        return $row->toArray();
    }

    public function addOrder($firstName, $lastName, $phone, $email, $comment, $items)
    {
        $data = array(
            'first_name' => $firstName,
            'last_name' => $lastName,
            'phone' => $phone,
            'email' => $email,
            'comment' => $comment,
            'items' => $items,
            'status' => 1
        );

        $this->insert($data);
    }

    public function getActiveOrders()
    {
        $sql = $this->select()->from($this->_name, array('id', 'first_name', 'last_name', 'date', 'phone', 'email', 'comment', 'items', 'status'))
                ->where('status=1');

        return $this->fetchAll($sql)->toArray();
    }

    public function getActiveOrdersCount()
    {
        $q = $this->select()->from($this->_name, 'count(*)')->where('status=1');

        return $this->fetchAll($q)->toArray();
    }

    public function editStatusById($id, $status)
    {
        $data = array(
            'status' => $status
        );

        $this->update($data, "id=$id");
    }

    public function deleteOrderById($id)
    {
        $sql = $this->delete("id = $id");

        return $this->fetchAll($sql)->toArray();
    }
}

