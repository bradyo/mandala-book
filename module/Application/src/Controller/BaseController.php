<?php
namespace Mandala\Application\Controller;

use Doctrine\ORM\EntityManager;
use Mandala\Analytics\Tracking\Tracker;
use Mandala\UserModule\User;
use Mandala\UserModule\UserRepository;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Http\Response;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Http\Request;

abstract class BaseController extends AbstractActionController implements ServiceLocatorAwareInterface
{
    /**
     * @var ServiceLocatorInterface
     */
    protected $serviceLocator;

    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
    }

    /**
     * @return Request
     */
    public function getRequest()
    {
        return parent::getRequest();
    }

    protected function getSuccessResponse($message = 'Success')
    {
        $response = new Response();
        $response->setContent($message);
        $response->setStatusCode(200);
        return $response;
    }

    protected function getNotFoundResponse($message = 'Not found')
    {
        $response = new Response();
        $response->setContent($message);
        $response->setStatusCode(404);
        return $response;
    }

    protected function getNotAllowedResponse($message = 'Not allowed')
    {
        $response = new Response();
        $response->setContent($message);
        $response->setStatusCode(403);
        return $response;
    }

    protected function getBadRequestResponse($message = 'Bad request')
    {
        $response = new Response();
        $response->setContent($message);
        $response->setStatusCode(400);
        return $response;
    }

    protected function getConflictResponse($message = 'Conflict')
    {
        $response = new Response();
        $response->setContent($message);
        $response->setStatusCode(409);
        return $response;
    }

    protected function getImageResponse($content, $contentType)
    {
        $response = new Response();
        $response->setContent($content);
        $response->setStatusCode(200);
        $response->getHeaders()
            ->addHeaderLine('Content-Transfer-Encoding', 'binary')
            ->addHeaderLine('Content-Type', $contentType)
            ->addHeaderLine('Content-Length', mb_strlen($content));
        return $response;
    }

    /**
     * @return EntityManager
     */
    public function getEntityManager()
    {
        return $this->serviceLocator->get('entity_manager');
    }

    /**
     * @return User
     */
    public function getCurrentUser()  {
        return $this->serviceLocator->get('current_user');
    }

    /**
     * @return UserRepository
     */
    public function getUserRepository() {
        return $this->serviceLocator->get('user_repository');
    }

    /**
     * @return Tracker
     */
    public function getTracker() {
        return $this->serviceLocator->get('analytics_tracker');
    }
}