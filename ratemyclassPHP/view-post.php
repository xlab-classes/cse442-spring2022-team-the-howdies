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

    function testphpfunc($temp){
        echo $temp;
    }
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
    <head>
        <meta charset="utf-8">
        <title>Rate My Classes</title>
        <link rel="stylesheet" href="css/style.css">
    </head>

    <style ref="css/view-post.css"></style>

    <body>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script>
        /*$(document).ready(function(){
            $("img.like").click(function(){
                var reviewID = $(this).attr('id');
                var userID = $(this).attr('user');
                $.ajax({
                    url: "includes/ajax-likes.php",
                    type: "post",
                    dataType: 'json',
                    data: {review_id: reviewID, user_id: userID, l_d: "l"},
                    success:function(result){
                        console.log("RESULT: " + result);
                        console.log(result.likes);
                        console.log(result.dislikes);
                        console.log(result.id);
                        console.log(result.uId);
                        console.log("#p"+reviewID);
                        var lText = $("#p"+reviewID).text(result.likes);
                        var dText = $("#pDL"+reviewID).text(result.dislikes);
                    } 
                });
            });
        }); */
    </script>

    <?php
        // if liked, removes that like; else, adds a like
        // if disliked, removes the dislike

        function onClickLikes($userId, $reviewId, $conn){
            $sql = "SELECT * FROM likes WHERE likesReviewId = ? AND likesUserId = ?;";
            $stmt = mysqli_stmt_init($conn);
            if(!mysqli_stmt_prepare($stmt, $sql)){
                //error 
                exit();
            }
            mysqli_stmt_bind_param($stmt, "ss", $reviewId, $userId);
            mysqli_stmt_execute($stmt);

            $result = mysqli_stmt_get_result($stmt);
            $resultLength = mysqli_num_rows($result);

            if($resultLength == 0){
                $sql = "INSERT INTO likes (likesReviewId, likesUserId) VALUES (?, ?);";
                $stmt = mysqli_stmt_init($conn);
                if(!mysqli_stmt_prepare($stmt, $sql)){
                    //error 
                    exit();
                }
                mysqli_stmt_bind_param($stmt, "ss", $reviewId, $userId);
                mysqli_stmt_execute($stmt);
            }else{
                $row = mysqli_fetch_assoc($result);
                $likeId = $row["likesId"];
                $sql = "DELETE FROM likes WHERE likesId=?;";
                $stmt = mysqli_stmt_init($conn);
                if(!mysqli_stmt_prepare($stmt, $sql)){
                    //error 
                    exit();
                }
                mysqli_stmt_bind_param($stmt, "s", $likeId);
                mysqli_stmt_execute($stmt);
            }

            $sql = "SELECT * FROM dislikes WHERE dislikesReviewId = ? AND dislikesUserId = ?;";
            $stmt = mysqli_stmt_init($conn);
            if(!mysqli_stmt_prepare($stmt, $sql)){
                //error 
                exit();
            }
            mysqli_stmt_bind_param($stmt, "ss", $reviewId, $userId);
            mysqli_stmt_execute($stmt);

            $result = mysqli_stmt_get_result($stmt);
            $resultLength = mysqli_num_rows($result);

            if($resultLength != 0){
                $row = mysqli_fetch_assoc($result);
                $dislikeId = $row["dislikesId"];
                $sql = "DELETE FROM dislikes WHERE dislikesId=?;";
                $stmt = mysqli_stmt_init($conn);
                if(!mysqli_stmt_prepare($stmt, $sql)){
                    //error 
                    exit();
                }
                mysqli_stmt_bind_param($stmt, "s", $dislikeId);
                mysqli_stmt_execute($stmt);
            }

            $likes = getLikes($reviewId, $conn);
            $dislikes = getDislikes($reviewId, $conn);

            return [$likes, $dislikes];
        }

        // if disliked, removes that dislike; else, adds a dislike
        // if liked, removes the like

        function onClickDislikes($userId, $reviewId, $conn){
            $sql = "SELECT * FROM dislikes WHERE dislikesReviewId = ? AND dislikesUserId = ?;";
            $stmt = mysqli_stmt_init($conn);
            if(!mysqli_stmt_prepare($stmt, $sql)){
                //error 
                exit();
            }
            mysqli_stmt_bind_param($stmt, "ss", $reviewId, $userId);
            mysqli_stmt_execute($stmt);

            $result = mysqli_stmt_get_result($stmt);
            $resultLength = mysqli_num_rows($result);

            if($resultLength == 0){
                $sql = "INSERT INTO dislikes (dislikesReviewId, dislikesUserId) VALUES (?, ?);";
                $stmt = mysqli_stmt_init($conn);
                if(!mysqli_stmt_prepare($stmt, $sql)){
                    //error 
                    exit();
                }
                mysqli_stmt_bind_param($stmt, "ss", $reviewId, $userId);
                mysqli_stmt_execute($stmt);
            }else{
                $row = mysqli_fetch_assoc($result);
                $dislikeId = $row["dislikesId"];
                $sql = "DELETE FROM dislikes WHERE dislikesId=?;";
                $stmt = mysqli_stmt_init($conn);
                if(!mysqli_stmt_prepare($stmt, $sql)){
                    //error 
                    exit();
                }
                mysqli_stmt_bind_param($stmt, "s", $dislikeId);
                mysqli_stmt_execute($stmt);
            }

            $sql = "SELECT * FROM likes WHERE likesReviewId = ? AND likesUserId = ?;";
            $stmt = mysqli_stmt_init($conn);
            if(!mysqli_stmt_prepare($stmt, $sql)){
                //error 
                exit();
            }
            mysqli_stmt_bind_param($stmt, "ss", $reviewId, $userId);
            mysqli_stmt_execute($stmt);

            $result = mysqli_stmt_get_result($stmt);
            $resultLength = mysqli_num_rows($result);

            if($resultLength != 0){
                $row = mysqli_fetch_assoc($result);
                $likeId = $row["likesId"];
                $sql = "DELETE FROM likes WHERE likesId=?;";
                $stmt = mysqli_stmt_init($conn);
                if(!mysqli_stmt_prepare($stmt, $sql)){
                    //error 
                    exit();
                }
                mysqli_stmt_bind_param($stmt, "s", $likeId);
                mysqli_stmt_execute($stmt);
            }

            $likes = getLikes($reviewId, $conn);
            $dislikes = getDislikes($reviewId, $conn);

            return [$likes, $dislikes];
        }

        // returns number of likes/dislikes on that review

        function getLikes($reviewId, $conn){
            $sql = "SELECT * FROM likes WHERE likesReviewId = ?;";
            $stmt = mysqli_stmt_init($conn);
            if(!mysqli_stmt_prepare($stmt, $sql)){
                //error 
                exit();
            }
            mysqli_stmt_bind_param($stmt, "s", $reviewId);
            mysqli_stmt_execute($stmt);

            $result = mysqli_stmt_get_result($stmt);
            $resultLength = mysqli_num_rows($result);

            return $resultLength;
        }

        function getDislikes($reviewId, $conn){
            $sql = "SELECT * FROM dislikes WHERE dislikesReviewId = ?;";
            $stmt = mysqli_stmt_init($conn);
            if(!mysqli_stmt_prepare($stmt, $sql)){
                //error 
                exit();
            }
            mysqli_stmt_bind_param($stmt, "s", $reviewId);
            mysqli_stmt_execute($stmt);

            $result = mysqli_stmt_get_result($stmt);
            $resultLength = mysqli_num_rows($result);

            return $resultLength;
        }
    ?>
    <link rel="stylesheet" href="css/view-post.css" type="text/css">
    <link rel="stylesheet" href="css/view-reviews.css" type="text/css">
        <div class="view">
            <div class="header">
                <div class="header-name">
                    <div class="header-decor-box">
                        <h1>RateMyClasses</h1>
                    </div>
                </div>
                <nav class="navbar">
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

            <section class="view-post-page">
                <div class="view-post">
                    <div class="post-container">
                        <?php
                            $classId = $_GET["classId"];
                            $className = $_GET["className"];
                            $reviewsId = $_GET["reviewsId"];
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

                            $postSQL = "SELECT * FROM reviews WHERE reviewsId= ?;";
                            $poststmt = mysqli_stmt_init($conn);
                            if(!mysqli_stmt_prepare($poststmt, $postSQL)){
                                header("location: ../signup.php?error=stmtFailed");
                                exit();
                            }
                        
                            mysqli_stmt_bind_param($poststmt, "s", $reviewsId);
                            mysqli_stmt_execute($poststmt);
                        
                            $postData = mysqli_stmt_get_result($poststmt);

                            $createHeader = "create-review.php?className=" . $className . "&classId=" . $classId;
                        ?>
                        <?php
                            $row = mysqli_fetch_assoc($postData);
                            $reviewsId = $row["reviewsId"];
                            $reviewsOwnerId = $row["reviewsOwnerId"];
                            $reviewsRating = $row["reviewsRating"];
                            $reviewsProfessor = $row["reviewsProfessor"];
                            $reviewsReview = $row["reviewsReview"];

                            
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
                            $reviewsAuthorName = $authorRow["usersUid"];
                            $year = $authorRow["usersYear"];
                            if($year == ""){
                                $year = "N/A";
                            }

                            $likeId = "p" . $reviewsId;
                            $dislikeId = "pDL" . $reviewsId;

                            $likes = getLikes($reviewsId, $conn);
                            $dislikes = getDislikes($reviewsId, $conn);

                        ?>
                        <div class="post">
                            <div class="user-review-header">
                                <div class="user-review-author">
                                    <label class="post-label">User: </label>
                                    <p><?php echo $reviewsAuthorName?></p>
                                </div>
                                <div class="user-review-year">
                                    <label class="post-label">Year: </label>
                                    <p><?php echo $year?></p>
                                </div>
                                <div class="user-review-rating">
                                    <label class="post-label">Rating: </label>
                                    <p><?php echo $reviewsRating?></p>
                                </div>
                            </div>

                            <div class="user-review-professor-container">
                                <div class="user-review-professor">
                                    <label class="post-label">Professor: </label>
                                    <p><?php echo $reviewsProfessor?></p>
                                </div>
                            </div>
                            <div class="user-review-content-container">
                                <div class="user-review-content">
                                    <label class="post-label">User Review: </label>
                                    <p><?php echo $reviewsReview?></p>
                                </div>
                            </div>
                            
                        </div>
                        <div class="comment-container">
                            <form method="post" action="includes/comments.inc.php">
                                <input type="text" name="commentComment" class="create-comment-field" placeholder="Enter your comment here">
                                <input type="hidden" name="reviewsId" value=<?php echo $_GET["reviewsId"] ?>>
                                <input type="hidden" name="className" value=<?php echo $_GET["className"] ?>>
                                <input type="hidden" name="classId" value=<?php echo $_GET["classId"] ?>>
                                <input type="hidden" name="userId" value=<?php echo $_SESSION["userid"] ?>>
                                <button name="submit" type="submit" class="submit-comment-btn">Submit</button>
                            </form>
                        </div>

                        <?php
                        
                            require_once 'includes/dbh.inc.php';

                            $sql = "SELECT * FROM comments WHERE commentReviewId = ?;";
                            $stmt = mysqli_stmt_init($conn);
                            if(!mysqli_stmt_prepare($stmt, $sql)){
                                header("location: ../signup.php?error=stmtFailed");
                                exit();
                            }
                        
                            mysqli_stmt_bind_param($stmt, "s", $_GET["reviewsId"]);
                            mysqli_stmt_execute($stmt);
                        
                            $commentsData = mysqli_stmt_get_result($stmt);

                            //$createHeader = "create-review.php?className=" . $className . "&classId=" . $classId;
                        ?>

                        <div class="user-review-list">
                            <?php
                            $resultLength = mysqli_num_rows($commentsData);
                            for ($x = 0; $x < $resultLength; $x++){
                                $row = mysqli_fetch_assoc($commentsData);
                                $comment = $row["commentComment"];
                                $commentOwnerId = $row["commentUserId"];

                                
                                $sql = "SELECT * FROM users WHERE usersId = ?;";
                                $stmt = mysqli_stmt_init($conn);
                                if(!mysqli_stmt_prepare($stmt, $sql)){
                                    header("location: ../signup.php?error=stmtFailed");
                                    exit();
                                }
                            
                                mysqli_stmt_bind_param($stmt, "s", $commentOwnerId);
                                mysqli_stmt_execute($stmt);
                            
                                $reviewAuthorData = mysqli_stmt_get_result($stmt);
                                $authorRow = mysqli_fetch_assoc($reviewAuthorData);
                                $reviewsAuthorName = $authorRow["usersUid"];

                            ?>
                            <div class="user-review">
                                <label><?php echo $reviewsAuthorName; ?></label>
                                <p><?php echo $comment; ?></p>
                            </div>
                            <br>
                            <?php
                            }
                            ?>
                        </div>
                    </div>
                    <?php
                    // }
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

    </html>
