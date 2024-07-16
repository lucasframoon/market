<?php

declare(strict_types=1);

namespace Src\Controller;

use Exception;
use Src\Model\{Sales, SalesDetails};
use Src\Repository\{ProductRepository, SaleRepository};

class SaleController extends AbstractController
{

    public function __construct(
        private readonly SaleRepository    $saleRepository,
        private readonly ProductRepository $productRepository
    )
    {
    }

    /**
     * @throws Exception
     */
    public function new(): bool
    {
        $rules = [
            'products' => ['type' => 'json', 'required' => true],
            'sale_date' => ['type' => 'date', 'required' => true]
        ];

        $postData = $this->validateInput(null, $rules);
        if (empty($postData['products']) || !is_array($postData['products'])) {
            throw new Exception('Adicione os itens da venda');
        }

        $arraySalesDetails = [];
        $totalAmount = 0;
        $totalTax = 0;
        $productQuantities = [];
        $productsIds = [];

        foreach ($postData['products'] as $product) {
            if (!isset($product['product_id'], $product['quantity'])) {
                throw new Exception('Dados dos produtos inválidos');
            }
            $productQuantities[$product['product_id']] = $product['quantity'];
            $productsIds[] = $product['product_id'];
        }

        $productsInfo = $this->productRepository->findIn($productsIds);

        foreach ($productsInfo as $product) {
            $productId = $product['id'];
            $price = $product['price'];
            $taxPercentage = $product['tax_percentage'];
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
        $sale->saleDate = $postData['sale_date'];
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
        $arguments = $this->validateInput($args, ['id' => ['type' => 'int', 'required' => true]]);
        return $this->saleRepository->findByIdWithDetails($arguments['id']);
    }

    //TODO implement update
    public function update(array $args): bool
    {
        throw new Exception("Método 'update' não implementado");
    }

    /**
     * @param array $args
     * @return bool
     * @throws Exception
     */
    public function delete(array $args): bool
    {
        $arguments = $this->validateInput($args, ['id' => ['type' => 'int', 'required' => true]]);
        return $this->saleRepository->deleteSale($arguments['id']);
    }
}