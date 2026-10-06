<?php 
if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
    session_start();
}
$error_msg = null;

if (isset($_POST['login'])) {
    if (isset($_POST['usertype']) && isset($_POST['username']) && isset($_POST['pass'])) {        
        require_once 'sql.php';
        $conn = mysqli_connect($servername, $username, $password, $dbname);
        if (!$conn) {
            $error_msg = "Database connection error. Please try again later.";
        } else {
            $type = mysqli_real_escape_string($conn, $_POST['usertype']);
            $username = mysqli_real_escape_string($conn, $_POST['username']);
            $password = mysqli_real_escape_string($conn, $_POST['pass']);
            $password = crypt($password, 'rakeshmariyaplarrakesh');
            
            // Only allow valid types
            if ($type === 'student' || $type === 'staff') {
                $sql = "SELECT * FROM " . $type . " WHERE mail='{$username}'";
                $res = mysqli_query($conn, $sql);
                if ($res && mysqli_num_rows($res) > 0) {
                    $row = mysqli_fetch_array($res);
                    $dbpw = $row['pw'];
                    $dbmail = $row['mail'];

                    if ($dbpw === $password) {
                        $_SESSION["name"] = $row['name'];
                        $_SESSION["type"] = $type;
                        $_SESSION["username"] = $dbmail;

                        if ($type === 'student') {
                            header("Location: homestud.php");
                            exit();
                        } elseif ($type === 'staff') {
                            header("Location: homestaff.php");
                            exit();
                        }
                    } else {
                        $error_msg = "Incorrect password! Please try again.";
                    }
                } else {
                    $error_msg = "No account found with this email as " . ucfirst($type) . ". Please sign up.";
                }
            } else {
                $error_msg = "Invalid account type selected.";
            }
        }
    }
}
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
    <title>Sign In | Quiz Management System</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700;900&display=swap" rel="stylesheet">
    <!-- Auth Custom CSS -->
    <link rel="stylesheet" href="auth.css">
</head>

<body>

    <div class="auth-card">
        <!-- Header -->
        <div class="auth-header">
            <div class="auth-logo">
                <?php if ($site_logo): ?>
                <img src="<?= $site_logo ?>?v=<?= time() ?>" alt="Quizzy Logo"
                    style="max-height: 56px; max-width: 56px; object-fit: contain;">
                <?php else: ?>
                &#127891;
                <?php endif; ?>
            </div>
            <h1 class="auth-title">Quiz Management System</h1>
            <p class="auth-subtitle">Sign in to your account to continue</p>
        </div>

        <!-- Alert Notification -->
        <?php if ($error_msg): ?>
        <div class="auth-alert danger">
            <span>&#9888;</span>
            <div><?= htmlspecialchars($error_msg) ?></div>
        </div>
        <?php endif; ?>

        <!-- Login Form -->
        <form method="POST" id="loginForm">
            <!-- Role Toggle -->
            <div class="role-toggle">
                <div class="role-option active" id="roleStud" onclick="selectRole('student')">
                    &#128104;&#8205;&#127891; Student
                </div>
                <div class="role-option" id="roleStaff" onclick="selectRole('staff')">
                    &#128188; Staff / Admin
                </div>
            </div>

            <!-- Hidden input for usertype -->
            <input type="hidden" name="usertype" id="usertypeInput" value="student">

            <!-- Email Address -->
            <div class="form-group">
                <label for="username" class="form-label">Email Address</label>
                <input type="email" id="username" name="username" class="form-control"
                    placeholder="e.g. yourname@gmail.com" required autofocus>
            </div>

            <!-- Password -->
            <div class="form-group">
                <label for="password" class="form-label">Password</label>
                <input type="password" id="password" name="pass" class="form-control"
                    placeholder="&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;" required>
            </div>

            <!-- Submit Button -->
            <button type="submit" name="login" class="btn-auth">
                Sign In &rarr;
            </button>
        </form>

        <!-- Footer Link -->
        <div class="auth-footer-link">
            New user? <a href="signup.php">Create an Account</a>
        </div>
    </div>

    <!-- Page Footer -->
    <footer class="auth-page-footer">
        <p>Copyright &copy; 2026 Quiz Management System. All rights reserved.</p>
    </footer>

    <script>
    function selectRole(role) {
        document.getElementById('usertypeInput').value = role;
        if (role === 'student') {
            document.getElementById('roleStud').classList.add('active');
            document.getElementById('roleStaff').classList.remove('active');
        } else {
            document.getElementById('roleStaff').classList.add('active');
            document.getElementById('roleStud').classList.remove('active');
        }
    }
    </script>
</body>

</html>