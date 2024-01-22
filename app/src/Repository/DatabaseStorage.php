<?php

namespace App\Repository;

use App\Repository\PDOFactory;

class DatabaseStorage
{

    /**
     * @var \PDO
     */
    private $pdo;


    public function __construct(PDOFactory $pdoFactory)
    {
        $dsn = 'mysql:host=mysql-hello_world:3306;dbname=hello_world_db';
        $username = 'root';
        $password = 'hello_world';
        $this->pdo = $pdoFactory->createPDO($dsn, $username, $password);
    }


    public function getCurrentNotes()
    {
        $query = "
        SELECT id,amount FROM atm order by id desc limit 1";

        $statement = $this->pdo->prepare($query);
        $statement->execute();

        return $statement->fetchObject();
    }


    public function insertNotesToATM($amount)
    {
        $statement = $this->pdo->prepare("
        INSERT INTO atm (
            amount
        ) VALUES (:amount)
    ");

        $statement->execute([
            "amount" => $amount,
        ]);

        return true;
    }


    public function updateCurrentNotes($id, $amount)
    {
        $statement = $this->pdo->prepare("
            UPDATE atm
            SET 
            amount=:amount
            WHERE id=:id 
        ");

        $statement->execute([
            "id"     => $id,
            "amount" => $amount,
        ]);

        return true;
    }


    public function removeAtmNotes($id)
    {
        $statement = $this->pdo->prepare("
            DELETE FROM atm 
            WHERE id=:id 
        ");

        $statement->execute([
            "id"     => $id,
        ]);

        return true;
    }
}
