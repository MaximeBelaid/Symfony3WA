<?php
/**
 * Created by PhpStorm.
 * User: wap21
 * Date: 28/10/15
 * Time: 11:28
 */

namespace Troiswa\BackBundle\Validator;


use Doctrine\ORM\EntityManager;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class PositionCategoryValidator extends ConstraintValidator
{
    private $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function validate($value, Constraint $constraint)
    {
        //die(dump($this->em));

        $position = $this->em->getRepository('TroiswaBackBundle:Categorie')->findOneByPosition($value);

        if ($position)
        {
            $this->context->buildViolation($constraint->message)
                ->addViolation();
        }

        // $value c'est la valeur du mot de passe
        // $constraint c'est l'objet MotDePasse
    }
}