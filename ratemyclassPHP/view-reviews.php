<?php
    //include_once 'header.php';
?>

<?php 
    session_start();
    if(!isset($_SESSION["useruid"])){
        header("location: /ratemyclassPHP/login.php");
        exit();
    }
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
    <head>
        <meta charset="utf-8">
        <title>PHP Project</title>
        <link rel="stylesheet" href="css/view-reviews.css">
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
                        // $classId = $_GET["classId"];
                        // $class = $_GET["class"];
                        // $userId = $_SESSION["userid"];
                        // if(empty($classId) || empty($class)){
                        //     echo "<p class='fail'>There was an error</p>";
                        // }else{
                        // echo "<h1>Submit a Review for " . $_GET["class"] . "</h1>";

                        // $reviewsOwnerId = $_GET[""];
                        // $reviewsYear = $_GET[""];
                        // $reviewsRating = $_GET[""];
                        // $reviewsProfessor = $_GET[""];
                        // $reviewsReview = $_GET[""];
                    ?>
                    <div class="view-review-header">
                        <p>Showing Reviews for TEMP<?php //echo $classId; ?></p>
                        <input class="leave-review-button" type="submit" value="Review">
                    </div>
                    <div class="user-review-list">
                        <?php
                            // for entry in entries:
                            //     professor = entry.professor
                            //     echo <entire post template>
                        ?>
                        <div class="user-review">
                            <div class="user-review-header">
                                <div class="user-review-author">
                                    <label>User:</label>
                                    <p>TEMP</p>
                                </div>
                                <div class="user-review-year">
                                    <label>Year:</label>
                                    <p>TEMP</p>
                                </div>
                                <div class="user-review-rating">
                                    <label>Rating:</label>
                                    <p>TEMP</p>
                                </div>
                            </div>
                            <div class="user-review-professor">
                                <label>Professor:</label>
                                <p>TEMP</p>
                            </div>
                            <div class="user-review-content">
                                <label>User Review:</label>
                                <p>TEMP</p>
                            </div>
                        </div>
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
