<?php

namespace Form;

use Symfony\Component\Form\Test\TypeTestCase;
use Symfony\Component\Form\Extension\Validator\ValidatorExtension;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Form\Form;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\ConstraintViolationList;
use App\Form\TweetType;
use App\Entity\Tweet;

/**
 * Class TweetTypeTest
 * @author Michael Phillips <michaeljoelphillips@gmail.com>
 */
class TweetTypeTest extends TypeTestCase
{
    protected $validator;

    protected function setUp()
    {
        $this->validator = $this->createMock(ValidatorInterface::class);

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
        return [
            new ValidatorExtension($this->validator),
        ];
    }

    public function testSubmitValidData()
    {
        $formData = [
            'message' => 'Hello World!',
        ];

        $form = $this->factory->create(TweetType::class);

        $object = (new Tweet())
            ->setMessage('Hello World!');

        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());
        $this->assertEquals($object->getMessage(), $form->getData()->getMessage());

        $view = $form->createView();
        $children = $view->children;

        foreach (array_keys($formData) as $key) {
            $this->assertArrayHasKey($key, $children);
        }
    }
}
