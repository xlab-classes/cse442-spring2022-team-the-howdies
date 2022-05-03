<?php

if(isset($_POST["submit"])){
    $reviewsId = $_POST["reviewsId"];
    $classId = $_POST["classId"];
    $rating = $_POST["rating"];

    require_once 'dbh.inc.php';

    $sql = "SELECT * FROM classes WHERE classesId = ?;";
    $stmt = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt, $sql)){
        header("location: ../my-reviews.php");
        exit();
    }
    mysqli_stmt_bind_param($stmt, "s", $classId);
    mysqli_stmt_execute($stmt);
    $resultData = mysqli_stmt_get_result($stmt);
    if($row = mysqli_fetch_assoc($resultData)){
        $total = $row["classesTotalReviews"];
        $totalScore = $row["classesRatingSum"];

        $total = $total - 1;
        $totalF = sprintf("%.2f", $total);
        $totalScore = $totalScore - $rating;
        $totalScoreF = sprintf("%.2f", $totalScore);
        $newAvg = $totalScoreF / $totalF;
        //$newAvg = 1.0;

        $sql = "UPDATE classes SET classesAvg=?, classesTotalReviews=?, classesRatingSum=? WHERE classesId=?;";
        $stmt = mysqli_stmt_init($conn);
        if(!mysqli_stmt_prepare($stmt, $sql)){
            header("location: ../my-reviews.php");
            exit();
        }
        mysqli_stmt_bind_param($stmt, "dsss", $newAvg, $total, $totalScore, $classId);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }else{
        header("location: ../my-reviews.php");
        exit();
    }

    $sql = "DELETE FROM reviews WHERE reviewsId=?;";
    $stmt = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt, $sql)){
        //error 
        exit();
    }
    mysqli_stmt_bind_param($stmt, "s", $reviewsId);
    mysqli_stmt_execute($stmt);

    //Delete favorites record with this
    /*$sql = "DELETE FROM dislikes WHERE dislikesReviewId=?;";
    $stmt = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt, $sql)){
        //error 
        exit();
    }
    mysqli_stmt_bind_param($stmt, "s", $dislikeId);
    mysqli_stmt_execute($stmt);*/

    header("location: ../my-reviews.php");
    exit();
}else{
    header("location: ../my-reviews.php?blurb=h");
    exit();
}