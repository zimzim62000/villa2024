<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GuadeloupeController extends AbstractController
{
    #[Route('/guadeloupe', name: 'app_guadeloupe')]
    public function index(): Response
    {
        return $this->render('guadeloupe/index.html.twig', [
            'controller_name' => 'GuadeloupeController',
        ]);
    }
}
