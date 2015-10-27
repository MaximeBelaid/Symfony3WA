<?php
namespace Troiswa\BackBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Troiswa\BackBundle\Entity\User;

class UserCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('user:create')
            ->setDescription('permet de créer un user')
            ->addArgument('login', InputArgument::REQUIRED, 'veuillez entrer votre login')
            ->addArgument('mdp', InputArgument::REQUIRED, 'veuillez entrer votre mdp');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // Récupération de doctrine
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');

        // Récupération d'un argument
        $login = $input->getArgument('login');
        $mdp = $input->getArgument('mdp');

        $faker = \Faker\Factory::create('fr_FR');

        $user = new User();

        $factory = $this->getContainer()->get('security.encoder_factory');
        $encoder = $factory->getEncoder($user); // Je récupère l'encoder de la class Troiswa\BackBundle\Entity\User
        $newPassword = $encoder->encodePassword($mdp, $user->getSalt());

        $user->setFirstname($faker->firstName);
        $user->setLastname($faker->lastName);
        $user->setEmail($faker->email);
        $user->setGender($faker->randomFloat(0,0,1));
        $user->setAddress($faker->country);
        $user->setPhone($faker->phoneNumber);

        $user->setLogin($login);
        $user->setPassword($newPassword);

        $em->persist($user);
        $em->flush();

        $output->writeln('L\'utilisateur '.$login.' a bien été enregistré');
    }
}