<?php
namespace Mandala\Application\Controller;

class UpdateController extends BaseController
{
    public function updateAction()
    {
        // call update function based on date param
        $method = 'update' .  $this->getRequest()->getParam('date', date('Y-m-d'));
        $this->$method();
    }

    public function update20130530()
    {
        echo 'running update 20130530' . "\n";

        $services = $this->getServiceLocator();

        // regenerate design files
        $fileService = $services->get('design_file_service');
        $designs = $services->get('design_repository')->findAll();
        foreach ($designs as $design) {
            echo 'generating files for design ' . $design->id . "\n";
            try {
                $fileService->createSvg($design);
                $fileService->createThumbnail($design, 164);
            } catch (\Exception $e) {
                echo "failed with exception: " . $e->getMessage() . "\n";
            }
        }

        // resend orders
        $orderProcessor = $services->get('order_processor');
        $orders = $services->get('entity_manager')->getRepository('Mandala\OrderModule\Order')->findAll();
        foreach ($orders as $order) {
            echo 'resending order ' . $order->id . ' (confirmation #' . $order->confirmationCode . ')' . "\n";
            try {
                $orderProcessor->send($order);
            } catch (\Exception $e) {
                echo "failed with exception: " . $e->getMessage() . "\n";
            }
        }
        echo "done\n";
    }
} 