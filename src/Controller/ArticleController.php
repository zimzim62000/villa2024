<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }
    #[Route('/article', name: 'app_article')]
    public function index(): Response
    {
        $articles = $this->entityManager->getRepository(Article::class)->findBy([], ['date'=>'DESC'], 3);

        return $this->render('article/index.html.twig', [
            'articles' => $articles,
            'controller_name' => 'ArticleController',
        ]);
    }

    #[Route('/article/new', name: 'app_article_new')]
    #[IsGranted('ROLE_ADMIN')]
    public function new(Request $request): Response
    {
        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $this->entityManager->persist($article);
            $this->entityManager->flush();

            return $this->redirectToRoute('app_article');
        }

        return $this->render('article/new.html.twig', [
            'article' => $article,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/article/{id}', name: 'app_article_show', methods: ['GET'])]
    public function show(Article $article): Response
    {
        return $this->render('article/show.html.twig', [
            'article' => $article,
        ]);
    }

    #[Route('/articleList', name: 'app_article_list')]
    public function articleList(): Response
    {
        $articles = $this->entityManager->getRepository(Article::class)->findBy([], ['date'=>'DESC']);

        return $this->render('article/list.html.twig', [
            'articles' => $articles,
            'controller_name' => 'ArticleController',
        ]);
    }

    #[Route('/article/{id}/edit', name: 'app_article_edit')]
    #[IsGranted('ROLE_ADMIN')]
    public function edit(Request $request, Article $article): Response
    {
        $form = $this->createForm(ArticleType::class, $article);
        $form->add('submit', SubmitType::class, [
            'label' => 'Update',
            'attr' => ['class' => 'btn btn-custom'],
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();

            return $this->redirectToRoute('app_article_show', ['id' => $article->getId()]);
        }

        return $this->render('article/edit.html.twig', [
            'article' => $article,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/article/{id}/delete', name: 'app_article_delete', methods: ['GET'])]
    #[IsGranted('ROLE_ADMIN')]
    public function delete(Request $request, Article $article): Response
    {
        $this->entityManager->remove($article);
        $this->entityManager->flush();

        return $this->redirectToRoute('app_article');
    }

    #[Route('/article/{id}/enabled', name: 'app_article_enable', methods: ['GET'])]
    #[IsGranted('ROLE_ADMIN')]
    public function enableDisable(Request $request, Article $article): Response
    {
        $article->setEnabled(!$article->isEnabled());
        $this->entityManager->flush();

        return $this->redirectToRoute('app_article');
    }
}
