<?php

namespace App\DataFixtures;

use App\Entity\Article;
use App\Entity\Category;
use App\Entity\Comment;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;


/**
 * $ bin/console doctrine:fixtures:load
 *
 * Class ArticleFixtures
 * @package App\DataFixtures
*/
class ArticleFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');

        // Creer 3 categories fakees
        for ($i=1; $i <= 3; $i++)
        {
            $category = new Category();
            $category->setTitle($faker->sentence())
                     ->setDescription($faker->paragraph());

            $manager->persist($category);

            // Creer entre 4 et 6 article
            for($j = 1; $j <= mt_rand(4, 6); $j++)
            {
                $article = new Article();
                $content = '<p>'. join($faker->paragraphs(5), '</p><p>') .'</p>';

                $article->setTitle($faker->sentence())
                    ->setContent($content)
                    ->setImage($faker->imageUrl())
                    ->setCreatedAt($faker->dateTimeBetween('-6 months'))
                    ->setCategory($category);

                $manager->persist($article);

                # On donne des commentaires a l'article
                for ($k =1; $k <= mt_rand(4, 10); $k++)
                {
                    $comment = new Comment();
                    $content = '<p>'. join($faker->paragraphs(2), '</p><p>') .'</p>';

                    /*
                    # date actuel (maintenant)
                    $now = new \DateTime();

                    # difference entre la date de maintenant et la date de creation de l'article
                    $interval = $now->diff($article->getCreatedAt());
                    $days = $interval->days;
                    $minimum = '-'. $days . ' days'; // -100 days

                    $comment->setAuthor($faker->name)
                            ->setContent($content)
                            ->setCreatedAt($faker->dateTimeBetween($minimum));
                    */
                    $days = (new \DateTime())->diff($article->getCreatedAt())
                                                 ->days;

                    $comment->setAuthor($faker->name)
                        ->setContent($content)
                        ->setCreatedAt($faker->dateTimeBetween('-'. $days.' days'))
                        ->setArticle($article);

                    $manager->persist($comment);
                }
            }
        }

        $manager->flush();
    }
}

# bin/console doctrine:fixtures:load