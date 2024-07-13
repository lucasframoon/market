<?php

namespace Src\Model;

class ProductType extends AbstractModel
{

    private string $table = 'product_types'; // @phpstan-ignore-line

    protected  int $id = 0;
    protected  string $name = '';
    protected  float $taxPercentage = 0.0;

}