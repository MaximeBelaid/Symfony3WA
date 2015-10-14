<?php
namespace Troiswa\BackBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class MyCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('mycommande:test')
            ->setDescription('permet de faire une commande de test')
        ->addArgument('prenom', InputArgument::OPTIONAL, 'veuillez entrer votre prénom')
        ->addOption('color', 'c', InputOption::VALUE_OPTIONAL, 'permet de mettre de la couleur');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // Récupération de doctrine
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');

        // Récupération d'un argument
        $prenom = $input->getArgument('prenom');
        if (!$prenom)
        {
            $prenom = 5;
        }

        $nbproducts = $em->getRepository("TroiswaBackBundle:Product")
            ->findProductNoQuantity($prenom);

        // Récupération d'une option
        $optionColor = $input->getOption('color');
        // code
        if ($optionColor) {
            $output->writeln('<info>Il y a <comment>' . $nbproducts . '</comment><info> produits qui ont une quantité <bg='.$optionColor.'>inférieure</> à </info><comment>' . $prenom.'</comment>');
        }
        else
        {
            $output->writeln('Il y a ' . $nbproducts . ' produits qui ont une qunatité inférieure à' . $prenom);
        }

        // Récupération du service mail
        //$this->getContainer()->get('mailer')
    }
}