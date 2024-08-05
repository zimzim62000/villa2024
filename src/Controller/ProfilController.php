<?php

namespace App\Controller;

use App\Entity\Contact;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

#[IsGranted('ROLE_USER')]
class ProfilController extends AbstractController
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    #[Route('/profile', name: 'app_profile')]
    public function index(Request $request)
    {
        $contactsPerPage = 5; // You can change this to your preferred number of contacts per page
        $page = $request->query->getInt('page', 1);

        $offset = ($page - 1) * $contactsPerPage;
        $contacts = $this->entityManager->getRepository(Contact::class)->findBy([], ['createdAt' => 'DESC'], $contactsPerPage, $offset);

        foreach ($contacts as $contact) {
            $createdAt = $contact->getCreatedAt();
            $createdAt->modify('+2 hour');
            $contact->setCreatedAt($createdAt);
        }

        $totalContacts = $this->entityManager->getRepository(Contact::class)->count([]);

        return $this->render('profil/index.html.twig', [
            'contacts' => $contacts,
            'currentPage' => $page,
            'totalPages' => ceil($totalContacts / $contactsPerPage),
        ]);
    }

}
