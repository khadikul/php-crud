<?php
require_once('inc/functions.php');
$info = "";
$task = $_GET['task'] ?? 'report';
$error = $_GET['error'] ?? '0';

if('delete' == $task){
    $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
    if($id>0){
        deleteStudnet($id);
        header('location: index.php?task=report');
    }
}

if('seed' == $task){
    seed();
    $info = "Seeding Is Completed";
}

$fname = '';
$lname = '';
$roll = '';

if(isset($_POST['submit'])){
    $fname = filter_input(INPUT_POST, 'fname', FILTER_SANITIZE_STRING);
    $lname = filter_input(INPUT_POST, 'lname', FILTER_SANITIZE_STRING);
    $roll = filter_input(INPUT_POST, 'roll', FILTER_SANITIZE_NUMBER_INT);
    $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);

    if($id){
        //update the existing student
        if($fname != '' && $lname != '' && $roll != ''){
            $dcRollChecker = updateStudent($id, $fname, $lname, $roll);
            if($dcRollChecker){
                header('location: index.php?task=report');
            }else{
                $error = 1;
            }
        }
    }else{
        //add a new student
        if($fname != '' && $lname != '' && $roll != ''){
            $dcRollChecker = add_student($fname, $lname, $roll);
            if($dcRollChecker){
                header('location: index.php?task=report');
            }else{
                $error = 1;
            }
        }
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="Assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="Assets/css/style.css">
    <title>Document</title>
</head>
<body>
<div class="container">
    <div class="row">
        <div class="col-lg-8 offset-2 mt-5">
            <h2>Crud Project</h2>
            <p>A Simple Project to CRUD Operations Using Plain File And PHP</p>
            <?php include 'inc/template/nav.php'; ?>
            <hr>
            <?php 
                if($info){
                    printf("<p>%s</p>", $info);
                } 
            ?>
        </div>
    </div>

    <?php
        if('1' == $error) :?>
            <div class="row">
                <div class="col-lg-10">
                    <pre class="text-left">
                        Dublicate Roll Number.Please check and input a new roll number!
                    </pre>
                </div>
            </div>
    <?php endif; ?>

    <?php
        if('report' == $task) :?>
        <div class="row">
            <div class="col-lg-8 offset-2">
                <?php genarateRepot(); ?>
            </div>
        </div>
    <?php endif;?>

    <?php
        if('add' == $task){
            ?>
                <div class="add_student">
                    <form action="index.php?task=add" method="POST">
                        <div class="form-group">
                            <label for="first-name" class="col-form-label">First Name</label>
                            <input type="text" class="form-control" id="fname" name="fname" value="<?php echo $fname ?>">
                        </div>
                        <div class="form-group">
                            <label for="last-name" class="col-form-label">Last Name</label>
                            <input type="text" class="form-control" id="lname" name="lname" value="<?php echo $lname ?>"></input>
                        </div>
                        <div class="form-group">
                            <label for="roll" class="col-form-label">Roll</label>
                            <input type="number" class="form-control" id="roll" name="roll" value="<?php echo $roll ?>"></input>
                        </div>
                        <button class="btn btn-success" name="submit">Save</button>
                    </form>
                </div>
            <?php
        } 
    ?>

    <?php
        if('edit' == $task):
            $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_STRING);
            $student = get_student($id);
            if($student):
                ?>
                    <div class="add_student">
                        <form method="POST">
                            <input type="hidden" name="id" value="<?php echo $id ?>">
                            <div class="form-group">
                                <label for="first-name" class="col-form-label">First Name</label>
                                <input type="text" class="form-control" id="fname" name="fname" value="<?php echo $student['fname'] ?>">
                            </div>
                            <div class="form-group">
                                <label for="last-name" class="col-form-label">Last Name</label>
                                <input type="text" class="form-control" id="lname" name="lname" value="<?php echo $student['lname'] ?>"></input>
                            </div>
                            <div class="form-group">
                                <label for="roll" class="col-form-label">Roll</label>
                                <input type="number" class="form-control" id="roll" name="roll" value="<?php echo $student['roll'] ?>"></input>
                            </div>
                            <button class="btn btn-success" name="submit">Update</button>
                        </form>
                    </div>
                <?php
            endif;
        endif;
    ?>
             
</div>
<script src="Assets/js/bootstrap.min.js"></script>
<script src="Assets/js/main.js"></script>
</body>
</html>