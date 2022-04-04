<?php
    //include_once 'header.php';
?>

<?php 
    session_start();
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


        <section class="review-form">
            <div class="review-form-form">
                <?php 
                    if(isset($_GET["cname"])){
                        echo "<h1>Submit a Review for " . $_GET["cname"] . "</h1>";
                    }else{
                        echo "<h1>Submit a Review</h1>";
                    }
                ?>
                <form action="includes/create-review.inc.php" method="post">
                    <input required type="text" name="title" placeholder="Title. . .">
                    <input type="text" name="professor" placeholder="Professor (optional). . .">
                    <textarea rows="10" name="review" placeholder="Write your review. . ."></textarea>
                    <h3>Please rate the class out of 10</h3>
                    <input required type="number" name="rating" min="1" max="10" placeholder="Rating">
                    <button class="submit-btn" type="submit" name="submit">Submit</button>
                    <button class="cancel-btn" name="cancel">Cancel</button>
                </form>
            </div>

            <?php 
                if(isset($_GET["error"])){
                    if($_GET["error"] == "emptyInput"){
                        echo "<p class='fail'>Fill in all fields!</p>";
                    }else if($_GET["error"] == "noUser"){
                        echo "<p class='fail'>No user was found with that email/username</p>";
                    }else if($_GET["error"] == "wrongPwd"){
                        echo "<p class='fail'>Incorrect password</p>";
                    }
                }
                if(isset($_GET["message"])){
                    if($_GET["message"] == "pwdUpdated"){
                        echo "<p class='success'>Your password has been updated!</p>";
                    }
                }
            ?>
        </section>
        </div>

        <!--<div class="create-review-form">
            <h1>Submit a Review</h1>
            <div class="create-review-form-form">
                <form action="includes/create-review.inc.php" method="post">
                    <input type="text" name="title" placeholder="Title. . .">
                    <input type="text" name="class-name" placeholder="Class. . .">
                    <input type="text" name="professor" placeholder="Professor (optional). . .">
                    <textarea name="review" placeholder="Write your review. . ."></textarea>
                    <button type="submit" name="submit">Submit</button>
                    <button type="submit" name="cancel">Cancel</button>
                </form>
            </div>

            <?/*php 
                if(isset($_GET["error"])){
                    if($_GET["error"] == "emptyInput"){
                        echo "<p class='fail'>Fill in all fields!</p>";
                    }else if($_GET["error"] == "noUser"){
                        echo "<p class='fail'>No user was found with that email/username</p>";
                    }else if($_GET["error"] == "wrongPwd"){
                        echo "<p class='fail'>Incorrect password</p>";
                    }
                }
                if(isset($_GET["message"])){
                    if($_GET["message"] == "pwdUpdated"){
                        echo "<p class='success'>Your password has been updated!</p>";
                    }
                }
            ?>
        </div>-->