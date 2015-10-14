<?php

namespace Troiswa\BackBundle\Form;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Troiswa\BackBundle\Repository\CategorieRepository;

class ProductType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('description')
            ->add('price')
            ->add('dateCreated',"date",
                ["widget"=>"single_text",'format' => 'dd/MM/yyyy'
                ])
            ->add('quantity')
            ->add('categorie', "entity", [
                //"expanded"=>"true",
                "class"=>"TroiswaBackBundle:Categorie",
                /*
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('cat')
                        ->orderBy('cat.position', 'ASC');
                },
                */
                "choice_label"=>"title",
                'query_builder' => function (CategorieRepository $er) {
                    return $er->builderCategoryOrderPosition();

                },
            ])
            ->add('marque', "entity", [
                //"expanded"=>"true",
                "class"=>"TroiswaBackBundle:Marque",
                "choice_label"=>"titre",
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('m')
                        ->orderBy('m.titre', 'ASC');
                }]);
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Troiswa\BackBundle\Entity\Product'
        ));
    }
    /**
     * @return string
     */
    public function getName()
    {
        return 'troiswa_backbundle_product';
    }
}
