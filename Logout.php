<?php
session_start();
$_SESSION["isadmin"]= null;
$_SESSION["canAdd"]=null;
session_destroy();
header("Location:Login.php");
?>
