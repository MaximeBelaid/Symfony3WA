<?php

namespace Troiswa\BackBundle\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * permet d'utiliser cette contraintes en annotation
 * @Annotation
 */
class MotDePasse extends Constraint
{
    public $min = 6;
    public $message = "Le mot de passe doit comporter au minimum {{ nb }} caractères";
}