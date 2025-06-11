<?php

declare(strict_types=1);

namespace App\Common\Domain\Abstract;

use App\Common\Domain\Interface\QueryDTOInterface;
use Symfony\Component\Validator\Constraints as Assert;

class QueryDTOAbstract implements QueryDTOInterface
{
    public ?string $createdAt = null;

    public ?string $updatedAt = null;

    public ?string $deletedAt = null;

    #[Assert\Type(type: 'integer', message: "pageMustBeAnInteger")]
    #[Assert\GreaterThan(value: 0, message: "pageMustBeGreaterThan0")]
    public ?int $page = 1;

    #[Assert\Type(type: 'integer', message: "pageMustBeAnInteger")]
    #[Assert\GreaterThan(value: 0, message: "pageMustBeGreaterThan0")]
    public ?int $pageSize = 10;

    public ?string $sortBy = 'createdAt';

    public ?string $sortDirection = 'desc';

    public ?int $deleted = null;

    public ?string $phrase = null;

    public ?string $includes = null;
}
