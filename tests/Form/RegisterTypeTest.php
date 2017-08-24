<?php

namespace Form;

use Symfony\Component\Form\Test\TypeTestCase;
use App\Form\RegisterType;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;
use Symfony\Component\Form\PreloadedExtension;
use App\Repository\UserRepository;
use Symfony\Component\Form\Extension\Validator\ValidatorExtension;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Form\Form;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use App\Entity\User;
use Symfony\Component\Validator\ConstraintViolationList;

/**
 * Class RegisterTypeTest
 * @author Michael Phillips <michaeljoelphillips@gmail.com>
 */
class RegisterTypeTest extends TypeTestCase
{
    protected $accessor;

    protected $encoder;

    protected $repo;

    protected $validator;

    protected function setUp()
    {
        $this->accessor = PropertyAccess::createPropertyAccessor();
        $this->encoder = $this->createMock(PasswordEncoderInterface::class);
        $this->repo = $this->createMock(UserRepository::class);
        $this->validator = $this->createMock(ValidatorInterface::class);

        $this->encoder
            ->method('encodePassword')
            ->willReturn('encoded_password');

        $this->validator
            ->method('validate')
            ->willReturn(new ConstraintViolationList());

        $this->validator
            ->method('getMetadataFor')
            ->willReturn(new ClassMetadata(Form::class));

        parent::setUp();
    }

    protected function getExtensions()
    {
        $type = new RegisterType($this->encoder, $this->repo);

        return [
            new PreloadedExtension([$type], []),
            new ValidatorExtension($this->validator),
        ];
    }

    /**
     * @group unittest
     */
    public function testSubmitValidData()
    {
        $formData = [
            'firstName' => 'Test',
            'lastName' => 'User',
            'email' => 'new_user@twitter.com',
            'handle' => 'new_user',
            'password' => 'password',
        ];

        $form = $this->factory->create(RegisterType::class);

        $object = new User();

        // Fill the User object with the form data.
        foreach ($formData as $field => $value) {
            $this->accessor->setValue(
                $object,
                $field,
                $value
            );
        }

        // Set the encoded password on the mock.
        $object->setPassword('encoded_password');

        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());
        $this->assertEquals($object, $form->getData());

        $view = $form->createView();
        $children = $view->children;

        foreach (array_keys($formData) as $key) {
            $this->assertArrayHasKey($key, $children);
        }
    }
}
