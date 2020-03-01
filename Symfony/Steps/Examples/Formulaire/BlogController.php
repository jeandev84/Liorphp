<?php

namespace App\Controller;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * Class BlogController
 * @package App\Controller
 */
class BlogController extends AbstractController
{
    /**
     * @Route("/blog", name="blog")
     * @param ArticleRepository $articleRepository
     * @return Response
     */
    public function index(ArticleRepository $articleRepository)
    {
        /*
        $repo = $this->getDoctrine()
                     ->getRepository(Article::class);
        $article = $repo->find(12);
        $article = $repo->findBy(12);
        $article = $repo->findOneByTitle('Titre de l\'article');
        $articles = $repo->findByTitle('Titre de l\'article');
        */
        $articles = $articleRepository->findAll();

        return $this->render('blog/index.html.twig', [
            'controller_name' => 'BlogController',
            'articles' => $articles
        ]);
    }



    /**
     * @Route("/", name="home")
     * @return Response
     */
    public function home()
    {
        return $this->render('blog/home.html.twig', [
            'title' => "Bienvenue ici les amis!",
            'age' => 31
        ]);
    }


    /**
     * @Route("/blog/new", name="blog_create")
     * @Route("/blog/{id}/edit", name="blog_edit")
     * @param Article $article
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     * @throws \Exception
     */
    public function form(?Article $article, Request $request, EntityManagerInterface $manager)
    {
        if(! $article)
        {
            $article = new Article();
        }

        /* Creation d'un formulaire */
        $form = $this->createFormBuilder($article)
            ->add('title')
            ->add('content')
            ->add('image')
            ->getForm();

        /* Analyse de requette par le formulaire */
        $form->handleRequest($request);

        /* dump($article); */

        if($form->isSubmitted() && $form->isValid())
        {
            # Si l'article n'a pas d' identifiant
            if(! $article->getId())
            {
                # alors on met ajour la date de creation
                $article->setCreatedAt(new \DateTime());
            }

            $manager->persist($article);
            $manager->flush();

            return $this->redirectToRoute('blog_show', ['id' => $article->getId()]);
        }

        return $this->render('blog/create.html.twig', [
            'article_form' => $form->createView(),
            'editMode' => $article->getId() !== null
        ]);
    }


    /**
     * @Route("/blog/{id}", name="blog_show")
     * @param Article $article
     * @return Response
     */
    public function show(Article $article)
    {
        /*
        $repo = $this->getDoctrine()
                     ->getRepository(Article::class);
        $article = $repo->find(1);
        */

        return $this->render('blog/show.html.twig', compact('article'));
    }

    /*
    public function createPrimitiveMehtod(Request $request, EntityManagerInterface $manager)
    {
       dump($request);

    if($request->request->count() > 0)
    {
        $article = new Article();
        $article->setTitle($request->request->get('title'))
            ->setContent($request->request->get('content'))
            ->setImage($request->request->get('image'))
            ->setCreatedAt(new \DateTime());

        // $manager = $this->getDoctrine()->getManager();
        $manager->persist($article);
        $manager->flush();

        return $this->redirectToRoute('blog_show', ['id' => $article->getId()]);
    }

    return $this->render('blog/create.html.twig');
   }

    public function createForm(Request $request, EntityManagerInterface $manager)
    {
        $article = new Article();

        $form = $this->createFormBuilder($article)
                     ->add('title', TextType::class, [
                         'attr' => [
                             'placeholder' => "Titre de l'article",
                             'class' => 'form-control'
                         ]
                     ])
                     ->add('content', TextareaType::class, [
                         'attr' => [
                             'placeholder' => "Contenu de l'article",
                             'class' => 'form-control'
                         ]
                     ])
                     ->add('image', TextType::class, [
                         'attr' => [
                             'placeholder' => "Image de l'article",
                             'class' => 'form-control'
                         ]
                     ])
                     ->getForm();


        return $this->render('blog/create.html.twig', [
            'article_form' => $form->createView()
        ]);
    }


    public function createArticle(Request $request, EntityManagerInterface $manager)
    {
        $article = new Article();

        $form = $this->createFormBuilder($article)
                     ->add('title', TextType::class, [
                         'attr' => [
                             'placeholder' => "Titre de l'article"
                         ]
                     ])
                     ->add('content', TextareaType::class, [
                         'attr' => [
                             'placeholder' => "Contenu de l'article"
                         ]
                     ])
                     ->add('image', TextType::class, [
                         'attr' => [
                             'placeholder' => "Image de l'article"
                         ]
                     ])
                     /*
                     ->add('save', SubmitType::class, [
                        'label' => 'Enregistrer'
                     ])
                     *//*
                     ->getForm();

        // Creation d'un formulaire
        $form = $this->createFormBuilder($article)
            ->add('title')
            ->add('content')
            ->add('image')
            ->getForm();

        // Analyse de requette par le formulaire
        $form->handleRequest($request);

         dump($article);

//        if($form->isSubmitted() && $form->isValid())
//        {
//            $article->setCreatedAt(new \DateTime());
//
//            $manager->persist($article);
//            $manager->flush();
//
//            return $this->redirectToRoute('blog_show', ['id' => $article->getId()]);
//        }
//
//        return $this->render('blog/create.html.twig', [
//            'article_form' => $form->createView()
//        ]);
    }

*/
}
