<?php


namespace App\Notification;


use App\Entity\Contact;
use Twig\Environment;

class ContactNotification
{
    /**
     * @var \Swift_Mailer
     */
    private $mailer;
    /**
     * @var Environment
     */
    private $renderer;

    public function __construct(\Swift_Mailer $mailer, Environment $renderer){

        $this->mailer = $mailer;
        $this->renderer = $renderer;
    }

    public function notify(Contact $contact){
        $message = (new \Swift_Message('Test :' . $contact->getMessage()))
            ->setFrom($contact->getEmail())
            ->setTo('contact@contact.fr')
            ->setReplyTo($contact->getEmail())
            ->setBody($this->renderer->render('email/contact.html.twig', [
                'contact'=> $contact
            ]), 'text/html');
        $this->mailer->send($message);
    }

    public function sendResponse()
    {
        $message = (new \Swift_Message('Mail de confirmation'))
            ->setFrom('test@resa.com')
            ->setTo('contact@contact.fr')
            ->setBody($this->renderer->render('email/validateEmail.html.twig', [
            ]), 'text/html');
        $this->mailer->send($message);
    }

    public function sendResponseOrder()
    {
        $message = (new \Swift_Message('Mail de commande  '))
            ->setFrom('test@resa.com')
            ->setTo('contact@contact.fr')
            ->setBody($this->renderer->render('email/validateEmail.html.twig', [
            ]), 'text/html');
        $this->mailer->send($message);
    }
}