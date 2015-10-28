<?php

namespace Troiswa\BackBundle\Form;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Troiswa\BackBundle\Form\Type\GenderType;
use Troiswa\BackBundle\Form\Type\TelType;
use Troiswa\BackBundle\Repository\CategorieRepository;

class UserType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstname', 'text')
            ->add('lastname')
            ->add('email')
            ->add('login')
            ->add('gender', 'gender')
            ->add('address')
            ->add('phone', new TelType())
            ->add('groupes', "entity",[
                    "multiple"=>"true",

                    "class"=>"TroiswaBackBundle:Groupe",
                    "choice_label"=>"name",
                ]
            )
            ->add('password', 'repeated', array(
                'type' => 'password',
                'invalid_message' => 'Les mots de passe ne matchent pas',
                'options' => array('attr' => array('class' => 'password-field')),
                'required' => true,
                'first_options'  => array('label' => 'Password'),
                'second_options' => array('label' => 'Repeat Password'),
            ));

        // Greffer un événement PRE_SET_DATA (avant l'affichage du formulaire)*
        // On lance la méthode editUser
        $builder->addEventListener(FormEvents::PRE_SET_DATA, [$this, 'editUser']);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Troiswa\BackBundle\Entity\User'
        ));
    }
    /**
     * @return string
     */
    public function getName()
    {
        return 'troiswa_backbundle_register';
    }

    public function editUser(FormEvent $event)
    {
        //die('ok');
        $user = $event->getData(); // objet user
        $form = $event->getForm(); // le formulaire


        //die(dump($user, $form));
        // Si j'ai un utilisateur et que l'id de l'utilisateur existe = je suis entrain de faire une modification
        if ($user && $user->getId())
        {
            $form->remove('login');
        }
    }
}
