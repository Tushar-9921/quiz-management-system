<?php
if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
    session_start();
}

// Ensure user is authenticated as staff / admin
if (!isset($_SESSION["username"]) || !isset($_SESSION["type"]) || $_SESSION["type"] !== "staff") {
    header("Location: index.php");
    exit();
}

require_once __DIR__ . '/sql.php';
// Save database name in case $dbname is re-used for staff name
$db_actual_name = $dbname ?? 'quiz';

$conn = mysqli_connect($servername, $username, $password, $db_actual_name);
if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

$username1 = $_SESSION["username"];
$type1 = $_SESSION["type"];

// Fetch staff profile details
$sql_staff = "SELECT * FROM staff WHERE mail = '" . mysqli_real_escape_string($conn, $username1) . "'";
$res_staff = mysqli_query($conn, $sql_staff);
$staff_data = ($res_staff && mysqli_num_rows($res_staff) > 0) ? mysqli_fetch_assoc($res_staff) : [];

$staff_name = htmlspecialchars($staff_data['name'] ?? 'Staff Member');
$dbname = $staff_name; // for backward compatibility with existing codebase variable
$dbmail = htmlspecialchars($staff_data['mail'] ?? $username1);
$dbusn = htmlspecialchars($staff_data['staffid'] ?? 'N/A');
$dbphno = htmlspecialchars($staff_data['phno'] ?? 'N/A');
$dbgender = htmlspecialchars($staff_data['gender'] ?? 'N/A');
$dbdob = htmlspecialchars($staff_data['DOB'] ?? 'N/A');
$dbdept = htmlspecialchars($staff_data['dept'] ?? 'General');

// Check / Add avatar column in staff table if not present
$col_check = mysqli_query($conn, "SHOW COLUMNS FROM staff LIKE 'avatar'");
if ($col_check && mysqli_num_rows($col_check) == 0) {
    @mysqli_query($conn, "ALTER TABLE staff ADD COLUMN avatar VARCHAR(255) DEFAULT NULL");
}

// Resolve staff avatar
$staff_avatar = 'images/user.png';
if (!empty($staff_data['avatar']) && file_exists(__DIR__ . '/' . $staff_data['avatar'])) {
    $staff_avatar = $staff_data['avatar'];
} elseif (!empty($staff_data['staffid'])) {
    foreach (['jpg', 'png', 'jpeg', 'webp'] as $ext) {
        if (file_exists(__DIR__ . '/images/avatars/staff_' . $staff_data['staffid'] . '.' . $ext)) {
            $staff_avatar = 'images/avatars/staff_' . $staff_data['staffid'] . '.' . $ext;
            break;
        }
    }
}

// Resolve website logo
$site_logo = null;
foreach (['png', 'jpg', 'jpeg', 'webp', 'svg'] as $ext) {
    if (file_exists(__DIR__ . '/images/logo.' . $ext)) {
        $site_logo = 'images/logo.' . $ext;
        break;
    }
}

// Asset path
if (!defined('ADMINLTE_PATH')) {
    define('ADMINLTE_PATH', 'AdminLTE 3.0.0/ColorlibHQ-AdminLTE-e78ee8d/');
}

$active_page = $active_page ?? 'dashboard';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title><?= isset($page_title) ? htmlspecialchars($page_title) . ' | Quizzy Admin' : 'Quizzy Admin' ?></title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="<?= ADMINLTE_PATH ?>plugins/fontawesome-free/css/all.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="<?= ADMINLTE_PATH ?>dist/css/adminlte.min.css">

    <style>
        .brand-link {
            background-color: #343a40 !important;
            border-bottom: 1px solid #4b545c !important;
        }
        .main-sidebar {
            background-color: #1e282c;
        }
        .user-panel {
            border-bottom: 1px solid #3c4852 !important;
        }
        .nav-sidebar .nav-link.active {
            background-color: #17a2b8 !important;
            color: #fff !important;
            box-shadow: 0 1px 3px rgba(0,0,0,0.12), 0 1px 2px rgba(0,0,0,0.24);
        }
        .card-primary.card-outline {
            border-top: 3px solid #17a2b8;
        }
        .stat-card-clickable {
            cursor: pointer;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .stat-card-clickable:hover {
            transform: translateY(-3px);
            box-shadow: 0 4px 15px rgba(0,0,0,0.15);
        }
        .table td, .table th {
            vertical-align: middle;
        }
    </style>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light border-bottom">
        <!-- Left navbar links -->
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
            </li>
            <li class="nav-item d-none d-sm-inline-block">
                <a href="homestaff.php" class="nav-link"><i class="fas fa-home mr-1"></i> Dashboard</a>
            </li>
            <li class="nav-item d-none d-sm-inline-block">
                <a href="homestaff.php#quizzes-section" class="nav-link"><i class="fas fa-book mr-1"></i> Quizzes</a>
            </li>
            <li class="nav-item d-none d-sm-inline-block">
                <a href="homestaff.php#leaderboard-section" class="nav-link"><i class="fas fa-trophy mr-1"></i> Leaderboard</a>
            </li>
        </ul>

        <!-- Right navbar links -->
        <ul class="navbar-nav ml-auto">
            <!-- User Menu Dropdown -->
            <li class="nav-item dropdown user-menu">
                <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">
                    <img src="<?= $staff_avatar ?>" class="user-image img-circle elevation-1" alt="User Image" style="object-fit: cover;">
                    <span class="d-none d-md-inline font-weight-bold ml-1"><?= $staff_name ?></span>
                </a>
                <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                    <!-- User image -->
                    <li class="user-header bg-info">
                        <img src="<?= $staff_avatar ?>" class="img-circle elevation-2" alt="User Image" style="object-fit: cover;">
                        <p class="font-weight-bold mb-0">
                            <?= $staff_name ?>
                            <small class="d-block text-light">Staff ID: <?= $dbusn ?> | Dept: <?= $dbdept ?></small>
                            <small class="d-block text-white-50"><?= $dbmail ?></small>
                        </p>
                    </li>
                    <!-- Menu Footer-->
                    <li class="user-footer d-flex justify-content-between">
                        <a href="homestaff.php#profile-section" class="btn btn-default btn-flat"><i class="fas fa-user mr-1"></i> Profile</a>
                        <a href="logout.php" onclick="return confirm('Are you sure you want to sign out?');" class="btn btn-danger btn-flat"><i class="fas fa-sign-out-alt mr-1"></i> Sign Out</a>
                    </li>
                </ul>
            </li>
            <!-- Fullscreen -->
            <li class="nav-item">
                <a class="nav-link" data-widget="fullscreen" href="#" role="button" title="Toggle Fullscreen">
                    <i class="fas fa-expand-arrows-alt"></i>
                </a>
            </li>
        </ul>
    </nav>
    <!-- /.navbar -->
