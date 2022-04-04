<?php 
if(isset($_POST["submit"])){
    $title = $_POST["title"];
    $professor = $_POST["professor"];
    $review = $_POST["review"];
    $rating = $_POST["rating"];
    $classId = $_POST["classId"];
    $className = $_POST["className"];
    $ownerId = $_POST["ownerId"];

    require_once 'dbh.inc.php';
    require_once 'functions.inc.php';

    if(invalidClassId($conn, $classId, $className) !== false){
        header("location: ../create-review.php?className=". $className . "&classId=" . $classId . "&error=invalid");
        exit();
    }

    createReview($conn, $title, $professor, $review, $rating, $classId, $ownerId, $className);
}else{
    header("location: ../index.php");
    exit();
}
