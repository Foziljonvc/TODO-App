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

    public function StrikedUpdate(int $id, bool $number) 
    {
        $status = $number;
        $query = "UPDATE planuser SET status = :status WHERE id = :id";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':status', $status);
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

    public function TruncateTodo() 
    {
        $query = "TRUNCATE TABLE planuser";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
    }

    public function sendText(string $text) 
    {
        $query = "INSERT INTO telebot (`add`) VALUES (:add)";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':add', $text);
        $stmt->execute();
    }

    public function getText() 
    {
        $query = "SELECT `add` FROM telebot";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();

        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $results;
    }

    public function saveTeleText(string $text)
    {
        $query = "INSERT INTO planuser (todos, status) VALUES (:todos, :status)";
        $status = 0;
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':todos', $text);
        $stmt->bindParam(':status', $status);
        $stmt->execute();
    }

    public function deleteAddText()
    {
        $value = "add";

        $query = "DELETE FROM telebot WHERE `add` = :value";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':value', $value);
        $stmt->execute();
    }

    public function getAllTasks() 
    {
        $query = "SELECT todos FROM planuser";
        $stmt = $this->pdo->query($query);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function checkTask(int $checkNumber)
    {
        $status = 1;
    
        $stmtSub = $this->pdo->prepare("
            SELECT id 
            FROM planuser 
            ORDER BY id 
            LIMIT 1 OFFSET :offset
        ");
        $stmtSub->bindValue(':offset', $checkNumber, PDO::PARAM_INT);
        $stmtSub->execute();
        $result = $stmtSub->fetch(PDO::FETCH_ASSOC);
        
        if ($result) {
            $id = $result['id'];
            
            $stmt = $this->pdo->prepare("
                UPDATE planuser 
                SET status = :status 
                WHERE id = :id
            ");
            $stmt->bindValue(':status', $status, PDO::PARAM_INT);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
        }
    }
    
    public function saveCheck(string $text)
    {
        $query = "INSERT INTO telebot (`check`) VALUES (:check)";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':check', $text);
        $stmt->execute();
    }

    public function getCheck ()
    {
        $query = "SELECT `check` FROM telebot";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();

        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $results;
    }

    public function deleteCheck ()
    {
        $value = "check";

        $query = "DELETE FROM telebot WHERE `check` = :value";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':value', $value);
        $stmt->execute();
    }

    public function uncheckTask(int $checkNumber)
    {
        $status = 0;
    
        $stmtSub = $this->pdo->prepare("
            SELECT id 
            FROM planuser 
            ORDER BY id 
            LIMIT 1 OFFSET :offset
        ");
        $stmtSub->bindValue(':offset', $checkNumber, PDO::PARAM_INT);
        $stmtSub->execute();
        $result = $stmtSub->fetch(PDO::FETCH_ASSOC);
        
        if ($result) {
            $id = $result['id'];
            
            $stmt = $this->pdo->prepare("
                UPDATE planuser 
                SET status = :status 
                WHERE id = :id
            ");
            $stmt->bindValue(':status', $status, PDO::PARAM_INT);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
        }
    }

    public function saveUncheck (string $text)
    {
        $query = "INSERT INTO telebot (`uncheck`) VALUES (:uncheck)";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':uncheck', $text);
        $stmt->execute();
    }

    public function getUncheck ()
    {
        $query = "SELECT `uncheck` FROM telebot";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();

        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $results;
    }

    public function deleteUncheck ()
    {
        $value = "uncheck";

        $query = "DELETE FROM telebot WHERE `uncheck` = :value";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':value', $value);
        $stmt->execute();
    }

    public function saveDelete (string $text)
    {
        $query = "INSERT INTO telebot (delete) VALUES (:delete)";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':delete', $text);
        $stmt->execute();
    }

    public function getDelete ()
    {
        $query = "SELECT `delete` FROM telebot";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();

        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $results;
    }

    public function deleteTask ()
    {
        $value = "delete";

        $query = "DELETE FROM telebot WHERE `delete` = :value";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':value', $value);
        $stmt->execute();
    }

    public function deleteTaskUser(int $id)
    {
        $stmtSub = $this->pdo->prepare("
        SELECT id
        FROM planuser
        ORDER BY id
        LIMIT 1 OFFSET :offset
        ");
        $stmtSub->bindParam(':offset', $id);
        $stmtSub->execute();
    }
}
