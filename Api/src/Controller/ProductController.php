<?php

declare(strict_types=1);

namespace Src\Controller;

use Exception;
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

    public function new(): int
    {

        //TODO post data validation
        if (!$name = $_POST['name'] ?? null) {
            throw new Exception('Parametro nome não pode ser vazio');
        }

        if (!$price = $_POST['price'] ?? null) {
            throw new Exception('Parametro preço não pode ser vazio');
        }

        if (!$typeId = $_POST['type_id'] ?? null) {
            throw new Exception('Parametro tipo não pode ser vazio');
        }

        $productType = $this->productTypeRepository->findById((int)$typeId, true);
        if (!$productType) {
            throw new Exception('Nao foi possivel encontrar o tipo de produto selecionado');
        }

        $product = new Product();
        $product->name = $name;
        $product->price = $price;
        $product->typeId = $typeId;
        if ($description = $_POST['description'] ?? null) {
            $product->description = $description;
        }

        return $this->productRepository->create($product);
    }

    public function findAllWithDetails(): array
    {
        return $this->productRepository->findAll();
    }

    public function findAll(): array
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
        //TODO id validation
        if (!$id = $args['id'] ?? null) {
            throw new Exception('Parametro id não pode ser vazio');
        }

        return $this->productRepository->findById((int)$id);
    }

    /**
     * @param array $args
     * @return bool
     * @throws Exception
     */
    public function update(array $args): bool
    {
        //TODO id and put data validation
        $id = $args['id'] ?? null;
        if (!is_null($id) && $id > 0) {
            throw new Exception('Parametro id não pode ser vazio');
        }

        /** @var ?Product $product */
        $product = $this->productRepository->findById((int)$id, true);
        if (!$product) {
            throw new Exception('Nao foi possivel encontrar o produto');
        }

        if (!empty($args['PUT']) && $args['PUT']['name']) {
            $name = $args['PUT']['name'];
            $product->name = $name;
        }

        if (!empty($args['PUT']) && $args['PUT']['price']) {
            $price = $args['PUT']['price'];
            $product->price = $price;
        }

        if (!empty($args['PUT']) && $args['PUT']['type_id'] && $args['PUT']['type_id'] > 0) {
            $typeId = $args['PUT']['type_id'];

            $productType = $this->productTypeRepository->findById((int)$typeId, true);
            if (!$productType) {
                throw new Exception('Nao foi possivel encontrar o tipo de produto selecionado');
            }
            $product->typeId = $typeId;
        }

        if (!empty($args['PUT']) && $args['PUT']['description']) {
            $description = $args['PUT']['description'];
            $product->description = $description;
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
        //TODO id validation
        if (!$id = $args['id'] ?? null) {
            throw new Exception('Parametro id não pode ser vazio');
        }

        return $this->productRepository->delete((int)$id);
    }
}