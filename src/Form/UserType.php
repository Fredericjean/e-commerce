<?php
// src/Form/UserType.php
namespace App\Form;

use App\Entity\User;
use App\Entity\Adress;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'attr' => [
                    'placeholder' => 'admin@test.com'
                ],
                'required' => true,
            ])
            ->add(
                'password',
                RepeatedType::class,
                [
                    'type' => PasswordType::class,
                    'invalid_message' => 'Les mots de passe doivent être identiques',
                    'first_options' => [
                        'label' => "Mot de passe",
                        'attr' => [
                            'placeholder' => 'Test12345',
                        ],
                        'constraints' => [
                            new NotBlank(),
                            new Length([
                                'max' => 4000,
                            ]),
                            new Regex([
                                'pattern' => "/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/",
                                'message' => "Le mot de passe doit contenir au moins une lettre minuscule, une lettre majuscule, un chiffre et un caractère spécial, et doit être d’au moins 8 caractères de long."
                            ]),
                        ],
                    ],
                    'second_options' => [
                        'label' => 'Confirmer le mot de passe',
                        'attr' => [
                            'placeholder' => 'Test12345',
                        ],
                    ],
                    'mapped' => false,
                    'required' => true,
                ]
            );

        if ($options['isAdmin']) {
            $builder->remove('password')
                ->add('roles', ChoiceType::class, [
                    'label' => 'Rôles',
                    'choices' => [
                        'Utilisateur' => 'ROLE_USER',
                        'Admin' => 'ROLE_ADMIN',
                    ],
                    'expanded' => true,
                    'multiple' => true,
                ]);
        }

        if($options['isEdit']) {
            $builder
                ->add('adresses', EntityType::class, [
                    'class' => Adress::class,
                    'choice_label' => 'adress',
                    'multiple' => true,
                ])
                ->add('first_name', TextType::class, [
                    'label' => 'Prénom'
                ])
                ->add('last_name', TextType::class, [
                    'label' => 'Nom'
                ])
                ->add('telephone', TextType::class, [
                    'label' => 'Téléphone'
                ])
                ->add('birth_date', null, [
                    'label' => 'Date de naissance',
                    'widget' => 'single_text',
                ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'isAdmin' => false,
            'isEdit' => false,
            'user' => null,
            'sanitize_html' => true,
        ]);
    }
}
