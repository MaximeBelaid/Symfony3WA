<?php

/**
 * Created by PhpStorm.
 * User: wap21
 * Date: 28/10/15
 * Time: 11:27
 */

namespace Troiswa\BackBundle\Validator;
use Symfony\Component\Validator\Constraint;


/**
 * @Annotation
 */
class PositionCategory extends Constraint
{
    public $message = "La position existe déjà";

    public function validatedBy()
    {
        return 'troiswa_back_position_category';
    }
}