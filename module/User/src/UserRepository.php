<?php
namespace Mandala\UserModule;

use Doctrine\ORM\EntityRepository;

class UserRepository extends EntityRepository
{
    public function findOneByCredentials($email, $password)
    {
        $user = $this->findOneBy(array('email' => $email));
        if ($user !== null && $user->isPassword($password)) {
            return $user;
        }
        return null;
    }
} 