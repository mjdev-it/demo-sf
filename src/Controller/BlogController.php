<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Common\Persistence\ObjectManager;


use App\Entity\Article;
use App\Repository\ArticleRepository;
use App\Form\ArticleType;

class BlogController extends AbstractController
{
    /**
     * @Route("/blog", name="blog")
     */
    public function index(): Response
    {
        $repo = $this->getDoctrine()->getRepository(Article::Class);
        $article = $repo->findAll();
        
        return $this->render('blog/index.html.twig', [
            'controller_name' => 'BlogController',
        ]);
    }

        
   /**
    * @Route("/", name="home")
    */
    public function home(): Response
    {
        return $this->render('blog/home.html.twig');
    }


    // cette fonction s'appelait create au depart
    // puis a servi à créer et mettre à jour 
    // d'ou elle est devenu la fonction form
    /**
    * @Route("/blog/new", name="blog_create")
    * @Route("/blog/{id}/edit", name="blog_edit")
    */
    //public function form(Article $article = null, Request $request, ObjectManager $manager){
        public function form(Article $article = nullr){

        if (!$article){
            $article = new Article();
        }


        // si form créé avec php bin/console make:form
        // alors donner un nom qui se termine par Type (convention pour formulaire)
        // en CamelCase 1ere lettre en Majuscule ici on choisit ArticleType
        // puis on nous demande si on veut la rattacher à une entité
        // ici on dit à Article
        // d'ou ci-dessous en commentaires remplacé par juste en dessous
        //$form = $this->createFormBuiler($article)
        //            ->add('title')
        //           ->add('content')
        //            ->add('image')
        //            ->getForm();
        // et ne pas oublier de rajouter en haut 
        $form = $this->createForm(ArticleType::Class, $article);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            if (!$article->getId()){
                $article->setCreatedAt(new \DateTime());
            }

            $manager->persit();
            
            $manager->flush();


            return $this->redirectToRoute('blog_show', [
                            'id' => $article->getId(),
                            'editMode' => $article->getId() !== null
                        ]);

        }
    }

    /**
    * @Route("/blog/{$id}", name="blog_show")
    */
    public function show($id): Response
    {
        $repo = $this->getDoctrine()->getRepository(Article::Class);
        $article = $repo->find($id);
        return $this->render('blog/show.html.twig',[
            'article' => $article
        ]);
    }

}
