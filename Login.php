<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Login</title>
        <!-- Google Fonts -->
        <link href='https://fonts.googleapis.com/css?family=Roboto+Slab:400,100,300,700|Lato:400,100,300,700,900' rel='stylesheet' type='text/css'>
        <link rel="stylesheet" href="animate.css">
        <!-- Custom Stylesheet -->
        <link rel="stylesheet" href="style.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
        <script src="jquery-3.2.1.min.js"></script>
        <script>
            $(document).ready(function () {
                var password = "";
                var username = "";
                $("#login").click(function () {
                    username = $("#user").val();
                    password = $("#password").val();
                    if (username == "" || password == "") {
                        alert("some fields are empty");
                        if (username == "") {
                            $("#userError").text("Enter username");
                        }
                        if (password ==""){
                            $("#passError").text("Enter password");
                        }
                        return false;
                    }
                    var settings = {
                        type: "POST",
                        datatype: "json",
                        url: "api.php",
                        data: {"username": username, "password": password, "act": "check"},
                        success: function (result) {
                            var r= $.parseJSON(result);
                            console.log(r);
                            if (r == "true") {
                               //alert(r);
                                window.location.href = "Home.php";
                            } else if (r == "bye")
                            {
                                alert("Wrong username or password");
                                //window.location.href="Login.php";
                            }

                        },
                        error: function () {
                            alert("some problem occured");
                        }
                    };
                    $.ajax(settings);
                });
            });
        </script>
        <style>
            .modal {
                display: none; /* Hidden by default */
                position: fixed; /* Stay in place */
                z-index: 1; /* Sit on top */
                padding-top: 100px; /* Location of the box */
                left: 0;
                top: 0;
                width: 100%; /* Full width */
                height: 100%; /* Full height */
                overflow: auto; /* Enable scroll if needed */
                background-color: rgb(0,0,0); /* Fallback color */
                background-color: rgba(0,0,0,0.4); /* Black w/ opacity */

            }

            /* Modal Content */
            .modal-content {
                position: relative;
                background-color: #fefefe;
                margin: auto;
                padding: 10px;
                border: 1px solid #888;
                width: 80%;
                box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2),0 6px 20px 0 rgba(0,0,0,0.19);
                -webkit-animation-name: animatetop;
                -webkit-animation-duration: 0.4s;
                animation-name: animatetop;
                animation-duration: 0.4s
            }
            @-webkit-keyframes animatetop {
                from {top:-300px; opacity:0} 
                to {top:0; opacity:1}
            }

            @keyframes animatetop {
                from {top:-300px; opacity:0}
                to {top:0; opacity:1}
            }

            /* The Close Button */
            .close {
                color: #aaaaaa;
                float: right;
                font-size: 28px;
                font-weight: bold;
            }

            .close:hover,
            .close:focus {
                color: #000;
                text-decoration: none;
                cursor: pointer;
            }
        </style>

    </head>

    <body>

        <div class="container">
            <div class="top">
                <h1 id="title" class="hidden"><span id="logo">Security <span>Management</span></span></h1>
            </div>

            <table align="center" >
                <tr>
                    <td style="width:400px"><div class="login-box">
                            <div class="box-header">
                                <h2>Login</h2>
                            </div>
                            <label for="username">Username</label>
                            <br/>
                            <input type="text" name="user" id="user" >
                            <br/>
                            <span id="userError"></span>
                            <br/>
                            <label for="password">Password</label>
                            <br/>
                            <input type="password" id="password">
                            <br/>
                            <span id="passError"></span>
                            <br/>
                            <button type="submit" name="login" id="login">Sign In</button>
                            <br/>

                        </div></td>
                </tr>
            </table>


        </div>

        <div id="myModal" class="modal">

            <!-- Modal content -->
            <div class="modal-content">
                <span class="close">&times;</span>
                <p align="center" style="font-family: Comic Sans MS">Wrong username or password..</p>
            </div>

        </div>

    </body>

    <script>
        $(document).ready(function () {
            $('#logo').addClass('animated fadeInDown');
            $("input:text:visible:first").focus();
        });
        $('#username').focus(function () {
            $('label[for="username"]').addClass('selected');
        });
        $('#username').blur(function () {
            $('label[for="username"]').removeClass('selected');
        });
        $('#password').focus(function () {
            $('label[for="password"]').addClass('selected');
        });
        $('#password').blur(function () {
            $('label[for="password"]').removeClass('selected');
        });
    </script>

</html>