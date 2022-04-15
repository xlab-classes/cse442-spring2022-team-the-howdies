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

        <?php 
            $userId = $_SESSION["userid"];

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
            $row = mysqli_fetch_assoc($currentUserData);

            $picPath = $row["usersPicPath"];
            $bio = $row["usersBio"];
            $un = $row["usersUid"];
            $year = $row["usersYear"];

        ?>

        <div class="profile-container">
            <div class="profile-picture-container">
                <h2 class="label-text1"><?php echo $un; ?></h2>
                <img src="<?php echo $picPath; ?>" class="profile-picture"></img>
                <form action="includes/profile-img.inc.php" method="POST" enctype="multipart/form-data" class="pfpform">
                    <input type="hidden" name="uId" value="<?php echo $userId; ?>">
                    <input type="file" name="profilePicture" class="pfpbutton">
                    <input type="submit" name="submit" value="Change!" class="pfpbutton">
                </form>
                <?php 
                if(isset($_GET["error"])){
                    if($_GET["error"] == "error"){
                        echo "<p class='fail'>There was an error</p>";
                    }
                    if($_GET["error"] == "notReal"){
                        echo "<p class='fail'>The file needs to be a .jpg, .png, or .jpeg</p>";
                    }
                    if($_GET["error"] == "fileType"){
                        echo "<p class='fail'>The file needs to be a .jpg, .png, or .jpeg</p>";
                    }
                }
                ?>
            </div>

            <div class="bio-container">
                <form action="includes/profile-bio.inc.php" method="POST" enctype="multipart/form-data">
                    <label for="year" class="label-text2">Enter your current year:</label><br>
                    <input name="year-input" type="text" value=<?php echo $year; ?>></input><br>
                    <label for="bio" class="label-text2">Enter your bio here:</label>
                    <input type="hidden" name="uId" value="<?php echo $userId; ?>">
                    <textarea name="bio-input" class="textarea1"><?php echo $bio; ?></textarea>
                    <input type="submit" name="submit"/>
                </form>
            </div>
            <?php 
                if(isset($_GET["success"])){
                    if($_GET["success"] == "success"){
                        echo "<p class='success'>Your profile has been updated!</p>";
                    }
                }
                ?>
        </div>
        </div>
