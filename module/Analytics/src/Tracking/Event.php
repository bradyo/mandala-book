<?php
namespace Mandala\Analytics\Tracking;

class Event 
{
    const NEW_VISITOR = 'new-visitor';
    const NEW_USER = 'new-user';
    const NEW_DESIGN = 'new-design';
    const NEW_BOOK = 'new-book';
    const BOOK_PURCHASED = 'book-purchased';

    public $id;
    public $createdAt;
    public $type;
    public $data;

    public function __construct($type, $data = array())
    {
        $this->type = $type;
        $this->data = $data;
    }
}