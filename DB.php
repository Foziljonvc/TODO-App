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
}
?>
