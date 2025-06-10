<?php

declare(strict_types=1);

namespace App\Module\Company\Structure\Validator\Constraints\Company;

use App\Module\Company\Domain\Interface\Company\CompanyReaderInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Contracts\Translation\TranslatorInterface;

class UniqueCompanyNIPValidator extends ConstraintValidator
{
    public function __construct(private readonly CompanyReaderInterface $companyReaderRepository, private readonly TranslatorInterface $translator)
    {
    }

    public function validate(mixed $value, Constraint $constraint)
    {
        if (!$constraint instanceof UniqueCompanyNIP) {
            throw new \InvalidArgumentException(sprintf('%s can only be used with UniqueCompanyNIP constraint.', __CLASS__));
        }

        if (!is_string($value) || empty($value)) {
            return;
        }

        $object = $this->context->getObject();
        $nip = property_exists($object, 'nip') ? $object->nip : null;

        if ($this->companyReaderRepository->isCompanyExistsWithNIP($value, $nip)) {
            $this->context->buildViolation($this->translator->trans($constraint->message, [], 'companies'))
                ->setParameter('{{ value }}', $value)
                ->addViolation();
        }
    }
}
