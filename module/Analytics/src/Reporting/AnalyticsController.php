<?php
namespace Mandala\Analytics\Reporting;

use Mandala\Analytics\Tracking\Event;
use Mandala\Application\Controller\BaseController;
use Zend\View\Model\ViewModel;
use MongoDB;

class AnalyticsController extends BaseController
{
    private $db;

    public function __construct(MongoDB $db)
    {
        $this->db = $db;
    }

    public function indexAction()
    {
        $collection = $this->db->selectCollection('weekly');
        $rows = $collection->find();

        $types = array(
            Event::NEW_VISITOR,
            Event::NEW_USER,
            Event::NEW_DESIGN,
            Event::NEW_BOOK,
            Event::BOOK_PURCHASED,
        );

        $tableRows = array();
        foreach ($rows as $row) {
            $date = date('Y-m-d', $row['week']->sec);
            if (! isset($tableRows[$date])) {
                $tableRows[$date] = array(
                    'week' => $date
                );
                foreach ($types as $type) {
                    $tableRows[$date][$type] = 0;
                }
            }

            $type = $row['type'];
            $tableRows[$date][$type] = $row['count'];
        }

        $totals = array();
        foreach ($types as $type) {
            $totals[$type] = array();
        }
        foreach ($rows as $row) {
            $type = $row['type'];
            $totals[$type][] = $row['count'];
        }

        return new ViewModel(array(
            'types' => $types,
            'dates' => array_keys($tableRows),
            'rows' => $tableRows,
            'totals' => $totals
        ));
    }
}