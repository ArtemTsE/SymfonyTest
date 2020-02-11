<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class PhoneNumberWrong extends Constraint
{
    /**
     * @var string
     */
    public $message = 'The phone number "{{ string }}" is wrong.';
}