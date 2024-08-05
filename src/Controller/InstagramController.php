<?php

namespace App\Controller;

use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\InstagramService;
use App\Form\InstagramSettingsFormType;
use App\Entity\InstagramSettings;
use App\Repository\InstagramSettingsRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class InstagramController extends AbstractController
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    #[Route('/instagram', name: 'app_instagram')]
    public function index(InstagramService $instagramService, InstagramSettingsRepository $instagramSettingsRepository): Response
    {
        $instagramSettings = $instagramSettingsRepository->findOneBy(['id' => 1]);

        if (!$instagramSettings) {
            $instagramAccountId = null;
            $instagramAccessToken = null;
        } else {
            $instagramAccountId = $instagramSettings->getAccountId();
            $instagramAccessToken = $instagramSettings->getAccessToken();
        }

        try {
            $stories = $instagramService->getUserStories($instagramAccountId, $instagramAccessToken);
        } catch (Exception $e) {
            // Si il y a une erreur au niveau de l'account ID ou de l'access token, on renvoit aucune story pour éviter les erreurs Symfony au niveau de l'utilisateur
            $stories = [];
        }

        foreach ($stories as &$story) {
            $story['media_info'] = $instagramService->getMediaInfo($story, $instagramAccessToken);
        }

        return $this->render('instagram/index.html.twig', [
            'stories' => $stories,
        ]);
    }

    #[Route('/instagram/settings', name: 'app_instagram_settings')]
    #[IsGranted('ROLE_ADMIN')]
    public function settings(Request $request, InstagramSettingsRepository $instagramSettingsRepository): Response
    {
        $instagramSettings = $instagramSettingsRepository->findOneBy([]) ?? new InstagramSettings();
        $form = $this->createForm(InstagramSettingsFormType::class, $instagramSettings);

        $form->handleRequest($request);

        $instagramCurrentAccountId = $instagramSettings->getAccountId();
        $instagramCurrentAccessToken = $instagramSettings->getAccessToken();

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($instagramSettings);
            $this->entityManager->flush();

            return $this->render('instagram/settings.html.twig', [
                'success' => true,
                'instagramSettings' => $instagramSettings,
                'form' => $form->createView(),
                'instagramCurrentAccountId' => $instagramCurrentAccountId,
                'instagramCurrentAccessToken' => $instagramCurrentAccessToken
            ]);
        }

        return $this->render('instagram/settings.html.twig', [
            'instagramSettings' => $instagramSettings,
            'form' => $form->createView(),
            'instagramCurrentAccountId' => $instagramCurrentAccountId,
            'instagramCurrentAccessToken' => $instagramCurrentAccessToken
        ]);
    }
}
