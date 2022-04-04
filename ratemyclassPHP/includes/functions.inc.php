<?php 

function emptyInputSignup($email, $username, $pwd, $pwdRepeat){
    $result;
    if(empty($email) || empty($username) || empty($pwd) || empty($pwdRepeat)){
        $result = true;
    }else{
        $result = false;
    }
    return $result;
}

function invalidUid($username){
    $result;
    if(!preg_match("/^[a-zA-Z0-9]*$/", $username)){
        $result = true;
    }else{
        $result = false;
    }
    return $result;
}

function invalidEmail($email){
    $result;
    if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        $result = true;
    }else{
        $result = false;
    }
    return $result;
}

function pwdMatch($pwd, $pwdRepeat){
    $result;
    if($pwd !== $pwdRepeat){
        $result = true;
    }else{
        $result = false;
    }
    return $result;
}

function uidExists($conn, $username, $email){
    $sql = "SELECT * FROM users WHERE usersUid = ? OR usersEmail = ?;";
    $stmt = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt, $sql)){
        header("location: ../signup.php?error=stmtFailed");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "ss", $username, $email);
    mysqli_stmt_execute($stmt);

    $resultData = mysqli_stmt_get_result($stmt);

    if($row = mysqli_fetch_assoc($resultData)){
        return $row;
    }else{
        $result = false;
        return $result;
    }

    mysqli_stmt_close($stmt);
}

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

function createUser($conn, $email, $username, $pwd){
    $sql = "INSERT INTO users (usersEmail, usersUid, usersPwd) VALUES (?, ?, ?);";
    $stmt = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt, $sql)){
        header("location: ../signup.php?error=stmtFailed");
        exit();
    }

    $hashedPwd = password_hash($pwd, PASSWORD_DEFAULT);

    mysqli_stmt_bind_param($stmt, "sss", $email, $username, $hashedPwd);
    mysqli_stmt_execute($stmt);

    mysqli_stmt_close($stmt);

    //header("location: ../signup.php");
    loginUser($conn, $username, $pwd);
    exit();
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

function emptyInputLogin($username, $pwd){
    $result;
    if(empty($username) || empty($pwd)){
        $result = true;
    }else{
        $result = false;
    }
    return $result;
}

function loginUser($conn, $username, $pwd){
    $uidExists = uidExists($conn, $username, $username);

    if($uidExists === false){
        header("location: ../login.php?error=noUser");
        exit();
    }

    $pwdHashed = $uidExists["usersPwd"];
    $checkPwd = password_verify($pwd, $pwdHashed);

    if($checkPwd === false){
        header("location: ../login.php?error=wrongPwd");
        exit();
    }else if($checkPwd === true){
        session_start();
        $_SESSION["userid"] = $uidExists["usersId"];
        $_SESSION["useruid"] = $uidExists["usersUid"];
        header("location: ../index.php");
        exit();
    }
}
