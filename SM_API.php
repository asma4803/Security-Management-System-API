<?php
require ('validateSession.php');
require ('connector.php');
if ($_REQUEST["act"] == "u") {
    if ($_REQUEST["username"] == "") {
        $obj = "Enter Username";
        //$r= json_encode($obj);
        echo json_encode($obj);
    }
}
if ($_REQUEST["act"] == "pl") {
    if ($_REQUEST["password"] == "") {
        $obj_password = "Enter Password";
        echo json_encode($obj_password);
    }
}
if ($_REQUEST["act"] == "country") {
    if (isset($_REQUEST["id"]) == true) {
        $s_id = $_REQUEST["id"];
    }
    $sql = "SELECT id,name FROM country";
    $result = mysqli_query($conn, $sql);
    $recordsFound = mysqli_num_rows($result);
    if ($recordsFound > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $id = $row["id"];
            $name = $row["name"];
            if ($id == $s_id) {
                echo "<option selected value='$id'>$name</option>";
            } else {
                echo "<option value='$id'>$name</option>";
            }
        }
    }
}
if ($_REQUEST["act"] == "cityFill") {
    $cid = $_REQUEST["countryid"];
    if (isset($_REQUEST["id"]) == true) {
        $s_id = $_REQUEST["id"];
    }
    $sql1 = "SELECT ID,Name FROM city where countryid='" . $cid . "'";

    $result1 = mysqli_query($conn, $sql1);
    $recordsFound1 = mysqli_num_rows($result1);

    if ($recordsFound1 > 0) {
        //echo $recordsFound;
        while ($row1 = mysqli_fetch_assoc($result1)) {

            $id = $row1["ID"];
            $name = $row1["Name"];
            if ($id == $s_id) {
                echo "<option selected value='$id'>$name</option>";
            } else {
                echo "<option value='$id'>$name</option>";
            }
        }
    }
}

if ($_REQUEST["act"] == "saveUser") {
    $objUser = array();
    if (isset($_REQUEST["updated"]) == true) {
        $id = $_REQUEST["id"];
        $username = $_REQUEST["username"];
        $password = $_REQUEST["password"];
        $name = $_REQUEST["name"];
        $country = $_REQUEST["country"];
        $email = $_REQUEST["email"];
        $city = $_REQUEST["city"];
        $is = $_REQUEST["isadmin"];

        $uSql = "UPDATE `users` SET `login` = '" . $username . "', `password` = '" . $password . "', `name` = '" . $name . "', `email` = '" . $email . "', `countryid` = '" . $country . "', `cityid` = '" . $city . "', `isadmin` = '$is' WHERE `users`.`userid` = '" . $id . "';";
        if (mysqli_query($conn, $uSql)) {
            $objUser["updated"] = "updated";
            echo json_encode($objUser);
        }
    } else {
        $username = $_REQUEST["username"];
        $password = $_REQUEST["password"];
        $name = $_REQUEST["name"];
        $country = $_REQUEST["country"];
        $email = $_REQUEST["email"];
        $city = $_REQUEST["city"];
        $date = date("Y-m-d H:i:s");
        $is = $_REQUEST["isadmin"];
        $flagLogin = true;
        $flagEmail = true;



        $sql3 = "SELECT login,email from users";
        $result3 = mysqli_query($conn, $sql3);
        while ($row3 = mysqli_fetch_assoc($result3)) {
            if ($username == $row3["login"]) {
                $flagLogin = false;
            }
            if ($email == $row3["email"]) {
                $flagEmail = false;
            }
        }
        if ($username != "" && $password != "" && $country != 0 && $email != "" && $name != "" && $flagEmail == true && $flagLogin == true && $city != 0) {
            $addedBy = $_SESSION["canAdd"];
            $sql = "INSERT INTO `users` (`userid`, `login`, `password`, `name`, `email`, `countryid`, `createdon`, `createdby`, `isadmin`,`cityid`) VALUES (NULL, '" . $username . "', '" . $password . "', '" . $name . "', '" . $email . "', '" . $country . "', '" . $date . "', '" . $addedBy . "', '" . $is . "','" . $city . "');";
            if (mysqli_query($conn, $sql)) {
                //echo "user added";
                $add = "userAdded";
                $objUser["added"] = $add;
            }
        } else if ($flagLogin == false) {
            //echo "username already exists";
            $l = "alreadyLoggedIn";
            $objUser["loginError"] = $l;
        } else if ($flagEmail == false) {
            //echo "Email already exists";
            $e = "alreadyEmail";
            $objUser["emailError"] = $e;
        }
        echo json_encode($objUser);
    }
}

if ($_REQUEST["act"] == "tableLoad") {
    $objTable = array();
    $total = array();
    $sql = "Select * from users";
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) {
        $i = 0;
        while ($row = mysqli_fetch_assoc($result)) {

            $sql1 = "SELECT name from country where id='" . $row["countryid"] . "'";
            $result1 = mysqli_query($conn, $sql1);
            $row1 = mysqli_fetch_assoc($result1);



            $sql2 = "SELECT name from users where userid='" . $row["createdby"] . "'";
            $result2 = mysqli_query($conn, $sql2);
            $row2 = mysqli_fetch_assoc($result2);
            if (mysqli_num_rows($result2) > 0) {
                $createdby = $row2["name"];
            } else {
                $createdby = "N/A";
            }

            $sql3 = "SELECT Name from city where ID='" . $row["cityid"] . "'";
            $result3 = mysqli_query($conn, $sql3);
            $row3 = mysqli_fetch_assoc($result3);
            $objTable["id"] = $row["userid"];
            $objTable["name"] = $row["name"];
            $objTable["email"] = $row["email"];
            $objTable["countryid"] = $row1["name"];
            $objTable["cityid"] = $row3["Name"];
            $objTable["createdby"] = $createdby;
            $objTable["createdon"] = $row["createdon"];
            $total[$i] = $objTable;
            $i++;
        }
        echo json_encode($total);
    }
}
if ($_REQUEST["act"] == "delete") {
    $id = $_REQUEST["id"];
    $delSql = "DELETE from users where userid='" . $id . "'";
    $res = mysqli_query($conn, $delSql);
    if ($res) {
        echo "deleted";
    }
}

if ($_REQUEST["act"] == "edit") {
    $id = $_REQUEST["id"];
    $editObj = array();
    $editSql = "SELECT * from users where userid='" . $id . "'";
    $result = mysqli_query($conn, $editSql);
    if (mysqli_num_rows($result) == 1) {
        while ($row = mysqli_fetch_assoc($result)) {
            $editObj["login"] = $row["login"];
            $editObj["password"] = $row["password"];
            $editObj["name"] = $row["name"];
            $editObj["email"] = $row["email"];
            $editObj["countryid"] = $row["countryid"];
            $editObj["cityid"] = $row["cityid"];
            $editObj["isadmin"] = $row["isadmin"];
        }
    }
    echo json_encode($editObj);
}
if ($_REQUEST["act"] == "loadRoleTable") {
    $objRoleTable = array();
    $objRole = array();
    $i = 0;
    $sql4 = "SELECT * from roles";
    $result4 = mysqli_query($conn, $sql4);
    if (mysqli_num_rows($result4) > 0) {
        while ($row4 = mysqli_fetch_assoc($result4)) {
            $objRole["roleid"] = $row4["roleid"];
            $objRole["role"] = $row4["name"];
            $objRole["description"] = $row4["description"];
            $objRole["creadtedby"] = $row4["createdby"];
            $objRole["createdon"] = $row4["createdon"];
            $objRoleTable[$i] = $objRole;
            $i++;
        }
    }
    echo json_encode($objRoleTable);
}


if ($_REQUEST["act"] == "deleteRole") {
    $delId = $_REQUEST["id"];
    $sql6 = "DELETE from roles where roleid='" . $delId . "'";
    $result6 = mysqli_query($conn, $sql6);
    if ($result6) {
        echo json_encode("Role Deleted Successfully");
    }
}

if ($_REQUEST["act"] == "editRole") {
    $objEditRole = array();
    $idEdit = $_REQUEST["id"];
    $sql7 = "SELECT name, description from roles where roleid='" . $idEdit . "'";
    $result7 = mysqli_query($conn, $sql7);
    if (mysqli_num_rows($result7) > 0) {
        while ($row7 = mysqli_fetch_assoc($result7)) {
            $objEditRole["role"] = $row7["name"];
            $objEditRole["description"] = $row7["description"];
        }
    }
    echo json_encode($objEditRole);
}

if ($_REQUEST["act"] == "saveNewRole") {
    $objRoleSave = array();
    $role = $_REQUEST["role"];
    $description = $_REQUEST["description"];
    if (isset($_REQUEST["updation"]) == true) {
        $id = $_REQUEST["id"];
        $rSql = "UPDATE `roles` SET `name` = '" . $role . "', `description` = '" . $description . "' WHERE `roles`.`roleid` = '" . $id . "';";
        if (mysqli_query($conn, $rSql)) {
            $objRoleSave["updateRole"] = "Role updated successfully";
            echo json_encode($objRoleSave);
        }
    } else {
        $date = date("Y-m-d H:i:s");
        $createdby = $_SESSION["canAdd"];
        $sql5 = "INSERT INTO `roles` (`roleid`, `name`, `description`, `createdon`, `createdby`) VALUES (NULL, '" . $role . "', '" . $description . "', '" . $date . "', '" . $createdby . "');";
        if (mysqli_query($conn, $sql5)) {
            $objRoleSave["newRole"] = "New Role added successfully";
            echo json_encode($objRoleSave);
        }
    }
}


if ($_REQUEST["act"] == "loadPermissionTable") {
    $objPermissionTable = array();
    $objPermission = array();
    $i = 0;
    $sql4 = "SELECT * from permissions";
    $result4 = mysqli_query($conn, $sql4);
    if (mysqli_num_rows($result4) > 0) {
        while ($row4 = mysqli_fetch_assoc($result4)) {
            $objPermission["permissionid"] = $row4["permissionid"];
            $objPermission["permission"] = $row4["name"];
            $objPermission["description"] = $row4["description"];
            $objPermission["creadtedby"] = $row4["createdby"];
            $objPermission["createdon"] = $row4["createdon"];
            $objPermissionTable[$i] = $objPermission;
            $i++;
        }
    }
    echo json_encode($objPermissionTable);
}


if ($_REQUEST["act"] == "deletePermission") {
    $delId = $_REQUEST["id"];
    $sql6 = "DELETE from permissions where permissionid='" . $delId . "'";
    $result6 = mysqli_query($conn, $sql6);
    if ($result6) {
        echo json_encode("Permission Deleted Successfully");
    }
}

if ($_REQUEST["act"] == "editPermission") {
    $objEditRole = array();
    $idEdit = $_REQUEST["id"];
    $sql7 = "SELECT name, description from permissions where permissionid='" . $idEdit . "'";
    $result7 = mysqli_query($conn, $sql7);
    if (mysqli_num_rows($result7) > 0) {
        while ($row7 = mysqli_fetch_assoc($result7)) {
            $objEditRole["permission"] = $row7["name"];
            $objEditRole["description"] = $row7["description"];
        }
    }
    echo json_encode($objEditRole);
}

if ($_REQUEST["act"] == "saveNewPermission") {
    $objPermissionSave = array();
    $permission = $_REQUEST["permission"];
    $description = $_REQUEST["description"];
    if (isset($_REQUEST["updation"]) == true) {
        $id = $_REQUEST["id"];
        $rSql = "UPDATE `permissions` SET `name` = '" . $permission . "', `description` = '" . $description . "' WHERE `permissions`.`permissionid` = '" . $id . "';";
        if (mysqli_query($conn, $rSql)) {
            $objPermissionSave["updatePermission"] = "Permission updated successfully";
            echo json_encode($objPermissionSave);
        }
    } else {
        $date = date("Y-m-d H:i:s");
        $createdby = $_SESSION["canAdd"];
        $sql5 = "INSERT INTO `permissions` (`permissionid`, `name`, `description`, `createdon`, `createdby`) VALUES (NULL, '" . $permission . "', '" . $description . "', '" . $date . "', '" . $createdby . "');";
        if (mysqli_query($conn, $sql5)) {
            $objPermissionSave["newPermission"] = "New Permission added successfully";
            echo json_encode($objPermissionSave);
        }
    }
}

if ($_REQUEST["act"] == "loadLoginHistoryTable") {
    $sql = "SELECT * from loginhistory";
    $objLogHis = array();
    $table = array();
    $result = mysqli_query($conn, $sql);
    $count = mysqli_num_rows($result);
    $i = 0;
    while ($row = mysqli_fetch_assoc($result)) {
        $sql2 = "SELECT name from users where userid='" . $row["userid"] . "'";
        $result2 = mysqli_query($conn, $sql2);
        $row2 = mysqli_fetch_assoc($result2);

        $objLogHis["name"] = $row2["name"];
        $objLogHis["logintime"] = $row["logintime"];
        $objLogHis["machinip"] = $row["machineip"];
        $table[$i] = $objLogHis;
        $i++;
    }
    echo json_encode($table);
}

if ($_REQUEST["act"] == "loadRoles") {
    $r_id = 0;
    if (isset($_REQUEST["id"]) == true) {
        $r_id = $_REQUEST["id"];
    }

    $sql = "SELECT roleid,name FROM roles";
    $result = mysqli_query($conn, $sql);
    $recordsFound = mysqli_num_rows($result);
    if ($recordsFound > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $id = $row["roleid"];
            $name = $row["name"];
            if ($id == $r_id) {
                echo "<option selected value='$id'>$name</option>";
            } else {
                echo "<option value='$id'>$name</option>";
            }
        }
    }
}

if ($_REQUEST["act"] == "loadPermissions") {
    $p_id = 0;
    if (isset($_REQUEST["id"]) == true) {
        $p_id = $_REQUEST["id"];
    }

    $sql = "SELECT permissionid,name FROM permissions";
    $result = mysqli_query($conn, $sql);
    $recordsFound = mysqli_num_rows($result);
    if ($recordsFound > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $id = $row["permissionid"];
            $name = $row["name"];
            if ($id == $p_id) {
                echo "<option selected value='$id'>$name</option>";
            } else {
                echo "<option value='$id'>$name</option>";
            }
        }
    }
}

if ($_REQUEST["act"] == "loadUsers") {
    $u_id = 0;
    if (isset($_REQUEST["id"]) == true) {
        $u_id = $_REQUEST["id"];
    }

    $sql = "SELECT userid,name FROM users";
    $result = mysqli_query($conn, $sql);
    $recordsFound = mysqli_num_rows($result);
    if ($recordsFound > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $id = $row["userid"];
            $name = $row["name"];
            if ($id == $u_id) {
                echo "<option selected value='$id'>$name</option>";
            } else {
                echo "<option value='$id'>$name</option>";
            }
        }
    }
}

if ($_REQUEST["act"] == "loadRolePermTable") {

    $objRolePerm = array();
    $rolePermTable = array();
    $sql = "SELECT * from role_permission";
    $result = mysqli_query($conn, $sql);
    $count = mysqli_num_rows($result);
    $i = 0;
    while ($row = mysqli_fetch_assoc($result)) {

        $sql2 = "SELECT name from roles where roleid='" . $row["roleid"] . "'";
        $result2 = mysqli_query($conn, $sql2);
        $row2 = mysqli_fetch_assoc($result2);

        $sql3 = "SELECT name from permissions where permissionid='" . $row["permissionid"] . "'";
        $result3 = mysqli_query($conn, $sql3);
        $row3 = mysqli_fetch_assoc($result3);
        if (mysqli_num_rows($result2) > 0 && mysqli_num_rows($result3) > 0) {
            $role = $row2["name"];
            $permission = $row3["name"];
        }
        $objRolePerm["id"] = $row["id"];
        $objRolePerm["role"] = $role;
        $objRolePerm["permission"] = $permission;
        $rolePermTable[$i] = $objRolePerm;
        $i++;
    }
    echo json_encode($rolePermTable);
}

if ($_REQUEST["act"] == "DeleteRolePerm") {
    $id = $_REQUEST["id"];
    $sql8 = "DELETE from role_permission where id ='" . $id . "'";
    if (mysqli_query($conn, $sql8)) {
        echo json_encode("Role-Permission deleted successfully");
    }
}

if ($_REQUEST["act"] == "SaveRolePerm") {
    if (isset($_REQUEST["update"])) {
        $id = $_REQUEST["id"];
        $role1 = $_REQUEST["role"];
        $permission1 = $_REQUEST["permission"];
        $sql9 = "UPDATE `role_permission` SET `roleid` = '" . $role1 . "', `permissionid` = '" . $permission1 . "' WHERE `role_permission`.`id` = '" . $id . "';";
        if (mysqli_query($conn, $sql9)) {
            $result = array("updates" => "updated");
            echo json_encode($result);
        }
    } else {
        $role = $_REQUEST["role"];
        $permission = $_REQUEST["permission"];
        $sql9 = "INSERT INTO `role_permission` (`id`, `roleid`, `permissionid`) VALUES (NULL, '" . $role . "', '" . $permission . "')";
        if (mysqli_query($conn, $sql9)) {
            $result9 = array("added" => "Successfully Added");
            echo json_encode($result9);
        }
    }
}

if ($_REQUEST["act"] == "editRolePerm") {
    $rolePermObj = array();
    $rolePermId = $_REQUEST["id"];
    $sql10 = "SELECT roleid, permissionid from role_permission where id ='" . $rolePermId . "'";
    $result10 = mysqli_query($conn, $sql10);

    while ($row8 = mysqli_fetch_assoc($result10)) {
        $rolePermObj["role"] = $row8["roleid"];
        $rolePermObj["permission"] = $row8["permissionid"];
    }

    echo json_encode($rolePermObj);
}


if ($_REQUEST["act"] == "loadUserRoleTable") {
    $objUserRole = array();
    $tableUserRole = array();
    $sql = "SELECT * from user_role";
    $result = mysqli_query($conn, $sql);
    $count = mysqli_num_rows($result);
    $i = 0;
    while ($row = mysqli_fetch_assoc($result)) {
        $sql2 = "SELECT name from roles where roleid='" . $row["roleid"] . "'";
        $result2 = mysqli_query($conn, $sql2);
        $row2 = mysqli_fetch_assoc($result2);
        $sql3 = "SELECT name from users where userid='" . $row["userid"] . "'";
        $result3 = mysqli_query($conn, $sql3);
        $row3 = mysqli_fetch_assoc($result3);
        if (mysqli_num_rows($result2) > 0 && mysqli_num_rows($result3) > 0) {
            $role = $row2["name"];
            $user = $row3["name"];
        }
        $objUserRole["user"] = $user;
        $objUserRole["role"] = $role;
        $objUserRole["id"] = $row["id"];
        $tableUserRole[$i] = $objUserRole;
        $i++;
    }
    echo json_encode($tableUserRole);
}
if ($_REQUEST["act"] == "DeleteUserRole") {
    $id = $_REQUEST["id"];
    $sql8 = "DELETE from user_role where id ='" . $id . "'";
    if (mysqli_query($conn, $sql8)) {
        echo json_encode("User-Role deleted successfully");
    }
}
if ($_REQUEST["act"] == "SaveUserRole") {
    if (isset($_REQUEST["update"])) {
        $id = $_REQUEST["id"];
        $role1 = $_REQUEST["role"];
        $user1 = $_REQUEST["user"];
        $sql9 = "UPDATE `user_role` SET `userid` = '" . $user1 . "', `roleid` = '" . $role1 . "' WHERE `user_role`.`id` = '" . $id . "';";
        if (mysqli_query($conn, $sql9)) {
            $result = array("updates" => "updated");
            echo json_encode($result);
        }
    } else {
        $role = $_REQUEST["role"];
        $user = $_REQUEST["user"];
        $sql9 = "INSERT INTO `user_role` (`id`, `userid`, `roleid`) VALUES (NULL, '" . $user . "', '" . $role . "')";
        if (mysqli_query($conn, $sql9)) {
            $result9 = array("added" => "Successfully Added");
            echo json_encode($result9);
        }
    }
}

if ($_REQUEST["act"] == "editUserRole") {
    $userRoleObj = array();
    $userRoleId = $_REQUEST["id"];
    $sql10 = "SELECT userid, roleid from user_role where id ='" . $userRoleId . "'";
    $result10 = mysqli_query($conn, $sql10);

    while ($row8 = mysqli_fetch_assoc($result10)) {
        $userRoleObj["user"] = $row8["userid"];
        $userRoleObj["role"] = $row8["roleid"];
    }

    echo json_encode($userRoleObj);
}
?>
