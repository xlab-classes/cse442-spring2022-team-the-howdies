<?php

function invalidUid($username){
    $result;
    if(!preg_match('/^[a-z\d_]{2,20}$/i', $username)){
        $result = true;
    }else{
        $result = false;
    }
    return $result;
}

function pwdMatch($pwd, $pwdRepeat){
    $result;
    if($pwd != $pwdRepeat){
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

function createUser($conn, $email, $username, $pwd){
    $sql = "INSERT INTO users (usersEmail, usersUid, usersPwd, usersPicPath, usersBio, usersYear) VALUES (?, ?, ?, ?, ?, ?);";
    $stmt = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt, $sql)){
        header("location: ../signup.php?error=stmtFailed");
        exit();
    }

    $hashedPwd = password_hash($pwd, PASSWORD_DEFAULT);
    $picPath = "images/pfp.png";
    $bio = "No bio";
    $year = "No year";

    mysqli_stmt_bind_param($stmt, "ssssss", $email, $username, $hashedPwd, $picPath, $bio, $year);
    mysqli_stmt_execute($stmt);

    mysqli_stmt_close($stmt);

    //header("location: ../signup.php");
    loginUser($conn, $username, $pwd);
    exit();
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

if(isset($_POST["submit"])){
    $email = $_POST["email"];
    $username = $_POST["uid"];
    $pwd = $_POST["pwd"];
    $pwdRepeat = $_POST["pwdrepeat"];

    require_once 'dbh.inc.php';
    //require_once 'functions.inc.php';

    if(invalidUid($username) !== false){
        header("location: ../signup.php?error=invalidUid");
        exit();
    }

    if(pwdMatch($pwd, $pwdRepeat) !== false){
        header("location: ../signup.php?error=pwdNotMatch");
        exit();
    }

    if(uidExists($conn, $username, $email) !== false){
        header("location: ../signup.php?error=usernameTaken");
        exit();
    }

    createUser($conn, $email, $username, $pwd);

}else{
    header("location: ../signup.php");
    exit();
}