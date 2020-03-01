<?php

namespace App\Controller;

use App\Entity\Article;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
     */
    public function index()
    {
        $repo = $this->getDoctrine()
                     ->getRepository(Article::class);

        /*
        $article = $repo->find(12);
        $article = $repo->findBy(12);
        $article = $repo->findOneByTitle('Titre de l\'article');
        $articles = $repo->findByTitle('Titre de l\'article');
        */
        $articles = $repo->findAll();
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
}
