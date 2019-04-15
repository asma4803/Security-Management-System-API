<?php
session_start();
require 'connector.php';
?>
<html>
    <head>
        <title> Home </title>
        <link href='https://fonts.googleapis.com/css?family=Roboto+Slab:400,100,300,700|Lato:400,100,300,700,900' rel='stylesheet' type='text/css'>

        <link rel="stylesheet" href="animate.css">
        <!-- Custom Stylesheet -->
        <link rel="stylesheet" href="style1.css">

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
    </head>
    <style>
        body{
            margin: 0px;
            background-image: url("photo_bg.jpg");
            height: auto;
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
        }
        .topnav {
            overflow: hidden;
            background-color: #333;
        }

        .topnav a {
            float: left;
            color: #f2f2f2;
            text-align: center;
            padding: 14px 16px;
            text-decoration: none;
            font-size: 16px;
            font-family: Comic Sans MS;
        }

        .topnav a:hover {
            background-color: #F0B27A;
            color: black;
            text-shadow: 0px 0px 5px white;
        }

        .topnav a.active {
            background-color: #935116;
            color: white;

        }
    </style>
</head>
<body >
    <?php
    $c_id = $_SESSION["userid"];
    if ($_SESSION["isadmin"] == 1) {
        include 'Header.php';
        ?>
        <br><br><br><br><br><br><br>
        <p style="color:White; font-size:400%; text-align:center; padding-top:20px; color:black; text-shadow: 3px 3px 5px saddlebrown;">Welcome</p>

        <?php
    }
    ?>

    <?php
    if ($_SESSION["isadmin"] == 0) {
        ?>
        <div class="topnav">
            <a class="active" href="#" style="float: left">Home</a>
            <a href="Logout.php" >Logout</a>
            <p id="p1"></p>
        </div>

        <br><br><br><br><br><br>
        <p style="color:White; font-size:400%; text-align:center; padding-top:20px; color:black; text-shadow: 3px 3px 5px saddlebrown;">Welcome</p>



        <div class="container" id="div1" >
            <div id="div2" class="login-box">
                <div class="box-header">
                    <h2>Details</h2>
                </div>
                <label for="role"><u>Role </u></label>
                <br/>
                <?php
                //echo $c_id;
                $q1 = "SELECT * from user_role where userid='" . $_SESSION["userid"] . "'";
                $res1 = mysqli_query($conn, $q1);
                if (mysqli_num_rows($res1) > 0) {
                    $row1 = mysqli_fetch_assoc($res1);
                    $roleid = $row1["roleid"];
                }
                $q2 = "Select name from roles where roleid='" . $roleid . "'";
                $res2 = mysqli_query($conn, $q2);
                if (mysqli_num_rows($res2) > 0) {
                    while ($row2 = mysqli_fetch_assoc($res2)) {
                        echo "<b style='font-size:20px; font-Family:Comic Sans MS'>$row2[name]</b>";
                    }
                }
                ?>
                <br/>
                <br/>
                <label for="permission"><u>Permission</u></label>
                <br/>
                <?php
                $q3 = "SELECT name from permissions where permissionid=ANY(Select permissionid from role_permission where roleid='" . $roleid . "')";
                $res3 = mysqli_query($conn, $q3);
                if (mysqli_num_rows($res3) > 0) {
                    while ($row3 = mysqli_fetch_assoc($res3)) {
                        echo "<b style='font-size:17px; font-Family:Comic Sans MS'>> " . $row3["name"] . "</b><br>";
                    }
                }
                ?> 
                <br/>

            </div>
        </div>

        <?php
    }
    ?>


</body>
</html>