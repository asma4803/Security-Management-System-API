<?php
session_start();
if (isset($_SESSION["isadmin"]) == false || $_SESSION["isadmin"] != 1) {
    header("Location:Login.php");
}
?>