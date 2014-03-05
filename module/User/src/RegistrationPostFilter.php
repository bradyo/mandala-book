<?php
namespace Mandala\UserModule;

use Zend\InputFilter\InputFilter;
use Zend\Validator\NotEmpty;

class RegistrationPostFilter extends InputFilter
{
    public function __construct()
    {
        $this->add(array(
            'name' => 'email',
            'required' => true,
            'filters' => array(
                array('name' => 'StringTrim')
            ),
            'validators' => array(
                array(
                    'name' => 'NotEmpty',
                    'options' => array(
                        'messages' => array(
                            NotEmpty::IS_EMPTY => 'Please enter your email address'
                        ),
                    ),
                )
            )
        ));
        $this->add(array(
            'name' => 'password',
            'required' => true,
            'filter' => array(
                array('name' => 'StringTrim')
            ),
            'validators' => array(
                array(
                    'name' => 'NotEmpty',
                    'options' => array(
                        'messages' => array(
                            NotEmpty::IS_EMPTY => 'Please enter a password'
                        ),
                    ),
                )
            )
        ));
    }
} 