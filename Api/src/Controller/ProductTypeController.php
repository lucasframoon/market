<?php

namespace Src\Controller;

use Src\Model\AbstractModel;
use Src\Repository\ProductTypeRepository;

class ProductTypeController extends AbstractController
{

    public function __construct(
        private readonly ProductTypeRepository $productTypeRepository
    )
    {
    }

    public function new(): int
    {
        //TODO post data validation
        return 1;
//        $this->productTypeRepository->model;
//        return $this->productTypeRepository->create();
    }

    public function findAll(): array
    {
        return $this->productTypeRepository->findAll();
    }

    public function findById(string $id): AbstractModel
    {
        //TODO id validation
        return $this->productTypeRepository->findById((int)$id);
    }

//    public function update(string $id): AbstractModel
//    {
//        //TODO id and put data validation
////        return $this->productTypeRepository->update((int)$id);
//    }
}