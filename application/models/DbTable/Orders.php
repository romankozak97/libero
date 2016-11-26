<?php

class Application_Model_DbTable_Orders extends Zend_Db_Table_Abstract
{

    protected $_name = 'Orders';

    /**
     * Get orders list
     * READ
     * @return array Orders assoc array
     */
    public function getOrdersList()
    {
        $sql = $this->select()->from($this->_name, array('id', 'first_name', 'last_name', 'date', 'phone', 'email', 'comment', 'items', 'status'));

        return $this->fetchAll($sql)->toArray();
    }

    /**
     * Get order
     * READ
     * @param $id int Order ID
     * @return array Order info assoc array
     */
    public function getOrderById($id)
    {
        $id = intval($id);
        $row = $this->fetchRow("id = $id");

        return $row->toArray();
    }

    /**
     * Add order
     * CREATE
     * @param $firstName string Customer first name
     * @param $lastName string Customer last name
     * @param $phone string Customer phone number
     * @param $email string Customer email
     * @param $comment string Customer comment
     * @param $items string JSON formatted string with items IDs
     */
    public function addOrder($firstName, $lastName, $phone, $email, $comment, $items)
    {
        $stream = @fopen('C:/wamp/www/libero/log.txt', 'a', false);
        if (!$stream) {
            throw new Exception('Failed to open stream');
        }
        $writer = new Zend_Log_Writer_Stream($stream);
        $logger = new Zend_Log($writer);

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

    /**
     * Get only active orders list
     * @return array Active orders assoc array
     */
    public function getActiveOrders()
    {
        $sql = $this->select()->from($this->_name, array('id', 'first_name', 'last_name', 'date', 'phone', 'email', 'comment', 'items', 'status'))
                ->where('status=1');

        return $this->fetchAll($sql)->toArray();
    }

    /**
     * Get orders by user ID
     * @param $id int User ID
     * @return array Orders assoc array
     */
    public function getOrdersByUserId($id)
    {
        $sql = $this->select()->from($this->_name, array('id', 'first_name', 'last_name', 'date', 'phone', 'email', 'comment', 'items', 'status'))
                ->where("user_id=$id AND status > 0");

        return $this->fetchAll($sql)->toArray();
    }

    /**
     * Get number of active orders
     * @return array SQL count-like assoc array
     */
    public function getActiveOrdersCount()
    {
        $sql = $this->select()->from($this->_name, 'count(*)')->where('status=1');

        return $this->fetchAll($sql)->toArray();
    }

    /**
     * Edit order status
     * UPDATE
     * @param $id int Order ID
     * @param $status int Order status to set
     */
    public function editStatusById($id, $status)
    {
        $data = array(
            'status' => $status
        );

        $this->update($data, "id=$id");
    }

    /**
     * Delete order
     * DELETE
     * @param $id int Order ID
     */
    public function deleteOrderById($id)
    {
        $sql = $this->delete("id = $id");
    }
}

