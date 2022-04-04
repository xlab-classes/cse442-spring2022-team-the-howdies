<?php 

if(isset($_POST["submit"])){
    $email = $_POST["email"];
    $username = $_POST["uid"];
    $pwd = $_POST["pwd"];
    $pwdRepeat = $_POST["pwdrepeat"];

    require_once 'dbh.inc.php';
    require_once 'functions.inc.php';

    if(emptyInputSignup($email, $username, $pwd, $pwdRepeat) !== false){
        header("location: ../signup.php?error=emptyInput");
        exit();
    }

    if(invalidUid($username) !== false){
        header("location: ../signup.php?error=invalidUid");
        exit();
    }

    if(invalidEmail($email) !== false){
        header("location: ../signup.php?error=invalidEmail");
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