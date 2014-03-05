<?php
namespace Mandala\OrderModule;

use Zend\Form\Form;

class PaymentForm extends Form
{
    public function __construct()
    {
        parent::__construct();

        $this->add(array(
            'name' => 'name',
            'type' => 'text',
            'options' => array(
                'label' => 'Cardholder Name'
            )
        ));
        $this->add(array(
            'name' => 'number',
            'type' => 'text',
            'attributes' => array(
                'autocomplete' => "off",
            ),
            'options' => array(
                'label' => 'Card Number'
            )
        ));
        $this->add(array(
            'name' => 'expirationMonth',
            'type' => 'text',
            'attributes' => array(
                'size' => 2,
                'placeholder' => 'MM'
            ),
            'options' => array(
                'label' => 'Exp. Month'
            )
        ));
        $this->add(array(
            'name' => 'expirationYear',
            'type' => 'text',
            'attributes' => array(
                'size' => 4,
                'placeholder' => 'YYYY'
            ),
            'options' => array(
                'label' => 'Exp. Year',
            )
        ));
        $this->add(array(
            'name' => 'securityCode',
            'type' => 'text',
            'attributes' => array(
                'size' => 4,
            ),
            'options' => array(
                'label' => 'Security Code'
            )
        ));
        $this->add(array(
            'name' => 'token',
            'type' => 'csrf',
        ));
    }
}