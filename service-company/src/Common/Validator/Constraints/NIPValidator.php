<?php

namespace App\Common\Validator\Constraints;

use App\Common\Shared\Utils\NIPValidator as NIP;
use App\Common\Validator\Constraints\NIP as NIPConstraints;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Contracts\Translation\TranslatorInterface;

class NIPValidator extends ConstraintValidator
{
    public function __construct(private readonly TranslatorInterface $translator)
    {
    }

    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof NIPConstraints) {
            throw new UnexpectedTypeException($constraint, NIPConstraints::CLASS_CONSTRAINT);
        }

        $error = NIP::validate($value);
        if ($error) {
            $message = $this->translator->trans(
                $constraint->message['invalidNIP'],
                [':nip' => $value],
                $constraint->message['domain']
            );

            $this->context->buildViolation($message)->addViolation();
        }
    }
}
