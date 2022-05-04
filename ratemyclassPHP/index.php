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
                <div class="header-decor-box">
                    <h1>RateMyClasses</h1>
                </div>
            </div>
            <nav class="navbar">
                <div class="wrapper">
                    <ul>
                        <li><a class="active" href="index.php">Home</a></li>
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

        <?php
           /* if(isset($_SESSION["useruid"])){
                echo "<p>Hello " . $_SESSION["useruid"] . "!</p>";
            }
            if(isset($_SESSION["uniId"])) {
                echo "<p>Your university is: " . $_SESSION["uniName"] . "</p>";
            }
            if(isset($_GET["error"])){ 
                
            }*/
        ?>
        <section class="university-search">
            <div class="university-search-search">
                <h3 id="searchbar-title">Enter your university name</h3>
                <form action="includes/university-search.inc.php" method="post">
                    <Label class="add-university-text">University Name:</Label>
                    <input id="search" type="text" name="uniName" placeholder="University name">
                    <button id="searchbar-submit-btn" type="search" name="submit">Search</button>
                </form>
                
                <?php 
                    if(isset($_GET["error"])){    
                ?>
                    <br></br><br></br>
                    <form action="includes/add-university.inc.php" method="post">
                        <h3 class="add-university-text">Your university is not in the application. Add it in here!</h3>
                        <Label class="add-university-text">University Name:</Label>
                        <input class="" type="text" name="uniName" value="<?php echo $_GET["uniName"]; ?>">
                        <button id="add-university-btn" type="search" name="submit">Add University</button>
                    </form>
                <?php
                    }
                ?>

                <?php
                    if(isset($_GET["possible"])){
                        $possible = $_SESSION["possibleUnis"];
                        $count = count($possible);
                        ?>
                            <h3 class="au-or-text">OR</h3>
                            <h3 class="au-relatedUni-text">Are any of the following the university you are looking for?</h3>
                        <?php 

                        foreach ($possible as $possibleArr){
                            $possibleName = $possibleArr['uniName'];
                            $possibleId = $possibleArr['uniId'];

                            $header = "view-classes.php?uniId=" . $possibleId . "&uniName=" . $possibleName;
                        ?>
                            
                            <a class="class-name-link"href="<?php echo $header?>"><?php echo $possibleName?></a>
                    
                        <?php     
                        }
                    }
                ?>

                <?php
                    if(isset($_GET["error"])){   
                        if($_GET["error"] == "stmtFailed"){
                            echo "<p class='fail'>There was an error.</p>";
                        }
                        if($_GET["error"] == "nameTaken"){
                            echo "<p class='fail'>That university name is already used.</p>";
                        }
                    }

                    if(isset($_GET["success"])){
                        echo "<p class='success'>Your university has been added! Search for it above.</p>";
                    }
                ?>
            </div>
        </section>
