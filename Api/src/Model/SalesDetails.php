<?php

declare(strict_types=1);

namespace Src\Model;

class SalesDetails extends AbstractModel
{
    protected  int $id = 0;
    protected  int $saleId = 0;
    protected  int $productId = 0;
    protected  int $quantity = 0;
    protected  float $price = 0;
    protected  float $taxAmount = 0;
}