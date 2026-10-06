<?php
session_start();
if (!isset($_SESSION["username"]) || !isset($_SESSION["type"]) || $_SESSION["type"] !== "staff") {
    header("Location: ../index.php");
    exit();
}
header("Location: ../homestaff.php");
exit();
?>
