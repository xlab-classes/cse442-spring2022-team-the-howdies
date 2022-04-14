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
                        <li><a href="university-select.php">Find or Create Reviews</a></li>
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
                    $classId = $_GET["classId"];
                    $className = $_GET["className"];
                    $userId = $_SESSION["userid"];
                    if(empty($classId) || empty($className)){
                        echo "<p class='fail'>There was an error</p>";
                    }else{
                    echo "<h1>Submit a Review for " . $_GET["className"] . "</h1>";
                    $cancelHeader = "view-reviews.php?className=" . $className . "&classId=" . $classId;
                ?>
                <form action="includes/create-review.inc.php" method="post">
                    <input type="hidden" name="classId" value="<?php echo $classId; ?>">
                    <input type="hidden" name="className" value="<?php echo $className; ?>">
                    <input type="hidden" name="ownerId" value="<?php echo $userId; ?>">
                    <input type="text" name="professor" placeholder="Professor (optional). . .">
                    <textarea required rows="10" name="review" placeholder="Write your review. . ."></textarea>
                    <h3>Please rate the class out of 10</h3>
                    <input required type="number" name="rating" min="1" max="10" placeholder="Rating">
                    <button class="submit-btn" type="submit" name="submit">Submit</button>
                    <form action=<?php echo $cancelHeader; ?> method="post">
                        <button type="submit" class="cancel-btn" name="cancel">Cancel</button>
                    </from>
                </form>
                <?php 
                    }
                ?>
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
