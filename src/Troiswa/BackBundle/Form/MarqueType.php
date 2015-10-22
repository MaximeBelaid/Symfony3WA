<?php

namespace Troiswa\BackBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Troiswa\BackBundle\Form\DataTransformer\TagTransformer;


class MarqueType extends AbstractType
{
    private $em;

    public function __construct($doctrine = null)
    {
        $this->em = $doctrine;
    }
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        //die(dump($this->em));
        $builder
            ->add('titre');
            /*
            ->add('tags', "entity",[
                "multiple"=>"true",
                "class"=>"TroiswaBackBundle:Tag",
                "choice_label"=>"nom",
                ]
            );*/
        $builder
            ->add(
            $builder->create('tags','collection',array(
                'type'=>new TagWithoutMarqueType(),
                'allow_add'=>true)
            )
            ->addModelTransformer(new TagTransformer($this->em))
        );
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Troiswa\BackBundle\Entity\Marque'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'troiswa_backbundle_marque';
    }
}
