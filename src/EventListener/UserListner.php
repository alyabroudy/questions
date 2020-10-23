<?php


namespace App\EventListener;


use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class UserListner
{
    private $entityManager;

    private $userPasswordEncoder;

    public function __construct(UserPasswordEncoderInterface $userPasswordEncoder)
    {
        $this->userPasswordEncoder = $userPasswordEncoder;
    }
    // the entity listener methods receive two arguments:
    // the entity instance and the lifecycle event
    public function prePersist(User $user, LifecycleEventArgs $event)
    {
        $user->setPassword($this->userPasswordEncoder->encodePassword($user, $user->getPassword()));
        return $user;
    }
}