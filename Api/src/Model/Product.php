<?php

namespace Src\Model;

class Product extends AbstractModel
{


    protected  int $id = 0;
    protected  string $name = '';
    protected  string $description = '';
    protected  float $price = 0;
    protected  int $typeId = 0;
}