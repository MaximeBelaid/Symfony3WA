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
        $product = new Product();
        $product->setTitle('PROD-2');
        $product->setDescription('lorem ipsum');
        $product->setPrice(100);
        $product->setQuantity(3);

        $categorie = $this->getReference("categ");
        $product->setCategorie($categorie);

        $manager->persist($product);
        $manager->flush();
    }

    public function getOrder()
    {
        return 2;
    }
}

