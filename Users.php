<?php
require 'validateSession.php';
require 'connector.php';
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>user</title>
        <!-- Google Fonts -->
        <link href='https://fonts.googleapis.com/css?family=Roboto+Slab:400,100,300,700|Lato:400,100,300,700,900' rel='stylesheet' type='text/css'>

        <link rel="stylesheet" href="animate.css">
        <!-- Custom Stylesheet -->
        <link rel="stylesheet" href="style1.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
        <script src="jquery-3.2.1.min.js" type="text/javascript"></script>
        <script>
            $(document).ready(function () {
                //***loading table***//
                var tableLoadSettings = {
                    type: "POST",
                    datatype: "json",
                    url: "SM_API.php",
                    data: {"act": "tableLoad"},
                    success: function (r) {
                        //alert(r);
                        var td;
                        var tr;
                        var id;
                        var $editBtn;
                        var $deleteBtn;
                        var arr_users = $.parseJSON(r);
                        for (var i = 0; i < arr_users.length; i++) {
                            tr = $("<tr>");
                            for (var k in arr_users[i]) {
                                //console.log(arr_users[i][k]);
                                if (k != "id") {
                                    td = $("<td>").text(arr_users[i][k]);
                                    tr.append(td);
                                } else {
                                    id = arr_users[i][k];
                                }
                            }
                            $editBtn = $("<button>").text("Edit");
                            $editBtn.attr("id", "edit");
                            $editBtn.attr("value", id);
                            $editBtn.attr("type", "submit");
                            td = $("<td>").append($editBtn);
                            //*** UPDATE USER***//
                            $editBtn.bind("click", function () {
                                //alert("hello");
                                $("#country").html("");
                                $("#city").html("");
                                var editID = $(this).attr("value");
                                //alert(editID);
                                var editSettings = {
                                    type: "POST",
                                    datatype: "json",
                                    url: "SM_API.php",
                                    data: {"id": editID, "act": "edit"},
                                    success: function (r) {
                                        r = $.parseJSON(r);
                                        $("#u").val(r["login"]);
                                        $("#e").val(r["email"]);
                                        $("#p").val(r["password"]);
                                        $("#n").val(r["name"]);
                                        if (r["isadmin"] == 1) {
                                            $("#yes").prop('checked', true);
                                        } else if (r["isadmin"] == 0) {
                                            $("#yes").prop('checked', false);
                                        }
                                        var settings = {
                                            type: "POST",
                                            datatype: "json",
                                            url: "SM_API.php",
                                            data: {"act": "country", "id": r["countryid"]},
                                            success: function (r) {
                                                $("#country").append(r);
                                            },
                                            error: function () {
                                                alert("some problem occured")
                                            }
                                        };
                                        $.ajax(settings);

                                        var citySettings = {
                                            type: "POST",
                                            datatype: "json",
                                            url: "SM_API.php",
                                            data: {"act": "cityFill", "id": r["cityid"], "countryid": r["countryid"]},
                                            success: function (r) {
                                                $("#city").append(r);
                                            },
                                            error: function () {
                                                alert("some problem occured")
                                            }
                                        };
                                        $.ajax(citySettings);

                                        $("#save").click(function () {
                                            var user = $("#u").val();
                                            var pass = $("#p").val();
                                            var email = $("#e").val();
                                            var coun = $("#country").val();
                                            //var counName = $("#country option:selected").text();
                                            var city = $("#city").val();
                                            //var cityName = $("#city option:selected").text();
                                            var name = $("#n").val();
                                            var is = null;
                                            if ($("#yes").is(':checked')) {
                                                is = 1;
                                            } else {
                                                is = 0;
                                            }
                                            var updateSettings = {
                                                type: "POST",
                                                datatype: "json",
                                                url: "SM_API.php",
                                                data: {"id":editID,"username": user, "password": pass, "name": name, "email": email, "country": coun, "city": city, "isadmin": is,"updated":"update", "act": "saveUser"},
                                                success: function (r) {
                                                    r = $.parseJSON(r);
                                                    if (r["emailError"] == "alreadyEmail") {
                                                        //alert(r);
                                                        $("#emailErr").text("Email already exists");
                                                    }
                                                    if (r["loginError"] == "alreadyLoggedIn") {
                                                        //alert (r);
                                                        $("#userErr").text("Username already exists");
                                                    }
                                                    if (r["added"] == "userAdded") {
                                                        alert("User Successfully added");
                                                        window.location.href = "Users.php";
                                                    }
                                                    if (r["updated"]== "updated"){
                                                        alert("update");
                                                        window.location.href="Users.php";
                                                    }
                                                },
                                                error: function () {
                                                    alert("some problem occured");
                                                }
                                            };
                                            $.ajax(updateSettings);

                                        });

                                    },

                                    error: function () {
                                        alert("some error occured");
                                    }
                                };
                                $.ajax(editSettings);
                            });

                            tr.append(td);
                            $deleteBtn = $("<button>").text("Delete");
                            $deleteBtn.attr("id", "delete");
                            $deleteBtn.attr("type", "submit");
                            $deleteBtn.attr("value", id);
                            td = $("<td>").append($deleteBtn);
                            // **** DELETE USER  ****//
                            $deleteBtn.bind("click", function () {
                                var $isConfirm = confirm("Record will be deleted. Click Ok to continue and Cancel to Ignore");
                                if ($isConfirm == true) {
                                    $(this).closest("tr").remove();
                                    var delId = $(this).attr("value");
                                    //console.log(delId);
                                    var delSettings = {
                                        type: "POST",
                                        datatype: "json",
                                        url: "SM_API.php",
                                        data: {"id": delId, "act": "delete"},
                                        success: function (r) {
                                            alert(r);
                                        },
                                        error: function () {
                                            alert("some error occured");
                                        }
                                    };
                                    $.ajax(delSettings);
                                } else
                                    return false;
                            });
                            tr.append(td);
                            $("#data").append(tr);
                        }
                    },
                    error: function () {
                        alert("some problem occured")
                    }
                };
                $.ajax(tableLoadSettings);
                
                //****LOADING COUNTRIES****//
                var settings = {
                    type: "POST",
                    datatype: "json",
                    url: "SM_API.php",
                    data: {"act": "country"},
                    success: function (r) {
                        $("#country").append(r);
                    },
                    error: function () {
                        alert("some problem occured")
                    }
                };
                $.ajax(settings);
                
                
                //LOADING CITIES ON COUNTRY CHANGE//
                $("#country").change(function () {
                    $("#city").html("");
                    var sel = $("<option>");
                    sel.val(0);
                    sel.text("--Select--");
                    $("#city").append(sel);
                    var countryid = $("#country").val();
                    //console.log(countryid);
                    var citySettings = {
                        type: "POST",
                        datatype: "json",
                        url: "SM_API.php",
                        data: {"countryid": countryid, "act": "cityFill"},
                        success: function (r) {
                            $("#city").append(r);
                        },
                        error: function () {
                            alert("some error occured");
                        }
                    };
                    $.ajax(citySettings);
                });
                
               // SAVING NEW USER//
                $("#save").click(function () {
                    var user = $("#u").val();
                    var pass = $("#p").val();
                    var email = $("#e").val();
                    var coun = $("#country").val();
                    var counName = $("#country option:selected").text();
                    var city = $("#city").val();
                    var cityName = $("#city option:selected").text();
                    var name = $("#n").val();
                    var is = null;
                    if ($("#yes").is(':checked')) {
                        is = 1;
                    } else {
                        is = 0;
                    }
                    var settings = {
                        type: "POST",
                        datatype: "json",
                        url: "SM_API.php",
                        data: {"username": user, "password": pass, "name": name, "email": email, "country": coun, "city": city, "isadmin": is, "act": "saveUser"},
                        success: function (r) {
                            r = $.parseJSON(r);
                            if (r["emailError"] == "alreadyEmail") {
                                //alert(r);
                                $("#emailErr").text("Email already exists");
                            }
                            if (r["loginError"] == "alreadyLoggedIn") {
                                //alert (r);
                                $("#userErr").text("Username already exists");
                            }
                            if (r["added"] == "userAdded") {
                                alert("User Successfully added");
                                window.location.href = "Users.php";
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

        </style>
    </head>
    <body >

        <?php include 'Header.php'; ?>

        <div class="container" id="div1" >
            <table cellspacing="4" cellpadding="60px" >
                <tr>
                    <td style="width:400px">
                        <div id="div2" class="login-box">
                            <div class="box-header">
                                <h2>User Management</h2>
                            </div>
                            <label for="username">Username</label>
                            <br/>
                            <input type="text" id="u" name="username"  ><div id="userErr" style="color:saddlebrown"> </div>
                            <br/>
                            <label for="password">Password</label>
                            <br/>
                            <input type="password" id="p" name="password" ><div id="passErr" style="color:saddlebrown"> </div>
                            <br/>
                            <label for="name">Name</label>
                            <br/>
                            <input type="text" id="n" name="name" ><div id="nameErr"  style="color:saddlebrown"> </div>
                            <br/>
                            <label for="Email">Email</label>
                            <br/>
                            <input type="email" id="e" name="email" ><div id="emailErr" style="color:saddlebrown"> </div>
                            <br/>
                            <label for="country"> Country </label>
                            <br />
                            <select name="country" class="optStyle" id="country">
                                <option value="0">--Select--</option>				

                            </select><div id="counErr" style="color:saddlebrown"></div>
                            <br/>
                            <label for="city"> City </label>
                            <br/>
                            <select name="city" class="optStyle" id="city">

                            </select><div id="cityErr" style="color:saddlebrown"></div>
                            <br/>
                            <b>is admin?</b> <input id="yes" type="checkbox" name="yes">
                            <br/>
                            <button type="submit" id="save">Save</button>
                            <br/>
                        </div>
                    </td>

                    <td style="width:auto">
                        <div id="div2" class="login-box animated fadeInUp">
                            <div class="box-header">
                                <h2>Users</h2>
                            </div>

                            <table id="data" style="border:1px; text-align: left;" cellspacing="4" cellpadding="4" >
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Country</th>
                                    <th>City</th>
                                    <th>Created By</th>
                                    <th>Created On</th>
                                    <th>Edit</th>
                                    <th>Delete</th>
                                </tr>
                            </table>
                        </div>
                    </td>
                </tr>
            </table>
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