<?php

function uniIdExists($conn, $uniName) {
    $sql = "SELECT * FROM universities WHERE universityName = ?;";
    $stmt = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt, $sql)){
        header("location: ../index.php?error=stmtFailed");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "s", $uniName);
    mysqli_stmt_execute($stmt);

    $resultData = mysqli_stmt_get_result($stmt);

    $row = mysqli_fetch_assoc($resultData);

    if($row){
        return $row;
    }else{
        $result = false;
        return $result;
    }

    mysqli_stmt_close($stmt);
}

function selectUniversity($conn, $uniName) {
    $uniIdExists = uniIdExists($conn, $uniName);

    if($uniIdExists === false){
        header("location: ../index.php?uniName=" . $uniName . "&error=invalid");
        exit();
    }

    session_start();
    $_SESSION["uniId"] = $uniIdExists["universityID"];
    $_SESSION["uniName"] = $uniName;

    $sql = "SELECT * FROM universities WHERE universityName = ?;";
    $stmt = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt, $sql)){
        header("location: ../index.php?error=stmtFailed");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "s", $uniName);
    mysqli_stmt_execute($stmt);

    $resultData = mysqli_stmt_get_result($stmt);

    $row = mysqli_fetch_assoc($resultData);
    $uniId = $row["universityID"];

    //header("location: ../index.php?uniName=" . $uniName);
    header("location: ../view-classes.php?uniName=" . $uniName . "&uniId=". $uniId);
    exit();
}

if(isset($_POST["submit"])){
     $uniName = $_POST["uniName"];

    require_once 'dbh.inc.php';
    //require_once 'functions.inc.php';

    selectUniversity($conn, $uniName);
}else{
    header("location: ../index.php");
    exit();
}
