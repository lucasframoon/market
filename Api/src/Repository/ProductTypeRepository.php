<?php

namespace Src\Repository;

use PDO;
use Src\Model\AbstractModel;
use Src\Model\ProductType;

class ProductTypeRepository extends AbstractRepository
{
    protected string $tableName = 'product_types';
    public AbstractModel $model;

    public function __construct(
        protected PDO $db
    ) {
        $this->model = new ProductType();
        parent::__construct($db);
    }

}