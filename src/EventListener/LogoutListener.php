<?php

namespace App\EventListener;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\Security\Http\Event\LogoutEvent;

class LogoutListener
{
    public function __construct(private EntityManagerInterface $em)
    {}

    public function onLogout(LogoutEvent $event): void
    {
        $user = $event->getToken()?->getUser();
        if ($user) {
            $user->setIsConnected(false);
            $this->em->flush();
            $event->getRequest()->getSession()->invalidate();
        }
    }
}
