<?php

namespace App\Form\Subscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormError;
use App\Repository\UserRepository;

/**
 * Class UniqueUserSubscriber
 *
 * Checks if a user's email and username is unique.
 *
 * @author Michael Phillips <michaeljoelphillips@gmail.com>
 */
class UniqueUserSubscriber implements EventSubscriberInterface
{
    /**
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [FormEvents::SUBMIT => 'onSubmit'];
    }

    /**
     * Invalidates a Form if a User's username or password is non-unique.
     *
     * {@inheritdoc}
     */
    public function onSubmit(FormEvent $event)
    {
        $user = $event->getData();

        if (!$this->userRepository->isUnique($user)) {
            // In the future, Emails and Username should be checked separately.
            $event->getForm()->addError(new FormError('The Username or Email already exists.'));
        }
    }
}
