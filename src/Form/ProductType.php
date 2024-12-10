<?php

namespace App\Form;

use App\Entity\Brand;
use App\Entity\Model;
use App\Entity\Gender;
use App\Entity\Product;
use Doctrine\ORM\QueryBuilder;
use App\Repository\BrandRepository;
use App\Repository\ModelRepository;
use App\Repository\GenderRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom',
                'attr' => [
                    'placeholder' => 'Nom du produit',
                ],
                'required' => true,
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'attr' => [
                    'placeholder' => 'Description du produit',
                ],
                'required' => true,
            ])
            ->add('model', EntityType::class, [
                'class' => Model::class,
                'choice_label' => 'name',
                'expanded' => false,
                'multiple' => false,
                'query_builder' => function (ModelRepository $modelRepository): QueryBuilder {

                    return $modelRepository->createQueryBuilder('m')
                        ->where('m.enable = :enable')
                        ->setParameter('enable', true);
                }
            ])
            ->add('gender', EntityType::class,[
                'class'=>Gender::class,
                'choice_label'=>'name',
                'multiple'=>false,
                'query_builder'=>function (GenderRepository $genderRepository): QueryBuilder {

                return $genderRepository->createQueryBuilder('m')
                ->where('m.enable = :enable')
                ->setParameter('enable', true);
                }
            ])
            ->add('brand', EntityType::class, [
                'class'=>Brand::class,
                'choice_label'=>'name',
                'multiple'=>false,
                'query_builder'=>function (BrandRepository $brandRepository): QueryBuilder{
return $brandRepository->createQueryBuilder('m')
                    ->where('m.enable = :enable')
                    ->setParameter('enable', true);
                }
            ])
            ->add('authenticity', TextType::class, [
                'label' => 'Authenticité',
                'required' => false
            ])
            ->add('enable', CheckboxType::class, [
                'label' => 'Actif',
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
