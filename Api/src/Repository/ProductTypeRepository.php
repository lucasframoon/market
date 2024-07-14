<?php

namespace Src\Repository;

use PDO;
use Src\Model\ProductType;

class ProductTypeRepository extends AbstractRepository
{

    public function __construct(
        protected PDO $db
    ) {
        parent::__construct($db);
    }

    protected function createModelInstance(): ProductType
    {
        return new ProductType();
    }

    protected function getTableName(): string
    {
        return 'product_types';
    }
}