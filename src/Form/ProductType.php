<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Product;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

            ->add('name', TextType::class, [
                'label'=>"Nom du produit"
            ])
            ->add('image',FileType::class, array('data_class' => null),[
                'label'=>"Image produit",
                'required'=>false,
            ])
            ->add('categorie', EntityType::class,[
                'label'=>"Choisir la catégorie",
                'class'=>Category::class,
            ])
            ->add('user', EntityType::class,[
                'label'=>"Utilisateur",
                'class'=>User::class,
            ])

            ->add('time', TimeType::class, [
               'label'=>"Durée (si prestation)",
               'required'=>false,

            ])
            ->add('price',MoneyType::class, [
                'label'=>"Prix",
                'required'=>false,
                'attr'=>[
                ]
            ])
            ->add('stock',IntegerType::class, [
                'label'=>"Stock",
                'required'=>false,
                'attr'=>[
                ]
            ])
            ->add('description', TextareaType::class, [
                'label'=>"Descriptif du produit",
                'attr'=>[

                ]
            ])
            ->add('submit', SubmitType::class, [
                'label'=>'Enregistrer / modifier le produit ',
                'attr'=>[
                    'class'=>'btn btn-block btn-outline-secondary'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
