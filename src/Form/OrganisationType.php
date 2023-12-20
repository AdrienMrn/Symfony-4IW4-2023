<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Organisation;
use App\Repository\CategoryRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrganisationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom de l\'association',
                'attr' => [
                    'placeholder' => 'Nom de l\'association',
                ],
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description de l\'association',
            ])
            ->add('createdAt', DateTimeType::class, [
                'label' => 'Date de création de l\'association',
                'widget' => 'single_text',
                'input' => 'datetime_immutable',
            ])
            ->add('category', EntityType::class, [
                'label' => 'Test 2',
                'class' => Category::class,
                'choice_label' => 'name',
                'query_builder' => function (CategoryRepository $categoryRepository) {
                    return $categoryRepository->createQueryBuilder('c')
                        ->orderBy('c.name', 'ASC')
                        ->setMaxResults(5);
                },
            ])

            /*
            ->add('test', ChoiceType::class, [
                'label' => 'Test',
                'multiple' => true,
                'expanded' => true,
                'choices' => [
                    'Oui' => true,
                    'Non' => false,
                ],
                'mapped' => false,
            ])

            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'Les mots de passe doivent correspondre.',
                'options' => ['attr' => ['class' => 'password-field']],
                'first_options'  => ['label' => 'Mot de passe'],
                'second_options' => ['label' => 'Répéter le mot de passe'],
                'mapped' => false,
            ])*/
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Organisation::class,
        ]);
    }
}
