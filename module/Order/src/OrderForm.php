<?php
namespace Mandala\OrderModule;

use Mandala\BookModule\Book;
use Mandala\UserModule\User;
use Zend\Form\Form;

class OrderForm extends Form
{
    private $user;
    private $book;

    public function __construct(User $user, Book $book)
    {
        parent::__construct();

        $this->setInputFilter(new OrderPostFilter());

        $this->user = $user;
        $this->book = $book;

        $this->add(array(
            'name' => 'title',
            'type' => 'text',
            'attributes' => array(
                'placeholder' => 'Enter book title...',
                'value' => $book->title,
            ),
            'options' => array(
                'label' => 'Title'
            )
        ));
        $this->add(array(
            'name' => 'deliveryMethod',
            'type' => 'radio',
            'attributes' => array(
                'id' => 'delivery-method',
                'value' => 'email'
            ),
            'options' => array(
                'value_options' => array(
                    'email' => 'E-mail Delivery ($10.00)',
//                    'mail' => 'Mail Delivery ($20.00 + Shipping + Tax)'
                ),
            ),
        ));
        $this->add(array(
            'name' => 'recipientEmail',
            'type' => 'text',
            'attributes' => array(
                'placeholder' => 'Enter recipient e-mail address...'
            ),
            'options' => array(
                'label' => 'E-mail Address',
                'help-block' => 'A PDF file of your book will be e-mailed to this address.'
            )
        ));
        $this->add(array(
            'name' => 'shippingName',
            'type' => 'text',
            'attributes' => array(
                'placeholder' => 'Enter recipient name...'
            ),
            'options' => array(
                'label' => 'Name'
            )
        ));
        $this->add(array(
            'name' => 'shippingStreet',
            'type' => 'text',
            'attributes' => array(
                'placeholder' => 'Enter shipping address street...'
            ),
            'options' => array(
                'label' => 'Street'
            )
        ));
        $this->add(array(
            'name' => 'shippingCity',
            'type' => 'text',
            'attributes' => array(
                'placeholder' => 'Enter shipping address city...'
            ),
            'options' => array(
                'label' => 'City'
            )
        ));
        $this->add(array(
            'name' => 'shippingState',
            'type' => 'text',
            'attributes' => array(
                'placeholder' => 'Enter shipping address state...'
            ),
            'options' => array(
                'label' => 'State'
            )
        ));
        $this->add(array(
            'name' => 'shippingZip',
            'type' => 'text',
            'attributes' => array(
                'placeholder' => 'Enter shipping address zip code...'
            ),
            'options' => array(
                'label' => 'Zip Code'
            )
        ));
        $this->add(array(
            'name' => 'token',
            'type' => 'csrf'
        ));
    }

    public function getUser()
    {
        return $this->user;
    }

    public function getBook()
    {
        return $this->book;
    }
}