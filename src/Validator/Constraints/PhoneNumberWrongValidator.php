<?php

namespace App\Validator\Constraints;

use App\Helpers\ValidationHelper as Helper;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

/**
 * @Annotation
 */
class PhoneNumberWrongValidator extends ConstraintValidator
{
    /**
 * Phone number validation API key.
 * @var $phoneNumberValidateAPIKey
 */
    private $phoneNumberValidateAPIKey;

    /**
     * PhoneNumberWrongValidator constructor.
     * @param $phoneNumberValidateAPIKey
     */
    public function __construct($phoneNumberValidateAPIKey)
    {
        $this->phoneNumberValidateAPIKey = $phoneNumberValidateAPIKey;
    }

    /**
     * @param mixed $value
     * @param Constraint $constraint
     */
    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof PhoneNumberWrong) {
            throw new UnexpectedTypeException($constraint, PhoneNumberWrong::class);
        }

        // must be here!
        if (null === $value || '' === $value) {
            return;
        }

        if (!is_string($value)) {
            // throw this exception if your validator cannot handle the passed type so that it can be marked as invalid
            throw new UnexpectedValueException($value, 'string');
        }

        if (!Helper::validPhoneNumber($value, $this->phoneNumberValidateAPIKey)) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ string }}', $value)
                ->addViolation();
        }
    }
}