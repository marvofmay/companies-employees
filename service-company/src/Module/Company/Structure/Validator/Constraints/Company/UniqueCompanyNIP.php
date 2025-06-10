<?php

namespace App\Module\Company\Structure\Validator\Constraints\Company;

use Symfony\Component\Validator\Constraint;

#[\Attribute(\Attribute::TARGET_PROPERTY | \Attribute::TARGET_METHOD)]
class UniqueCompanyNIP extends Constraint
{
    public string $message = 'company.nip.alreadyExists';
}
