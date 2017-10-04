<?php

namespace Softspring\AebSepaBundle\Validator\Constraints;

use Softspring\AebSepaBundle\Utils\AebFormat;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class AebSepaTextValidator extends ConstraintValidator
{
    /**
     * @param string     $value
     * @param Constraint $constraint
     */
    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof AebSepaText) {
            throw new UnexpectedTypeException($constraint, __NAMESPACE__.'\AebSepaText');
        }

        if (null === $value) {
            return;
        }

        $validChars = array_map('preg_quote', AebFormat::getValidChars());
        $regex = '['.implode('', $validChars).']+';
        $regex = str_replace('/', '\\/', $regex);
        $regex = str_replace(' ', '\\s', $regex);

        if (!preg_match("/$regex/", $value)) {
            $this->context->buildViolation($constraint->message)
                ->addViolation();
        }
    }
}