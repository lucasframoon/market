<?php

namespace Src\Repository;

use Exception;
use PDO;
use Src\Model\{Sales, SalesDetails};

class SaleRepository extends AbstractRepository
{

    public function __construct(
        protected PDO $pdo,
        protected SaleDetailsRepository $saleDetailsRepository
    )
    {
        parent::__construct($pdo);
    }

    protected function createModelInstance(): Sales
    {
        return new Sales;
    }

    protected function getTableName(): string
    {
        return 'sales';
    }

    /**
     * @param Sales $sale
     * @param array<SalesDetails> $salesDetails
     * @return bool
     * @throws Exception
     */
    public function createSale(Sales $sale, array $salesDetails): bool {
        try {
            $this->pdo->beginTransaction();

            if (!$saleId = $this->create($sale)) {
                throw new Exception('Nao foi possivel registrar a venda');
            }

            foreach ($salesDetails as $salesDetail) {
                $salesDetail->saleId = $saleId;
            }

            $this->saleDetailsRepository->bulkCreate($salesDetails);

            $this->pdo->commit();
            return true;

        } catch (Exception $e) {
            //TODO log the error
            $this->pdo->rollBack();
            throw new Exception('Nao foi possivel registrar a venda');
        }
    }

    /**
     * List all sales with details
     *
     * @return array|null
     */
    public function findAllWithDetails(): ?array
    {
        $sql = "SELECT s.*,
                   JSON_AGG(
                       JSON_BUILD_OBJECT(
                           'detail_id', s_details.id,
                           'product_id', s_details.product_id,
                           'product_name', p.name,
                           'quantity', s_details.quantity,
                           'price', s_details.price,
                           'tax_amount', s_details.tax_amount
                       )
                   ) as details
            FROM sales s
                LEFT JOIN sales_details s_details 
                    ON s.id = s_details.sale_id
            LEFT JOIN products p
                ON s_details.product_id = p.id
            GROUP BY s.id";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    /**
     * List all sales with details
     *
     * @param int $id
     * @return array|null
     */
    public function findByIdWithDetails(int $id): ?array
    {
        $sql = "SELECT s.*,
                   JSON_AGG(
                       JSON_BUILD_OBJECT(
                           'detail_id', s_details.id,
                           'product_id', s_details.product_id,
                           'product_name', p.name,
                           'quantity', s_details.quantity,
                           'price', s_details.price,
                           'tax_amount', s_details.tax_amount
                       )
                   ) as details
            FROM sales s
                LEFT JOIN sales_details s_details 
                    ON s.id = s_details.sale_id
            LEFT JOIN products p
                ON s_details.product_id = p.id
            WHERE s.id = :id
            GROUP BY s.id";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':id', $id);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    /**
     * Delete sale and its details
     *
     * @param int $id
     * @return bool
     * @throws Exception
     */
    public function deleteSale(int $id): bool {
        $this->pdo->beginTransaction();
        try {
            $this->saleDetailsRepository->deleteBySaleId($id);
            $this->delete($id);
            $this->pdo->commit();
            return true;
        } catch (Exception $e) {
            //TODO log the error
            $this->pdo->rollBack();
            throw $e;
        }
    }
}