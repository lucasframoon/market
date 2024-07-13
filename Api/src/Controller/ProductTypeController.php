<?php

namespace Src\Controller;

use Exception;
use Src\Model\ProductType;
use Src\Repository\ProductTypeRepository;

class ProductTypeController extends AbstractController
{

    public function __construct(
        private readonly ProductTypeRepository $productTypeRepository,
    )
    {
    }

    public function new(): int
    {
        //TODO post data validation
        if (!$name = $_POST['name']?? null) {
            throw new Exception('Parametro nome não pode ser vazio');
        }

        if (!$tax_percentage = $_POST['tax_percentage']?? null){
            throw new Exception('Parametro taxa não pode ser vazio');
        }

        $productType = new ProductType();
        $productType->taxPercentage = $tax_percentage;
        $productType->name = $name;

        return $this->productTypeRepository->create($productType);
    }

    public function findAll(): array
    {
        return $this->productTypeRepository->findAll();
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

        return $this->productTypeRepository->findById((int)$id);
    }

    /**
     * @param array $args
     * @return bool
     * @throws Exception
     */
    public function update(array $args): bool
    {
        //TODO id and put data validation
        if (!$id = $args['id'] ?? null) {
            throw new Exception('Parametro id não pode ser vazio');
        }

        /** @var ProductType $productType */
        $productType = $this->productTypeRepository->findById((int)$id, true);
        if (!$productType) {
            throw new Exception('Nao foi possivel encontrar o tipo de produto');
        }

        if (!empty($args['PUT']) && $args['PUT']['name']) {
            $name = $args['PUT']['name'];
            $productType->name = $name;
        }

        if (!empty($args['PUT']) && $args['PUT']['tax_percentage']) {
            $taxPercentage = $args['PUT']['tax_percentage'];
            $productType->taxPercentage = $taxPercentage;
        }

        return $this->productTypeRepository->update($productType);
    }
}