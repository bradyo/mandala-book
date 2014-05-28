<?php
namespace Mandala\OrderModule;

use Guzzle\Http\Client;
use Guzzle\Http\Exception\BadResponseException;
use Guzzle\Http\Exception\RequestException;
use Zend\View\Model\ViewModel;
use Mandala\Application\Controller\BaseController;

class OrderController extends BaseController
{
    private $orderFactory;
    private $orderProcessor;

    public function __construct(OrderFactory $orderFactory, OrderProcessor $orderProcessor)
    {
        $this->orderFactory = $orderFactory;
        $this->orderProcessor = $orderProcessor;
    }

    public function createAction()
    {
        $bookId = (int) $this->params()->fromRoute('bookId');
        $book = $this->getServiceLocator()->get('book_repository')->createQueryBuilder('b')
            ->join('b.bookDesigns', 'bd')
            ->join('bd.design', 'd')
            ->where('b.id = :bookId')->setParameter('bookId', $bookId)
            ->getQuery()
            ->getSingleResult();
        if ($book === null) {
            return $this->notFoundAction('Book not found');
        }

        $form = new OrderForm($this->getCurrentUser(), $book);
        if ($this->getRequest()->isPost()) {
            $form->setData($this->params()->fromPost());
            if ($form->isValid()) {
                // save order
                $order = $this->orderFactory->createOrder($form);
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
        $order = $this->getEntityManager()->find('Mandala\OrderModule\Order', $id);
        if ($order->user !== $this->getCurrentUser()) {
            return $this->getNotAllowedResponse('Not allowed to access order');
        }

        $form = new PaymentForm();
        $errors = array();
        if ($this->getRequest()->isPost()) {
            $form->setData($this->params()->fromPost());
            if ($form->isValid()) {
                $errors = $this->orderProcessor->process($order, $form);
                if (count($errors) === 0) {
                    $this->redirect()->toRoute('order-confirmation', array('id' => $order->id));
                }
            }
        }

        return new ViewModel(array(
            'order' => $order,
            'form' => $form,
            'errors' => $errors
        ));
    }


    public function confirmationAction()
    {
        $id = (int) $this->params()->fromRoute('id');
        $order = $this->getEntityManager()->find('Mandala\OrderModule\Order', $id);
        if ($order->user !== $this->getCurrentUser()) {
            return $this->getNotAllowedResponse('Not allowed to access order');
        }

        return new ViewModel(array(
            'order' => $order
        ));
    }
} 