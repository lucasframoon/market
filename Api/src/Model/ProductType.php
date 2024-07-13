<?php

namespace Src\Model;

class ProductType extends AbstractModel
{

    protected string $table;

    public  int $id;
    public  string $name;
    public  float $tax_percentage;

    public function __construct()
    {
        $this->table = 'product_types';
    }
}