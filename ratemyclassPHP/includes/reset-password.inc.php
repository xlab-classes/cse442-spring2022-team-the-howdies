<?php 

if(isset($_POST["reset-password-submit"])){

    $selector = $_POST["selector"];
    $validator = $_POST["validator"];
    $password = $_POST["pwd"];
    $passwordRepeat = $_POST["pwd-repeat"];

    if(empty($password) || empty($passwordRepeat)){
        header("location: ../create-new-password.php?error=empty&selector=" . $selector . "&validator=" . $validator);
        exit();
    }else if($password != $passwordRepeat){
        header("location: ../create-new-password.php?error=pwdNotMatch&selector=" . $selector . "&validator=" . $validator);
        exit();
    }

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $currentDate = date("U");
    require 'dbh.inc.php';

    $sql = "SELECT * FROM pwdReset WHERE pwdResetSelector=? AND pwdResetExpires >= ?;";
    $stmt = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt, $sql)){
        echo "There was an error!";
        exit();
    }else{
        mysqli_stmt_bind_param($stmt, "ss", $selector, $currentDate);
        mysqli_stmt_execute($stmt);

        $result = mysqli_stmt_get_result($stmt);
        if(!$row = mysqli_fetch_assoc($result)){
            echo "Resubmit your reset request.";
            exit();
        }else{
            $tokenBin = hex2bin($validator);
            $tokenCheck = password_verify($tokenBin, $row["pwdResetToken"]);

            if($tokenCheck === false){
                echo "Resubmit your reset request.";
                exit();
            }else if($tokenCheck === true){
                $tokenEmail = $row['pwdResetEmail'];

                $sql = "SELECT * FROM users WHERE usersEmail=?;";
                $stmt = mysqli_stmt_init($conn);
                if(!mysqli_stmt_prepare($stmt, $sql)){
                    echo "There was an error!";
                    exit();
                }else{
                    mysqli_stmt_bind_param($stmt, "s", $tokenEmail);
                    mysqli_stmt_execute($stmt);

                    $result = mysqli_stmt_get_result($stmt);
                    if(!$row = mysqli_fetch_assoc($result)){
                        echo "There was an error!";
                        exit();
                    }else{

                        $sql = "UPDATE users SET usersPwd=? WHERE usersEmail=?;";
                        $stmt = mysqli_stmt_init($conn);
                        if(!mysqli_stmt_prepare($stmt, $sql)){
                            echo "There was an error!";
                            exit();
                        }else{
                            mysqli_stmt_bind_param($stmt, "ss", $hashedPassword, $tokenEmail);
                            mysqli_stmt_execute($stmt);

                            $sql = "DELETE FROM pwdReset WHERE pwdResetEmail=?;";
                            $stmt = mysqli_stmt_init($conn);
                            if(!mysqli_stmt_prepare($stmt, $sql)){
                                echo "There was an error!";
                                exit();
                            }else{
                                mysqli_stmt_bind_param($stmt, "s", $tokenEmail);
                                mysqli_stmt_execute($stmt);
                                header("location: ../login.php?message=pwdUpdated");
                            }

                        }

                    }
                }
            }
        }
    }

}else{
    header("location: ../index.php");
}