<?php

namespace Src\Controller;

use Exception;
use Src\Exception\ApiException;
use Src\Model\ProductType;
use Src\Repository\ProductTypeRepository;

class ProductTypeController extends AbstractController
{

    public function __construct(
        private readonly ProductTypeRepository $productTypeRepository,
    )
    {
    }

    /**
     * @return int|null
     * @throws Exception
     */
    public function new(): ?int
    {
        $rules = [
            'name' => ['type' => 'string', 'required' => true],
            'tax_percentage' => ['type' => 'float', 'required' => true]
        ];

        $postData = $this->validateInput(null, $rules);

        if ($postData['tax_percentage'] < 0) {
            throw new ApiException('O valor do imposto deve ser positivo', 400);
        }

        $productType = new ProductType();
        $productType->taxPercentage = $postData['tax_percentage'];
        $productType->name = $postData['name'];

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
        $arguments = $this->validateInput($args, ['id' => ['type' => 'int', 'required' => true]]);
        return $this->productTypeRepository->findById($arguments['id']);
    }

    /**
     * @param array $args
     * @return bool
     * @throws Exception
     */
    public function update(array $args): bool
    {
        if (empty($args['PUT'])) {
            throw new ApiException('Não foi possível atualizar o tipo de produto', 400);
        }

        $rules = [
            'id' => ['type' => 'int', 'required' => true],
            'name' => ['type' => 'string', 'required' => false],
            'tax_percentage' => ['type' => 'float', 'required' => false]
        ];

        $arguments = $this->validateInput(['id' => $args['id'], ...$args['PUT']], $rules);

        /** @var ?ProductType $productType */
        $productType = $this->productTypeRepository->findById($arguments['id'], true);
        if (!$productType) {
            throw new ApiException('Nao foi possivel encontrar o tipo de produto', 400);
        }

        if (isset($arguments['name'])) {
            $productType->name = $arguments['name'];
        }

        if (isset($arguments['tax_percentage'])) {
            if ($arguments['tax_percentage'] < 0) {
                throw new ApiException('Taxa deve ser maior que zero', 400);
            }
            $productType->taxPercentage = $arguments['tax_percentage'];
        }

        return $this->productTypeRepository->update($productType);
    }

    /**
     * @param array $args
     * @return bool
     * @throws Exception
     */
    public function delete(array $args): bool
    {
        $arguments = $this->validateInput($args, ['id' => ['type' => 'int', 'required' => true]]);
        if ($this->productTypeRepository->hasProductsForType($arguments['id'])) {
            throw new ApiException('Não é possivel excluir um tipo de produto que contém produtos relacionados', 400);
        }

        return $this->productTypeRepository->delete($arguments['id']);
    }
}