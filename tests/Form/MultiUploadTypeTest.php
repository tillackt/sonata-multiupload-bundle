<?php

namespace SilasJoisten\Sonata\MultiUploadBundle\Tests\Form;

use SilasJoisten\Sonata\MultiUploadBundle\Form\MultiUploadType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\PreloadedExtension;
use Symfony\Component\Form\Test\TypeTestCase;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MultiUploadTypeTest extends TypeTestCase
{
    public function testBuildForm()
    {
        $formBuilder = $this->createMock(FormBuilderInterface::class);

        $formBuilder->expects($this->exactly(3))
            ->method('add')
            ->willReturnCallback(function ($name, $type, $options) use ($formBuilder) {
                static $calls = 0;
                ++$calls;

                switch ($calls) {
                    case 1:
                        $this->assertEquals('context', $name);
                        $this->assertEquals(HiddenType::class, $type);
                        $this->assertEquals(['data' => 'default'], $options);
                        break;
                    case 2:
                        $this->assertEquals('providerName', $name);
                        $this->assertEquals(HiddenType::class, $type);
                        $this->assertEquals(['data' => 'sonata.media.provider.image'], $options);
                        break;
                    case 3:
                        $this->assertEquals('binaryContent', $name);
                        $this->assertEquals(FileType::class, $type);
                        $this->assertEquals(['attr' => ['multiple' => true]], $options);
                        break;
                }

                return $formBuilder;
            });

        $form = new MultiUploadType();
        $form->buildForm($formBuilder, ['provider' => 'sonata.media.provider.image']);
    }

    public function testConfigureOptions()
    {
        $optionResolver = $this->createMock(OptionsResolver::class);
        $optionResolver->expects($this->once())
            ->method('setDefaults')
            ->with([
                'data_class' => '',
                'provider' => '',
                'context' => 'default',
            ]);

        $form = new MultiUploadType();
        $form->configureOptions($optionResolver);
    }

    protected function getExtensions()
    {
        $childType = new MultiUploadType();

        return [new PreloadedExtension([
            $childType->getName() => $childType,
        ], [])];
    }
}
