<?php

class Application_Form_Addcategory extends Zend_Form
{

    public function init()
    {
        $this->setName('addcategory');
        $this->setAttrib('class', 'login-form clearfix center-block');
        $isEmptyMessage = 'Value is required and can\'t be empty!';

        $title = new Zend_Form_Element_Text('title');
        $title->setAttrib('class', 'login-input')
            ->setAttrib('placeholder', 'Title')
            ->setRequired(true)
            ->addFilter('StripTags')
            ->addFilter('StringTrim')
            ->addValidator('NotEmpty', true, array('messages' => array('isEmpty' => $isEmptyMessage)));

        $items = new Zend_Form_Element_Select('items');
        $items->setAttrib('class', 'login-input')
            ->setAttrib('multiple', 'multiple')
            ->setRequired(true)
            ->addFilter('StripTags')
            ->addFilter('StringTrim')
            ->addValidator('NotEmpty', true, array('messages' => array('isEmpty' => $isEmptyMessage)));

        $submit = new Zend_Form_Element_Submit('add');
        $submit->setAttrib('class', 'login-btn pull-left');

        $this->addElements(array($title, $items, $submit));
        $this->setMethod('post');
    }


}

