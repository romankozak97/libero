<?php

class Application_Form_Register extends Zend_Form
{

    public function init()
    {
        $this->setName('register');
        $this->setAttrib('class', 'login-form clearfix center-block');
        $isEmptyMessage = 'Value is required and can\'t be empty!';

        $name = new Zend_Form_Element_Text('name');
        $name->setAttrib('class', 'login-input')
            ->setLabel('Your name')
            ->setAttrib('id', 'login-name')
            ->setAttrib('onfocus', 'loginNameFocus()')
            ->setAttrib('onblur', 'loginNameBlur()')
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

        $recaptcha = new Zend_Service_ReCaptcha("6LfHmAgUAAAAAOe9uBjAw-k5nAitFt02n8BI09jo",
            "6LfHmAgUAAAAAOfcU5kmNxT5sPuc3_OjbZQV4IPP");
        $captcha = $this->createElement('Captcha', 'ReCaptcha',
            array('captcha'=>array('captcha'=>'ReCaptcha',
                'service'=>$recaptcha)));

        $captcha->setLabel('Enter CAPTCHA');

        $submit = new Zend_Form_Element_Submit('register');
        $submit->setAttrib('class', 'login-btn pull-left');

        $this->addElements(array($name, $email, $password, $captcha, $submit));
        $this->setMethod('post');
    }


}

