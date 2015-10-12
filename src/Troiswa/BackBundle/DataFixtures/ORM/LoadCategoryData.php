<?php

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Troiswa\BackBundle\Entity\Categorie;

class LoadUserData implements FixtureInterface
{
    /**
    * {@inheritDoc}
    */
    public function load(ObjectManager $manager)
    {
        $categorie = new Categorie();
        $categorie->setTitle('hello');
        $categorie->setDescription('lorem ipsum');
        $categorie->setPosition('3');
        $categorie->setActive('true');

        $manager->persist($categorie);
        $manager->flush();
    }
}