<?php
namespace Mandala\OrderModule;

class OrderFactory
{
    public function createOrder(OrderForm $orderForm)
    {
        $data = $orderForm->getData();
        $book = $orderForm->getBook();

        $order = new Order();
        $order->confirmationCode = substr(md5(uniqid(rand(), true)), 0, 10);
        $order->status = Order::STATUS_PENDING;
        $order->user = $orderForm->getUser();

        $order->title = $data['title'];
        foreach ($book->bookDesigns as $bookDesign) {
            $order->addDesign($bookDesign->design);
        }

        $order->deliveryMethod = $data['deliveryMethod'];
        switch ($order->deliveryMethod)
        {
            case Order::DELIVERY_TYPE_EMAIL:
                $order->recipientEmail = $data['recipientEmail'];
                $order->goodsCost = 1000;
                $order->taxCost = 0;
                $order->shippingCost = 0;
                $order->totalCost = $order->goodsCost + $order->taxCost + $order->shippingCost;
                break;

            case Order::DELIVERY_TYPE_MAIL:
                $order->shippingName = $data['shippingName'];
                $order->shippingStreet = $data['shippingStreet'];
                $order->shippingCity = $data['shippingCity'];
                $order->shippingState = $data['shippingState'];
                $order->shippingZip = $data['shippingZip'];
                $order->goodsCost = 2000;
                $order->taxCost = 0;
                $order->shippingCost = 400;
                break;
        }

        return $order;
    }
}