<?php

declare(strict_types=1);

class DB 
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = new PDO("mysql:host=localhost;dbname=MyTodo", "foziljonvc", "1220");
    }

    public function SaveUserTodo() 
    {
        if (isset($_POST['input']) && !empty($_POST['input'])) {
            $query = "INSERT INTO planuser (todos, status) VALUES (:todos, :status)";
            $status = 0;
            $stmt = $this->pdo->prepare($query);
            $stmt->bindParam(':todos', $_POST['input']);
            $stmt->bindParam(':status', $status);
            $stmt->execute();
        }
    }

    public function SendAllUsers() 
    {
        $query = "SELECT * FROM planuser";
        $stmt = $this->pdo->query($query);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function DeletePlanUser(int $id)
    {
        $query = "DELETE FROM planuser WHERE id = :id";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
    }

    public function StrikedUpdate (int $id, bool $number) 
    {
        $status = $number;
        $query = "UPDATE planuser SET status = :status WHERE id = :id";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':status', $status, PDO::PARAM_BOOL);
        $stmt->execute();
    }
    
    public function toggleTodoStatus($id)
    {
        $stmt = $this->pdo->prepare('SELECT status FROM planuser WHERE id = ?');
        $stmt->execute([$id]);
        $todo = $stmt->fetch();
        $newStatus = $todo['status'] ? 0 : 1;

        $stmt = $this->pdo->prepare('UPDATE planuser SET status = ? WHERE id = ?');
        $stmt->execute([$newStatus, $id]);
    }

    public function TruncateTodo () 
    {
        $query = "TRUNCATE TABLE planuser";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
    }
}