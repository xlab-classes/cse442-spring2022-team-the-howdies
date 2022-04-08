<?php 
if(isset($_POST["submit"])){
    $id = $_POST["uId"];

    require_once 'dbh.inc.php';
    //require_once 'functions.inc.php';
    $dir = "../images/";
    $realDir = "images/";
    $file = $dir . $id . basename($_FILES["profilePicture"]["name"]);
    $realFile = $realDir . $id . basename($_FILES["profilePicture"]["name"]);
    $uOk = 1;
    $fileType = strtolower(pathinfo($file,PATHINFO_EXTENSION));

    $isReal = getimagesize($_FILES["profilePicture"]["tmp_name"]);
    if($isReal !== false){
        $uOk = 1;
    }else{
        $uOk = 0;
    }

    if(file_exists($file)){
        $sql = "UPDATE users SET usersPicPath=? WHERE usersId=?;";
        $stmt = mysqli_stmt_init($conn);
        if(!mysqli_stmt_prepare($stmt, $sql)){
            echo "There was an error!";
            exit();
        }else{
            mysqli_stmt_bind_param($stmt, "ss", $realFile, $id);
            mysqli_stmt_execute($stmt);
        }
        header("location: ../profile.php");
        exit();
    }else{
        if($fileType != "jpg" && $fileType != "png" && $fileType != "jpeg" && fileType != "gif"){
            //return back and throw error
            $uOk = 0;
        }

        if($uOk == 0){
            // return back throw error
        }else{
            if(move_uploaded_file($_FILES["profilePicture"]["tmp_name"], $file)){
                $sql = "UPDATE users SET usersPicPath=? WHERE usersId=?;";
                $stmt = mysqli_stmt_init($conn);
                if(!mysqli_stmt_prepare($stmt, $sql)){
                    echo "There was an error!";
                    exit();
                }else{
                    mysqli_stmt_bind_param($stmt, "ss", $realFile, $id);
                    mysqli_stmt_execute($stmt);
                }
                header("location: ../profile.php");
                exit();
            }else{
                // return back throw error
            }
        }
    }
}else{
    header("location: ../profile.php");
    exit();
}