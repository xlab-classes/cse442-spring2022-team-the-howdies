<?php 

$serverName = "localhost";
$dBUsername = "root";
$dBPassword = "";
$dBName = "phptutorial";

$serverHost = "oceanus.cse.buffalo.edu";
$serverUsername = "dlschyke";
$serverPassword = "50379561";
$serverDbName = "cse442_2022_spring_team_w_db";
$serverPort = "3306";

//$conn = mysqli_connect($serverName, $dBUsername, $dBPassword, $dBName);
$conn = mysqli_connect($serverHost, $serverUsername, $serverPassword, $serverDbName, $serverPort);

if(!$conn){
    die("Connection failed: " . mysqli_connect_error());
}