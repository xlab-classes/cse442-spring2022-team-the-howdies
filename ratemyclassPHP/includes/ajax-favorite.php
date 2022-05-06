<?php 

function checkFavorite($userId, $classId, $conn){
    $sql = "SELECT * FROM favorites WHERE favoritesUserId = ? AND favoritesClassId = ?;";
    $stmt = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt, $sql)){
        //error 
        exit();
    }
    mysqli_stmt_bind_param($stmt, "ss", $userId, $classId);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);
    $resultLength = mysqli_num_rows($result);


    if($resultLength > 0){
        $row = mysqli_fetch_assoc($result);
        $id = $row["favoritesId"];
        return $id;
    }else{
        return -1;
    }
}

function unfavorite($id, $conn){
    $sql = "DELETE FROM favorites WHERE favoritesId=?;";
    $stmt = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt, $sql)){
        //error 
        exit();
    }
    mysqli_stmt_bind_param($stmt, "s", $id);
    mysqli_stmt_execute($stmt);
}

function favorite($userId, $classId, $uniId, $className, $uniName, $conn){
    $sql = "INSERT INTO favorites (favoritesUserId, favoritesClassId, favoritesUniId, favoritesClassName, favoritesUniName) VALUES (?, ?, ?, ?, ?);";
    $stmt = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt, $sql)){
        //error 
        exit();
    }
    mysqli_stmt_bind_param($stmt, "sssss", $userId, $classId, $uniId, $className, $uniName);
    mysqli_stmt_execute($stmt);
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
$classId = $_POST['class_id'];
$uniId = $_POST['uni_id'];
$className = $_POST['class_name'];
$uniName = $_POST['uni_name'];

$className = str_replace("`", " ", $className);
$uniName = str_replace("`", " ", $uniName);

$hasFavorite = checkFavorite($userId, $classId, $conn);
if($hasFavorite != -1){
    unfavorite($hasFavorite, $conn);
    echo json_encode(array("message"=>"Favorite"));
}else{
    favorite($userId, $classId, $uniId, $className, $uniName, $conn);
    echo json_encode(array("message"=>"Unfavorite"));
}