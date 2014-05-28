<?php
namespace Mandala\Analytics\Tracking;

use MongoDB;
use MongoDate;

class Tracker
{
    private $db;

    public function __construct(MongoDB $db)
    {
        $this->db = $db;
    }

    public function log(Event $event)
    {
        if ($event->createdAt === null) {
            $event->createdAt = new MongoDate();
        }
        $row = array(
            'created_at' => $event->createdAt,
            'type' => $event->type,
            'data' => $event->data
        );
        $collection = $this->db->selectCollection('events');
        $collection->insert($row);
    }
} 