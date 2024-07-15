<?php

declare(strict_types=1);

namespace Src\Repository;

use Exception;
use PDO;
use Src\Model\AbstractModel;

abstract class AbstractRepository
{
    /**
     * @param PDO $pdo
     */
    public function __construct(protected PDO $pdo)
    {
    }

    /**
     * Create new model instance
     *
     * @return AbstractModel
     */
    abstract protected function createModelInstance(): AbstractModel;

    /**
     * Get table name
     *
     * @return string
     */
    abstract protected function getTableName(): string;


    /**
     * Find data by id
     *
     * @param int $id
     * @param bool $fetchClass
     * @return AbstractModel|array|null
     */
    public function findById(int $id, bool $fetchClass = false): AbstractModel|array|null
    {
        $sql = "SELECT * 
                FROM " . $this->getTableName() . " 
                WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':id', $id);
        $stmt->execute();
        if ($fetchClass) {
            return $stmt->fetchObject(get_class($this->createModelInstance()));
        }

        return $stmt->fetch();
    }

    /**
     * Find all data
     *
     * @param bool $fetchClass
     * @return array
     */
    public function findAll(bool $fetchClass = false): array
    {
        $sql = "SELECT * 
                FROM " . $this->getTableName();
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();

        if ($fetchClass) {
            return $stmt->fetchAll(PDO::FETCH_CLASS, get_class($this->createModelInstance()));
        }

        return $stmt->fetchAll();
    }

    /**
     * Find data by specific column
     *
     * @param string $columnName
     * @param string|int $value
     * @return array
     */
    public function findByColumn(string $columnName, string|int $value): array
    {
        $sql = "SELECT * 
                FROM " . $this->getTableName() . " 
                WHERE " . $columnName . " LIKE :value";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':value', '%' . $value . '%');
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_CLASS, $this->createModelInstance());
    }

    /**
     * Create new data
     *
     * @param AbstractModel $model
     * @return int|null
     */
    public function create(AbstractModel $model): ?int
    {
        $data = $model->toArray();
        unset($data['id']);

        $fields = implode(', ', array_keys($data));
        $placeholders = ':' . implode(', :', array_keys($data));

        $sql = "INSERT INTO " . $this->getTableName() . " (" . $fields . ") VALUES (" . $placeholders . ")";
        $stmt = $this->pdo->prepare($sql);

        foreach ($data as $key => $value) {
            $stmt->bindValue(':' . $key, $value);
        }

        $result = $stmt->execute();

        return $result ? (int)$this->pdo->lastInsertId() : null;
    }

    /**
     * Create multiple data in one request
     *
     * @param array<AbstractModel> $models
     * @return array
     * @throws Exception
     */
    public function bulkCreate(array $models): array
    {
        if (empty($models)) {
            return [];
        }

        if (!$this->pdo->inTransaction()) {
            $this->pdo->beginTransaction();
        }

        $lastInsertIds = [];

        try {
            foreach ($models as $model) {
                $lastInsertIds[] = $this->create($model);
            }
        } catch (Exception $e) {
            $this->pdo->rollBack();
            throw $e;
        }
        return $lastInsertIds;
    }

    /**
     * Update data
     *
     * @param AbstractModel $model
     * @return bool
     */
    public function update(AbstractModel $model): bool
    {
        $data = $model->toArray();

        $fields = '';
        foreach ($data as $key => $value) {
            $fields .= $key . ' = :' . $key . ', ';
        }

        $fields = rtrim($fields, ', ');

        $sql = "UPDATE " . $this->getTableName() .
            " SET " . $fields .
            " WHERE id = :id";

        $stmt = $this->pdo->prepare($sql);
        foreach ($data as $key => $value) {
            $stmt->bindValue(':' . $key, $value);
        }

        $stmt->bindValue(':id', $data['id']);
        $stmt->execute();

        return $stmt->rowCount() > 0;
    }


    /**
     * Delete data by id
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        $sql = "DELETE 
                FROM " . $this->getTableName() . " 
                WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':id', $id);
        $stmt->execute();

        return $stmt->rowCount() > 0;
    }

    /**
     * Delete data by specific column and value
     *
     * @param string $columnName
     * @param string|int $value
     * @return bool
     */
    public function deleteByColumn(string $columnName, string|int $value): bool
    {
        $sql = "DELETE 
                FROM " . $this->getTableName() . " 
                WHERE " . $columnName . " = :value";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':value', $value);
        $stmt->execute();

        return $stmt->rowCount() > 0;
    }
}