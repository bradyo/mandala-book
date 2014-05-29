<?php
namespace Mandala\UserModule;

use Mandala\Analytics\Tracking\Event;
use Mandala\Application\Controller\BaseController;
use Zend\Session\Container;
use Zend\View\Model\ViewModel;

class UserController extends BaseController
{
    public function signInAction()
    {
        $form = new SignInForm();
        $message = null;
        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();
            $email = $data['email'];
            $password = $data['password'];
            $user = $this->getUserRepository()->findOneByCredentials($email, $password);
            if ($user !== null) {
                $session = new Container('user');
                $session['user_id'] = $user->id;
                $this->redirect()->toRoute('user-designs', array('userId' => $user->id));
            } else {
                $message = 'The account information you have entered is incorrect';
            }
        }
        return new ViewModel(array(
            'form' => $form,
            'message' => $message
        ));
    }

    public function signOutAction()
    {
        $session = new Container('user');
        $session->getManager()->destroy();
        $this->redirect()->toRoute('home');
    }

    public function signUpAction()
    {
        $form = new RegistrationForm();
        $form->setInputFilter(new RegistrationPostFilter());

        $message = null;
        if ($this->getRequest()->isPost()) {
            $form->setData($this->params()->fromPost());
            if ($form->isValid()) {
                $data = $form->getData();

                // check that user doesn't already exist
                $user = $this->getUserRepository()->findOneBy(array('email' => $data['email']));
                if ($user === null) {
                    // update current anonymous user data
                    $user = $this->getCurrentUser();
                    $user->type = User::TYPE_REGISTERED;
                    $user->email = $data['email'];
                    $user->setPassword($data['password']);

                    $this->getEntityManager()->persist($user);
                    $this->getEntityManager()->flush();

                    $this->getTracker()->log(new Event(Event::NEW_VISITOR));

                    $this->redirect()->toRoute('user-designs', array('userId' => $user->id));
                } else {
                    $message = 'A user with that e-mail address already exists';
                }
            }
        }
        return new ViewModel(array(
            'form' => $form,
            'message' => $message
        ));
    }
}
