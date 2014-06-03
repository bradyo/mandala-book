<?php
namespace Mandala\OrderModule;

use Zend\InputFilter\InputFilter;
use Zend\Validator\InArray;
use Zend\Validator\NotEmpty;

class OrderPostFilter extends InputFilter
{
    public function __construct()
    {
        $this->add(array(
            'name' => 'title',
            'required' => true,
            'filters'  => array(
                array('name' => 'StringTrim'),
            ),
            'validators' => array(
                array(
                    'name' => 'StringLength',
                    'options' => array(
                        'encoding' => 'UTF-8',
                        'min' => 1,
                        'max' => 100,
                    ),
                ),
            ),
        ));
        $this->add(array(
            'name' => 'deliveryMethod',
            'required' => true,
            'filters' => array(),
            'validators' => array(
                array(
                    'name' => 'InArray',
                    'break_chain_on_failure' => true,
                    'options' => array(
                        'haystack' => array('email', 'mail'),
                        'messages' => array(
                            InArray::NOT_IN_ARRAY => 'Please select a delivery method!'
                        ),
                    ),
                ),
                array(
                    'name' => 'NotEmpty',
                    'break_chain_on_failure' => true,
                    'options' => array(
                        'messages' => array(
                            NotEmpty::IS_EMPTY => 'Please select a delivery method',
                        ),
                    ),
                ),
            ),
        ));
        $this->add(array(
            'name' => 'token',
            'required' => true,
        ));

        $this->add(array(
            'name' => 'recipientEmail',
            'required' => true
        ));

        $rawFields = array(
            'shippingName',
            'shippingStreet',
            'shippingCity',
            'shippingState',
            'shippingZip',
        );
        foreach ($rawFields as $field) {
            $this->add(array(
                'name' => $field,
                'required' => false
            ));
        }
    }
} 