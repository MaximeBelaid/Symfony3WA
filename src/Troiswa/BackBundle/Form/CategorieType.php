<?php

namespace Troiswa\BackBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CategorieType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('dateCreated',"date")
            ->add('description')
            ->add('position')
            ->add('active')
            ->add('image',new ImageType())
        ;

        $builder->addEventListener(FormEvents::PRE_SET_DATA, [$this, 'editCategorie']);
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Troiswa\BackBundle\Entity\Categorie'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'troiswa_backbundle_categorie';
    }

    public function editCategorie(FormEvent $event)
    {
        //die('ok');
        $categorie = $event->getData(); // objet categorie
        $form = $event->getForm(); // le formulaire


        //die(dump($user, $form));
        // Si j'ai une categorie et que l'id de la categorie  existe = je suis entrain de faire une modification
        if ($categorie && $categorie->getId())
        {
            $form->remove('position');
        }

    }
}
