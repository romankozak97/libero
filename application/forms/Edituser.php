<?php

class Application_Form_Edituser extends Zend_Form
{

    public function init()
    {
        $this->setName('edit-user');
        $this->setAttrib('class', 'edit-form clearfix');
        $isEmptyMessage = 'Value is required and can\'t be empty!';

        $name = new Zend_Form_Element_Text('name');
        $name->setAttrib('class', 'login-input')
            ->setAttrib('placeholder', 'Name')
            ->setRequired(true)
            ->addFilter('StripTags')
            ->addFilter('StringTrim')
            ->addValidator('NotEmpty', true, array('messages' => array('isEmpty' => $isEmptyMessage)));

        $email = new Zend_Form_Element_Text('email');
        $email->setAttrib('class', 'login-input')
            ->setAttrib('placeholder', 'Email')
            ->setAttrib('disabled', 'disabled');

        $currentPassword = new Zend_Form_Element_Password('currentpassword');
        $currentPassword->setAttrib('class', 'login-input')
            ->setAttrib('placeholder', 'Current password')
            ->setRequired(true)
            ->addFilter('StripTags')
            ->addFilter('StringTrim')
            ->addValidator('NotEmpty', true, array('messages' => array('isEmpty' => $isEmptyMessage)));

        $newPassword = new Zend_Form_Element_Password('newpassword');
        $newPassword->setAttrib('class', 'login-input')
            ->setAttrib('placeholder', 'New password')
            ->setRequired(true)
            ->addFilter('StripTags')
            ->addFilter('StringTrim')
            ->addValidator('NotEmpty', true, array('messages' => array('isEmpty' => $isEmptyMessage)));

        $submit = new Zend_Form_Element_Submit('save');
        $submit->setAttrib('class', 'login-btn pull-left');

        $this->addElements(array($name, $email, $currentPassword, $newPassword, $submit));
        $this->setMethod('post');
    }


}

