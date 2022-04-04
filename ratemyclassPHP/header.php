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
        <nav>
            <div class="wrapper">
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="discover.php">About Us</a></li>
                    <li><a href="blog.php">Find Blogs</a></li>
                    <?php 
                        if(isset($_SESSION["useruid"])){
                            echo "<li><a href='profile.php'>Profile</a></li>";
                            echo "<li style='float:right'><a href='includes/logout.inc.php'>Logout</a></li>";
                        }else{
                            echo "<li style='float:right'><a href='signup.php'>Sign up</a></li>";
                            echo "<li style='float:right'><a href='login.php'>Login</a></li>";
                        }
                    ?>
                </ul>
            </div>
        </nav>