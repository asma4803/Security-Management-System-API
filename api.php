<?php
require 'connector.php';
session_start();
if ($_REQUEST["act"] == "check") {
    $flag = true;
    if ($_REQUEST["username"] != "" && $_REQUEST["password"] != "") {
        $user = $_REQUEST["username"];
        $pass = $_REQUEST["password"];
        $sql = "SELECT userid, login, password,isadmin from users where login='" . $user . "' AND password='" . $pass . "'";
        $result = mysqli_query($conn, $sql);
        $recordCount = mysqli_num_rows($result);
        if ($recordCount == 1) {
            while ($row = mysqli_fetch_assoc($result)) {
                $_SESSION["userid"] = $row["userid"];
                //insert login_history
                $logintime = date("Y-m-d H:i:s");
                $ip = $_SERVER['SERVER_ADDR'];
                $query = "INSERT INTO `loginhistory` (`id`, `userid`, `login`, `logintime`, `machineip`) VALUES (NULL, '" . $row["userid"] . "', '" . $row["login"] . "', '" . $logintime . "', '" . $ip . "')";
                $execute = mysqli_query($conn, $query);
                //inserted
                $is = $row["isadmin"];
                $login = $row["login"];
                $password = $row["password"];
                if ($is == 1) {
                    $_SESSION["isadmin"] = 1;
                    $_SESSION["canAdd"] = $row["userid"];
                    $flag = true;
                } else if ($is == 0) {
                    $_SESSION["isadmin"] = 0;
                    $flag = true;
                }
            }
        } else if ($recordCount == 0) {
            $flag = false;
        }
    }

    if ($flag == true) {
        $r = "true";
        echo json_encode($r);
    } else if ($flag == false) {
        $r1 = "bye";
        echo json_encode($r1);
    }
}


?>