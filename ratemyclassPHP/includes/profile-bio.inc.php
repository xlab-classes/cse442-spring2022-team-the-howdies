<?php 
if(isset($_POST["submit"])){
    $bio = $_POST["bio-input"];
    $id = $_POST["uId"];

    require_once 'dbh.inc.php';
    //require_once 'functions.inc.php';

    $sql = "UPDATE users SET usersBio=? WHERE usersId=?;";
    $stmt = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt, $sql)){
        echo "There was an error!";
        exit();
    }else{
        mysqli_stmt_bind_param($stmt, "ss", $bio, $id);
        mysqli_stmt_execute($stmt);
    }
    header("location: ../profile.php?success=success");
    exit();
}else{
    header("location: ../profile.php");
    exit();
}