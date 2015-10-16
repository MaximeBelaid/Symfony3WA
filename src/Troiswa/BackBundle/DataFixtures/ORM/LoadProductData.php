<?php

use Doctrine\Common\Persistence\ObjectManager;
use Troiswa\BackBundle\Entity\Product;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;

class LoadProductData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
    * {@inheritDoc}
    */
    public function load(ObjectManager $manager)
    {
        //die("Product");
        /*
        $product = new Product();
        $product->setTitle('PROD-2');
        $product->setDescription('lorem ipsum');
        $product->setPrice(100);
        $product->setQuantity(3);

        $categorie = $this->getReference("categ");
        $product->setCategorie($categorie);

        $manager->persist($product);
        $manager->flush();
        */

        $faker = \Faker\Factory::create('fr_FR');
        //die(dump($faker->randomElement($this->getReference("categ"))));
        for ($i = 0; $i < 10; $i++)
        {
            $product = new Product();
            $product->setTitle($faker->text(10));
            $product->setDescription($faker->text());
            $product->setQuantity($faker->randomDigitNotNull);
            $product->setPrice($faker->randomFloat);
            $product->setQuantity($faker->randomNumber);

            $category=$faker->randomElement($this->getReference("categ"));
            $product->setCategorie($category);

            $marque=$this->getReference("mar");
            $product->setMarque($marque);



            $manager->persist($product);
            $manager->flush();
        }

    }

    public function getOrder()
    {
        return 4;
    }
}

