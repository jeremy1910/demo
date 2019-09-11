<?php

namespace App\DataFixtures;


use App\Entity\Category;
use App\Entity\ImageArticle;
use App\Service\ImageProcessingHandler;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

use App\Entity\Article as ART;
use Faker;



require_once  '/home/jeje/lab/dev/symfony/demo/vendor/fzaninotto/faker/src/autoload.php';
//require_once 'C:\tmp\symfony\demo\vendor\fzaninotto\faker\src\autoload.php';


class Article extends Fixture
{


    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create();
        $category = new Category();
        $category->setLibele('PHP');
        $manager->persist($category);
        $manager->flush();




        for($i=0; $i<20; $i++)
        {
            $article = new ART();
            $article->setTitle($faker->sentence(rand(4, 10), true));
            $article->setBody($faker->paragraph(10, true));
            $article->setDescription($faker->paragraph(2, true));
            $article->setCreatedAt(new \DateTime());
            $image = new ImageArticle();
            $image->setFileName((string)rand(1,4).'.jpg');
            $article->setImage($image);


            $article->setNumCategory($category);
            $manager->persist($article);
            sleep(1);

        }

        $manager->flush();

        $category = new Category();
        $category->setLibele('Java Script');
        $manager->persist($category);
        $manager->flush();

        for($i=0; $i<20; $i++)
        {
            $article = new ART();
            $article->setTitle($faker->sentence(rand(4, 10), true));
            $article->setBody($faker->paragraph(10, true));
            $article->setDescription($faker->paragraph(2, true));
            $article->setCreatedAt(new \DateTime());

            $image = new ImageArticle();
            $image->setFileName((string)rand(1,4).'.jpg');
            $article->setImage($image);


            $article->setNumCategory($category);
            $manager->persist($article);
            sleep(1);

        }

        $manager->flush();
    }

}
