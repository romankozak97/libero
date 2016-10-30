<?php

class Application_Form_Login extends Zend_Form
{

    public function init()
    {
        $this->setName('login');
        $this->setAttrib('class', 'login-form clearfix center-block');
        $isEmptyMessage = 'Value is required and can\'t be empty!';

        $email = new Zend_Form_Element_Text('email');
        $email->setAttrib('class', 'login-input')
            ->setLabel('Email')
            ->setAttrib('id', 'login-email')
            ->setAttrib('onfocus', 'loginEmailFocus()')
            ->setAttrib('onblur', 'loginEmailBlur()')
            ->setRequired(true)
            ->addFilter('StripTags')
            ->addFilter('StringTrim')
            ->addValidator('NotEmpty', true, array('messages' => array('isEmpty' => $isEmptyMessage)));

        $password = new Zend_Form_Element_Password('password');
        $password->setAttrib('class', 'login-input')
            ->setLabel('Password')
            ->setAttrib('id', 'login-pass')
            ->setAttrib('onfocus', 'loginPassFocus()')
            ->setAttrib('onblur', 'loginPassBlur()')
            ->setRequired(true)
            ->addFilter('StripTags')
            ->addFilter('StringTrim')
            ->addValidator('NotEmpty', true, array('messages' => array('isEmpty' => $isEmptyMessage)));

        $submit = new Zend_Form_Element_Submit('login');
        $submit->setAttrib('class', 'login-btn pull-left');

        $register = new Zend_Form_Element_Button('register');
        $register->setAttrib('class', 'login-btn signup-btn pull-right')
            ->setAttrib('onclick', 'goto("register")');

        $this->addElements(array($email, $password, $submit, $register));
        $this->setMethod('post');
    }


}

