<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;

class RegisterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('lastname', TextType::class,[
                'label'=> 'Votre nom',
                'constraints'=> new Length([
                    'min'=>2,
                    'max'=>30
                ]),
                'attr' =>[
                    'placeholder' => 'Merci de saisir un nom'
                ]
            ])
            ->add('firstname',TextType::class,[
                'label'=> 'Votre prenom',
                'constraints'=> new Length([
                    'min'=>2,
                    'max'=>30
                ]),
                'attr' =>[
                    'placeholder' => 'Merci de saisir un prenom'
                ]
            ])
            ->add('email', EmailType::class, [
                'label'=> 'Votre email',
                'constraints'=> new Length([
                    'min'=>2,
                    'max'=>30
                ]),
                'attr' =>[
                    'placeholder' => 'Merci de saisir un email'
                ]
            ])
            ->add('password', RepeatedType::class, [
                'type'=> PasswordType::class,
                'invalid_message' => 'le mot de passe et la confirmation doivent être identiquent',
                'label' => 'Votre mot de passe',
                'required'=>true,
                'first_options' =>
                    ['label'=>'Mot de passe',
                        'attr'=>[
                            'placeholder'=> 'Merci de saisir votre mot de passe']

                    ],
                'second_options' =>
                    ['label'=> 'Confimer votre mot de passe',
                        'attr'=>[
                            'placeholder'=> 'Merci de confirmer votre mot de passe']
                    ],
            ])

            ->add('birthday', BirthdayType::class, [
                'label'=> "Votre date de naissance",
                'placeholder' => [
                    'day' => 'jour','year' => 'année', 'month' => 'mois',
                    ]
            ])

            ->add('phone',TelType::class, [
                'label'=>'Votre telephone',
                'attr'=>[
                    'placeholder'=>'Entrer votre téléphone'
                ]
            ] )
            ->add('image',FileType::class,[
                'label'=>"Votre image de profil",
                'required'=>false
            ])

            ->add('address',TextType::class, [
                'label'=>'Votre adresse',
                'attr'=>[
                    'placeholder'=>'8 rue des lilas...'
                ]
            ] )
            ->add('zipcode',TextType::class, [
                'label'=>'Votre code postal',
                'attr'=>[
                    'placeholder'=>'Entre votre code postal'
                ]
            ] )
            ->add('city',TextType::class, [
                'label'=>'Ville',
                'attr'=>[
                    'placeholder'=>'Entrer votre ville '
                ]
            ] )
            ->add('country',CountryType::class, [
                'label'=>'Pays',
                'attr'=>[
                    'placeholder'=>'Entrer votre pays'
                ]
            ] )
            ->add('submit', SubmitType::class,[
                'label' => "S'inscrire",
                'attr'=>[
                    'class'=>'btn btn-info btn-block'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
