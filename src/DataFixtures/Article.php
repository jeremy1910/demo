<?php

namespace App\DataFixtures;


use App\Entity\Category;
use App\Entity\ImageArticle;
use App\Entity\Tag;
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
		$category->setImagePath('php.png');
		$manager->persist($category);
		$manager->flush();

		$tags = [];
		$tag = new Tag();
		$tag->setTagName('Symfony');
		$tags[] = $tag;
		$manager->persist($tag);
		$manager->flush();


		$tag = new Tag();
		$tag->setTagName('PHP');
		$tags[] = $tag;
		$manager->persist($tag);
		$manager->flush();


		$tag = new Tag();
		$tag->setTagName('backend');
		$tags[] = $tag;
		$manager->persist($tag);
		$manager->flush();

		for($i=0; $i<20; $i++)
		{
			$article = new ART();
			$article->setTitle($faker->sentence(rand(4, 10), true));
			$article->setBody($faker->paragraph(rand(20, 80), true));
			$article->setDescription($faker->paragraph(2, true));
			$article->setCreatedAt(new \DateTime());
			$article->setNbView(rand(10, 350));
			$article->addTag($tags[rand(0, 2)]);
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
		$category->setImagePath('js.png');
		$manager->persist($category);
		$manager->flush();


		$tags = [];
		$tag = new Tag();
		$tag->setTagName('Jquery');
		$tags[] = $tag;
		$manager->persist($tag);
		$manager->flush();


		$tag = new Tag();
		$tag->setTagName('React');
		$tags[] = $tag;
		$manager->persist($tag);
		$manager->flush();


		$tag = new Tag();
		$tag->setTagName('ES6');
		$tags[] = $tag;
		$manager->persist($tag);
		$manager->flush();

		for($i=0; $i<20; $i++)
		{
			$article = new ART();
			$article->setTitle($faker->sentence(rand(4, 10), true));
			$article->setBody($faker->paragraph(rand(20, 80), true));
			$article->setDescription($faker->paragraph(2, true));
			$article->setCreatedAt(new \DateTime());
			$article->setNbView(rand(10, 350));
			$article->addTag($tags[rand(0, 2)]);
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
