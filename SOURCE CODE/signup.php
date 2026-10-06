<?php
if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
    session_start();
}
$error_msg = null;
$success_msg = null;

// Handle Student Signup
if (isset($_POST['studsu'])) {
    if (isset($_POST['name1']) && isset($_POST['usn1']) && isset($_POST['mail1']) && isset($_POST['phno1']) && isset($_POST['dept1']) && isset($_POST['dob1']) && isset($_POST['gender1']) && isset($_POST['password1']) && isset($_POST['cpassword1'])) {
        require_once 'sql.php';
        $conn = mysqli_connect($servername, $username, $password, $dbname);
        if (!$conn) {
            $error_msg = "Database error. Please retry after some time.";
        } else {
            $name1 = mysqli_real_escape_string($conn, trim($_POST['name1']));
            $usn1 = mysqli_real_escape_string($conn, trim($_POST['usn1']));
            $mail1 = mysqli_real_escape_string($conn, trim($_POST['mail1']));
            $phno1 = mysqli_real_escape_string($conn, trim($_POST['phno1']));
            $dept1 = mysqli_real_escape_string($conn, trim($_POST['dept1']));
            $dob1 = mysqli_real_escape_string($conn, trim($_POST['dob1']));
            $gender1 = mysqli_real_escape_string($conn, trim($_POST['gender1']));
            $password1 = mysqli_real_escape_string($conn, $_POST['password1']);
            $cpassword1 = mysqli_real_escape_string($conn, $_POST['cpassword1']);

            if ($password1 === $cpassword1) {
                $password1_hash = crypt($password1, 'rakeshmariyaplarrakesh');
                $sql = "INSERT INTO student (usn, name, mail, phno, dept, gender, DOB, pw) VALUES ('$usn1', '$name1', '$mail1', '$phno1', '$dept1', '$gender1', '$dob1', '$password1_hash')";
                
                if (mysqli_query($conn, $sql)) {
                    echo "<script>
                        alert('Student registration successful! Please sign in.');
                        window.location.replace('index.php');
                    </script>";
                    session_destroy();
                    exit();
                } else {
                    $error_msg = "An account with this Email, USN, or Phone already exists in the database.";
                }
            } else {
                $error_msg = "Passwords do not match. Please try again.";
            }
        }
    }
}

// Handle Staff Signup
if (isset($_POST['staffsu'])) {
    if (isset($_POST['name2']) && isset($_POST['staffid']) && isset($_POST['mail2']) && isset($_POST['phno2']) && isset($_POST['dept2']) && isset($_POST['dob2']) && isset($_POST['gender2']) && isset($_POST['password2']) && isset($_POST['cpassword2'])) {
        require_once 'sql.php';
        $conn = mysqli_connect($servername, $username, $password, $dbname);
        if (!$conn) {
            $error_msg = "Database error. Please retry after some time.";
        } else {
            $name2 = mysqli_real_escape_string($conn, trim($_POST['name2']));
            $usn2 = mysqli_real_escape_string($conn, trim($_POST['staffid']));
            $mail2 = mysqli_real_escape_string($conn, trim($_POST['mail2']));
            $phno2 = mysqli_real_escape_string($conn, trim($_POST['phno2']));
            $dept2 = mysqli_real_escape_string($conn, trim($_POST['dept2']));
            $dob2 = mysqli_real_escape_string($conn, trim($_POST['dob2']));
            $gender2 = mysqli_real_escape_string($conn, trim($_POST['gender2']));
            $password2 = mysqli_real_escape_string($conn, $_POST['password2']);
            $cpassword2 = mysqli_real_escape_string($conn, $_POST['cpassword2']);

            if ($password2 === $cpassword2) {
                $password2_hash = crypt($password2, 'rakeshmariyaplarrakesh');
                $sql = "INSERT INTO staff (staffid, name, mail, phno, dept, gender, DOB, pw) VALUES ('$usn2', '$name2', '$mail2', '$phno2', '$dept2', '$gender2', '$dob2', '$password2_hash')";
                
                if (mysqli_query($conn, $sql)) {
                    echo "<script>
                        alert('Staff registration successful! Please sign in.');
                        window.location.replace('index.php');
                    </script>";
                    session_destroy();
                    exit();
                } else {
                    $error_msg = "An account with this Email, Staff ID, or Phone already exists in the database.";
                }
            } else {
                $error_msg = "Passwords do not match. Please try again.";
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
    <title>Sign Up | Quiz Management System</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700;900&display=swap" rel="stylesheet">
    <!-- Auth Custom CSS -->
    <link rel="stylesheet" href="auth.css">
</head>

<body>

    <div class="auth-card signup-card">
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
            <h1 class="auth-title">Create an Account</h1>
            <p class="auth-subtitle">Register as a Student or Staff member to get started</p>
        </div>

        <!-- Role Toggle -->
        <div class="role-toggle">
            <div class="role-option active" id="tabStud" onclick="switchTab('student')">
                &#128104;&#8205;&#127891; Student Registration
            </div>
            <div class="role-option" id="tabStaff" onclick="switchTab('staff')">
                &#128188; Staff / Admin Registration
            </div>
        </div>

        <!-- Alert Notification -->
        <?php if ($error_msg): ?>
        <div class="auth-alert danger">
            <span>&#9888;</span>
            <div><?= htmlspecialchars($error_msg) ?></div>
        </div>
        <?php endif; ?>

        <!-- Student Signup Form -->
        <div id="studentForm">
            <form method="POST">
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Full Name</label>
                        <input type="text" name="name1" class="form-control" placeholder="e.g. John Doe" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">University Seat Number (USN)</label>
                        <input type="text" name="usn1" class="form-control" placeholder="e.g. 1KS20CS001" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Email Address</label>
                        <input type="email" name="mail1" class="form-control" placeholder="e.g. student@gmail.com"
                            required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Phone Number</label>
                        <input type="tel" name="phno1" class="form-control" placeholder="10-digit mobile number"
                            required maxlength="10">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Department</label>
                        <select name="dept1" class="form-control" required>
                            <option value="CSE">Computer Science (CSE)</option>
                            <option value="ISE">Information Science (ISE)</option>
                            <option value="ECE">Electronics & Comm (ECE)</option>
                            <option value="EEE">Electrical & Electronics (EEE)</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Date of Birth</label>
                        <input type="date" name="dob1" class="form-control" required>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Gender</label>
                    <div class="gender-group">
                        <label class="gender-option">
                            <input type="radio" name="gender1" value="M" required> Male
                        </label>
                        <label class="gender-option">
                            <input type="radio" name="gender1" value="F" required> Female
                        </label>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Password</label>
                        <input type="password" name="password1" class="form-control" placeholder="Create a password"
                            required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Confirm Password</label>
                        <input type="password" name="cpassword1" class="form-control" placeholder="Repeat password"
                            required>
                    </div>
                </div>

                <button type="submit" name="studsu" class="btn-auth">
                    Complete Student Registration &rarr;
                </button>
            </form>
        </div>

        <!-- Staff Signup Form -->
        <div id="staffForm" style="display: none;">
            <form method="POST">
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Full Name</label>
                        <input type="text" name="name2" class="form-control" placeholder="e.g. Prof. Jane Smith"
                            required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Staff ID</label>
                        <input type="text" name="staffid" class="form-control" placeholder="e.g. STF101" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Email Address</label>
                        <input type="email" name="mail2" class="form-control" placeholder="e.g. staff@gmail.com"
                            required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Phone Number</label>
                        <input type="tel" name="phno2" class="form-control" placeholder="10-digit mobile number"
                            required maxlength="10">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Department</label>
                        <select name="dept2" class="form-control" required>
                            <option value="CSE">Computer Science (CSE)</option>
                            <option value="ISE">Information Science (ISE)</option>
                            <option value="ECE">Electronics & Comm (ECE)</option>
                            <option value="EEE">Electrical & Electronics (EEE)</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Date of Birth</label>
                        <input type="date" name="dob2" class="form-control" required>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Gender</label>
                    <div class="gender-group">
                        <label class="gender-option">
                            <input type="radio" name="gender2" value="M" required> Male
                        </label>
                        <label class="gender-option">
                            <input type="radio" name="gender2" value="F" required> Female
                        </label>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Password</label>
                        <input type="password" name="password2" class="form-control" placeholder="Create a password"
                            required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Confirm Password</label>
                        <input type="password" name="cpassword2" class="form-control" placeholder="Repeat password"
                            required>
                    </div>
                </div>

                <button type="submit" name="staffsu" class="btn-auth">
                    Complete Staff Registration &rarr;
                </button>
            </form>
        </div>

        <!-- Footer Link -->
        <div class="auth-footer-link">
            Already have an account? <a href="index.php">Sign In</a>
        </div>
    </div>

    <!-- Page Footer -->
    <footer class="auth-page-footer">
        <p>Copyright &copy; 2026 Quiz Management System. All rights reserved.</p>
    </footer>

    <script>
    function switchTab(role) {
        if (role === 'student') {
            document.getElementById('studentForm').style.display = 'block';
            document.getElementById('staffForm').style.display = 'none';
            document.getElementById('tabStud').classList.add('active');
            document.getElementById('tabStaff').classList.remove('active');
        } else {
            document.getElementById('studentForm').style.display = 'none';
            document.getElementById('staffForm').style.display = 'block';
            document.getElementById('tabStaff').classList.add('active');
            document.getElementById('tabStud').classList.remove('active');
        }
    }
    </script>
</body>

</html>