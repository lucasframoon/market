<?php

namespace Src\Repository;

use PDO;
use Src\Model\AbstractModel;

abstract class AbstractRepository
{

    protected string $tableName;
    protected AbstractModel $model;

    /**
     * @param PDO $pdo
     */
    public function __construct(protected PDO $pdo)
    {
    }

    public function findById(int $id): ?AbstractModel
    {
        $sql = "SELECT * 
                FROM " . $this->tableName . " 
                WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $id]);

        return $stmt->rowCount() > 0 ? $stmt->fetchObject(get_class($this->model)) : null;
    }

    public function findAll(): ?array
    {
        $sql = "SELECT * 
                FROM " . $this->tableName;
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();

        return $stmt->rowCount() > 0 ? $stmt->fetchAll(PDO::FETCH_CLASS, get_class($this->model)) : null;
    }

    public function findByColumn(string $columnName, string $value): ?array
    {
        $sql = "SELECT * 
                FROM " . $this->tableName . " 
                WHERE {$columnName} LIKE :value";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':value', '%' . $value . '%');
        $stmt->execute();

        return $stmt->rowCount() > 0 ? $stmt->fetchAll(PDO::FETCH_CLASS, $this->model) : null;
    }

    public function create(AbstractModel $model): ?int
    {
        $data = $model->toArray();

        $fields = implode(', ', array_keys($data));
        $placeholders = ':' . implode(', :', array_keys($data));

        $sql = "INSERT INTO " . $this->tableName . " (" . $fields . ") VALUES (" . $placeholders . ")";
        $stmt = $this->pdo->prepare($sql);

        foreach ($data as $key => $value) {
            $stmt->bindValue(':' . $key, $value);
        }

        $result = $stmt->execute();

        return $result ? (int)$this->pdo->lastInsertId() : null;
    }

    public function update(AbstractModel $model): bool
    {
        $data = $model->toArray();

        $fields = '';
        foreach ($data as $key => $value) {
            $fields .= $key . ' = :' . $key . ', ';
        }

        $fields = rtrim($fields, ', ');

        $sql = "UPDATE " . $this->tableName . 
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

    public function delete(int $id): bool
    {
        $sql = "DELETE 
                FROM " . $this->tableName . " 
                WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $id]);

        return $stmt->rowCount() > 0;
    }
}