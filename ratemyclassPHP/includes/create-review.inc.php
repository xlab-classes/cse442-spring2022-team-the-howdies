<?php 

function invalidClassId($conn, $classId, $className){
    $sql = "SELECT * FROM classes WHERE classId = ?;";
    $stmt = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt, $sql)){
        header("location: ../create-review.php?className=". $className . "&classId=" . $classId . "&error=invalid");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "s", $classId);
    mysqli_stmt_execute($stmt);

    $resultData = mysqli_stmt_get_result($stmt);

    if($row = mysqli_fetch_assoc($resultData)){
        return true;
    }else{
        return false;
    }

    mysqli_stmt_close($stmt);
}

function createReview($conn, $title, $professor, $review, $rating, $classId, $ownerId, $className){
    $sql = "INSERT INTO reviews (reviewsTitle, reviewsProfessor, reviewsReview, reviewsRating, reviewsClassId, reviewsOwnerId, reviewsLikes, reviewsDislikes) VALUES (? ? ? ? ? ? ? ?);";
    $stmt = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt, $sql)){
        header("location: ../create-review.php?className=". $className . "&classId=" . $classId . "&error=invalid");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "sssiii", $title, $professor, $review, $rating, $classId, $ownerId, 0, 0);
    mysqli_stmt_execute($stmt);

    mysqli_stmt_close($stmt);

    $sql = "SELECT * FROM classes WHERE classId = ?;";
    $stmt = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt, $sql)){
        header("location: ../create-review.php?className=". $className . "&classId=" . $classId . "&error=invalid");
        exit();
    }
    mysqli_stmt_bind_param($stmt, "i", $classId);
    mysqli_stmt_execute($stmt);

    $resultData = mysqli_stmt_get_result($stmt);
    if($row != mysqli_fetch_assoc($resultData)){
        header("location: ../create-review.php?className=". $className . "&classId=" . $classId . "&error=invalid");
        exit();
    }else{
        $total = $row["classesTotalReviews"];
        $totalScore = $row["classesRatingSum"];

        $total = $total + 1;
        $totalF = sprintf("%.2f", $total);
        $totalScore = $totalScore + $rating;
        $totalScoreF = sprintf("%.2f", $totalScore);
        $newAvg = $totalScoreF / $totalF;

        $sql = "UPDATE classes SET classesAvg=? AND classesTotalReviews=? AND classesRatingSum=? WHERE classesId=? VALUES (? ? ? ?);";
        $stmt = mysqli_stmt_init($conn);
        if(!mysqli_stmt_prepare($stmt, $sql)){
            header("location: ../create-review.php?className=". $className . "&classId=" . $classId . "&error=invalid");
            exit();
        }else{
            mysqli_stmt_bind_param($stmt, "diii", $newAvg, $total, $totalScore, $classId);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
        }
    }
}

if(isset($_POST["submit"])){
    $title = $_POST["title"];
    $professor = $_POST["professor"];
    $review = $_POST["review"];
    $rating = $_POST["rating"];
    $classId = $_POST["classId"];
    $className = $_POST["className"];
    $ownerId = $_POST["ownerId"];

    require_once 'dbh.inc.php';
    //require_once 'functions.inc.php';

    if(invalidClassId($conn, $classId, $className) !== false){
        header("location: ../create-review.php?className=". $className . "&classId=" . $classId . "&error=invalid");
        exit();
    }

    createReview($conn, $title, $professor, $review, $rating, $classId, $ownerId, $className);
}else{
    header("location: ../index.php");
    exit();
}
