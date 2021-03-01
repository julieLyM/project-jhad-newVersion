<?php

namespace App\Controller;

use App\Form\ChangePasswordType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AccountPasswordController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager){
        $this->entityManager = $entityManager; /* $entitymanager permet d'aller chercher des informations dans notre base de données grace à l'ORM doctrine*/

    }


    /**
     * @Route("/compte/modifier-mot-de-passe", name="account_password", methods={"GET"})
     */

    public function index(Request $request, UserPasswordEncoderInterface $encoder)
    {
        $user = $this->getUser();
        $form= $this->createForm(ChangePasswordType::class, $user);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            /*Modifier le mot de passe*/
            /*Methode pour comparer le mot de passe actuel et le mot de passe en bdd => on utilise userPasswordEncoderInterface que je stocke dans $encoder*/

            $old_pwd = $form->get('old_password')->getData();

        if($encoder->isPasswordValid($user, $old_pwd)){#Si le password encoder est le meme que celui en BDD et celui que le user vient d'entrer
            #si oui
            #on récupère le nouveau mot de passe enregistrer(on le stocke dans une variable $new_pwd)
            $new_pwd = $form->get('new_password')->getData();

            #On encode le nouveau mot de passe(on le stocke dans une variable $password)
            $password = $encoder->encodePassword($user, $new_pwd);

            #on le set le password à l'utilisateur
            $user->setPassword($password);


            $this->entityManager->persist($user);
            $this->entityManager->flush();
            $this->addFlash('success', 'Votre mot de passe a bien été mis à jour ');

        }else{
            $this->addFlash('success', "Votre mot de passe mot de passe actuel n'est pas le bon");
        }

        }
        return $this->render('account/password.html.twig', [
            'form'=>$form->createView(),

        ]);
    }
}
