<?php

function invalidreviewsId($conn, $reviewsId) {
    $sql = "SELECT * FROM reviews WHERE reviewsId = ?;";
    $stmt = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt, $sql)){
        header("location: ../view-post.php?reviewsId=". $reviewsId . "&error=invalid");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "s", $reviewsId);
    mysqli_stmt_execute($stmt);

    $resultData = mysqli_stmt_get_result($stmt);

    if($row = mysqli_fetch_assoc($resultData)){
        return true;
    }else{
        return false;
    }
}

function createComment($conn, $userId, $reviewsId, $comment) {
    $sql = "INSERT INTO comments (commentUserId, commentReviewId, commentComment) VALUES (?, ?, ?);";
    $stmt = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt, $sql)){
        header("location: ../view-post.php?reviewsId=". $reviewsId . "&error=invalid");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "sss", $userId, $reviewsId, $comment);
    mysqli_stmt_execute($stmt);

    $sql = "SELECT * FROM reviews WHERE reviewsId = ?;";
    $stmt = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt, $sql)){
        header("location: ../view-post.php?reviewsId=". $reviewsId . "&error=invalid");
        exit();
    }
    mysqli_stmt_bind_param($stmt, "s", $reviewsId);
    mysqli_stmt_execute($stmt);
    $resultData = mysqli_stmt_get_result($stmt);
    if($row = mysqli_fetch_assoc($resultData)){
        $total = $row["reviewsTotalComments"];

        $total = $total + 1;

        $sql = "UPDATE reviews SET reviewsTotalComments=? WHERE reviewsId=?;";
        $stmt = mysqli_stmt_init($conn);
        if(!mysqli_stmt_prepare($stmt, $sql)){
            header("location: ../view-post.php?reviewsId=". $reviewsId . "&error=invalid");
            exit();
        }
        mysqli_stmt_bind_param($stmt, "ss", $total, $reviewsId);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        header("location: ../view-post.php?reviewsId=". $reviewsId . "&classId=" . $_POST["classId"] . "&className=" . $_POST["className"]);
     // header("location: create-review.php?classId=".$classId."&className=".$className);
    }else{
        header("location: ../view-post.php?reviewsId=". $reviewsId . "&error=invalid");
        exit();
    }
}

if(isset($_POST["submit"])){
    $userId = $_POST["userId"];
    $reviewsId = $_POST["reviewsId"];
    $comment = $_POST["commentComment"]; 

    require_once 'dbh.inc.php';
    //require_once 'functions.inc.php';

    if(invalidreviewsId($conn, $reviewsId) == false){
        header("location: ../view-post.php?reviewsId=". $reviewsId . "&error=invalid");
        exit();
    }

    createComment($conn, $userId, $reviewsId, $comment);
}else{
    header("location: ../view-post.php?reviewsId=". $reviewsId . "NOT_SET");
    exit();
}