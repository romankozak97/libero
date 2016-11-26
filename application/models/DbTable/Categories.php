<?php

class Application_Model_DbTable_Categories extends Zend_Db_Table_Abstract
{

    protected $_name = 'categories';

    /**
     * Get categories list
     * READ
     * @return array Categories assoc array
     */
    public function getCategoriesList()
    {
        $sql = $this->select()->from($this->_name, array('id', 'title', 'items'));

        return $this->fetchAll($sql)->toArray();
    }

    /**
     * Get category
     * READ
     * @param $id int Category ID
     * @return array Category info assoc arrray
     */
    public function getCategoryById($id)
    {
        $id = intval($id);
        $row = $this->fetchRow("id = $id");

        return $row->toArray();
    }

    /**
     * Add category
     * CREATE
     * @param $title string Category title
     * @param $items string JSON formatted string with items IDs
     */
    public function addCategory($title, $items)
    {
        $data = array(
            'title' => $title,
            'items' => $items
        );

        $this->insert($data);
    }

    /**
     * Edit category
     * UPDATE
     * @param $title string Category title
     * @param $items string JSON formatted string with items IDs
     */
    public function editCategory($id, $title, $items)
    {
        $data = array(
            'title' => $title,
            'items' => $items
        );

        $this->update($data, "id=$id");
    }

    /**
     * Delete category
     * DELETE
     * @param $id int Category ID
     */
    public function deleteCategoryById($id)
    {
        $sql = $this->delete("id = $id");
    }
}

