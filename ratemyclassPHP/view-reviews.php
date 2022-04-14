<?php
    //include_once 'header.php';
?>

<?php 
    session_start();
    if(!isset($_SESSION["useruid"])){
        header("location: login.php");
        exit();
    }

    function onClickHandler($classId, $className){
        header("location: create-review.php?classId=".$classId."&className=".$className);
        exit();
    }
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
    <head>
        <meta charset="utf-8">
        <title>PHP Project</title>
        <link rel="stylesheet" href="css/view-reviews.css">
        <script>
            function test(){
                console.log("testing. . .");
            }
            function submitLikes(user, reviewID, conn){
                var result = "<?php onClickLikes(user, reviewID, conn); ?>"
            }

            function submitDislikes(user, reviewID, conn){
                var result = "<?php onClickDislikes(user, reviewID, conn); ?>"
            }
        </script>
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
                        $classId = $_GET["classId"];
                        $className = $_GET["className"];
                        $userId = $_SESSION["userid"];
                    ?>
                    <?php
                    
                        require_once 'includes/dbh.inc.php';

                        $sql = "SELECT * FROM users WHERE usersId = ?;";
                        $stmt = mysqli_stmt_init($conn);
                        if(!mysqli_stmt_prepare($stmt, $sql)){
                            header("location: ../signup.php?error=stmtFailed");
                            exit();
                        }
                    
                        mysqli_stmt_bind_param($stmt, "s", $userId);
                        mysqli_stmt_execute($stmt);
                    
                        $currentUserData = mysqli_stmt_get_result($stmt);

                        $postSQL = "SELECT * FROM reviews WHERE reviewsClassId= ?;";
                        $poststmt = mysqli_stmt_init($conn);
                        if(!mysqli_stmt_prepare($poststmt, $postSQL)){
                            header("location: ../signup.php?error=stmtFailed");
                            exit();
                        }
                    
                        mysqli_stmt_bind_param($poststmt, "s", $classId);
                        mysqli_stmt_execute($poststmt);
                    
                        $postData = mysqli_stmt_get_result($poststmt);
                    ?>
                    <div class="view-review-header">
                        <p>Showing Reviews for <?php echo $className; ?></p>
                        <input class="leave-review-button" type="submit" value="Review"/>
                    </div>
                    <div class="user-review-list">
                        <?php
                        $resultLength = mysqli_num_rows($postData);
                        for ($x = 0; $x < $resultLength; $x++){
                            $row = mysqli_fetch_assoc($postData);
                            $reviewsId = $row["reviewsId"];
                            $reviewsOwnerId = $row["reviewsOwnerId"];
                            $reviewsRating = $row["reviewsRating"];
                            $reviewsProfessor = $row["reviewsProfessor"];
                            $reviewsReview = $row["reviewsReview"];
                            $reviewsLikes = getLikes($reviewsId, $conn);
                            $reviewsDislikes = getDislikes($reviewsId, $conn);

                            
                            $sql = "SELECT * FROM users WHERE usersId = ?;";
                            $stmt = mysqli_stmt_init($conn);
                            if(!mysqli_stmt_prepare($stmt, $sql)){
                                header("location: ../signup.php?error=stmtFailed");
                                exit();
                            }
                        
                            mysqli_stmt_bind_param($stmt, "s", $reviewsOwnerId);
                            mysqli_stmt_execute($stmt);
                        
                            $reviewAuthorData = mysqli_stmt_get_result($stmt);
                            $authorRow = mysqli_fetch_assoc($reviewAuthorData);
                            $reviewsAuthorName = $authorRow["usersUid"]

                        ?>
                        <div class="user-review">
                            <div class="user-review-header">
                                <div class="user-review-author">
                                    <label>User:</label>
                                    <p><?php echo $reviewsAuthorName; ?></p>
                                </div>
                                <div class="user-review-year">
                                    <label>Year:</label>
                                    <p>Senior</p>
                                </div>
                                <div class="user-review-rating">
                                    <label>Rating:</label>
                                    <p><?php echo $reviewsRating; ?> / 10</p>
                                </div>
                            </div>
                            <div class="user-review-professor">
                                <label>Professor:</label>
                                <p><?php echo $reviewsProfessor; ?></p>
                            </div>
                            <div class="user-review-content">
                                <label>User Review:</label>
                                <p><?php echo $reviewsReview; ?></p>
                            </div>
                            <div class="user-review-reactions">
                                <div class=user-review-likes>
                                    <img src="images/like.png" alt="" width=30 height=30 onClick="submitLikes(<?php echo $reviewId?>, <?php echo $userId?>, <?php echo $conn?>)">
                                    <p><?php echo $reviewsLikes; ?></p>
                                </div>
                                <div class=user-review-dislikes>
                                    <img src="images/dislike.png" alt="" width=30 height=25 onClick="submitDislikes(<?php echo $reviewId?>, <?php echo $userId?>, <?php echo $conn?>)">
                                    <p><?php echo $reviewsDislikes; ?></p>
                                </div>
                            </div>
                        </div>
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
