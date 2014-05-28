<?php
namespace Mandala\Analytics\Reporting;

use Mandala\Application\Controller\BaseController;
use MongoDB;

class AggregationController extends BaseController
{
    private $db;

    public function __construct(MongoDB $db)
    {
        $this->db = $db;
    }

    public function aggregateWeeklyAction()
    {
        $date = $this->getRequest()->getParam('date', date('Y-m-d'));

        $aggregator = new Aggregator($this->db);
        $aggregator->aggregateWeekly($date);

        return "success\n";
    }
} 