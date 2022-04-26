<?php

function invalidReviewId($conn, $reviewId) {
    $sql = "SELECT * FROM reviews WHERE reviewsId = ?;";
    $stmt = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt, $sql)){
        header("location: ../view-post.php?reviewId=". $reviewId . "&error=invalid");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "s", $reviewId);
    mysqli_stmt_execute($stmt);

    $resultData = mysqli_stmt_get_result($stmt);

    if($row = mysqli_fetch_assoc($resultData)){
        return true;
    }else{
        return false;
    }
}

function createComment($conn, $userId, $reviewId, $comment) {
    $sql = "INSERT INTO comments (commentUserId, commentReviewId, commentComment) VALUES (?, ?, ?);";
    $stmt = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt, $sql)){
        header("location: ../view-post.php?reviewId=". $reviewId . "&error=invalid");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "sss", $userId, $reviewId, $comment);
    mysqli_stmt_execute($stmt);

    $sql = "SELECT * FROM reviews WHERE reviewsId = ?;";
    $stmt = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt, $sql)){
        header("location: ../view-post.php?reviewId=". $reviewId . "&error=invalid");
        exit();
    }
    mysqli_stmt_bind_param($stmt, "s", $reviewId);
    mysqli_stmt_execute($stmt);
    $resultData = mysqli_stmt_get_result($stmt);
    if($row = mysqli_fetch_assoc($resultData)){
        $total = $row["reviewsTotalComments"];

        $total = $total + 1;

        $sql = "UPDATE reviews SET reviewsTotalComments=? WHERE reviewsId=?;";
        $stmt = mysqli_stmt_init($conn);
        if(!mysqli_stmt_prepare($stmt, $sql)){
            header("location: ../view-post.php?reviewId=". $reviewId . "&error=invalid");
            exit();
        }
        mysqli_stmt_bind_param($stmt, "ss", $total, $reviewId);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }else{
        header("location: ../view-post.php?reviewId=". $reviewId . "&error=invalid");
        exit();
    }
}

if(isset($_POST["submit"])){
    session_start();
    $userId = $_SESSION["userid"];
    $reviewId = $_POST["reviewId"];
    $comment = $_POST["comment"]; 

    require_once 'dbh.inc.php';
    //require_once 'functions.inc.php';

    if(invalidReviewId($conn, $reviewId) == false){
        header("location: ../view-post.php?reviewId=". $reviewId . "&error=invalid");
        exit();
    }

    createComment($conn, $userId, $reviewId, $comment);
}else{
    header("location: ../view-post.php?reviewId=". $reviewId);
    exit();
}