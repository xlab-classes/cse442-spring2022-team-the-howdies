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

        <section class="login-form">
            <div class="login-form-form">
                <h2>Login</h2>
                <h3>Create Your New Password</h3>
                <?php 
                    $selector = $_GET["selector"];
                    $validator = $_GET["validator"];

                    if(empty($selector) || empty($validator)){
                        echo "Could not validate your request";
                    }else{
                        if(ctype_xdigit($selector) !== false && ctype_xdigit($validator) !== false){
                        ?> 
                            
                            <form action="includes/reset-password.inc.php" method="post">
                                <input type="hidden" name="selector" value="<?php echo $selector; ?>">
                                <input type="hidden" name="validator" value="<?php echo $validator; ?>">
                                <input required type="password" name="pwd" placeholder="New password. . .">
                                <input required type="password" name="pwd-repeat" placeholder="Repeat new password. . .">
                                <button type="submit" name="reset-password-submit">Reset password</button>
                            </form>
                            <?php
                        }
                    }
                ?>
            </div>
            <?php 
                if(isset($_GET["error"])){
                    if($_GET["error"] == "empty"){
                        echo "<p>Fill in all fields!</p>";
                    }else if($_GET["error"] == "pwdNotMatch"){
                        echo "<p>The password fields did not match</p>";
                    }
                }
            ?>
        </section>
        </div>