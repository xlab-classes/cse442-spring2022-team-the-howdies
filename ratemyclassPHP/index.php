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
                        <li><a class="active" href="index.php">Home</a></li>
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
            if(isset($_SESSION["useruid"])){
                echo "<p>Hello " . $_SESSION["useruid"] . "!</p>";
            }
        ?>
        <section class="institution-search">
            <div class="institution-search-search">
                <h3>Enter your institution's name</h3>
                <form action="users.php" method="GET">
                <input id="search" type="text" placeholder="College name">
                </form>
            </div>
        </section>
