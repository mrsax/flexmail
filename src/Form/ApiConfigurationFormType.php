<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Url;

class ApiConfigurationFormType extends AbstractType
{
    /**
     * Create a form to setup an api.
     *
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('apiName', TextType::class, [
                'label' => 'Api Name : ',
                'required' => false,
                'constraints' => [
                    new NotBlank(),
                ],
                'empty_data' => 'api.darksky'
            ])
            ->add('apiMethod', ChoiceType::class, [
                'label' => 'Api Method : ',
                'constraints' => [
                    new NotBlank(),
                ],
                'required' => false,
                'choices'  => [
                    'GET' => 'get',
                    'POST' => 'post',
                    'PUSH' => 'push',
                    'DELETE' => 'delete',
                    'PATCH' => 'patch',
                ],
            ])
            ->add('apiUrl', TextType::class, [
                'required' => false,
                'label' => 'Api Base Url : ',
                'empty_data' => 'https://api.darksky.net/',
                'constraints' => [
                    new NotBlank(),
                    new Url(),
                ],
            ])
            ->add('endpoint', TextType::class, [
                'required' => false,
                'label' => 'Api Endpoint : ',
                'empty_data' => 'forecast',
                'constraints' => [
                    new NotBlank(),
                ]
            ])
            ->add('apiKey', TextType::class, [
                'label' => 'Api Secret Key : ',
                'constraints' => [
                    new NotBlank(),
                ],
                'required' => false,
            ])
            ->add('country', TextType::class, [
                'label' => 'Country  : ',
                'constraints' => [
                    new NotBlank(),
                ],
                'required' => false,
            ])
            ->add('city', TextType::class, [
                'label' => 'City by English name  : ',
                'constraints' => [
                    new NotBlank(),
                ],
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
