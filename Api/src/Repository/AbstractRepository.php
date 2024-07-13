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
                FROM " . $this->tableName . " 
                WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
        if ($fetchClass) {
            return $stmt->rowCount() > 0 ? $stmt->fetchObject(get_class($this->model)) : null;
        }

        return $stmt->rowCount() > 0 ? $stmt->fetch() : null;
    }

    /**
     * Find all data
     *
     * @param bool $fetchClass
     * @return array|null
     */
    public function findAll(bool $fetchClass = false): ?array
    {
        $sql = "SELECT * 
                FROM " . $this->tableName;
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();

        if ($fetchClass) {
            return $stmt->rowCount() > 0 ? $stmt->fetchAll(PDO::FETCH_CLASS, get_class($this->model)) : null;
        }

        return $stmt->rowCount() > 0 ? $stmt->fetchAll() : null;
    }

    /**
     * Find data by specific column
     *
     * @param string $columnName
     * @param string $value
     * @return array|null
     */
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

        $sql = "INSERT INTO " . $this->tableName . " (" . $fields . ") VALUES (" . $placeholders . ")";
        $stmt = $this->pdo->prepare($sql);

        foreach ($data as $key => $value) {
            $stmt->bindValue(':' . $key, $value);
        }

        $result = $stmt->execute();

        return $result ? (int)$this->pdo->lastInsertId() : null;
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


    /**
     * Delete data by id
     *
     * @param int $id
     * @return bool
     */
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