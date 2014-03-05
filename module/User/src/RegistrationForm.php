<?php
namespace Mandala\UserModule;

use Zend\Form\Form;

class RegistrationForm extends Form
{
    public function __construct()
    {
        parent::__construct();

        $this->add(array(
            'name' => 'email',
            'type' => 'text',
            'attributes' => array(
                'placeholder' => 'Enter your e-mail address...',
            ),
            'options' => array(
                'label' => 'E-mail Address',
            )
        ));
        $this->add(array(
            'name' => 'password',
            'type' => 'password',
            'attributes' => array(
                'autocomplete' => 'off',
                'placeholder' => 'Enter your password...',
            ),
            'options' => array(
                'label' => 'Password',
            )
        ));
        $this->add(array(
            'name' => 'token',
            'type' => 'csrf'
        ));
    }
}