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

    /**
     * @throws Exception
     */
    public function new(): int
    {
        $rules = [
            'name' => ['type' => 'string', 'required' => true],
            'tax_percentage' => ['type' => 'float', 'required' => true]
        ];

        $postData = $this->validateInput(null, $rules);

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
            throw new Exception('Não foi possível atualizar o tipo de produto');
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
            throw new Exception('Nao foi possivel encontrar o tipo de produto');
        }

        if (isset($arguments['name'])) {
            $productType->name = $arguments['name'];
        }

        if (isset($arguments['tax_percentage'])) {
            if ($arguments['tax_percentage'] < 0) {
                throw new Exception('Taxa deve ser maior que zero');
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
            throw new Exception('Não é possivel excluir um tipo de produto que contém produtos relacionados');
        }

        return $this->productTypeRepository->delete($arguments['id']);
    }
}