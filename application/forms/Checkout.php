<?php

class Application_Form_Checkout extends Zend_Form
{

    public function init()
    {

    	//TODO: fix input focus and label highlighting
        $this->setName('checkout');
        $this->setAttrib('class', 'login-form clearfix center-block');
        $isEmptyMessage = 'Value is required and can\'t be empty!';

        $firstName = new Zend_Form_Element_Text('firstname');
        $firstName->setAttrib('class', 'login-input')
            ->setLabel('First name')
            ->setAttrib('id', 'login-name')
            ->setAttrib('onfocus', 'loginNameFocus()')
            ->setAttrib('onblur', 'loginNameBlur()')
            ->setRequired(true)
            ->addFilter('StripTags')
            ->addFilter('StringTrim')
            ->addValidator('NotEmpty', true, array('messages' => array('isEmpty' => $isEmptyMessage)));

        $lastName = new Zend_Form_Element_Text('lastname');
        $lastName->setAttrib('class', 'login-input')
            ->setLabel('Last name')
            ->setAttrib('id', 'login-name')
            ->setAttrib('onfocus', 'loginNameFocus()')
            ->setAttrib('onblur', 'loginNameBlur()')
            ->setRequired(true)
            ->addFilter('StripTags')
            ->addFilter('StringTrim')
            ->addValidator('NotEmpty', true, array('messages' => array('isEmpty' => $isEmptyMessage)));

        $phone = new Zend_Form_Element_Text('phone');
        $phone->setAttrib('class', 'login-input')
            ->setLabel('Phone number')
            ->setAttrib('id', 'login-email')
            ->setAttrib('onfocus', 'loginEmailFocus()')
            ->setAttrib('onblur', 'loginEmailBlur()')
            ->setRequired(true)
            ->addFilter('StripTags')
            ->addFilter('StringTrim')
            ->addValidator('NotEmpty', true, array('messages' => array('isEmpty' => $isEmptyMessage)));

        $email = new Zend_Form_Element_Text('email');
        $email->setAttrib('class', 'login-input')
            ->setLabel('Email')
            ->setAttrib('id', 'login-email')
            ->setAttrib('onfocus', 'loginEmailFocus()')
            ->setAttrib('onblur', 'loginEmailBlur()')
            ->setRequired(true)
            ->addFilter('StripTags')
            ->addFilter('StringTrim');

        $comment = new Zend_Form_Element_Textarea('comment');
        $comment->setAttrib('class', 'login-input')
            ->setLabel('Comment')
            ->setAttrib('id', 'login-pass')
            ->setAttrib('onfocus', 'loginPassFocus()')
            ->setAttrib('onblur', 'loginPassBlur()')
            ->setAttrib('style', 'height:150px;')
            ->addFilter('StripTags')
            ->addFilter('StringTrim');

        $submit = new Zend_Form_Element_Submit('confirm');
        $submit->setAttrib('class', 'login-btn pull-left');

        $this->addElements(array($firstName, $lastName, $phone, $email, $comment, $submit));
        $this->setMethod('post');
    }


}

