<?php
    //include_once 'header.php';
?>

<?php 
    session_start();
    if(!isset($_SESSION["useruid"])){
        header("location: login.php");
        exit();
    }
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
    <head>
        <meta charset="utf-8">
        <title>PHP Project</title>
        <link rel="stylesheet" href="css/style.css">
    </head>

    <body>
        <div class="view">
            <div class="header">
                <div class="header-name">
                    <h1>RateMyClasses</h1>
                </div>
                <nav>
                    <div class="wrapper">
                        <ul>
                            <li><a href="index.php">Home</a></li>
                            <?php 
                                if(isset($_SESSION["useruid"])){
                                    echo "<li><a href='profile.php'>Profile</a></li>";
                                }
                            ?>
                            <li><a href="university-select.php">Find Reviews</a></li>
                            <li><a href="my-reviews.php">My Reviews</a></li>
                            <?php 
                                if(isset($_SESSION["useruid"])){
                                    echo "<li style='float:right'><a href='includes/logout.inc.php'>Logout</a></li>";
                                }else{
                                    echo "<li style='float:right'><a href='signup.php'>Sign up</a></li>";
                                    echo "<li style='float:right'><a href='login.php'>Login</a></li>";
                                }
                            ?>
                        </ul>
                    </div>
                </nav>
            </div>


            <section class="view-reviews-page">
                <div class="view-reviews">
                    <?php
                        $uniId = $_GET["uniId"];
                        $uniName = $_GET["uniName"];
                        $userId = $_SESSION["userid"];
                    ?>
                    <?php
                    
                        require_once 'includes/dbh.inc.php';

                        $sql = "SELECT * FROM classes WHERE classesUniId = ?;";
                        $stmt = mysqli_stmt_init($conn);
                        if(!mysqli_stmt_prepare($stmt, $sql)){
                            header("location: ../signup.php?error=stmtFailed");
                            exit();
                        }
                    
                        mysqli_stmt_bind_param($stmt, "s", $uniId);
                        mysqli_stmt_execute($stmt);
                    
                        $postData = mysqli_stmt_get_result($stmt);

                        /*$postSQL = "SELECT * FROM reviews WHERE reviewsClassId= ?;";
                        $poststmt = mysqli_stmt_init($conn);
                        if(!mysqli_stmt_prepare($poststmt, $postSQL)){
                            header("location: ../signup.php?error=stmtFailed");
                            exit();
                        }
                    
                        mysqli_stmt_bind_param($poststmt, "s", $classId);
                        mysqli_stmt_execute($poststmt);*/
                    
                        //$postData = mysqli_stmt_get_result($poststmt);
                    ?>
                    <div class="view-review-header">
                        <p>Showing Classes for <?php echo $uniName; ?></p>
                    </div>
                    <div class="user-review-list">
                        <?php
                        $resultLength = mysqli_num_rows($postData);
                        for ($x = 0; $x < $resultLength; $x++){
                            $row = mysqli_fetch_assoc($postData);
                            $classId = $row["classesId"];
                            $className = $row["classesName"];
                            $classAvg = $row["classesAvg"];
                            $classNum = $row["classesTotalReviews"];
                            $classSum = $row["classesRatingSum"];

                            
                            /*$sql = "SELECT * FROM users WHERE usersId = ?;";
                            $stmt = mysqli_stmt_init($conn);
                            if(!mysqli_stmt_prepare($stmt, $sql)){
                                header("location: ../signup.php?error=stmtFailed");
                                exit();
                            }
                        
                            mysqli_stmt_bind_param($stmt, "s", $reviewsOwnerId);
                            mysqli_stmt_execute($stmt);
                        
                            $reviewAuthorData = mysqli_stmt_get_result($stmt);
                            $authorRow = mysqli_fetch_assoc($reviewAuthorData);
                            $reviewsAuthorName = $authorRow["usersUid"]*/
                            $header = "view-reviews.php?className=". $className . "&classId=" . $classId;

                        ?>
                        <form action=<?php echo $header; ?> method="post">
                        <div class="user-review">
                            <div class="user-review-header">
                                <div class="user-review-author">
                                    <label>Class Name:</label>
                                    <p><?php echo $className; ?></p>
                                    <Label>Average Rating:</label>
                                    <p><?php echo $classAvg; ?></p>
                                    <label>Class Total Reviews:</label>
                                    <p><?php echo $classNum; ?></p>
                                    <Label>Class Rating Sum:</label>
                                    <p><?php echo $classSum; ?></p>
                                    <input type="hidden" id="classId" name="classId" value=<?php echo $classId?>>
                                    <button type="submit">View Reviews</button>
                                </div>
                            </div>
                        </div>
                        </form>
                        <br>
                        <?php
                        }
                        ?>
                    </div>
                </div>

                <?php 
                    if(isset($_GET["error"])){
                        if($_GET["error"] == "invalid"){
                            echo "<p class='fail'>There was an error</p>";
                        }
                    }
                ?>
            </section>
        </div>