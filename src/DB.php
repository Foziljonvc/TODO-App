<?php

declare(strict_types=1);

class DB 
{
    protected $pdo;

    public function __construct()
    {
        $this->pdo = new \PDO("mysql:host=localhost;dbname=MyTodo", "foziljonvc", "1220");
    }
}