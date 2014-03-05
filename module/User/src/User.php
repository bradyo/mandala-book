<?php
namespace Mandala\UserModule;

use Doctrine\ORM\Mapping as Orm;

/**
 * @Orm\Entity(repositoryClass="Mandala\UserModule\UserRepository")
 */
class User
{
    const TYPE_ANONYMOUS = 'anonymous';
    const TYPE_REGISTERED = 'registered';
    const TYPE_VERIFIED = 'verified';

    const STATUS_ACTIVE = 'active';
    const STATUS_DELETED = 'deleted';

    /**
     * @Orm\Id
     * @Orm\Column(type="integer")
     * @Orm\GeneratedValue(strategy="AUTO")
     */
    public $id;

    /**
     * @Orm\Column(type="string")
     */
    public $type = self::TYPE_ANONYMOUS;

    /**
     * @Orm\Column(type="string")
     */
    public $status = self::STATUS_ACTIVE;

    /**
     * @Orm\Column(type="string", nullable=true)
     */
    public $email;

    /**
     * @Orm\Column(type="string", nullable=true)
     */
    public $passwordHash;

    /**
     * @Orm\Column(type="string", nullable=true)
     */
    public $passwordSalt;

    public function setPassword($password)
    {
        $this->passwordSalt = sha1(time());
        $this->passwordHash = sha1($password . $this->passwordSalt);
    }

    public function isPassword($password)
    {
        return sha1($password . $this->passwordSalt) === $this->passwordHash;
    }

    public function isAnonymous()
    {
        return ($this->type === self::TYPE_ANONYMOUS);
    }
}