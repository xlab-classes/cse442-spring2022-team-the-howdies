<?php

function uniIdExists($conn, $uniName) {
    $sql = "SELECT * FROM universities WHERE universityName = ?;";
    $stmt = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt, $sql)){
        header("location: ../index.php?error=stmtFailed");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "s", $uniName);
    mysqli_stmt_execute($stmt);

    $resultData = mysqli_stmt_get_result($stmt);

    $row = mysqli_fetch_assoc($resultData);

    if($row){
        return $row;
    }else{
        $result = false;
        return $result;
    }

    mysqli_stmt_close($stmt);
}

function findSimilar($conn, $uniName){
    $possibleUnis = array();

    $uniWords = explode(" ", $uniName);

    $sql = "SELECT * FROM universities;";
    $stmt = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt, $sql)){
        header("location: ../index.php?error=stmtFailed");
        exit();
    }

    mysqli_stmt_bind_param($stmt);
    mysqli_stmt_execute($stmt);

    $resultData = mysqli_stmt_get_result($stmt);

    $resultLength = mysqli_num_rows($resultData);
    for ($x = 0; $x < $resultLength; $x++){
        $row = mysqli_fetch_assoc($resultData);
        $universityName = $row["universityName"];
        $universityId = $row["universityId"];

        $oldUniWords = explode(" ", $universityName);
        $bool = false;

        foreach ($uniWords as $uniWord){
            $uniWord = strtolower($uniWord);
            foreach ($oldUniWords as $oldUniWord){
                $oldUniWord = strtolower($oldUniWord);
                if($uniWord != "state" && $uniWord != "technology" && $uniWord != "of" && $uniWord != "at" && $uniWord != "a" && $uniWord != "the" && $uniWord != "college" && $uniWord != "university" && $uniWord != "institute" && $uniWord != "community" && $oldUniWord != "state" && $oldUniWord != "technology" && $oldUniWord != "of" && $oldUniWord != "at" && $oldUniWord != "a" && $oldUniWord != "the" && $oldUniWord != "college" && $oldUniWord != "university" && $oldUniWord != "institute" && $oldUniWord != "community"){
                    if(($uniWord == $oldUniWord || strpos($uniWord, $oldUniWord) || strpos($oldUniWord, $uniWord)) && !$bool){
                        /*if($oldUniWord == "buffalo"){
                            if(!mysqli_stmt_prepare($stmt, $sql)){
                                header("location: ../index.php?error=stmtFailed");
                                exit();
                            }
                        }*/
                        $addArray = array("uniName" => $universityName, "uniId" => $universityId);
                        $bool = true;
                        array_push($possibleUnis, $addArray);
                    }
                }
            }
        }
    }

    return $possibleUnis;
}

function selectUniversity($conn, $uniName) {
    if($uniName == ""){
        header("location: ../index.php?uniName=" . $uniName . "&error=invalid");
        exit();
    }

    $uniIdExists = uniIdExists($conn, $uniName);

    if($uniIdExists === false){
        $possible = findSimilar($conn, $uniName);
        if(count($possible) > 0){
            session_start();
            $_SESSION["possibleUnis"] = $possible;
            header("location: ../index.php?uniName=" . $uniName . "&error=invalid&possible=true");
            exit();
        }

        header("location: ../index.php?uniName=" . $uniName . "&error=invalid");
        exit();
    }

    session_start();
    $_SESSION["uniId"] = $uniIdExists["universityId"];
    $_SESSION["uniName"] = $uniName;

    $sql = "SELECT * FROM universities WHERE universityName = ?;";
    $stmt = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt, $sql)){
        header("location: ../index.php?error=stmtFailed");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "s", $uniName);
    mysqli_stmt_execute($stmt);

    $resultData = mysqli_stmt_get_result($stmt);

    $row = mysqli_fetch_assoc($resultData);
    $uniId = $row["universityId"];

    //header("location: ../index.php?uniName=" . $uniName);
    header("location: ../view-classes.php?uniName=" . $uniName . "&uniId=". $uniId);
    exit();
}

if(isset($_POST["submit"])){
    $uniName = $_POST["uniName"];

    require_once 'dbh.inc.php';
    //require_once 'functions.inc.php';

    selectUniversity($conn, $uniName);
}else{
    header("location: ../index.php");
    exit();
}
