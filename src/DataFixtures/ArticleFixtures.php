<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Article;
use App\Entity\Category;
use App\Entity\Comment;

class ArticleFixtures extends Fixture{

    public function load(ObjectManager $manager){
        $faker = \Faker\Factory::create("fr_FR");

        // Creer 3 categories fakées
        for($i=1; $i<=3; $i++){
            
            $categorie = new Category();
            $categorie->setTitle($faker->words($nb = 5, $asText = true)   )
                        ->setDescription($faker->paragraphs($nb = 2, $asText = true) );

            $manager->persist($categorie);

            $content = "<p>" . join($faker->paragraphs($nb = 3, $asText = false)) . "</p>";
        
            // creer entre 4 et 6 articles
            for($j=1 ; $j <= mt_rand(4, 6); $j++){
                $article = new Article();
                $article->setTitle($faker->paragraphs($nb = 4, $asText = false))
                        ->setContent($content)
                        ->setImage($faker->imageUrl($width = 640, $height = 480))
                        ->setCreatedAt($faker->dateTimeBetween("- 6 months"))
                        ->setCategory($categorie);
                $manager->persist($article);

                // creer entre 1 et 10 commentaires
                for($k=1 ; $k <= mt_rand(1, 10); $k++){
                    $comment = new Comment();
                    $content = "<p>" . join($faker->paragraphs($nb = 3, $asText = false)) . "</p>";

                    $now = new \DateTime();
                    $interval = $now->diff($article->getCreatedAt());
                    $interval = $interval->days;
                    $minimum = "- " . $interval . " days"; // exemple -100 days
                    /* ce qui peut s'ecrire aussi
                    $days = (new \DateTime())->diff($article->getCreatedAt())->days
                     et alors on peut remplacer $minimum ci-dessous par $days */

                    $comment->setAuthor($faker->name)
                            ->setContent($content)
                            ->setCreatedAt($faker->dateTimeBetween("$minimum"))
                            ->setArticle($article);
                    $manager->persist($comment);

                }
            }
            $manager->flush();
        }   
    }

    /*
    
    public function load(ObjectManager $manager)
    {
        for($i=1 ; $i <= 10; $i++){
            $article = new Article();
            $article->setTitle("Titre de l'article N°$i")
                    ->setContent("<p>Contenu de l'article N°$i</p>")
                    ->setImage("http://placehold.it/350x150")
                    ->setCreatedAt(new \DateTime());
            $manager->persist($article);        
        }

    }
    */
}
