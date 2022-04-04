<?php 
/*use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMAILER\PHPMAILER\Exception;

require 'PHPMailer/Exception.php';
require 'PHPMailer/PHPMailer.php';
require 'PHPMailer/SMTP.php';*/

if(isset($_POST["reset-request-submit"])){
    $selector = bin2hex(random_bytes(8));
    $token = random_bytes(32);

    $url = "https://www-student.cse.buffalo.edu/CSE442-542/2022-Spring/cse-442w/cse442-spring2022-team-the-howdies/ratemyclassPHP/create-new-password.php?selector=" . $selector . "&validator=" . bin2hex($token);
    $expires = date("U") + 1800;

    require 'dbh.inc.php';

    $userEmail = $_POST["email"];

    $sql = "SELECT * FROM users WHERE usersEmail = ?;";
    $stmt = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt, $sql)){
        header("location: ../reset-password.php?error=stmtFailed");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "s", $userEmail);
    mysqli_stmt_execute($stmt);

    $resultData = mysqli_stmt_get_result($stmt);

    if($row = mysqli_fetch_assoc($resultData)){
        //return $row;
    }else{
        header("location: ../reset-password.php?error=noUser");
        exit();
    }

    mysqli_stmt_close($stmt);

    $sql = "DELETE FROM pwdReset WHERE pwdResetEmail=?";
    $stmt = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt, $sql)){
        echo "There was an error";
        exit();
    }else{
        mysqli_stmt_bind_param($stmt, "s", $userEmail);
        mysqli_stmt_execute($stmt);
    }

    $sql = "INSERT INTO pwdReset (pwdResetEmail, pwdResetSelector, pwdResetToken, pwdResetExpires) VALUES (?, ?, ?, ?);";
    $stmt = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt, $sql)){
        echo "There was an error";
        exit();
    }else{
        $hashedToken = password_hash($token, PASSWORD_DEFAULT);
        mysqli_stmt_bind_param($stmt, "ssss", $userEmail, $selector, $hashedToken, $expires);
        mysqli_stmt_execute($stmt);
    }

    mysqli_stmt_close($stmt);
    mysqli_close($conn);

    require_once('../PHPMailer-5.2-stable/PHPMailerAutoload.php');

    $mail = new PHPMailer();
    $mail->isSMTP();
    $mail->SMTPAuth = true;
    $mail->SMTPSecure = 'ssl';
    $mail->Host = 'smtp.gmail.com';
    $mail->Port = 465;
    $mail->isHTML(true);
    $mail->Username='RateMyClassesNoReply@gmail.com';
    $mail->Password = 'Zxcvbnm0?';
    $mail->SetFrom('no-reply@domain.com');
    $mail->Subject = 'Reset password for the website';
    $mail->Body = '<p>The link to reset your password is below (ignore if you did not make this request)</p><p>Link: </br><a href="' . $url . '">' . $url . '</a></p>';
    $mail->AddAddress($userEmail);
    $mail->send();

    /*$to = $userEmail;
    $subject = 'Reset password for thisWebsit';

    $message = '<p>The link to reset your password is below (ignore if you did not make this request)</p>';
    $message .= '<p>Link: </br>';
    $message .= '<a href="' . $url . '">' . $url . '</a></p>';

    $headers = "From: domain <domain@gmail.com>\r\n";
    $headers .= "Reply-To: domain@gmail.com\r\n";
    $headers .= "Content-type: text/html\r\n";

    mail($to, $subject, $message, $headers);*/

    header("location: ../reset-password.php?reset=success");

}else{
    header("location: ../index.php");
    exit();
}
