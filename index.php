<?php

include_once("Connections/Database.php");

if (isset($_POST['addtodo'])) {
    $todo = $_POST['todo'];
    $insertTargetHistory = $db->prepare("INSERT INTO `tbl_todo`(todo) VALUES(:todo)");
    $result = $insertTargetHistory->execute(array(":todo" => $todo));

    if ($result) {
        header('location: index.php');
    } else {
        echo "Error Inserting Data";
    }
}

if (isset($_POST['edittodo'])) {
    $todo = $_POST['todo'];
    $todoid = $_POST['todoid'];
    $insertTargetHistory = $db->prepare("UPDATE `tbl_todo`SET todo=:todo WHERE id=:todoid");
    $result = $insertTargetHistory->execute(array(":todo" => $todo, ":todoid" => $todoid));

    if ($result) {
        header('location: index.php');
    } else {
        echo "Error Updating Data";
    }
}

if (isset($_GET['del_todo'])) {
    $del_todo = $_GET['del_todo'];
    $deleteQuery = $db->prepare("DELETE FROM `tbl_todo` WHERE id=:del_todo");
    $results = $deleteQuery->execute(array(':del_todo' => $del_todo));
    header('location: index.php');
}

$query_todo = $db->prepare("SELECT * FROM tbl_todo ");
$query_todo->execute();
$row_todo = $query_todo->fetch();
$totalTodo = $query_todo->rowCount();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.4.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/css/all.min.css">
    <link rel="stylesheet" href="style.css">
    <title>Simple Todo App </title>
</head>

<body>

    <div class="container">
        <?php
        if (isset($_GET['edit_todo'])) {
            $edit_todo = $_GET['edit_todo'];
            $query_todo = $db->prepare("SELECT *  FROM tbl_todo WHERE id='$edit_todo' ");
            $query_todo->execute();
            $row_todo = $query_todo->fetch();
            $totalTodo = $query_todo->rowCount();
            $todo = $row_todo['todo'];
            $todoid = $row_todo['id'];
        ?>
            <div id="myform" align="center">
                <h4>Edit Todo</h4>
                <form action="" method="post">
                    <div class="col-md-6">
                        <div class="form-group">
                            <input type="hidden" name="todoid" value="<?= $todoid ?>">
                            <input type="text" name="todo" id="todo" class="form-control" value="<?= $todo ?>" required>
                        </div>
                    </div>
                    <div class="list-inline">
                        <button type="submit" class="btn btn-primary btn-sm" id="edittodo" name="edittodo">
                            Save
                        </button>
                    </div>
                </form>
            </div>
        <?php
        } else {
        ?>
            <div id="myform" align="center">
                <h4>Add Todo</h4>
                <form action="" method="post">
                    <div class="col-md-6">
                        <div class="form-group">
                            <input type="text" name="todo" id="todo" class="form-control" required>
                        </div>
                    </div>
                    <div class="list-inline">
                        <button type="submit" class="btn btn-primary btn-sm" id="addtodo" name="addtodo">
                            Save
                        </button>
                    </div>
                </form>
            </div>
        <?php
        }
        ?>
        <div id="todolist" class="col-md-12" style="margin-top: 20px">
            <div class="table-responsive">
                <table class="table table-success">
                    <caption>Todo List</caption>
                    <thead class="thead-dark">
                        <tr>
                            <th style="width: 10%">#</th>
                            <th style="width: 70%">Todo Activity</th>
                            <th style="width: 10%">Edit</th>
                            <th style="width: 10%">Delete</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $counter = 0;
                        if ($totalTodo > 0) {
                            do {
                                $counter++;
                        ?>
                                <tr>
                                    <td><?= $counter ?></td>
                                    <td><?= $row_todo['todo']; ?></td>
                                    <td>
                                        <a href="index.php?edit_todo=<?= $row_todo['id']; ?>">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    </td>
                                    <td>
                                        <a href="index.php?del_todo=<?= $row_todo['id']; ?>">
                                            <i class="fas fa-trash" style="color: red"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php
                            } while ($row_todo = $query_todo->fetch());
                        } else {
                            ?>
                            <tr>
                                <td colspan="3"> No Records Found </td>
                            </tr>
                        <?php
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.4.1/js/bootstrap.min.js"></script>
</body>

</html>