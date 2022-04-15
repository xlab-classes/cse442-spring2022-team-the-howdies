<?php 

function onClickLikes($userId, $reviewId, $conn){
    $sql = "SELECT * FROM likes WHERE likesReviewId = ? AND likesUserId = ?;";
    $stmt = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt, $sql)){
        //error 
        exit();
    }
    mysqli_stmt_bind_param($stmt, "ss", $reviewId, $userId);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);
    $resultLength = mysqli_num_rows($result);

    if($resultLength == 0){
        $sql = "INSERT INTO likes (likesReviewId, likesUserId) VALUES (?, ?);";
        $stmt = mysqli_stmt_init($conn);
        if(!mysqli_stmt_prepare($stmt, $sql)){
            //error 
            exit();
        }
        mysqli_stmt_bind_param($stmt, "ss", $reviewId, $userId);
        mysqli_stmt_execute($stmt);
    }else{
        $row = mysqli_fetch_assoc($result);
        $likeId = $row["likesId"];
        $sql = "DELETE FROM likes WHERE likesId=?;";
        $stmt = mysqli_stmt_init($conn);
        if(!mysqli_stmt_prepare($stmt, $sql)){
            //error 
            exit();
        }
        mysqli_stmt_bind_param($stmt, "s", $likeId);
        mysqli_stmt_execute($stmt);
    }

    $sql = "SELECT * FROM dislikes WHERE dislikesReviewId = ? AND dislikesUserId = ?;";
    $stmt = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt, $sql)){
        //error 
        exit();
    }
    mysqli_stmt_bind_param($stmt, "ss", $reviewId, $userId);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);
    $resultLength = mysqli_num_rows($result);

    if($resultLength != 0){
        $row = mysqli_fetch_assoc($result);
        $dislikeId = $row["dislikesId"];
        $sql = "DELETE FROM dislikes WHERE dislikesId=?;";
        $stmt = mysqli_stmt_init($conn);
        if(!mysqli_stmt_prepare($stmt, $sql)){
            //error 
            exit();
        }
        mysqli_stmt_bind_param($stmt, "s", $dislikeId);
        mysqli_stmt_execute($stmt);
    }

    $likes = getLikes($reviewId, $conn);
    $dislikes = getDislikes($reviewId, $conn);

    return [$likes, $dislikes];
}

// if disliked, removes that dislike; else, adds a dislike
// if liked, removes the like

function onClickDislikes($userId, $reviewId, $conn){
    $sql = "SELECT * FROM dislikes WHERE dislikesReviewId = ? AND dislikesUserId = ?;";
    $stmt = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt, $sql)){
        //error 
        exit();
    }
    mysqli_stmt_bind_param($stmt, "ss", $reviewId, $userId);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);
    $resultLength = mysqli_num_rows($result);

    if($resultLength == 0){
        $sql = "INSERT INTO dislikes (dislikesReviewId, dislikesUserId) VALUES (?, ?);";
        $stmt = mysqli_stmt_init($conn);
        if(!mysqli_stmt_prepare($stmt, $sql)){
            //error 
            exit();
        }
        mysqli_stmt_bind_param($stmt, "ss", $reviewId, $userId);
        mysqli_stmt_execute($stmt);
    }else{
        $row = mysqli_fetch_assoc($result);
        $dislikeId = $row["dislikesId"];
        $sql = "DELETE FROM dislikes WHERE dislikesId=?;";
        $stmt = mysqli_stmt_init($conn);
        if(!mysqli_stmt_prepare($stmt, $sql)){
            //error 
            exit();
        }
        mysqli_stmt_bind_param($stmt, "s", $dislikeId);
        mysqli_stmt_execute($stmt);
    }

    $sql = "SELECT * FROM likes WHERE likesReviewId = ? AND likesUserId = ?;";
    $stmt = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt, $sql)){
        //error 
        exit();
    }
    mysqli_stmt_bind_param($stmt, "ss", $reviewId, $userId);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);
    $resultLength = mysqli_num_rows($result);

    if($resultLength != 0){
        $row = mysqli_fetch_assoc($result);
        $likeId = $row["likesId"];
        $sql = "DELETE FROM likes WHERE likesId=?;";
        $stmt = mysqli_stmt_init($conn);
        if(!mysqli_stmt_prepare($stmt, $sql)){
            //error 
            exit();
        }
        mysqli_stmt_bind_param($stmt, "s", $likeId);
        mysqli_stmt_execute($stmt);
    }

    $likes = getLikes($reviewId, $conn);
    $dislikes = getDislikes($reviewId, $conn);

    return [$likes, $dislikes];
}

// returns number of likes/dislikes on that review

function getLikes($reviewId, $conn){
    $sql = "SELECT * FROM likes WHERE likesReviewId = ?;";
    $stmt = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt, $sql)){
        //error 
        exit();
    }
    mysqli_stmt_bind_param($stmt, "s", $reviewId);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);
    $resultLength = mysqli_num_rows($result);

    return $resultLength;
}

function getDislikes($reviewId, $conn){
    $sql = "SELECT * FROM dislikes WHERE dislikesReviewId = ?;";
    $stmt = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt, $sql)){
        //error 
        exit();
    }
    mysqli_stmt_bind_param($stmt, "s", $reviewId);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);
    $resultLength = mysqli_num_rows($result);

    return $resultLength;
}

$serverHost = "oceanus.cse.buffalo.edu";
$serverUsername = "nmtryon";
$serverPassword = "50297962";
$serverDbName = "cse442_2022_spring_team_w_db";
$serverPort = 3306;

//$conn = mysqli_connect($serverName, $dBUsername, $dBPassword, $dBName);
$conn = mysqli_connect($serverHost, $serverUsername, $serverPassword, $serverDbName);

if(!$conn){
    die("Connection failed: " . mysqli_connect_error());
}

$userId = $_POST["user_id"];
$reviewId = $_POST['review_id'];
$ld = $_POST['l_d'];

if($ld == "l"){
    onClickLikes($userId, $reviewId, $conn);
    $likes = getLikes($reviewId, $conn);
    $dislikes = getDislikes($reviewId, $conn);
    echo json_encode(array("likes"=>$likes, "dislikes"=>$dislikes, "id"=>$reviewId, "uId"=>$userId));
}else{
    onClickDislikes($userId, $reviewId, $conn);
    $likes = getLikes($reviewId, $conn);
    $dislikes = getDislikes($reviewId, $conn);
    echo json_encode(array("likes"=>$likes, "dislikes"=>$dislikes, "id"=>$reviewId, "uId"=>$userId));
}