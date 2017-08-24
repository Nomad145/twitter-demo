<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Silex\Application;
use App\Form\TweetType;
use App\Entity\Tweet;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Form\FormFactoryInterface;
use App\Repository\TweetRepository;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use App\Controller\AbstractController;
use App\Concerns\FlashBagHelper;

/**
 * Class TwitterController
 * @author Michael Phillips <michaeljoelphillips@gmail.com>
 */
class TwitterController
{
    use FlashBagHelper;

    /** @ar EntityManagerInterface */
    protected $em;

    /** @var Session */
    protected $session;

    /** @var TokenStorageInterface */
    protected $tokenStorage;

    /** @var FormFactoryInterface */
    protected $formFactory;

    /** @var Twig_Environment */
    protected $twig;

    /**
     * @param EntityManagerInterface $em
     * @param Session $session
     * @param TokenStorageInterface $tokenStorage
     * @param FormFactoryInterface $formFactory
     * @param \Twig_Environment $twig
     */
    public function __construct(
        EntityManagerInterface $em,
        SessionInterface $session,
        TokenStorageInterface $tokenStorage,
        FormFactoryInterface $formFactory,
        \Twig_Environment $twig
    ) {
        $this->em = $em;
        $this->session = $session;
        $this->tokenStorage = $tokenStorage;
        $this->formFactory = $formFactory;
        $this->twig = $twig;
    }

    /**
     * The default view for an authenticated user.  All the user's Tweets are
     * displayed on the page.
     *
     * @return Response
     */
    public function homeAction()
    {
        // Create the Tweet form, setting the action to self::tweetAction.
        $form = $this->formFactory->create(TweetType::class, new Tweet(), [
            // Bind the route in the future.
            'action' => '/tweet',
        ]);

        // Fetch all Tweets by the current User.
        $tweets = $this->em
            ->getRepository(Tweet::class)
            ->findByUser(
                $this->tokenStorage->getToken()->getUser()
            );

        return $this->twig->render('app/home.html.twig', [
            'form' => $form->createView(),
            'tweets' => $tweets,
        ]);
    }

    /**
     * The route accepting the TweetType form data.  This route allows for
     * submitting Tweets anywhere within the application via AJAX.
     *
     * @param Request $request
     * @return Response
     */
    public function tweetAction(Request $request)
    {
        $form = $this->formFactory->create(TweetType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $tweet = $form->getData();

            // Set the User on the Tweet from the Token.
            $tweet->setUser($this->tokenStorage->getToken()->getUser());

            // Persist and Flush the Tweet.
            $this->em->persist($tweet);
            $this->em->flush();

            // Display a success message.  In the future, if this were an AJAX
            // request we might return a partial HTML response with the Tweet.
            $this->addFlash('info', 'Your Tweet was posted!');
        } else {
            // Display the failure message.  There needs to be some mechanism
            // in the session for persisting form data between route changes.
            $this->addFlash('error', (string) $form->getErrors(true));
        }

        // Redirect back to the Home route.
        return new RedirectResponse('/');
    }
}
