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
        <title>Rate My Classes</title>
        <link rel="stylesheet" href="css/view-classes.css">
    </head>

    <body>


    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script>
        $(document).ready(function(){
            $("p.class-favorite").click(function(){
                var className = $(this).attr('className');
                var classId = $(this).attr('classId');
                var uniId = $(this).attr('uniId');
                var uniName = $(this).attr('uniName');
                var userId = $(this).attr('userId');

                $.ajax({
                    url: "includes/ajax-favorite.php",
                    type: "post",
                    dataType: 'json',
                    data: {class_name: className, class_id: classId, uni_id: uniId, uni_name: uniName, user_id: userId},
                    success:function(result){
                        var lText = $("#favorite"+classId).text(result.message);
                    }
                });
            });
        });
    </script>


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

                                    echo "<li> <a href='my-reviews.php'>My Reviews</a></li>";
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


            <!-- vc short for view classes -->
            <section class="vc-page">
                <div class="vc-display-box">
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
                    <div class="vc-header">
                        <p id="vc-header-intro">You are now viewing courses from: </p>
                        <p id="vc-header-uniName"><?php echo $uniName?></p>
                    </div>
            
                    <div class="class-display-box">
                        <div class="class-sortby-box">
                        </div>
                        <div class="class-display-list">
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

                            $favSql = "SELECT * FROM favorites WHERE favoritesUserId = ? AND favoritesClassId = ?;";
                            $favStmt = mysqli_stmt_init($conn);
                            if(!mysqli_stmt_prepare($favStmt, $favSql)){
                                //error 
                                header("location: ../signup.php?error=stmtFailed");
                                exit();
                            }
                            mysqli_stmt_bind_param($favStmt, "ss", $userId, $classId);
                            mysqli_stmt_execute($favStmt);

                            $favData = mysqli_stmt_get_result($favStmt);
                            $favLength = mysqli_num_rows($favData);

                            $favoriteMessage = "";


                            if($favLength > 0){
                                $favoriteMessage = "Unfavorite";
                            }else{
                                $favoriteMessage = "Favorite";
                            }

                            //$favoriteMessage = "Favorite";

                            $header = "view-reviews.php?className=". $className . "&classId=" . $classId;
                            $favoriteId = "favorite" . $classId;

                        ?>
                        <form class="class-body-box" action=<?php echo $header; ?> method="post">
                        <div class="class-display-template">
                            <div class="class-header">
                                    <a class="class-name-link"href="<?php echo $header?>"><?php echo $className?></a>
                                    <p id="avg-rating">Avg. Rating: <?php echo $classAvg; ?>/10</p>
                            </div>
                            <div class="class-content">
                                <div class="class-info">
                                    <p id="total-ratings">Class Total Reviews: <?php echo $classNum; ?></p>
                                    <p id="rating-sum">Class Rating Sum: <?php echo $classSum; ?></p>
                                    
                                </div>
                                <p id=<?php echo $favoriteId; ?> class="class-favorite" userId=<?php echo $userId; ?> classId=<?php echo $classId; ?> className=<?php echo str_replace(" ", "`", $className); ?> uniName=<?php echo str_replace(" ", "`", $uniName); ?> uniId=<?php echo $uniId; ?>><?php echo $favoriteMessage; ?></p>
                                <!-- <button type="submit">View Reviews</button> -->
                            </div>
                        </div>
                        </form>
                        <br>
                        <?php
                        }
                        ?>
                    </div>
                    </div>
                    
                    <br></br>
                    <form class="vc-addClass-form" action="includes/add-class.inc.php" method="post">
                        <Label class="add-class-text">Don't see your class? Add it here!</Label>
                        <div class="newclass-field-box">
                            <input required name="newClassName" type="text" placeholder="Enter class name"/>
                            <input type="hidden" name="uniId" value="<?php echo $uniId; ?>">
                            <input type="hidden" name="uniName" value="<?php echo $uniName; ?>">
                            <button class="vc-addClass-btn" type="submit" name="submit" value="Add Class">Add Class</button>
                        </div>
                    </form>

                    <?php 
                    if(isset($_GET["error"])){
                        if($_GET["error"] == "invalid"){
                            echo "<p class='fail'>There was an error</p>";
                        }
                        if($_GET["error"] == "nameTaken"){
                            echo "<p class='fail'>That class name is already used</p>";
                        }
                    }
                    
                    if(isset($_GET["success"])){
                        echo "<p class='success'>Your class has been added</p>";
                    }
                ?>
                </div>
            </section>
        </div>