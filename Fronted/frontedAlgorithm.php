<?php

require_once 'src/Task.php';


class FrondAlgr
{

    private $db;

    private function __construct()
    {
        $this->db = new User();
    }

    public function server () {
        if (isset($_POST['truncateButton'])) {
            $this->db->TruncateTodo();
        } elseif (isset($_POST['id'])) {
            $this->db->toggleTodoStatus((int)$_POST['id']);
        } else {
            $this->db->SaveUserTodo($_POST['todos']);
        }
        header('Location: View.php');
        exit();
    }

    public function complated () {
        $id = (int)$_GET['complated'];
        $this->db->StrikedUpdate($id, true);
        header('Location: View.php');
        exit();
    }

    public function uncomplated () {
        $id = (int)$_GET['uncomplated'];
        $this->db->StrikedUpdate($id, false);
        header('Location: View.php');
        exit();
    }

    public function kickplan () {
        $id = (int)$_GET['id'];
        $this->db->DeletePlanUser($id);
        header('Location: View.php');
        exit();
    }

    public function paganation () {
        $usersInfo = $this->db->SendAllUsers();

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

        return $currentItems;
    }

}