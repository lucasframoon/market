<?php

declare(strict_types=1);

namespace Src\Repository;

use PDO;
use Src\Model\SaleDetails;

class SaleDetailsRepository extends AbstractRepository
{
    public function __construct(
        protected PDO $pdo
    )
    {
        parent::__construct($pdo);
    }

    protected function createModelInstance(): SaleDetails
    {
        return new SaleDetails;
    }

    protected function getTableName(): string
    {
        return 'sales_details';
    }

    /**
     * Find sale details by sale id
     *
     * @param int $saleId
     * @param bool $fetchClass
     * @return array|null
     */
    public function findBySaleId(int $saleId, bool $fetchClass = false): ?array
    {
        return $this->findByColumn('sale_id', $saleId);
    }

    public function deleteBySaleId(int $saleId): void
    {
        $this->deleteByColumn('sale_id', $saleId);
    }
}