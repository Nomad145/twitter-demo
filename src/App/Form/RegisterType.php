<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;
use Symfony\Component\Form\FormBuilderInterface;
use App\Entity\User;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use App\Repository\UserRepository;
use App\Form\Subscriber\PasswordEncodeSubscriber;
use App\Form\Subscriber\UniqueUserSubscriber;

/**
 * Class RegisterType
 *
 * Registration form for a new User.
 *
 * @author Michael Phillips <michaeljoelphillips@gmail.com>
 */
class RegisterType extends AbstractType
{
    /** @var PasswordEncoderInterface */
    protected $encoder;

    /** @var UserRepository */
    protected $userRepository;

    /**
     * @param PasswordEncoderInterface $encoder
     * @param UserRepository $userRepository
     */
    public function __construct(
        PasswordEncoderInterface $encoder,
        UserRepository $userRepository
    ) {
        $this->encoder = $encoder;
        $this->userRepository = $userRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName', TextType::class)
            ->add('lastName', TextType::class)
            ->add('email', EmailType::class)
            ->add('handle', TextType::class, [
                'constraints' => [
                    // Limit the username to 15 characters.
                    new Length(['max' => 15]),
                ],
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'first_options'  => ['label' => 'Password'],
                'second_options' => ['label' => 'Repeat Password'],
            ])
            ->add('submit', SubmitType::class);

        // Attach subscribers for encoding the password and checking for
        // uniqueness.  Post submit, the model data is a gaurunteed unique
        // user with an already encoded password.
        $builder->addEventSubscriber(new PasswordEncodeSubscriber($this->encoder));
        $builder->addEventSubscriber(new UniqueUserSubscriber($this->userRepository));
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
