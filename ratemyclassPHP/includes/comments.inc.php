<?php



if(isset($_POST["submit"])){
    $userId = $_POST["professor"];
    $review = $_POST["review"];
    $rating = $_POST["rating"];
    $classId = $_POST["classId"];
    $className = $_POST["className"];
    $ownerId = $_POST["ownerId"];

    require_once 'dbh.inc.php';
    //require_once 'functions.inc.php';

    if(){
        header();
        exit();
    }


}else{
    header();
    exit();
}