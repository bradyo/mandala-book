<?php
namespace Mandala\BookModule;

use Zend\InputFilter\InputFilter;

class NewBookPostFilter extends InputFilter
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
            'name' => 'token',
            'required' => true,
        ));
    }
} 