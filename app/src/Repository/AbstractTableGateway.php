<?php

namespace App\Repository;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;

abstract class AbstractTableGateway
{
    /**
     * @var \Doctrine\DBAL\Connection
     */
    public $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * Set the name of database table
     *
     * @return string
     */
    abstract public function tableName();

    /**
     * Start a transaction
     */
    public function beginTransaction()
    {
        $this->connection->beginTransaction();
    }

    /**
     * Rollback a transaction
     */
    public function rollback()
    {
        $this->connection->rollBack();
    }

    /**
     * Commit a transaction
     */
    public function commit()
    {
        $this->connection->commit();
    }

    public function insert($data)
    {
        $this->connection->insert($this->tableName(), $data);
        return $this->connection->lastInsertId();
    }

    public function update($data, $identifier)
    {
        return $this->connection->update($this->tableName(), $data, $identifier);
    }

    public function delete($identifier)
    {
        return $this->connection->delete($this->tableName(), $identifier);
    }

    public function select($fields, $identifier, $groupBy = null, $orderByField = null, $order = null)
    {
        $queryBuilder = $this->connection->createQueryBuilder();
        $queryBuilder->select($fields);
        $queryBuilder->from($this->tableName());
        $this->setWhere($identifier, $queryBuilder);

        if ($groupBy != null) {
            $queryBuilder->groupBy($groupBy);
        }

        if ($orderByField != null) {
            $queryBuilder->orderBy($orderByField, $order);
        }

        return $this->executeQuery($queryBuilder);
    }

    public function all($fields = '*')
    {
        $queryBuilder = $this->connection->createQueryBuilder();
        $queryBuilder->select($fields);
        $queryBuilder->from($this->tableName());

        return $this->executeQuery($queryBuilder)->fetchAll();
    }


    public function selectIn($fields, $where, $in)
    {
        return $this->connection->executeQuery(
            "SELECT $fields FROM {$this->tableName()} WHERE $where IN(?)",
            [(array)$in],
            [\Doctrine\DBAL\Connection::PARAM_INT_ARRAY]
        );
    }

    public function count($identifier = [])
    {
        $queryBuilder = $this->connection->createQueryBuilder();
        $queryBuilder->select("COUNT(*)");
        $queryBuilder->from($this->tableName());
        $this->setWhere($identifier, $queryBuilder);
        return $this->executeQuery($queryBuilder)->fetchColumn();
    }

    /**
     * Get a QuyryBuilder to construct complex queries
     *
     * @return \Doctrine\DBAL\Query\QueryBuilder
     */
    public function queryBuilder()
    {
        return $this->connection->createQueryBuilder();
    }

    public function executeQuery(QueryBuilder $queryBuilder)
    {
        return $this->connection
            ->executeQuery(
                $queryBuilder->getSQL(),
                $queryBuilder->getParameters(),
                $queryBuilder->getParameterTypes()
            );
    }

    /**
     * Get the underlying connection object
     *
     * @return Connection
     */
    protected function connection()
    {
        return $this->connection;
    }

    public function setWhere($identifier, $queryBuilder)
    {
        if (empty($identifier)) {
            return;
        }

        $where = implode(" AND ", array_map(function ($key) {
            return "$key = :$key";
        }, array_keys($identifier)));
        $queryBuilder->where($where);
        $queryBuilder->setParameters($identifier);
    }
}
