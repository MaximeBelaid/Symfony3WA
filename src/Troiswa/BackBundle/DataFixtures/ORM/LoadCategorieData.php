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
        $categorie = new Categorie();
        $categorie->setTitle('CAT-2');
        $categorie->setDescription('lorem ipsum');
        $categorie->setPosition('3');
        $categorie->setActive('true');

        $manager->persist($categorie);
        $manager->flush();
        $this->addReference("categ", $categorie);
    }

    public function getOrder()
    {
        return 1;
    }
}