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
                        <?php 
                            if(isset($_SESSION["useruid"])){
                                echo "<li> <a href='university-select.php'>My Favorites</a></li>";
                                echo "<li> <a href='my-reviews.php'>My Reviews</a></li>";
                                echo "<li style='float:right'><a href='includes/logout.inc.php'>Logout</a></li>";
                            }else{
                                echo "<li style='float:right'><a href='signup.php'>Sign up</a></li>";
                                echo "<li style='float:right'><a class='active' href='login.php'>Login</a></li>";
                            }
                        ?>
                    </ul>
                </div>
            </nav>
        </div>
        <section class="login-form">
            <div class="login-form-form">
                <h1>Welcome Back to RateMyClasses!</h1>
                <h3>Login</h3>
                <form action="includes/login.inc.php" method="post">
                    <input required type="text" name="uid" placeholder="Username/Email. . .">
                    <input required type="password" name="pwd" placeholder="Password. . .">
                    <button type="submit" name="submit">Login!</button>
                    <a class="reset-link" href="reset-password.php">Forgot your password?</a>
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