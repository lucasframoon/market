<?php

namespace Src\Repository;

use PDO;
use Src\Model\Product;

class ProductRepository extends AbstractRepository
{

    public function __construct(
        protected PDO $db
    )
    {
        parent::__construct($db);
    }

    protected function createModelInstance(): Product
    {
        return new Product();
    }

    protected function getTableName(): string
    {
        return 'products';
    }

    public function findAll(bool $fetchClass = false): ?array
    {
        $sql = "SELECT prod.*, 
                    prod_type.name as type_name
                FROM products prod
                    JOIN product_types prod_type
                        ON prod.type_id = prod_type.id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();

        if ($fetchClass) {
            return $stmt->rowCount() > 0 ? $stmt->fetchAll(PDO::FETCH_CLASS, get_class($this->createModelInstance())) : null;
        }

        return $stmt->rowCount() > 0 ? $stmt->fetchAll() : null;
    }
}