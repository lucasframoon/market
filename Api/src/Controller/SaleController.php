<?php

declare(strict_types=1);

namespace Src\Controller;

use Exception;
use Src\Model\{Sales, SalesDetails};
use Src\Repository\{ProductRepository, SaleRepository};

class SaleController extends AbstractController
{

    public function __construct(
        private readonly SaleRepository        $saleRepository,
        private readonly ProductRepository     $productRepository
    )
    {
    }

    public function new(): bool
    {

        //TODO post data validation

        $postProducts = $_POST['products'] ?? null;
        $saleDate = $_POST['sale_date'] ?? null;

        if (is_null($postProducts)) {
            throw new Exception('Adicione os itens da venda');
        }

        $postProducts = json_decode($postProducts, true);
        if (empty($postProducts)) {
            throw new Exception('Adicione os itens da venda');
        }

        $arraySalesDetails = [];
        $totalAmount = 0;
        $totalTax = 0;

        $productQuantities = [];
        $productsIds = [];
        foreach ($postProducts as $product) {
            $productQuantities[$product['product_id']] = $product['quantity'];
            $productsIds[] = $product['product_id'];
        }

        $productsInfo = $this->productRepository->findIn($productsIds);

        foreach ($productsInfo as $product) {
            $productId = $product['id'];
            $price = $product['price'];
            $taxPercentage  = $product['tax_percentage'];
            $quantity = $productQuantities[$productId];

            $productTotalAmount = $price * $quantity;
            $productTaxAmount = ($productTotalAmount * $taxPercentage) / 100;

            $totalAmount += $productTotalAmount;
            $totalTax += $productTaxAmount;

            $salesDetails = new SalesDetails();
            $salesDetails->productId = $productId;
            $salesDetails->quantity = $quantity;
            $salesDetails->price = $price;
            $salesDetails->taxAmount = $productTaxAmount;
            $arraySalesDetails[] = $salesDetails;
        }

        $sale = new Sales();
        $sale->saleDate = $saleDate;
        $sale->totalAmount = $totalAmount;
        $sale->totalTax = $totalTax;

        return $this->saleRepository->createSale($sale, $arraySalesDetails);
    }

    public function findAll(): ?array
    {
        $sales = $this->saleRepository->findAllWithDetails();
        $salesWithFormattedDate = [];
        foreach ($sales as $sale) {
            $sale['sale_date'] = date('d/m/Y', strtotime($sale['sale_date']));
            $salesWithFormattedDate[] = $sale;
        }
        return $salesWithFormattedDate;
    }

    /**
     * @param array $args
     * @return array
     * @throws Exception
     */
    public function findById(array $args): array
    {
        //TODO id validation
        if (!$id = $args['id'] ?? null) {
            throw new Exception('Parametro id não pode ser vazio');
        }

        return $this->saleRepository->findByIdWithDetails((int)$id);
    }

    //TODO implement update
    public function update(array $args): bool
    {
        return false;
    }

    /**
     * @param array $args
     * @return bool
     * @throws Exception
     */
    public function delete(array $args): bool
    {
        //TODO id validation
        if (!$id = $args['id'] ?? null) {
            throw new Exception('Parametro id não pode ser vazio');
        }

        return $this->saleRepository->deleteSale((int)$id);
    }
}