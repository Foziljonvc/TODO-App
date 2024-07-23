<?php

declare(strict_types=1);

require_once 'src/Task.php';

$frondAlgr = new FrondAlgr();

$db = new User();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $frondAlgr->server();
}

if (isset($_GET['complated'])) {
    $frondAlgr->complated();
}

if (isset($_GET['uncomplated'])) {
    $frondAlgr->uncomplated();
}

if (isset($_GET['id'])) {
    $frondAlgr->kickplan();
}

$currentItems = $frondAlgr->paganation();
?>

<?php include_once "fronted/htmlHead.php"; ?>

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

<?php include_once "ronted/htmlfooter.php"; ?>