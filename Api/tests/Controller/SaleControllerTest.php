<?php

namespace Controller;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Src\Controller\AbstractController;
use Src\Controller\SaleController;
use Src\Exception\ApiException;
use Src\Repository\ProductRepository;
use Src\Repository\SaleRepository;

class SaleControllerTest extends TestCase
{

    protected function setUp(): void
    {
        parent::setUp();

        $this->productRepositoryMock = $this->createMock(ProductRepository::class);
        $this->saleRepositoryMock = $this->createMock(SaleRepository::class);
        $this->abstractControllerMock = $this->getMockBuilder(AbstractController::class)
            ->onlyMethods(['validateInput', 'isValidJson', 'isValidDate'])
            ->getMock();

        $this->controller = new SaleController($this->saleRepositoryMock, $this->productRepositoryMock);
    }

    public static function validDataProvider(): array
    {
        return [
            [[
                'id' => rand(1, 100),
                'sale_date' => date('Y-m-d'),
                'products' => [
                    ['product_id' => rand(1, 100), 'quantity' => rand(1, 100)]
                ],
            ]],
            [[
                'id' => rand(1, 100),
                'sale_date' => date('Y-m-d'),
                'products' => [
                    ['product_id' => rand(1, 100), 'quantity' => rand(1, 100)]
                ],
            ]],
        ];
    }

    public static function invalidDataProvider(): array
    {
        return [
            [[
                'id' => null,
                'sale_date' => date('Y-m-d'),
                'products' => [],
            ]],
            [[
                'id' => 'id',
                'sale_date' => date('Y-m-d'),
                'products' => []
            ]],
            [[
                'id' => rand(1, 100),
                'sale_date' => '2024/07/16',
                'products' => [],
            ]],
            [[
                'id' => rand(1, 100),
                'sale_date' => date('d-m-Y'),
                'products' => []
            ]],
            [[
                'id' => rand(1, 100),
                'sale_date' => date('Y-m-d'),
                'products' => null,
            ]],
            [[
                'id' => rand(1, 100),
                'sale_date' => date('Y-m-d'),
                'products' => ['id' => null]
            ]],
        ];
    }

    #[DataProvider('validDataProvider')]
    public function testNew($validData)
    {
        $_POST = [
            'sale_date' => $validData['sale_date'],
            'products' => json_encode($validData['products'])
        ];

        $productIds = array_column($validData['products'], 'product_id');
        $this->productRepositoryMock
            ->expects($this->once())
            ->method('findIn')
            ->with($productIds)
            ->willReturn([]);

        $this->saleRepositoryMock
            ->expects($this->once())
            ->method('createSale')
            ->willReturn(true);

        $response = $this->controller->new();
        $this->assertTrue($response);
    }

    #[DataProvider('validDataProvider')]
    public function testFindById($validData)
    {
        $this->saleRepositoryMock
            ->expects($this->once())
            ->method('findByIdWithDetails')
            ->with((int)$validData['id'])
            ->willReturn([]);

        $response = $this->controller->findById(['id' => (int)$validData['id']]);
        $this->assertIsArray($response);
    }

    public function testFindByIdException()
    {
        $this->expectException(ApiException::class);
        $this->controller->findById(['id' => null]);
    }

    #[DataProvider('validDataProvider')]
    public function testDelete($validData)
    {
        $this->saleRepositoryMock
            ->expects($this->once())
            ->method('deleteSale')
            ->with((int)$validData['id'])
            ->willReturn(true);

        $response = $this->controller->delete(['id' => $validData['id']]);
        $this->assertTrue($response);
    }

    public function testDeleteInvalidId()
    {
        $this->expectException(ApiException::class);
        $this->controller->delete(['id' => null]);
    }

    public function testFindAll()
    {
        $this->saleRepositoryMock
            ->expects($this->once())
            ->method('findAllWithDetails')
            ->willReturn([]);

        $response = $this->controller->findAll();
        $this->assertIsArray($response);
    }
}
