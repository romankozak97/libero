<?php

class Application_Model_DbTable_Categories extends Zend_Db_Table_Abstract
{

    protected $_name = 'categories';

    public function getCategoriesList()
    {
        $sql = $this->select()->from($this->_name, array('id', 'title', 'items'));

        return $this->fetchAll($sql)->toArray();
    }

    public function deleteCategoryById($id)
    {
        $sql = $this->delete("id = $id");

        return $this->fetchAll($sql)->toArray();
    }
}

