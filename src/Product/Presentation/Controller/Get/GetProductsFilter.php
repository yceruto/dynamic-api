<?php

namespace App\Product\Presentation\Controller\Get;

use Symfony\Component\Validator\Constraints as Assert;

class GetProductsFilter
{
    #[Assert\PositiveOrZero]
    public int $limit = 10;

    #[Assert\PositiveOrZero]
    public int $offset = 0;
}
