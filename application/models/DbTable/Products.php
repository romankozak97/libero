<?php

class Application_Model_DbTable_Products extends Zend_Db_Table_Abstract
{

    protected $_name = 'products';

    /**
     * Get all products
     * @return array Products associative array
     */
    public function getProductsList()
    {
        $sql = $this->select()->from($this->_name, array('id', 'title', 'description', 'price', 'photo', 'size', 'category', 'brand'));

        return $this->fetchAll($sql)->toArray();
    }

    /**
     * Get product
     * @param $id int Product ID
     * @return array Product row associative array
     */
    public function getProductById($id)
    {
        $id = intval($id);
        $row = $this->fetchRow("id = $id");

        return $row->toArray();
    }

    /**
     * Get number of all products
     * @return array SQL Count-like associative array
     */
    public function getProductsCount()
    {
        $q = $this->select()->from($this->_name, 'count(*)');

        return $this->fetchAll($q)->toArray();
    }

    /**
     * Get recommended products list
     * @return array Recommended products assoc array
     */
    public function getRecommended()
    {
        $sql = $this->select()->from($this->_name, array('id', 'title', 'description', 'price', 'photo'))
            ->where("is_recommended = 1");

        return $this->fetchAll($sql)->toArray();
    }

    /**
     * Search product
     * @param $query string Query to search
     * @return array Search results assoc array
     */
    public function searchForQuery($query)
    {
        $q = $this->select()->from($this->_name, array('id', 'title', 'description', 'price', 'photo'))
            ->where("title LIKE '%$query%'");

        return $this->fetchAll($q)->toArray();
    }

    /**
     * Get products limited by page
     * @param $page int Page number
     * @param $sortType int Sorting type of products
     * @return array Products assoc array
     * @throws Zend_Exception
     */
    public function getProductsByPage($page, $sortType)
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

    /**
     * Get products limited by brand
     * @param $page int Page number
     * @param $brand string Brand
     * @return array Products assoc array
     * @throws Zend_Exception
     */
    public function getProductsByBrand($page, $brand)
    {
        $sql = false;
        $limit = Zend_Registry::get('limit');
        $offset = ($page-1) * $limit;

        $sql = $this->select()->from($this->_name, array('id', 'title', 'description', 'price', 'photo'))
            ->where("brand=$brand")
            ->limit($limit, $offset);

        return $this->fetchAll($sql)->toArray();
    }

    /**
     * Get products in category
     * @param $page int Page number
     * @param $category string Category
     * @return array Products assoc array
     * @throws Zend_Exception
     */
    public function getProductsByCategory($page, $category)
    {
        $sql = false;
        $limit = Zend_Registry::get('limit');
        $offset = ($page-1) * $limit;

        $sql = $this->select()->from($this->_name, array('id', 'title', 'description', 'price', 'photo'))
            ->where("category=$category")
            ->limit($limit, $offset);

        return $this->fetchAll($sql)->toArray();
    }

    /**
     * Add product
     * @param $title string Product title
     * @param $price int Product price
     * @param $description string Product description
     * @param $size int Product size
     * @param $brand string Product brand
     * @param $category int Product category
     * @param $photo string Product cover photo
     */
    public function addProduct($title, $price, $description, $size, $brand, $category, $photo)
    {
        $data = array(
            'title' => $title,
            'description' => $description,
            'price' => $price,
            'category' => $category,
            'brand' => $brand,
            'size' => $size,
            'photo' => $photo,
        );

        $this->insert($data);
    }

    /**
     * Edit product
     * @param $id int Product ID
     * @param $title string Product title
     * @param $price int Product price
     * @param $description string Product description
     * @param $size int Product size
     * @param $brand string Product brand
     * @param $category int Product category
     * @param $photo string Product cover photo
     */
    public function editProduct($id, $title, $price, $description, $size, $brand, $category, $photo)
    {
        $data = array(
            'title' => $title,
            'description' => $description,
            'price' => $price,
            'category' => $category,
            'brand' => $brand,
            'size' => $size,
            'photo' => $photo,
        );

        $this->update($data, "id=$id");
    }

    /**
     * Delete product
     * @param $id int Product ID
     */
    public function deleteProductById($id)
    {
        $sql = $this->delete("id = $id");
    }
}
