<?php

namespace Controller;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Src\Controller\AbstractController;
use Src\Controller\ProductTypeController;
use Src\Exception\ApiException;
use Src\Model\ProductType;
use Src\Repository\ProductTypeRepository;

class ProductTypeControllerTest extends TestCase
{

    protected function setUp(): void
    {
        parent::setUp();

        $this->productTypeRepositoryMock = $this->createMock(ProductTypeRepository::class);
        $this->productTypeMock = $this->createMock(ProductType::class);
        $this->abstractControllerMock = $this->getMockBuilder(AbstractController::class)
            ->onlyMethods(['validateInput'])
            ->getMock();

        $this->controller = new ProductTypeController($this->productTypeRepositoryMock);
    }

    public static function validDataProvider(): array
    {
        return [
            [['id' => rand(1, 100),
                'name' => 'test',
                'tax_percentage' => (float)rand(),
            ]],
            [['id' => rand(1, 100),
                'name' => 'test2',
                'tax_percentage' => '1.1',
            ]],
            [['id' => rand(1, 100),
                'name' => 'test3',
                'tax_percentage' => rand(1, 100)
            ]]
        ];
    }

    public static function invalidDataProvider(): array
    {
        return [
            [['id' => rand(1, 100),
                'name' => null,
                'tax_percentage' => '1.1',
            ]],
            [['id' => rand(1, 100),
                'name' => 'test3',
                'tax_percentage' => '1,1'
            ]],
            [['id' => rand(1, 100),
                'name' => 'test3',
                'tax_percentage' => -1
            ]],
        ];
    }

    public function testFindAll()
    {
        $this->productTypeRepositoryMock
            ->expects($this->once())
            ->method('findAll')
            ->willReturn([]);

        $response = $this->controller->findAll();
        $this->assertIsArray($response);
    }

    #[DataProvider('validDataProvider')]
    public function testNew($validData)
    {
        $_POST = [
            'name' => $validData['name'],
            'tax_percentage' => $validData['tax_percentage'],
        ];

        $this->productTypeRepositoryMock
            ->expects($this->once())
            ->method('create')
            ->with($this->isInstanceOf(ProductType::class))
            ->willReturn((int)$validData['id']);

        $response = $this->controller->new();
        $this->assertEquals($validData['id'], $response);
    }

    #[DataProvider('invalidDataProvider')]
    public function testNewExpectException($invalidData)
    {
        $_POST = [
            'name' => $invalidData['name'],
            'tax_percentage' => $invalidData['tax_percentage'],
        ];

        $this->expectException(ApiException::class);
        $this->controller->new();
    }

    #[DataProvider('validDataProvider')]
    public function testFindById($validData)
    {
        $this->productTypeRepositoryMock
            ->expects($this->once())
            ->method('findById')
            ->with((int)$validData['id'])
            ->willReturn([]);

        $response = $this->controller->findById(['id' => (int)$validData['id']]);
        $this->assertIsArray($response);
    }

    public function testFindByIdExpectException()
    {
        $this->expectException(ApiException::class);
        $this->controller->findById(['id' => null]);
    }

    #[DataProvider('validDataProvider')]
    public function testUpdate($validData)
    {
        $this->productTypeRepositoryMock
            ->expects($this->once())
            ->method('findById')
            ->with((int)$validData['id'], true)
            ->willReturn($this->productTypeMock);

        $this->productTypeMock->id = $validData['id'];
        $this->productTypeMock->name = $validData['name'];
        $this->productTypeMock->taxPercentage = $validData['tax_percentage'];

        $this->productTypeRepositoryMock
            ->expects($this->once())
            ->method('update')
            ->with($this->productTypeMock)
            ->willReturn(true);

        $response = $this->controller->update(['id' => $validData['id'], 'PUT' => $validData]);
        $this->assertTrue($response);
    }

    #[DataProvider('invalidDataProvider')]
    public function testUpdateExpectException($invalidData)
    {
        $this->expectException(ApiException::class);
        $this->controller->update(['id' => $invalidData['id'], 'PUT' => $invalidData]);
    }

    #[DataProvider('validDataProvider')]
    public function testDelete($validData)
    {
        $this->productTypeRepositoryMock
            ->expects($this->once())
            ->method('hasProductsForType')
            ->with((int)$validData['id'])
            ->willReturn(false);

        $this->productTypeRepositoryMock
            ->expects($this->once())
            ->method('delete')
            ->with((int)$validData['id'])
            ->willReturn(true);

        $response = $this->controller->delete(['id' => (int)$validData['id']]);
        $this->assertTrue($response);
    }

    public function testDeleteExpectException()
    {
        $id = rand(1, 100);
        $this->productTypeRepositoryMock
            ->expects($this->once())
            ->method('hasProductsForType')
            ->with($id)
            ->willReturn(true);

        $this->expectException(ApiException::class);
        $this->controller->delete(['id' => $id]);
    }

    public function testDeleteInvalidId()
    {
        $this->expectException(ApiException::class);
        $this->controller->delete(['id' => null]);
    }
}
