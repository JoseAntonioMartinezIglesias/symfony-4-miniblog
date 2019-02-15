<?php

namespace App\Event;

use App\Mailer\Mailer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class UserSubscriber implements EventSubscriberInterface {

    /**
     * @var Mailer
     */
    private $mailer;

    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var string
     */
    private $defaultLocale;

    public function __construct(
    Mailer $mailer,
    EntityManagerInterface $entityManager, string $defaultLocale
    ) {
        $this->mailer = $mailer;
        $this->entityManager = $entityManager;
        $this->defaultLocale = $defaultLocale;
    }

    public static function getSubscribedEvents() : array {
        return [
            UserRegisterEvent::NAME => 'onUserRegister',
        ];
    }

    public function onUserRegister(UserRegisterEvent $event) : void {
        $this->mailer->sendConfirmationEmail($event->getRegisteredUser());
    }

}
