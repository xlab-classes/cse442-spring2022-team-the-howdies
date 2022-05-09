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
        <title>Rate My Classes</title>
        <link rel="stylesheet" href="css/reset-password.css">
    </head>

    <body>
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

        <section class="reset-password-form">
            <div class="reset-password-form-form">
                <h1>Reset your password</h1>
                <p class="reset-message">An email will be sent with instructions on how to reset your password.</p>
                <form action="includes/reset-requests.inc.php" method="post">
                    <input required type="email" name="email" placeholder="Email. . .">
                    <button type="submit" name="reset-request-submit">Send</button>
                </form>
                <?php 
                    if(isset($_GET["reset"])){
                        if($_GET["reset"] == "success"){
                            echo '<p class="success">Check your email!</p>';
                        }
                    }

                    if(isset($_GET["error"])){
                        if($_GET["error"] == "stmtFailed"){
                            echo '<p class="fail">There was an error: please try again</p>';
                        }

                        if($_GET["error"] == "noUser"){
                            echo '<p class="fail">There is no user with that email</p>';
                        }
                    }
                ?>
            </div>
        </section>
        </div>