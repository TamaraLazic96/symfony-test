<?php

namespace App\Event;

use App\Entity\UserPreferences;
use App\Mailer\Mailer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class UserSubscriber implements EventSubscriberInterface
{

    /** @var Mailer */
    private $mailer;
    private $manager;
    private $defaultLocale;

    public function __construct(
        Mailer $mailer,
        EntityManagerInterface $manager,
        string $defaultLocale)
    {
        $this->mailer = $mailer;
        $this->manager = $manager;
        $this->defaultLocale = $defaultLocale;
    }

    public static function getSubscribedEvents()
    {
        return [
            UserRegisterEvent::NAME => 'onUserRegister'
        ];
    }

    public function onUserRegister(UserRegisterEvent $event)
    {
        $preferences = new UserPreferences();
        $preferences->setLocal($this->defaultLocale);

        $user = $event->getRegisteredUser();
        $user->setUserPreferences($preferences);

        $this->manager->flush();

        $this->mailer->sendConfirmationMail($event->getRegisteredUser());
    }
}