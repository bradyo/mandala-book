<?php
namespace Mandala\OrderModule;

use Doctrine\ORM\EntityManager;
use Guzzle\Http\Client;
use Guzzle\Http\Exception\BadResponseException;
use DateTime;
use Guzzle\Http\Exception\RequestException;
use Zend\Mail\Transport\TransportInterface as Mailer;
use Exception;

class OrderProcessor
{
    private $bookFileMaker;
    private $em;
    private $stripeClient;
    private $mailer;

    public function __construct(BookFileMaker $bookFileMaker, EntityManager $em, Client $stripeClient, Mailer $mailer)
    {
        $this->bookFileMaker = $bookFileMaker;
        $this->em = $em;
        $this->stripeClient = $stripeClient;
        $this->mailer = $mailer;
    }

    public function process(Order $order, PaymentForm $paymentForm)
    {
        $errors = $this->charge($order, $paymentForm);
        if (count($errors) === 0) {
            $errors = $this->send($order);
        }

        return $errors;
    }

    private function charge(Order $order, PaymentForm $paymentForm)
    {
        $errors = array();
        try {
            // create customer/card for payment
            $paymentData = $paymentForm->getData();
            $cardData = array(
                'number' => $paymentData['number'],
                'name' => $paymentData['name'],
                'exp_month' => $paymentData['expirationMonth'],
                'exp_year' => $paymentData['expirationYear'],
                'cvc' => $paymentData['securityCode'],
            );
            if (isset($paymentData['address_line_1'])) {
                $cardData = array_merge($cardData, array(
                    'address_line_1' => $paymentData['address'],
                    'address_state' => $paymentData['state'],
                    'address_zip' => $paymentData['zip'],
                ));
            }

            $request = $this->stripeClient->post('v1/customers', array(), array(
                'card' => $cardData
            ));
            $response = $request->send();
            $responseData = $response->json();
            $customerId = $responseData['id'];

            // charge customer
            $request = $this->stripeClient->post('v1/charges', array(), array(
                'amount' => round($order->totalCost * 100),
                'currency' => 'usd',
                'customer' => $customerId,
                'description' => 'Mandala Maker Order #' . $order->confirmationCode
            ));
            $request->send();

            // update order status
            $order->paidAt = new DateTime('now');
            $order->status = Order::STATUS_PAID;
            $this->em->persist($order);
            $this->em->flush();
        }
        catch (BadResponseException $e) {
            // get error message from error response
            $responseData = $e->getResponse()->json();
            $errors[] = $responseData['error']['message'];
        }
        catch (RequestException $e) {
            $errors[] = $e->getMessage();
        }

        return $errors;
    }

    private function send(Order $order)
    {
        $errors = array();
        try {
            $pdfPath = $this->bookFileMaker->createPdf($order);
            $message = new BookEmailMessage($order, $pdfPath);
            $this->mailer->send($message);

            // update order status
            $order->shippedAt = new DateTime('now');
            $order->status = Order::STATUS_SHIPPED;
            $this->em->persist($order);
            $this->em->flush();
        }
        catch (Exception $e) {
            $errors[] = $e->getMessage();
        }

        return $errors;
    }
}