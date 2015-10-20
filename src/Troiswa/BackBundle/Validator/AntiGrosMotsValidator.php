<?php

namespace Troiswa\BackBundle\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class AntiGrosMotsValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        //die(dump($value));
        $grosMots = ["merde","putain","fait chier"];
        foreach($grosMots as $mot)
        {
            if (preg_match("#\b(".$mot.")\b#ui", $value))
            {
                $this->context->buildViolation($constraint->message)
                    ->addViolation();
                return;
            }
        }
    }
}