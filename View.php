<?php

declare(strict_types=1);

require 'DB.php';

$db = new DB();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $db->SaveUserTodo();
    header('Location: View.php');
    exit();
}

if (isset($_GET['id'])) {
    $id = (int) $_GET['id'];
    $db->DeletePlanUser($id);
    header('Location: View.php');
    exit();
}

$usersInfo = $db->SendAllUsers();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>To-Do App</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .large-checkbox {
            width: 35px;
            height: 35px;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <div class="card">
            <div class="card-header">
                <h3>To-Do App</h3>
            </div>
            <div class="card-body">
                <form action="View.php" method="post" class="mb-3">
                    <div class="input-group">
                        <input type="text" name="input" class="form-control" placeholder="Enter your plan">
                        <div class="input-group-append">
                            <button type="submit" class="btn btn-primary">Add</button>
                        </div>
                    </div>
                </form>
                <div>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th scope="col">Plan</th>
                                <th scope="col">Status</th>
                                <th scope="col">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($usersInfo as $userInfo): ?>
                                <tr>
                                    <th scope="row"><?php 
                                        echo $userInfo['todos']; ?></th>
                                    <td>
                                        <input type="checkbox" name="checkbox" class="large-checkbox">
                                    </td>
                                    <td>
                                        <a href="View.php?id=<?php echo $userInfo['id']; ?>" class="btn btn-danger">Delete</a>
                                    </td>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
