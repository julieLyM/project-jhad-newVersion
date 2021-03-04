<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegisterType;
use App\Notification\ContactNotification;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegisterController extends AbstractController
{
    /**
     * @Route("/inscription", name="register", methods={"GET|POST"})
     */

        public function index(Request $request, UserPasswordEncoderInterface $encoder, ContactNotification $notification)
        {
            $user = new User();
            $user->setRoles(['ROLE_USER']);
            $user->setCreatedAt(new \DateTime());
            $form = $this->createForm(RegisterType::class, $user);
            $form->handleRequest($request);


            if ($form->isSubmitted() && $form->isValid()){

                $user = $form->getData();

                //On verifier que le mail utilisateur n'est pas déja utilisé
                $search_email = $this->getDoctrine()->getRepository(User::class)->findOneByEmail($user->getEmail());

                if(!$search_email){#si l'email n'existe pas déjà

                    $password = $encoder->encodePassword($user, $user->getPassword()) ;

                    $user->setPassword($password);


                    /** @var UploadedFile $image */
                    $image = $form->get('image')->getData();

                    if ($image) {
                        $originalFilename = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
                        $safeFilename = $originalFilename;
                        $newFilename = $safeFilename.'-'.uniqid('', true).'.'.$image->guessExtension();

                        try {
                            $image->move(
                                $this->getParameter('images_directory'),
                                $newFilename
                            );
                        } catch (FileException $e) {
                            $this->addFlash('danger','une erreur est survenue durant le chargement de votre page');
                        }

                        $user->setImage($newFilename);

                    }

                    $em =$this->getDoctrine()->getManager();
                    $em->persist($user);;/*fige la data car j'aurai besoin de l'enregister*/
                    $em->flush();/*tu enregistre la data que tu as figé*/


                    $notification->sendResponseRegister();



                    $this->addFlash('success',"Votre inscription s'est bien déroulée, vous pouvez dès à présent vous connecter à votre compte ");

                }else{

                    $this->addFlash('danger',"L'adresse email est déja utilisée");
                }


            }

            return $this->render('register/index.html.twig', [
                'form'=> $form->createView(),

            ]);
        }
}
