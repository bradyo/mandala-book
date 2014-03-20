<?php
namespace Mandala\OrderModule;

use Mandala\BookModule\Book;
use Mandala\BookModule\BookDesign;
use Mandala\BookModule\BookService;
use Guzzle\Http\Client;
use Guzzle\Http\Exception\BadResponseException;
use Guzzle\Http\Exception\RequestException;
use Zend\View\Model\ViewModel;
use Mandala\Application\Controller\BaseController;

class OrderController extends BaseController
{
    public function createAction()
    {
        $bookId = (int) $this->params()->fromRoute('bookId');

        $book = $this->getServiceLocator()->get('book_repository')->findOneById($bookId);
        if ($book === null) {
            return $this->notFoundAction('Book not found');
        }

        $form = new OrderForm();
        $form->setInputFilter(new OrderPostFilter());

        if ($this->getRequest()->isPost()) {
            $form->setData($this->params()->fromPost());
            if ($form->isValid()) {
                $data = $form->getData();

                // create order
                $order = new Order();
                $order->status = Order::STATUS_PENDING;
                $order->user = $this->getCurrentUser();
                $order->book = $book;
                $order->deliveryMethod = $data['deliveryMethod'];
                if ($order->deliveryMethod == Order::DELIVERY_TYPE_EMAIL) {
                    $order->recipientEmail = $data['recipientEmail'];
                    $order->goodsCost = 4;
                    $order->taxCost = 0;
                    $order->shippingCost = 0;
                } else {
                    $order->shippingName = $data['shippingName'];
                    $order->shippingStreet = $data['shippingStreet'];
                    $order->shippingCity = $data['shippingCity'];
                    $order->shippingState = $data['shippingState'];
                    $order->shippingZip = $data['shippingZip'];
                    $order->goodsCost = 6;
                    $order->taxCost = 0;
                    $order->shippingCost = 4;
                }
                $order->totalCost = round($order->goodsCost + $order->taxCost + $order->shippingCost, 2);

                $this->getEntityManager()->persist($order);
                $this->getEntityManager()->flush();

                $this->redirect()->toRoute('review-order', array('id' => $order->id));
            }
        }

        return new ViewModel(array(
            'form' => $form,
            'book' => $book
        ));
    }

    public function reviewAction()
    {
        $id = (int) $this->params()->fromRoute('id');
        $order = $this->getEntityManager()->find('Application\Model\Order', $id);
        if ($order->user !== $this->getCurrentUser()) {
            return $this->getNotAllowedResponse('Not allowed to access order');
        }
        $book = $order->book;

        $form = new PaymentForm();
        $form->setInputFilter(new PaymentPostFilter());

        $errors = array();
        if ($this->getRequest()->isPost()) {
            $form->setData($this->params()->fromPost());
            if ($form->isValid()) {
                $data = $form->getData();
                try {
                    $this->chargeCard($order, $data);
                } catch (\Exception $e) {
                    $errors[] = $e->getMessage();
                }
                if (count($errors) == 0) {
                    // render pdfs of each design
                    $designs = $this->getDesignRepository()->findByBook($book);
                    $pdfPath = BookService::generatePdf($book, $designs);

                    if ($order->deliveryMethod == Order::DELIVERY_TYPE_EMAIL) {
                        // send book to customer
                        $bodyPart = new \Zend\Mime\Message();
                        $bodyMessage = new \Zend\Mime\Part(
                            '<p>Enjoy your book from <a href="https://www.mandalabook.com">MandalaBook.com</a>!</p>'
                            . '<p>- The MandalaBook Team</p>');
                        $bodyMessage->type = 'text/html';
                        $attachment = new \Zend\Mime\Part(fopen($pdfPath, 'r'));
                        $attachment->type = 'application/pdf';
                        $attachment->encoding = \Zend\Mime\Mime::ENCODING_BASE64;
                        $attachment->disposition = \Zend\Mime\Mime::DISPOSITION_ATTACHMENT;
                        $attachment->filename = 'mandala-book-' . $order->getConfirmationCode();
                        $bodyPart->setParts(array($bodyMessage, $attachment));

                        $message = new \Zend\Mail\Message();
                        $message->setEncoding('utf-8')
                            ->setTo($order->recipientEmail)
                            ->setReplyTo('support@mandalabook.com')
                            ->setFrom('support@mandalabook.com', 'MandalaBook.com')
                            ->setSubject('Your Mandala Book for Order #' . $order->getConfirmationCode())
                            ->setBody($bodyPart);
                        $mailer = $this->getServiceLocator()->get('mailer');
                        $mailer->send($message);
                    }
                    else {
                        // send to distributor
                        $contactEmail = $this->getCurrentUser()->email;
                        $bodyPart = new \Zend\Mime\Message();
                        $bodyMessage = new \Zend\Mime\Part(
                            '<p><strong>Someone bought a book! Printing and shipping of book is required...</strong></p>'
                            . '<p>Shipping address:</p>'
                            . '<p>' . $order->shippingName . '<br>'
                            . $order->shippingStreet . '<br>'
                            . $order->shippingCity . ', ' . $order->shippingState . ' ' . $order->shippingZip . '<br>'
                            . '</p>'
                            . '<p>Contact email: ' . (! empty($contactEmail) ? $contactEmail : 'none given') . '</p>'
                            . '<p>Book Title: ' . $book->title . '</p>'
                            . '<p>[Book PDF attached]</p>'
                        );
                        $bodyMessage->type = 'text/html';
                        $attachment = new \Zend\Mime\Part(fopen($pdfPath, 'r'));
                        $attachment->type = 'application/pdf';
                        $attachment->encoding = \Zend\Mime\Mime::ENCODING_BASE64;
                        $attachment->disposition = \Zend\Mime\Mime::DISPOSITION_ATTACHMENT;
                        $attachment->filename = 'mandala-book-' . $order->getConfirmationCode();
                        $bodyPart->setParts(array($bodyMessage, $attachment));

                        $message = new \Zend\Mail\Message();
                        $message->setEncoding('utf-8')
                            ->setTo(array('bradyaolsen@gmail.com', 'lisalorenandonia@gmail.com'))
                            ->setReplyTo('support@madala.com')
                            ->setFrom('support@mandalabook.com', 'MandalaBook.com')
                            ->setSubject('Shipment required for order '  . $order->getConfirmationCode())
                            ->setBody($bodyPart);
                        $mailer = $this->getServiceLocator()->get('mailer');
                        $mailer->send($message);
                    }
                    $this->redirect()->toRoute('order-confirmation', array('id' => $order->id));
                }
            }
        }

        $designCount = (int) $this->getEntityManager()
            ->createQuery('SELECT COUNT(bd.id) FROM Application\Model\BookDesign bd WHERE bd.book = :book')
            ->setParameter('book', $book)
            ->getSingleScalarResult();

        return new ViewModel(array(
            'order' => $order,
            'form' => $form,
            'book' => $book,
            'designCount' => $designCount,
            'errors' => $errors
        ));
    }

    private function chargeCard($order, $paymentData)
    {
        // create Stripe client
        $config = $config = $this->getServiceLocator()->get('Config');;
        $client = new Client('https://api.stripe.com', array(
            'request.options' => array(
                'auth' => array($config['stripe']['secret_key'] . ':', '', 'Basic')
            ),
        ));

        // create customer/card
        $request = $client->post('v1/customers', array(), array(
            'card' => array(
                'number' => $paymentData['number'],
                'name' => $paymentData['name'],
                'exp_month' => $paymentData['expirationMonth'],
                'exp_year' => $paymentData['expirationYear'],
                'cvc' => $paymentData['securityCode'],
                'address_line_1' => $paymentData['address'],
                'address_state' => $paymentData['state'],
                'address_zip' => $paymentData['zip'],
            )
        ));

        try {
            $response = $request->send();
        } catch (BadResponseException $e) {
            $responseData = $e->getResponse()->json();
            throw new \Exception($responseData['error']['message'], 0, $e);
        }
        $responseData = $response->json();
        $customerId = $responseData['id'];

        $request = $client->post('v1/charges', array(), array(
            'amount' => round($order->totalCost * 100),
            'currency' => 'usd',
            'customer' => $customerId,
            'description' => 'Mandala Maker Order #' . $order->getConfirmationCode()
        ));
        try {
            $response = $request->send();
        } catch (RequestException $e) {
            throw new \Exception($e->getMessage(), 0, $e);
        }

        // update order status
        $order->status = Order::STATUS_PAID;
        $this->getEntityManager()->persist($order);
        $this->getEntityManager()->flush();
    }

    public function confirmationAction()
    {
        $id = (int) $this->params()->fromRoute('id');
        $order = $this->getEntityManager()->find('Application\Model\Order', $id);
        if ($order->user !== $this->getCurrentUser()) {
            return $this->getNotAllowedResponse('Not allowed to access order');
        }
        return new ViewModel(array(
            'order' => $order
        ));
    }
} 