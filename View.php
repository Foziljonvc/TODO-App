<?php

declare(strict_types=1);

require 'DB.php';

$db = new DB();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['truncateButton'])) {
        $db->TruncateTodo();
    } elseif (isset($_POST['id'])) {
        $db->toggleTodoStatus((int)$_POST['id']);
    } else {
        $db->SaveUserTodo();
    }
    header('Location: View.php');
    exit();
}

if (isset($_GET['complated'])) {
    $id = (int)$_GET['complated'];
    $db->StrikedUpdate($id, true);
    header('Location: View.php');
    exit();
}

if (isset($_GET['uncomplated'])) {
    $id = (int)$_GET['uncomplated'];
    $db->StrikedUpdate($id, false);
    header('Location: View.php');
    exit();
}

if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $db->DeletePlanUser($id);
    header('Location: View.php');
    exit();
}

$usersInfo = $db->SendAllUsers();

$itemsPerPage = 5;
$totalItems = count($usersInfo);
$totalPages = ceil($totalItems / $itemsPerPage);

$currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($currentPage < 1) {
    $currentPage = 1;
} elseif ($currentPage > $totalPages) {
    $currentPage = $totalPages;
}

$offset = ($currentPage - 1) * $itemsPerPage;

$currentItems = array_slice($usersInfo, $offset, $itemsPerPage);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>To-Do App</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .completed {
            text-decoration: line-through;
        }
        .large-checkbox {
            width: 35px;
            height: 35px;
        }
        .checkbox {
            width: 30px;
            height: 30px;
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
                            <?php foreach ($currentItems as $userInfo): ?>
                                <tr>
                                    <td class="<?php echo $userInfo['status'] ? 'completed' : ''; ?>">
                                        <?php echo htmlspecialchars($userInfo['todos']); ?>
                                    </td>
                                    <td>
                                        <form action="View.php" method="post">
                                            <input type="hidden" name="id" value="<?php echo $userInfo['id']; ?>">
                                            <input type="checkbox" class="checkbox" 
                                                   onChange="this.form.submit()" 
                                                   <?php if ($userInfo['status']) echo 'checked'; ?>>
                                        </form>
                                    </td>
                                    <td>
                                        <a href="View.php?id=<?php echo $userInfo['id']; ?>" class="btn btn-danger">Delete</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <div class="d-flex justify-content-between align-items-center">
                        <form action="View.php" method="post">
                            <button type="submit" class="btn btn-primary" name="truncateButton">Truncate</button>
                        </form>

                        <nav aria-label="Page navigation example">
                            <ul class="pagination mb-0">
                                <li class="page-item <?php if($currentPage <= 1) echo 'disabled'; ?>">
                                    <a class="page-link" href="<?php if($currentPage > 1) echo '?page='.($currentPage - 1); else echo '#'; ?>" aria-label="Previous">
                                        <span aria-hidden="true">&laquo;</span>
                                    </a>
                                </li>
                                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                    <li class="page-item <?php if($i == $currentPage) echo 'active'; ?>">
                                        <a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                                    </li>
                                <?php endfor; ?>
                                <li class="page-item <?php if($currentPage >= $totalPages) echo 'disabled'; ?>">
                                    <a class="page-link" href="<?php if($currentPage < $totalPages) echo '?page='.($currentPage + 1); else echo '#'; ?>" aria-label="Next">
                                        <span aria-hidden="true">&raquo;</span>
                                    </a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
