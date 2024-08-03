<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
class ContactController extends AbstractController
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    #[Route('/contact', name: 'app_contact')]
    public function index(Request $request, MailerInterface $mailer): Response
    {
        $contact = new Contact();
        $form = $this->createForm(ContactType::class, $contact);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $contact->setCreatedAt(new \DateTime());

            $email = (new Email())
                ->from($contact->getEmail())
                ->to('zimzim62000@gmail.com')
                ->subject('New contact form submission')
                ->html(sprintf('<p>Message from %s:</p><p>%s</p>', $contact->getEmail(), $contact->getMessage()));

            $mailer->send($email);

            $this->entityManager->persist($contact);
            $this->entityManager->flush();

            return $this->render('contact/index.html.twig', array(
                'success' => true,
                'contact' => $contact,
                'form' => $form->createView()
            ));
        }

        return $this->render('contact/index.html.twig', [
            'contact' => $contact,
            'form' => $form->createView(),
        ]);
    }


}
