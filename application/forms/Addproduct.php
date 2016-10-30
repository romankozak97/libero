<?php

class Application_Form_Addproduct extends Zend_Form
{

    public function init()
    {
        $this->setName('addproduct');
        $this->setAttrib('class', 'login-form clearfix center-block');
        $isEmptyMessage = 'Value is required and can\'t be empty!';

        $title = new Zend_Form_Element_Text('title');
        $title->setAttrib('class', 'login-input')
            ->setAttrib('placeholder', 'Title')
            ->setRequired(true)
            ->addFilter('StripTags')
            ->addFilter('StringTrim')
            ->addValidator('NotEmpty', true, array('messages' => array('isEmpty' => $isEmptyMessage)));

        $price = new Zend_Form_Element_Text('price');
        $price->setAttrib('class', 'login-input')
            ->setAttrib('placeholder', 'Price')
            ->setRequired(true)
            ->addFilter('StripTags')
            ->addFilter('StringTrim')
            ->addValidator('NotEmpty', true, array('messages' => array('isEmpty' => $isEmptyMessage)));

        $desc = new Zend_Form_Element_Textarea('description');
        $desc->setAttrib('class', 'login-input')
            ->setAttrib('placeholder', 'Description')
            ->setRequired(true)
            ->addFilter('StripTags')
            ->addFilter('StringTrim')
            ->addValidator('NotEmpty', true, array('messages' => array('isEmpty' => $isEmptyMessage)));

        $size = new Zend_Form_Element_Text('size');
        $size->setAttrib('class', 'login-input')
            ->setAttrib('placeholder', 'Size')
            ->setRequired(true)
            ->addFilter('StripTags')
            ->addFilter('StringTrim')
            ->addValidator('NotEmpty', true, array('messages' => array('isEmpty' => $isEmptyMessage)));

        $brand = new Zend_Form_Element_Text('brand');
        $brand->setAttrib('class', 'login-input')
            ->setAttrib('placeholder', 'Brand')
            ->setRequired(true)
            ->addFilter('StripTags')
            ->addFilter('StringTrim')
            ->addValidator('NotEmpty', true, array('messages' => array('isEmpty' => $isEmptyMessage)));

        $category = new Zend_Form_Element_Select('category');
        $category->setAttrib('class', 'login-input')
               ->setMultiOptions(array(
                   '0' => 'Accessories',
                   '1' => 'Shoes',
                   '2' => 'Hoodies & Pullovers',
                   '3' => 'Jackets',
                   '4' => 'Pants',
                   '5' => 'Shorts'
               ))
            ->setRequired(true)
            ->addFilter('StripTags')
            ->addFilter('StringTrim')
            ->addValidator('NotEmpty', true, array('messages' => array('isEmpty' => $isEmptyMessage)));

        $photo = new Zend_Form_Element_File('photo');
        $photo->setAttrib('class', 'login-input')
            ->setRequired(true)
            ->addFilter('StripTags')
            ->addFilter('StringTrim')
            ->addValidator('NotEmpty', true, array('messages' => array('isEmpty' => $isEmptyMessage)));

        $submit = new Zend_Form_Element_Submit('add');
        $submit->setAttrib('class', 'login-btn pull-left');

        $this->addElements(array($title, $price, $desc, $size, $brand, $category, $photo, $submit));
        $this->setMethod('post');
    }
}

