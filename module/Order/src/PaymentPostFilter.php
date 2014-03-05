<?php
namespace Mandala\OrderModule;

use Zend\InputFilter\InputFilter;
use Zend\Validator\NotEmpty;

class PaymentPostFilter extends InputFilter
{
    public function __construct()
    {
        $this->add(array(
            'name' => 'name',
            'required' => true,
            'filters' => array(
                array('name' => 'StringTrim')
            ),
            'validators' => array(
                array(
                    'name' => 'NotEmpty',
                    'options' => array(
                        'messages' => array(
                            NotEmpty::IS_EMPTY => 'Please enter cardholder name'
                        ),
                    ),
                )
            )
        ));
        $this->add(array(
            'name' => 'number',
            'required' => true,
            'filter' => array(
                array('name' => 'StringTrim')
            ),
            'validators' => array(
                array('name' => 'CreditCard'),
            )
        ));
        $this->add(array(
            'name' => 'expirationMonth',
            'required' => true,
            'validators' => array(
                array(
                    'name' => 'Between',
                    'options' => array(
                        'min' => 1,
                        'max' => 12,
                    ),
                )
            )
        ));
        $this->add(array(
            'name' => 'expirationYear',
            'required' => true,
            'validators' => array(
                array(
                    'name' => 'Between',
                    'options' => array(
                        'min' => 2013,
                        'max' => 2050,
                    ),
                )
            )
        ));
        $this->add(array(
            'name' => 'securityCode',
            'required' => true,
            'validators' => array(
                array(
                    'name' => 'Between',
                    'options' => array(
                        'min' => 0,
                        'max' => 9999,
                    ),
                )
            )
        ));
    }
} 