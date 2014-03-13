<?php
namespace Mandala\BookModule;

use Zend\Form\Form;

class NewBookForm extends Form
{
    public function __construct()
    {
        parent::__construct();
        $this->add(array(
            'name' => 'title',
            'type' => 'text',
            'attributes' => array(
                'type' => 'text',
                'placeholder' => 'Enter a book title...',
            ),
            'options' => array(
                'label' => 'Book Title',
            )
        ));
        $this->add(array(
            'name' => 'token',
            'type' => 'csrf'
        ));
    }
}