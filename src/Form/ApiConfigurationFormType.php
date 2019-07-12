<?php

namespace App\Form;

use App\Entity\City;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Url;
use App\Repository\CityRepository;

class ApiConfigurationFormType extends AbstractType
{

    /**
     * @var RouterInterface
     */
    private $router;

    public function __construct(RouterInterface $router)
    {

        $this->router = $router;
    }
    /**
     * Create a form to setup an api.
     *
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
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
            ->add('city', EntityType::class, [
                'label' => 'City : ',
                'constraints' => [
                    new NotBlank(),
                ],
                'required' => false,
                'class' => City::class,
                'query_builder' => function (CityRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->orderBy('c.name', 'ASC');
                },
                'choice_label' => function(City $city) {
                    return sprintf('(%d) %s', $city->getZipcode(), $city->getName());
                },
                'choice_value' => 'id',
                'placeholder' => 'Choose a city',
                'invalid_message' => 'Choose a City!',
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
