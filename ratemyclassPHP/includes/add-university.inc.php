<?php 
function invalidUniversityName($conn, $uniName){
    $sql = "SELECT * FROM universities WHERE universityName = ?;";
    $stmt = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt, $sql)){
        header("location: ../index.php?error=stmtFailed");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "s", $uniName);
    mysqli_stmt_execute($stmt);

    $resultData = mysqli_stmt_get_result($stmt);

    $resultLength = mysqli_num_rows($resultData);

    if($resultLength > 0){
        return true;
    }else{
        return false;
    }

    //mysqli_stmt_close($stmt);
}

function createUniversity($conn, $uniName){
    $sql = "INSERT INTO universities (universityName) VALUES (?);";
    $stmt = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt, $sql)){
        header("location: ../index.php?uniName=" . $uniName . "&error=stmtFailed");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "s", $uniName);
    mysqli_stmt_execute($stmt);
}

if(isset($_POST["submit"])){
    $uniName = $_POST["uniName"];

    require_once 'dbh.inc.php';
    //require_once 'functions.inc.php';

    if(invalidUniversityName($conn, $uniName)){
        header("location: ../index.php?uniName=" . $uniName . "&error=nameTaken");
        exit();
    }

    createUniversity($conn, $uniName);
//     header("location: ../create-review.php?className=". $className . "&classId=" . $classId);
    header("location: ../index.php?success=true");
    exit();
}else{
    header("location: ../index.php");
    exit();
}