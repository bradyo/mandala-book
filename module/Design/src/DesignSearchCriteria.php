<?php
namespace Mandala\DesignModule;

class DesignSearchCriteria
{
    public $userFavorited;
    public $status;
    public $author;

    public static function fromArray(array $data)
    {
        $mapping = array(
            'user_favorited' => 'userFavorited',
            'status' => 'status',
            'author' => 'author'
        );
        $criteria = new self();
        foreach ($data as $key => $value) {
            if (isset($mapping[$key])) {
                $property = $mapping[$key];
                $criteria->$property = $value;
            }
        }
        return $criteria;
    }
}