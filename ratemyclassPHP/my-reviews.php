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
        <link rel="stylesheet" href="css/view-reviews.css">
    </head>

    <body>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script>
        $(document).ready(function(){
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
        });

        $(document).ready(function(){
            $("img.dislike").click(function(){
                var reviewID = $(this).attr('id');
                var userID = $(this).attr('user');
                $.ajax({
                    url: "includes/ajax-likes.php",
                    type: "post",
                    dataType: 'json',
                    data: {review_id: reviewID, user_id: userID, l_d: "d"},
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
        });
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
                            <?php 
                                if(isset($_SESSION["useruid"])){

                                    echo "<li> <a href='my-favorites.php'>My Favorites</a></li>";

                                    echo "<li> <a class='active' href='my-reviews.php'>My Reviews</a></li>";
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

                        $postSQL = "SELECT * FROM reviews WHERE reviewsOwnerId= ?;";
                        $poststmt = mysqli_stmt_init($conn);
                        if(!mysqli_stmt_prepare($poststmt, $postSQL)){
                            header("location: ../signup.php?error=stmtFailed");
                            exit();
                        }
                    
                        mysqli_stmt_bind_param($poststmt, "s", $userId);
                        mysqli_stmt_execute($poststmt);
                    
                        $postData = mysqli_stmt_get_result($poststmt);
                    ?>
                    <form class="view-review-header"  method="post">
                        <p>Showing Your Reviews</p>
                    </form>
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
                            $reviewsClassId = $row["reviewsClassId"];

                            $sql = "SELECT * FROM classes WHERE classesId = ?;";
                            $stmt = mysqli_stmt_init($conn);
                            if(!mysqli_stmt_prepare($stmt, $sql)){
                                header("location: ../signup.php?error=stmtFailed");
                                exit();
                            }
                        
                            mysqli_stmt_bind_param($stmt, "s", $reviewsClassId);
                            mysqli_stmt_execute($stmt);
                        
                            $reviewClassData = mysqli_stmt_get_result($stmt);
                            $classRow = mysqli_fetch_assoc($reviewClassData);
                            $reviewsClassName = $classRow["classesName"];
                            $reviewsUniversityId = $classRow["classesUniId"];

                            $sql = "SELECT * FROM universities WHERE universityId = ?;";
                            $stmt = mysqli_stmt_init($conn);
                            if(!mysqli_stmt_prepare($stmt, $sql)){
                                header("location: ../signup.php?error=stmtFailed");
                                exit();
                            }
                        
                            mysqli_stmt_bind_param($stmt, "s", $reviewsUniversityId);
                            mysqli_stmt_execute($stmt);
                        
                            $reviewUniversityData = mysqli_stmt_get_result($stmt);
                            $universityRow = mysqli_fetch_assoc($reviewUniversityData);
                            $reviewsUniversityName = $universityRow["universityName"];

                            
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
                        <div class="user-review">
                            <Label>University:</Label>
                            <p><?php echo $reviewsUniversityName; ?></p>

                            <Label>Class:</Label>
                            <p><?php echo $reviewsClassName; ?></p>
                            <div class="user-review-header">
                                <div class="user-review-author">
                                    <label>User:</label>
                                    <p><?php echo $reviewsAuthorName; ?></p>
                                </div>
                                <div class="user-review-year">
                                    <label>Year:</label>
                                    <p><?php echo $year; ?></p>
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
                                    <!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js">
                                        var reviewID = "";
                                    </script>-->
                                    <img class="like" user=<?php echo $userId; ?> id=<?php echo $reviewsId; ?> src="images/like.png" alt="" width=30 height=30>
                                    <p id=<?php echo $likeId; ?> ><?php echo $likes; ?></p>
                                </div>
                                <div class=user-review-dislikes>
                                    <img class="dislike" user=<?php echo $userId; ?> id=<?php echo $reviewsId; ?> src="images/dislike.png" alt="" width=30 height=25 >
                                    <p id=<?php echo $dislikeId; ?>><?php echo $dislikes; ?></p>
                                </div>
                            </div>
                            <form class="delete-review-header" action="includes/delete.inc.php" method="post">
                                <input hidden type="text" name="reviewsId" value="<?php echo $reviewsId; ?>"/>
                                <input hidden type="text" name="classId" value="<?php echo $reviewsClassId; ?>"/>
                                <input hidden type="text" name="rating" value="<?php echo $reviewsRating; ?>"/>
                                <input name="submit" class="delete-review-button" type="submit" value="Delete"/>
                            </form>
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