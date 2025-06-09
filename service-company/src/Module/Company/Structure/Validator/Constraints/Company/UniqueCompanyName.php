<?php

namespace App\Module\Company\Structure\Validator\Constraints\Company;

use Symfony\Component\Validator\Constraint;

#[\Attribute(\Attribute::TARGET_PROPERTY | \Attribute::TARGET_METHOD)]
class UniqueCompanyName extends Constraint
{
    public string $message = 'company.name.alreadyExists';
}
