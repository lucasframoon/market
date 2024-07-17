<?php

declare(strict_types=1);

namespace Src\Repository;

use PDO;
use Src\Model\ProductType;

class ProductTypeRepository extends AbstractRepository
{

    public function __construct(
        protected PDO $pdo
    ) {
        parent::__construct($pdo);
    }

    protected function createModelInstance(): ProductType
    {
        return new ProductType();
    }

    protected function getTableName(): string
    {
        return 'product_types';
    }

    /**
     * Check if a product type has products registered
     *
     * @param int $id
     * @return bool
     */
    public function hasProductsForType(int $id): bool
    {
        $sql = "SELECT count(prod.id) as count
                FROM products prod
                    JOIN product_types prod_type
                        ON prod.type_id = prod_type.id
                WHERE prod_type.id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        $result = $stmt->fetchAll();
        $hasProducts = $result[0]['count'] > 0;
        return $stmt->rowCount() > 0 ? $hasProducts : false;
    }
}