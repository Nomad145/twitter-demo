<?php

namespace App\Form\Subscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;

/**
 * Class PasswordEncodeSubscriber
 *
 * Encodes a password on Form Submit.  It would also be acceptable for this
 * to be a Doctrine Event Listener.
 *
 * @author Michael Phillips <michaeljoelphillips@gmail.com>
 */
class PasswordEncodeSubscriber implements EventSubscriberInterface
{
    /**
     * @param PasswordEncoderInterface $encoder
     */
    public function __construct(PasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [FormEvents::SUBMIT => 'onSubmit'];
    }

    /**
     * Encodes the password from User Input.
     *
     * {@inheritdoc}
     */
    public function onSubmit(FormEvent $event)
    {
        /** @var App\Entity\User */
        $user = $event->getData();

        // Encode the password.
        $user->setPassword(
            $this->encoder->encodePassword(
                $user->getPassword(),
                // BCrypt generates it own salt.  This returns NULL.
                $user->getSalt()
            )
        );

        // Set the model data back on the event.  This might already be by
        // "reference" and not completely necessary.
        $event->setData($user);
    }
}
