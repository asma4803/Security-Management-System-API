<?php
require 'validateSession.php';
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>role</title>
        <!-- Google Fonts -->
        <link href='https://fonts.googleapis.com/css?family=Roboto+Slab:400,100,300,700|Lato:400,100,300,700,900' rel='stylesheet' type='text/css'>

        <link rel="stylesheet" href="animate.css">
        <!-- Custom Stylesheet -->
        <link rel="stylesheet" href="style1.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
        <script src="jquery-3.2.1.min.js" type="text/javascript"></script>
        <script>
            window.flag = false;
            $(document).ready(function () {
                var tableLoadSettings = {
                    type: "POST",
                    datatype: "json",
                    url: "SM_API.php",
                    data: {"act": "loadRoleTable"},
                    success: function (r) {
                        var td;
                        var tr;
                        var $delBtn;
                        var $editBtn;
                        var roleid;
                        var table = $("#data");
                        var res = $.parseJSON(r);
                        for (var i = 0; i < res.length; i++) {
                            tr = $("<tr>");
                            for (var k in res[i]) {
                                if (k != "roleid") {
                                    td = $("<td>").text(res[i][k]);
                                    tr.append(td);
                                } else {
                                    roleid = res[i][k];
                                }
                            }

                            $editBtn = $("<button>").text("Edit");
                            $editBtn.attr("type", "submit");
                            $editBtn.attr("value", roleid);
                            $editBtn.attr("id", "edit");
                            td = $("<td>").append($editBtn);
                            $editBtn.bind("click", function () {
                                flag = true;
                                var editID = $(this).attr("value");
                                var editSettings = {
                                    type: "POST",
                                    datatype: "json",
                                    url: "SM_API.php",
                                    data: {"id": editID, "act": "editRole"},
                                    success: function (response) {
                                        response = $.parseJSON(response);
                                        $("#role").val(response["role"]);
                                        $("#description").val(response["description"]);
                                        var $sBtn = $("#save");
                                        if (flag == true) {
                                            $sBtn.click(function () {
                                                var u_role = $("#role").val();
                                                var u_description = $("#description").val();
                                                //alert(u_description);
                                                var updateSettings = {
                                                    type: "POST",
                                                    datatype: "json",
                                                    url: "SM_API.php",
                                                    data: {"id": editID, "role": u_role, "description": u_description, "act": "saveNewRole", "updation": "update"},
                                                    success: function (r) {
                                                        r = $.parseJSON(r);
                                                        alert(r["updateRole"]);
                                                        window.location.href="Roles.php";
                                                    },
                                                    error: function () {
                                                        alert("some problem occured");
                                                    }
                                                };
                                                $.ajax(updateSettings);
                                                // alert("hello");
                                                //e.preventDefault();
                                            });
                                        }
                                    },
                                    error: function () {
                                        alert("Some error occured");
                                    }
                                };
                                $.ajax(editSettings);
                            });
                            tr.append(td);
                            $delBtn = $("<button>").text("Delete");
                            $delBtn.attr("type", "submit");
                            $delBtn.attr("value", roleid);
                            $delBtn.attr("id", "edit");
                            td = $("<td>").append($delBtn);
                            $delBtn.bind("click", function () {
                                var $isConfirm = confirm("Record will be deleted. Click Ok to continue and Cancel to Ignore");
                                if ($isConfirm == true) {
                                    $(this).closest("tr").remove();
                                    var delId = $(this).attr("value");
                                    var delSettings = {
                                        type: "POST",
                                        datatype: "json",
                                        url: "SM_API.php",
                                        data: {"id": delId, "act": "deleteRole"},
                                        success: function (r) {
                                            r = $.parseJSON(r);
                                            alert(r);
                                        },
                                        error: function () {
                                            alert("some problem occured");
                                        }
                                    };
                                    $.ajax(delSettings);
                                } else
                                    return false;
                            });
                            tr.append(td);
                            table.append(tr);
                        }
                    },
                    error: function () {
                        alert("some problem occured");
                    }
                };
                $.ajax(tableLoadSettings);
                var $newSave = $("#save");
                $newSave.click(function () {
                    if (flag == false) {
                        var role = $("#role").val();
                        var description = $("#description").val();
                        var saveSettings = {
                            data: "POST",
                            datatype: "json",
                            url: "SM_API.php",
                            data: {"role": role, "description": description, "act": "saveNewRole"},
                            success: function (r) {
                                r = $.parseJSON(r);
                                alert(r["newRole"]);
                                window.location.href="Roles.php";
                            },
                            error: function () {
                                alert("some problem occured")
                            }
                        };
                        $.ajax(saveSettings);
                    }
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
                                <h2>Role Management</h2>
                            </div>
                            <label for="role">Role</label>
                            <br/>
                            <input type="text" id="role" name="role"  ><div id="roleErr" style="color:saddlebrown"> </div>
                            <br/>
                            <label for="description">Description</label>
                            <br/>
                            <input type="text" id="description" name="description" >
                            <br/>
                            <button type="submit" id="save">Save</button>
                            <br/>
                        </div>
                    </td>

                    <td style="width:auto">
                        <div id="div2" class="login-box animated fadeInUp">
                            <div class="box-header">
                                <h2>Roles</h2>
                            </div>

                            <table id="data" style="border:1px; text-align: left;" cellspacing="4" cellpadding="4" >
                                <tr>
                                    <th>Name</th>
                                    <th>Description</th>
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