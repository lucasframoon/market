<?php

declare(strict_types=1);

namespace Src\Controller;

use Exception;
use Src\Exception\ApiException;
use Src\Model\{Product};
use Src\Repository\{ProductRepository, ProductTypeRepository};

class ProductController extends AbstractController
{

    public function __construct(
        private readonly ProductRepository     $productRepository,
        private readonly ProductTypeRepository $productTypeRepository
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
            'price' => ['type' => 'float', 'required' => true],
            'type_id' => ['type' => 'int', 'required' => true],
            'description' => ['type' => 'string', 'required' => false]
        ];

        $postData = $this->validateInput(null, $rules);

        if ($postData['name'] === '') {
            throw new ApiException('O nome deve ser informado', 400);
        }

        if ($postData['price'] < 0) {
            throw new ApiException('O preço deve ser positivo', 400);
        }

        $productType = $this->productTypeRepository->findById($postData['type_id'], true);
        if (!$productType) {
            throw new ApiException('Nao foi possivel encontrar o tipo de produto selecionado', 400);
        }

        $product = new Product();
        $product->name = $postData['name'];
        $product->price = $postData['price'];
        $product->typeId = $postData['type_id'];
        if (isset($postData['description'])) {
            $product->description = $postData['description'];
        }

        return $this->productRepository->create($product);
    }

    public function findAllWithDetails(): array
    {
        return $this->productRepository->findAll();
    }

    /**
     * @param array $args
     * @return array
     * @throws Exception
     */
    public function findById(array $args): array
    {
        $arguments = $this->validateInput($args, ['id' => ['type' => 'int', 'required' => true]]);
        return $this->productRepository->findById($arguments['id']);
    }

    /**
     * @param array $args
     * @return bool
     * @throws Exception
     */
    public function update(array $args): bool
    {
        if (empty($args['PUT'])) {
            throw new ApiException('Não foi possível atualizar o produto', 400);
        }

        $rules = [
            'id' => ['type' => 'int', 'required' => true],
            'name' => ['type' => 'string', 'required' => false],
            'price' => ['type' => 'float', 'required' => false],
            'type_id' => ['type' => 'int', 'required' => false],
            'description' => ['type' => 'string', 'required' => false]
        ];

        $arguments = $this->validateInput(['id' => $args['id'], ...$args['PUT']], $rules);

        if ($arguments['id'] < 0) {
            throw new ApiException('Não foi possível atualizar o produto', 400);
        }

        /** @var ?Product $product */
        $product = $this->productRepository->findById($arguments['id'], true);
        if (!$product) {
            throw new ApiException('Não foi possível atualizar o produto', 400);
        }

        if (isset($arguments['name'])) {
            $product->name = $arguments['name'];
        }

        if (isset($arguments['price']) && $arguments['price'] > 0) {
            $product->price = $arguments['price'];
        }

        if (isset($arguments['type_id']) && $arguments['type_id'] > 0) {
            $productType = $this->productTypeRepository->findById($arguments['type_id'], true);
            if (!$productType) {
                throw new ApiException('Não foi possível encontrar o tipo de produto', 400);
            }
            $product->typeId = $arguments['type_id'];
        }

        if (isset($arguments['description'])) {
            $product->description = $arguments['description'];
        }

        return $this->productRepository->update($product);
    }

    /**
     * @param array $args
     * @return bool
     * @throws Exception
     */
    public function delete(array $args): bool
    {
        $arguments = $this->validateInput($args, ['id' => ['type' => 'int', 'required' => true]]);
        return $this->productRepository->delete($arguments['id']);
    }
}