<?php

declare(strict_types=1);

namespace Src\Repository;

use PDO;
use Src\Model\Product;

class ProductRepository extends AbstractRepository
{

    public function __construct(
        protected PDO $pdo
    )
    {
        parent::__construct($pdo);
    }

    protected function createModelInstance(): Product
    {
        return new Product();
    }

    protected function getTableName(): string
    {
        return 'products';
    }

    /**
     * Find data by ids using IN clause
     * @todo prevent sql injection (only ids )
     * @param array $ids
     * @return array
     */
    public function findIn( array $ids): array
    {
        if (empty($ids)) {
            return [];
        }

        $ids = implode(',', $ids);

        $sql = "SELECT prod.*, 
                    prod_type.name as type_name,
                    prod_type.tax_percentage
                FROM products prod
                    JOIN product_types prod_type
                        ON prod.type_id = prod_type.id 
                WHERE prod.id IN (" . $ids . ")";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function findAll(bool $fetchClass = false): array
    {
        $sql = "SELECT prod.*, 
                    prod_type.name as type_name,
                    prod_type.tax_percentage
                FROM products prod
                    JOIN product_types prod_type
                        ON prod.type_id = prod_type.id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();

        if ($fetchClass) {
            return $stmt->fetchAll(PDO::FETCH_CLASS, get_class($this->createModelInstance()));
        }

        return $stmt->fetchAll();
    }
}