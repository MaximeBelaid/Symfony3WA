<?php

use Doctrine\Common\Persistence\ObjectManager;
use Troiswa\BackBundle\Entity\Categorie;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;

class LoadCategorieData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
    * {@inheritDoc}
    */
    public function load(ObjectManager $manager)
    {
        //die("Categorie");
        /*
        $categorie = new Categorie();
        $categorie->setTitle('CAT-2');
        $categorie->setDescription('lorem ipsum');
        $categorie->setPosition('3');
        $categorie->setActive('true');

        $manager->persist($categorie);
        $manager->flush();
        $this->addReference("categ", $categorie);
        */


        $faker = \Faker\Factory::create('fr_FR');
        //die('ok');
        //die(dump(json_decode($allImg)));

        for ($i = 0; $i < 10; $i++) {
            $category = new Categorie();
            $category->setTitle($faker->text(20));
            $category->setDescription($faker->text(20));
            $category->setPosition($faker->randomDigitNotNull);
            $category->setActive($faker->numberBetween(0, 1));

            $image = $this->getReference("img_".$i);
            $category->setImageFaker($image);

            $manager->persist($category);
            $manager->flush();

            // J'envoie la catégorie
            $this->addReference('categorie_'.$i, $category);
        }

    }

    public function getOrder()
    {
        return 2;
    }
}