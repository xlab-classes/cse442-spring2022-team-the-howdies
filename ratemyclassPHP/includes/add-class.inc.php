<?php 
function invalidClassName($conn, $className, $uniId, $uniName){
    $sql = "SELECT * FROM classes WHERE classesName = ? AND classesUniId = ?;";
    $stmt = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt, $sql)){
        header("location: ../view-classes.php?uniId=". $uniId . "&uniName=" . $uniName . "&error=invalid");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "ss", $className, $uniId);
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

function createClass($conn, $className, $uniId, $uniName){
    $sql = "INSERT INTO classes (classesRatingSum, classesTotalReviews, classesAvg, classesUniId, classesName) VALUES (?, ?, ?, ?, ?);";
    $stmt = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt, $sql)){
        header("location: ../view-classes.php?uniId=". $uniId . "&uniName=" . $uniName . "&error=invalid");
        exit();
    }

    $z = 0;
    $z2 = 0.0;

    mysqli_stmt_bind_param($stmt, "sssss", $z, $z, $z2, $uniId, $className);
    mysqli_stmt_execute($stmt);
}

if(isset($_POST["submit"])){
    $className = $_POST["newClassName"];
    $uniId = $_POST["uniId"];
    $uniName = $_POST["uniName"];

    require_once 'dbh.inc.php';
    //require_once 'functions.inc.php';

    if(invalidClassName($conn, $className, $uniId, $uniName)){
        header("location: ../view-classes.php?uniId=". $uniId . "&uniName=" . $uniName . "&error=nameTaken");
        exit();
    }

    createClass($conn, $className, $uniId, $uniName);
//     header("location: ../create-review.php?className=". $className . "&classId=" . $classId);
    header("location: ../view-classes.php?uniId=". $uniId . "&uniName=" . $uniName . "&success=true");
    exit();
}else{
    header("location: ../index.php");
    exit();
}