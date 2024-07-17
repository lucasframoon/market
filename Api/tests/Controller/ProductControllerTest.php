<?php

namespace Controller;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Src\Controller\AbstractController;
use Src\Controller\ProductController;
use Src\Exception\ApiException;
use Src\Model\Product;
use Src\Model\ProductType;
use Src\Repository\ProductRepository;
use Src\Repository\ProductTypeRepository;

class ProductControllerTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->productRepositoryMock = $this->createMock(ProductRepository::class);
        $this->productTypeRepositoryMock = $this->createMock(ProductTypeRepository::class);
        $this->productMock = $this->createMock(Product::class);
        $this->productTypeMock = $this->createMock(ProductType::class);
        $this->abstractControllerMock = $this->getMockBuilder(AbstractController::class)
            ->onlyMethods(['validateInput'])
            ->getMock();

        $this->controller = new ProductController($this->productRepositoryMock, $this->productTypeRepositoryMock);
    }

    public static function validDataProvider(): array
    {
        return [
            [[  'id' => rand(1, 100),
                'name' => 'test',
                'price' => (float)rand(),
                'type_id' => rand(),
                'description' => 'description'
            ]],
            [[  'id' => rand(1, 100),
                'name' => 'test2',
                'price' => '1.1',
                'type_id' => rand(1, 100),
                'description' => 'description2'
            ]],
            [[  'id' => rand(1, 100),
                'name' => 'test3',
                'price' => (float)rand(),
                'type_id' => '1',
                'description' => 'description3'
            ]],
            [[  'id' => rand(1, 100),
                'name' => 'test 4',
                'price' => (float)rand(1, 100),
                'type_id' => rand(1, 100),
                'description' => 'description 4'
            ]]
        ];
    }

    public static function invalidDataProvider(): array
    {
        return [
            [[
                'name' => '',
                'price' => 1.1,
                'type_id' => 1
            ]],
            [[
                'name' => 'test',
                'price' => '1,1',
                'type_id' => 1
            ]],
            [[
                'name' => 'test',
                'price' => 1.1,
                'type_id' => ''
            ]],
            [[
                'name' => 'test',
                'price' => 1.1,
                'type_id' => '1.1'
            ]],
            [[
                'id' => 'id',
                'name' => 'test'
            ]],
            [[
                'id' => 1.1,
                'name' => 'test',
                'type_id' => null
            ]],
            [[
                'price' => -1,
                'id' => 1.1,
                'name' => 'test'
            ]]
        ];
    }

    #[DataProvider('validDataProvider')]
    public function testNew($validData)
    {
        $_POST = [
            'name' => $validData['name'],
            'price' => $validData['price'],
            'type_id' => $validData['type_id'],
            'description' => $validData['description']
        ];

        $this->productTypeMock = $this->createMock(ProductType::class);
        $this->productTypeMock->id = 1;
        $this->productTypeRepositoryMock
            ->expects($this->once())
            ->method('findById')
            ->with($_POST['type_id'], true)
            ->willReturn($this->productTypeMock);

        $this->productTypeMock->id = 1;

        $this->productRepositoryMock
            ->expects($this->once())
            ->method('create')
            ->willReturn(1);

        $response = $this->controller->new();
        $this->assertEquals(1, $response);
    }

    #[DataProvider('invalidDataProvider')]
    public function testNewExpectException($invalidData)
    {
        $_POST = [
            'name' => ($validData['name'] ?? null),
            'price' => ($validData['price'] ?? null),
            'type_id' => ($validData['type_id'] ?? null),
            'description' => ($validData['description'] ?? null)
        ];

        $this->expectException(ApiException::class);
        $this->controller->new();
    }

    public function testFindAllWithDetails()
    {
        $this->productRepositoryMock
            ->expects($this->once())
            ->method('findAll')
            ->willReturn([]);

        $response = $this->controller->findAllWithDetails();
        $this->assertIsArray($response);
    }

    #[DataProvider('validDataProvider')]
    public function testUpdate($validData)
    {
        $this->productRepositoryMock
            ->expects($this->once())
            ->method('findById', true)
            ->with((int)$validData['id'])
            ->willReturn($this->productMock);

        $this->productTypeRepositoryMock
            ->expects($this->once())
            ->method('findById')
            ->with((int)$validData['type_id'])
            ->willReturn($this->productTypeMock);

        $this->productRepositoryMock
            ->expects($this->once())
            ->method('update')
            ->with($this->productMock)
            ->willReturn(true);

        $response = $this->controller->update(['id' => $validData['id'], 'PUT' => $validData]);
        $this->assertTrue($response);
    }

    #[DataProvider('validDataProvider')]
    public function testDelete($validData)
    {
        $this->productRepositoryMock
            ->expects($this->once())
            ->method('delete')
            ->with((int)$validData['id'])
            ->willReturn(true);

        $response = $this->controller->delete(['id' => $validData['id']]);
        $this->assertTrue($response);
    }

    #[DataProvider('invalidDataProvider')]
    public function testDeleteExpectException($invalidData)
    {
        $this->expectException(ApiException::class);
        $this->controller->delete(['id' => $invalidData]);
    }

    #[DataProvider('validDataProvider')]
    public function testFindById($validData)
    {
        $this->productRepositoryMock
            ->expects($this->once())
            ->method('findById')
            ->with((int)$validData['id'])
            ->willReturn([]);

        $response = $this->controller->findById(['id' => (int)$validData['id']]);
        $this->assertIsArray($response);
    }

    #[DataProvider('invalidDataProvider')]
    public function testFindByIdExpectException($invalidData)
    {
        $this->expectException(ApiException::class);
        $this->controller->findById(['id' => $invalidData]);
    }
}
