<?php

use Doctrine\Common\Persistence\ObjectManager;
use Troiswa\BackBundle\Entity\Image;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use \stdClass;

class LoadImageData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $allImg = [];
        $faker = \Faker\Factory::create('fr_FR');

        for ($i = 0; $i < 10; $i++) {
            $image = new Image();
            $image->setName("S7-modele--ferrari-laferrari.jpg");
            $image->setCaption($faker->text(20));

            $manager->persist($image);
            $manager->flush();

            array_push($allImg, $image);
        }
        $test = new \stdClass(json_encode($allImg));
        $this->addReference('img', $test);

    }

    public function getOrder()
    {
        return 1;
    }
}