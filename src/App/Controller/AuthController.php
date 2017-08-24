<?php

namespace App\Controller;

use Symfony\Component\Form\FormFactoryInterface;
use App\Repository\UserRepository;
use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use App\Form\RegisterType;
use Doctrine\ORM\EntityManagerInterface;
use App\Controller\AbstractController;
use App\Concerns\FlashBagHelper;

/**
 * Class AuthController
 * @author Michael Phillips <michaeljoelphillips@gmail.com>
 */
class AuthController
{
    use FlashBagHelper;

    /** @var EntityManagerInterface */
    protected $em;

    /** @var SessionInterface */
    protected $session;

    /** @var FormFactoryInterface */
    protected $formFactory;

    /** @var \Twig_Environment */
    protected $twig;

    /** @var PasswordEncoderInterface */
    protected $encoder;

    /**
     * A closure provided by Silex to fetch the last login error.
     *
     * @var \Closure
     */
    protected $lastError;

    /**
     * @param EntityManagerInterface $em
     * @param Session $session
     * @param FormFactoryInterface $formFactory
     * @param \Twig_Environment $twig
     * @param PasswordEncoderInterface $encoder
     * @param \Closure $lastError
     */
    public function __construct(
        EntityManagerInterface $em,
        SessionInterface $session,
        FormFactoryInterface $formFactory,
        \Twig_Environment $twig,
        PasswordEncoderInterface $encoder,
        \Closure $lastError
    ) {
        $this->em = $em;
        $this->session = $session;
        $this->formFactory = $formFactory;
        $this->twig = $twig;
        $this->encoder = $encoder;
        $this->lastError = $lastError;
    }

    /**
     * registerAction
     *
     * Registers a user.  The Email address and Username must be unique for the
     * form to be valid.
     *
     * @param Request $request
     * @return Response
     */
    public function registerAction(Request $request)
    {
        // Create the Register form and handle the request.
        $form = $this->formFactory->create(RegisterType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Persist the new user.
            $this->em->persist($form->getData());
            $this->em->flush();

            // Notify the user of the successful registration.
            $this->addFlash('info', 'Your account was created.');

            // Redirect to the Login page.  In the future, this would be a
            // better UX if they were already logged in and redirected 'Home'.
            return new RedirectResponse('/login');
        }

        return $this->twig->render('app/register.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * loginAction
     *
     * Authenticates a user with the Symfony Security Component.
     *
     * @param Request
     * @return Response
     */
    public function loginAction(Request $request)
    {
        // Allow the Security Component to handle Authentication.
        return $this->twig->render('app/login.html.twig', [
            // Execute the closure saved on the class.
            'error' => call_user_func($this->lastError, $request),
            'last_username' => $this->session->get('_security.last_username'),
        ]);
    }
}
