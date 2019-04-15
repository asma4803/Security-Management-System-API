<?php
require ('connector.php');
require ('validateSession.php');
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Login History</title>
        <!-- Google Fonts -->
        <link href='https://fonts.googleapis.com/css?family=Roboto+Slab:400,100,300,700|Lato:400,100,300,700,900' rel='stylesheet' type='text/css'>

        <link rel="stylesheet" href="animate.css">
        <!-- Custom Stylesheet -->
        <link rel="stylesheet" href="style1.css">

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
        <script src="jquery-3.2.1.min.js" type="text/javascript"></script>
        <script>
            $(document).ready(function(){
                 var table = $("#data");
                var tr;
                var td;
                var loadLoginTableSettings= {
                    type:"POST",
                    datatype:"json",
                    url:"SM_API.php",
                    data :{"act":"loadLoginHistoryTable"},
                    success:function(r){
                        r= $.parseJSON(r);
                        for (var i =0; i< r.length ;i++){
                            tr= $("<tr>");
                            for (var k in r[i]){
                                td = $("<td>").text(r[i][k]);
                                tr.append(td);
                            }
                            table.append(tr);
                        }
                    },
                    error:function(){
                        alert("some problem occured")
                    }
                }; 
                $.ajax(loadLoginTableSettings);
            });
            
               
        </script>

        <style>

            .optStyle{
                width:190px ;
                margin-bottom: 20px;
                padding: 8px;
                border: 1px solid #ccc;
                border-radius: 2px;
                font-size: .9em;
                color: #888;
            }

            body{
                margin: 0px;
                background-image: url("photo_bg.jpg");
                height: auto;
                background-position: center;
                background-repeat: no-repeat;
                background-size: cover;
            }

            #abc{
                margin-top: 0px;
                border: 0;
                border-radius: 2px;
                color: white;
                padding: 10px;
                text-transform: uppercase;
                font-weight: 400;
                font-size: 0.7em;
                letter-spacing: 1px;
                background-color: #665851;
                cursor:pointer;
                outline: none;
            }
            #abc:hover{
                opacity: 0.7;
                transition: 0.5s;
            }

        </style>


    </head>

    <body>

        <?php include 'Header.php'; ?>

        <div class="container">
            <form action="" method="POST">
                <div class="login-box">
                    <div class="box-header">
                        <h2>Login History</h2>
                    </div>
                    <table id="data" align="center" width="900px" cellspacing="4" cellpadding="4" style="text-align: center">
                        <tr>
                            <th>Name</th>
                            <th>Login Time</th>
                            <th>Machine IP</th>

                        </tr>

                        

                    </table>

                </div>

            </form>

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