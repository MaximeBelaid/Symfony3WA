<?php

use Doctrine\Common\Persistence\ObjectManager;
use Troiswa\BackBundle\Entity\Marque;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;

class LoadMarqueeData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
    * {@inheritDoc}
    */
    public function load(ObjectManager $manager)
    {


        $faker = \Faker\Factory::create('fr_FR');

        $marque = new Marque();
        $marque->setTitre($faker->text(20));

        $manager->persist($marque);
        $manager->flush();

        $this->addReference('mar', $marque);

    }

    public function getOrder()
    {
        return 3;
    }
}