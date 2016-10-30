<?php

class Application_Model_DbTable_Products extends Zend_Db_Table_Abstract
{

    protected $_name = 'products';

    public function getProductsList()
    {
        $sql = $this->select()->from($this->_name, array('id', 'title', 'description', 'price', 'photo', 'size', 'category', 'brand'));

        return $this->fetchAll($sql)->toArray();
    }

    public function getProductById($id)
    {
        $id = intval($id);
        $row = $this->fetchRow("id = $id");

        return $row->toArray();
    }

    public function getProductsCount()
    {
        $q = $this->select()->from($this->_name, 'count(*)');

        return $this->fetchAll($q)->toArray();
    }

    public function getRecommended()
    {
        $sql = $this->select()->from($this->_name, array('id', 'title', 'description', 'price', 'photo'))
            ->where("is_recommended = 1");

        return $this->fetchAll($sql)->toArray();
    }

    public function searchForQuery($query)
    {
        $q = $this->select()->from($this->_name, array('id', 'title', 'description', 'price', 'photo'))
            ->where("title LIKE '%$query%'");

        return $this->fetchAll($q)->toArray();
    }

    public function getProductsByPage($page, $sortType, $brand)
    {
        $sql = false;
        $limit = Zend_Registry::get('limit');
        $offset = ($page-1) * $limit;

        if ($sortType > 0)
        {
            switch($sortType)
            {
                case 1:
                    $sql = $this->select()->from($this->_name, array('id', 'title', 'description', 'price', 'photo'))
                        ->limit($limit, $offset);
                    break;
                case 2:
                    $sql = $this->select()->from($this->_name, array('id', 'title', 'description', 'price', 'photo'))
                        ->order('price DESC')
                        ->limit($limit, $offset);
                    break;
                case 3:
                    $sql = $this->select()->from($this->_name, array('id', 'title', 'description', 'price', 'photo'))
                        ->order('price ASC')
                        ->limit($limit, $offset);
                    break;
                default:
                    $sql = $this->select()->from($this->_name, array('id', 'title', 'description', 'price', 'photo'))
                        ->limit($limit, $offset);
                    break;
            }
        }
        else {
            $sql = $this->select()->from($this->_name, array('id', 'title', 'description', 'price', 'photo'))
                ->limit($limit, $offset);
        }

        return $this->fetchAll($sql)->toArray();
    }

    public function deleteProductById($id)
    {
        $sql = $this->delete("id = $id");

        return $this->fetchAll($sql)->toArray();
    }
}
