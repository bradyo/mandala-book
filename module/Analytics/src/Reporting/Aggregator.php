<?php
namespace Mandala\Analytics\Reporting;

use Mandala\Analytics\Tracking\Event;
use MongoDB;
use MongoDate;

/**
 * Aggregates raw event data into aggregate data (for example, weekly totals)
 */
class Aggregator 
{
    private $db;

    public function __construct(MongoDB $db)
    {
        $this->db = $db;
    }

    public function aggregateWeekly($date = null)
    {
        $time = ($date !== null) ? strtotime($date) : time();
        $startTime = strtotime('last sunday', $time);
        $endTime = strtotime('last sunday + 1 week', $time);
        $startDate = new MongoDate($startTime);
        $endDate = new MongoDate($endTime);

        echo "date range: " . date('Y-m-d', $startDate->sec) . ' - ' . date('Y-m-d', $endDate->sec) . "\n";

        $events = $this->db->selectCollection('events')->find(array(
            'created_at' => array(
                '$gt' => $startDate,
                '$lte' => $endDate
            )
        ));

        echo "events: " . count(iterator_to_array($events)) . "\n";

        // compute event counts
        // todo: replace this with a map-reduce in mongodb

        $counts = array(
            Event::NEW_VISITOR => 0,
            Event::NEW_USER => 0,
            Event::NEW_DESIGN => 0,
            Event::NEW_BOOK => 0,
            Event::BOOK_PURCHASED => 0,
        );
        foreach ($events as $event) {
            $type = $event['type'];
            $counts[$type]++;
        }

        $weeklyStats = $this->db->selectCollection('weekly');
        $weeklyStats->remove(array('week' => $startDate));
        foreach ($counts as $type => $count) {
            $row = array(
                'week' => $startDate,
                'type' => $type,
                'count' => $count
            );
            $weeklyStats->insert($row);
        }
    }
}