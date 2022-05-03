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
                                echo "<li style='float:right'><a class='active' href='signup.php'>Sign up</a></li>";
                                echo "<li style='float:right'><a href='login.php'>Login</a></li>";
                            }
                        ?>
                    </ul>
                </div>
            </nav>
        </div>
        <section class="signup-form">
            <div class="signup-form-form">
                <h1>Welcome to RateMyClasses!</h1>
                <h3>Sign Up</h3>
                <form action="includes/signup.inc.php" method="post">
                    <input required type="text" name="uid" placeholder="Username. . .">
                    <input required type="email" name="email" placeholder="Email. . .">
                    <input required type="password" name="pwd" placeholder="Password. . .">
                    <input required type="password" name="pwdrepeat" placeholder="Repeat password. . .">
                    <button type="submit" name="submit">Sign Up!</button>
                </form>
            </div>

            <?php 
                if(isset($_GET["error"])){
                    if($_GET["error"] == "emptyInput"){
                        echo "<p class='fail'>Error: Fill in all fields!</p>";
                    }else if($_GET["error"] == "invalidUid"){
                        echo "<p class='fail'>Error: Username can only contain a-z, A-Z, and 0-9</p>";
                    }else if($_GET["error"] == "invalidEmail"){
                        echo "<p class='fail'>Error: Enter a valid email</p>";
                    }else if($_GET["error"] == "pwdNotMatch"){
                        echo "<p class='fail'>Error: The passwords did not match</p>";
                    }else if($_GET["error"] == "usernameTaken"){
                        echo "<p class='fail'>Error: Username or email taken</p>";
                    }else if($_GET["error"] == "stmtFailed"){
                        echo "<p class='fail'>Error: Something went wrong</p>";
                    }
                }else{
                    //echo "<p>You have signed up!</p>";
                }
            ?>
        </section>
        </div>