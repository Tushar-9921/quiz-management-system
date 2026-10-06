<?php
if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
    session_start();
}

// Ensure user is authenticated as student
if (!isset($_SESSION["username"]) || !isset($_SESSION["type"]) || $_SESSION["type"] !== "student") {
    header("Location: index.php");
    exit();
}

require_once __DIR__ . '/sql.php';
$db_actual_name = $dbname ?? 'quiz';

$conn = mysqli_connect($servername, $username, $password, $db_actual_name);
if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

$username1 = $_SESSION["username"];
$type1 = $_SESSION["type"];

// Fetch student details
$sql_stud = "SELECT * FROM student WHERE mail = '" . mysqli_real_escape_string($conn, $username1) . "'";
$res_stud = mysqli_query($conn, $sql_stud);
$stud_data = ($res_stud && mysqli_num_rows($res_stud) > 0) ? mysqli_fetch_assoc($res_stud) : [];

$dbname = htmlspecialchars($stud_data['name'] ?? 'Student');
$dbmail = htmlspecialchars($stud_data['mail'] ?? $username1);
$dbusn = htmlspecialchars($stud_data['usn'] ?? 'N/A');
$dbphno = htmlspecialchars($stud_data['phno'] ?? 'N/A');
$dbgender = htmlspecialchars($stud_data['gender'] ?? 'N/A');
$dbdob = htmlspecialchars($stud_data['DOB'] ?? 'N/A');
$dbdept = htmlspecialchars($stud_data['dept'] ?? 'General');

$active_page = $active_page ?? 'dashboard';

$site_logo = null;
foreach (['png', 'jpg', 'jpeg', 'webp', 'svg'] as $ext) {
    if (file_exists(__DIR__ . '/images/logo.' . $ext)) {
        $site_logo = 'images/logo.' . $ext;
        break;
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($page_title) ? htmlspecialchars($page_title) . ' | Quizzy Student' : 'Quizzy Student Portal' ?>
    </title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700;900&display=swap" rel="stylesheet">
    <!-- Custom Student CSS -->
    <link rel="stylesheet" href="student_style.css">
</head>

<body>

    <!-- Student Navigation Bar -->
    <header class="stud-navbar">
        <div class="stud-container">
            <div class="stud-navbar-inner">
                <a href="homestud.php" class="stud-brand">
                    <?php if (!empty($site_logo) && file_exists(__DIR__ . '/' . $site_logo)): ?>
                    <img src="<?= $site_logo ?>?v=<?= time() ?>" alt="Quizzy Logo"
                        style="height: 28px; width: 28px; margin-right: 8px; vertical-align: middle; object-fit: contain; border-radius: 4px; background: white; padding: 2px;">
                    <?php endif; ?>
                    Quiz Management System <span>Student</span>
                </a>
                <ul class="stud-nav-links">
                    <li><a href="homestud.php"
                            class="<?= ($active_page === 'dashboard') ? 'active' : '' ?>">Dashboard</a></li>
                    <li><a href="student_score.php" class="<?= ($active_page === 'score') ? 'active' : '' ?>">My
                            Scores</a></li>
                    <li><a href="student_leaderboard.php"
                            class="<?= ($active_page === 'leaderboard') ? 'active' : '' ?>">Leaderboard</a></li>
                    <li><a href="student_profile.php" class="<?= ($active_page === 'profile') ? 'active' : '' ?>">My
                            Profile</a></li>
                    <li><a href="logout.php" onclick="return confirm('Are you sure you want to sign out?');"
                            class="btn-logout">Sign Out</a></li>
                </ul>
            </div>
        </div>
    </header>