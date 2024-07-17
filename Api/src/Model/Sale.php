<?php

declare(strict_types=1);

namespace Src\Model;

class Sale extends AbstractModel
{
    protected  int $id = 0;
    protected  string $saleDate = '';
    protected  float $totalAmount = 0;
    protected  float $totalTax = 0;
}