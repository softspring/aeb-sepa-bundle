<?php

namespace Softspring\AebSepaBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * Class AebSepaText
 *
 * @Annotation
 * @Target({"PROPERTY", "METHOD", "ANNOTATION"})
 */
class AebSepaText extends Constraint
{
    public $message = 'This value can only contain valid SEPA characters.';
}