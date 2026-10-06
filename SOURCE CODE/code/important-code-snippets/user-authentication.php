<?php
// User Authentication & Role-Based Routing (index.php)
session_start();
if (isset($_POST['login'])) {
    if (isset($_POST['usertype']) && isset($_POST['username']) && isset($_POST['pass'])) {
        require_once 'sql.php';
        $type = mysqli_real_escape_string($conn, $_POST['usertype']);
        $username = mysqli_real_escape_string($conn, $_POST['username']);
        $password = mysqli_real_escape_string($conn, $_POST['pass']);
        $password = crypt($password, 'rakeshmariyaplarrakesh');

        $sql = "SELECT * FROM " . $type . " WHERE mail='{$username}'";
        $res = mysqli_query($conn, $sql);
        if ($res && mysqli_num_rows($res) > 0) {
            $row = mysqli_fetch_array($res);
            if ($row['pw'] === $password) {
                $_SESSION["name"] = $row['name'];
                $_SESSION["type"] = $type;
                $_SESSION["username"] = $row['mail'];
                if ($type === 'student') {
                    header("Location: homestud.php");
                } elseif ($type === 'staff') {
                    header("Location: homestaff.php");
                }
                exit();
            }
        }
    }
}
?>