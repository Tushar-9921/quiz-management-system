<?php
$page_title = "Dashboard";
$active_page = "dashboard";
require_once __DIR__ . '/admin_header.php';

$alert = null;

// Handle Add Quiz
if (isset($_POST['submit'])) {
    $qname = strtolower(trim($_POST['quizname']));
    if (!empty($qname)) {
        $qname_clean = mysqli_real_escape_string($conn, $qname);
        $sql_check = "SELECT quizid FROM quiz WHERE quizname = '$qname_clean'";
        $res_check = mysqli_query($conn, $sql_check);
        if ($res_check && mysqli_num_rows($res_check) > 0) {
            $alert = ["type" => "danger", "msg" => "A quiz named '<strong>" . htmlspecialchars($qname) . "</strong>' already exists! Please choose another name."];
        } else {
            $sql1 = "INSERT INTO quiz (quizname, mail) VALUES ('$qname_clean', '$username1')";
            $res1 = mysqli_query($conn, $sql1);
            if ($res1) {
                $_SESSION["qname"] = $qname;
                echo "<script>window.location.href = 'addqs.php';</script>";
                exit();
            } else {
                $alert = ["type" => "danger", "msg" => "Failed to create quiz: " . mysqli_error($conn)];
            }
        }
    } else {
        $alert = ["type" => "warning", "msg" => "Please enter a valid quiz name."];
    }
}

// Handle Delete Quiz via POST
if (isset($_POST['submit1'])) {
    $qid1 = intval($_POST['quizid']);
    $sql1 = "DELETE FROM quiz WHERE quizid = '$qid1' AND mail = '$username1'";
    $res1 = mysqli_query($conn, $sql1);
    if ($res1 && mysqli_affected_rows($conn) > 0) {
        $alert = ["type" => "success", "msg" => "Quiz #$qid1 has been successfully deleted along with its questions."];
    } else {
        $alert = ["type" => "warning", "msg" => "Quiz #$qid1 could not be deleted (not found or not created by you)."];
    }
}

// Handle Delete Quiz via GET
if (isset($_GET['delete_quiz'])) {
    $del_id = intval($_GET['delete_quiz']);
    $sql_del = "DELETE FROM quiz WHERE quizid = '$del_id' AND mail = '$username1'";
    $res_del = mysqli_query($conn, $sql_del);
    if ($res_del && mysqli_affected_rows($conn) > 0) {
        $alert = ["type" => "success", "msg" => "Quiz #$del_id has been successfully deleted."];
    } else {
        $alert = ["type" => "warning", "msg" => "Could not delete Quiz #$del_id."];
    }
}

// Handle Edit Quiz Title via POST
if (isset($_POST['edit_quiz_submit'])) {
    $edit_qid = intval($_POST['edit_quizid']);
    $edit_qname = trim($_POST['edit_quizname']);
    if (!empty($edit_qname) && $edit_qid > 0) {
        $edit_qname_clean = mysqli_real_escape_string($conn, $edit_qname);
        $update_sql = "UPDATE quiz SET quizname = '$edit_qname_clean' WHERE quizid = '$edit_qid' AND mail = '$username1'";
        if (mysqli_query($conn, $update_sql)) {
            $alert = ["type" => "success", "msg" => "Quiz #$edit_qid has been successfully updated to '<strong>" . htmlspecialchars($edit_qname) . "</strong>'."];
        } else {
            $alert = ["type" => "danger", "msg" => "Failed to update quiz: " . mysqli_error($conn)];
        }
    }
}

// Handle View Quiz via POST
if (isset($_POST['submit2'])) {
    $qid1 = intval($_POST['quizid']);
    echo "<script>window.location.href = 'viewq.php?qid=$qid1';</script>";
    exit();
}

// Handle Admin Profile & System Settings Update
if (isset($_POST['update_profile_submit'])) {
    $new_dept = trim($_POST['dept'] ?? '');
    $new_mail = strtolower(trim($_POST['mail'] ?? ''));
    $new_phno = trim($_POST['phno'] ?? '');
    $new_gender = strtoupper(trim($_POST['gender'] ?? ''));
    $new_dob = trim($_POST['dob'] ?? '');
    $staff_id = $staff_data['staffid'] ?? '';

    $errors = [];

    // Validation
    if (empty($new_mail) || !filter_var($new_mail, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Please provide a valid email address.";
    }
    if (empty($new_phno) || !preg_match('/^[0-9]{10}$/', $new_phno)) {
        $errors[] = "Phone number must be exactly 10 digits.";
    }
    if (!in_array($new_gender, ['M', 'F', 'O'])) {
        $errors[] = "Please select a valid gender.";
    }
    if (empty($new_dob)) {
        $errors[] = "Please provide a valid date of birth.";
    }
    if (empty($new_dept)) {
        $errors[] = "Please select a department.";
    }

    // Check if new email is already taken by another account
    if ($new_mail !== $username1) {
        $new_mail_c = mysqli_real_escape_string($conn, $new_mail);
        $check_staff = mysqli_query($conn, "SELECT mail FROM staff WHERE mail = '$new_mail_c'");
        $check_stud = mysqli_query($conn, "SELECT mail FROM student WHERE mail = '$new_mail_c'");
        if (($check_staff && mysqli_num_rows($check_staff) > 0) || ($check_stud && mysqli_num_rows($check_stud) > 0)) {
            $errors[] = "The email address '<strong>" . htmlspecialchars($new_mail) . "</strong>' is already registered by another account.";
        }
    }

    // Handle Profile Picture Upload
    $avatar_db_path = $staff_data['avatar'] ?? null;
    if (isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] === UPLOAD_ERR_OK) {
        $file_tmp = $_FILES['profile_pic']['tmp_name'];
        $file_name = $_FILES['profile_pic']['name'];
        $file_size = $_FILES['profile_pic']['size'];
        $ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'webp', 'gif'];

        if (!in_array($ext, $allowed)) {
            $errors[] = "Profile picture must be an image (JPG, JPEG, PNG, WEBP, or GIF).";
        } elseif ($file_size > 5 * 1024 * 1024) {
            $errors[] = "Profile picture size must not exceed 5MB.";
        } else {
            $target_filename = 'images/avatars/staff_' . ($staff_id ?: preg_replace('/[^a-zA-Z0-9]/', '_', $username1)) . '_' . time() . '.' . $ext;
            if (move_uploaded_file($file_tmp, __DIR__ . '/' . $target_filename)) {
                $avatar_db_path = $target_filename;
            } else {
                $errors[] = "Failed to save uploaded profile picture.";
            }
        }
    }

    // Handle Website Logo Upload
    if (isset($_FILES['site_logo']) && $_FILES['site_logo']['error'] === UPLOAD_ERR_OK) {
        $logo_tmp = $_FILES['site_logo']['tmp_name'];
        $logo_name = $_FILES['site_logo']['name'];
        $logo_size = $_FILES['site_logo']['size'];
        $logo_ext = strtolower(pathinfo($logo_name, PATHINFO_EXTENSION));
        $allowed_logo = ['jpg', 'jpeg', 'png', 'webp', 'svg', 'gif'];

        if (!in_array($logo_ext, $allowed_logo)) {
            $errors[] = "Website logo must be an image (PNG, JPG, JPEG, WEBP, SVG, or GIF).";
        } elseif ($logo_size > 5 * 1024 * 1024) {
            $errors[] = "Website logo size must not exceed 5MB.";
        } else {
            // Remove previous logo files to avoid collision
            foreach (['png', 'jpg', 'jpeg', 'webp', 'svg', 'gif'] as $old_ext) {
                if (file_exists(__DIR__ . '/images/logo.' . $old_ext)) {
                    @unlink(__DIR__ . '/images/logo.' . $old_ext);
                }
            }
            $target_logo = 'images/logo.' . ($logo_ext === 'jpeg' ? 'jpg' : $logo_ext);
            if (!move_uploaded_file($logo_tmp, __DIR__ . '/' . $target_logo)) {
                $errors[] = "Failed to save uploaded website logo.";
            }
        }
    }

    // If no validation errors, update the database
    if (empty($errors)) {
        $new_mail_c = mysqli_real_escape_string($conn, $new_mail);
        $old_mail_c = mysqli_real_escape_string($conn, $username1);
        $new_phno_c = mysqli_real_escape_string($conn, $new_phno);
        $new_dept_c = mysqli_real_escape_string($conn, $new_dept);
        $new_gender_c = mysqli_real_escape_string($conn, $new_gender);
        $new_dob_c = mysqli_real_escape_string($conn, $new_dob);
        $avatar_val = $avatar_db_path ? "'" . mysqli_real_escape_string($conn, $avatar_db_path) . "'" : "NULL";

        // If email changed, update quiz table foreign keys first
        if ($new_mail !== $username1) {
            mysqli_query($conn, "UPDATE quiz SET mail = '$new_mail_c' WHERE mail = '$old_mail_c'");
        }

        // Update staff record
        $update_staff_sql = "UPDATE staff SET 
            mail = '$new_mail_c',
            phno = '$new_phno_c',
            dept = '$new_dept_c',
            gender = '$new_gender_c',
            DOB = '$new_dob_c',
            avatar = $avatar_val
            WHERE mail = '$old_mail_c'";

        $res_update = mysqli_query($conn, $update_staff_sql);

        if ($res_update) {
            // Update session if email changed
            if ($new_mail !== $username1) {
                $_SESSION['username'] = $new_mail;
                $username1 = $new_mail;
            }
            $alert = ["type" => "success", "msg" => "Profile and system settings updated successfully!"];

            // Refresh local staff data
            $sql_staff = "SELECT * FROM staff WHERE mail = '$new_mail_c'";
            $res_staff = mysqli_query($conn, $sql_staff);
            if ($res_staff && mysqli_num_rows($res_staff) > 0) {
                $staff_data = mysqli_fetch_assoc($res_staff);
                $staff_name = htmlspecialchars($staff_data['name'] ?? 'Staff Member');
                $dbname = $staff_name;
                $dbmail = htmlspecialchars($staff_data['mail']);
                $dbusn = htmlspecialchars($staff_data['staffid']);
                $dbphno = htmlspecialchars($staff_data['phno']);
                $dbgender = htmlspecialchars($staff_data['gender']);
                $dbdob = htmlspecialchars($staff_data['DOB']);
                $dbdept = htmlspecialchars($staff_data['dept']);
                if (!empty($staff_data['avatar']) && file_exists(__DIR__ . '/' . $staff_data['avatar'])) {
                    $staff_avatar = $staff_data['avatar'];
                }
            }
            foreach (['png', 'jpg', 'jpeg', 'webp', 'svg'] as $ext) {
                if (file_exists(__DIR__ . '/images/logo.' . $ext)) {
                    $site_logo = 'images/logo.' . $ext;
                    break;
                }
            }
        } else {
            $alert = ["type" => "danger", "msg" => "Database error updating profile: " . mysqli_error($conn)];
        }
    } else {
        $alert = ["type" => "danger", "msg" => implode("<br>", $errors)];
    }
}

// Handle Edit Leaderboard Entry via POST (Rank, Student Name, Student Scores)
if (isset($_POST['edit_leaderboard_submit'])) {
    $edit_slno = intval($_POST['edit_slno'] ?? 0);
    $edit_student_mail = trim($_POST['edit_student_mail'] ?? '');
    $edit_name = trim($_POST['edit_name'] ?? '');
    $edit_score = intval($_POST['edit_score'] ?? 0);
    $edit_totalscore = intval($_POST['edit_totalscore'] ?? 0);
    $edit_rank = !empty($_POST['edit_rank']) ? intval($_POST['edit_rank']) : null;

    if ($edit_slno > 0 && !empty($edit_student_mail) && !empty($edit_name)) {
        $edit_name_c = mysqli_real_escape_string($conn, $edit_name);
        $edit_student_mail_c = mysqli_real_escape_string($conn, $edit_student_mail);
        $new_remark = ($edit_score == 0) ? 'bad' : 'good';
        $rank_val = ($edit_rank !== null && $edit_rank > 0) ? intval($edit_rank) : "NULL";

        // Update student name
        $upd_student = mysqli_query($conn, "UPDATE student SET name = '$edit_name_c' WHERE mail = '$edit_student_mail_c'");

        // Update score, totalscore, remark, and custom_rank in score table
        $upd_score_sql = "UPDATE score SET score = '$edit_score', totalscore = '$edit_totalscore', remark = '$new_remark', custom_rank = $rank_val WHERE slno = '$edit_slno'";
        $upd_score = mysqli_query($conn, $upd_score_sql);

        if ($upd_student && $upd_score) {
            $alert = ["type" => "success", "msg" => "Leaderboard entry for student '<strong>" . htmlspecialchars($edit_name) . "</strong>' (Rank: #" . ($edit_rank ?: 'Auto') . ", Score: $edit_score/$edit_totalscore) updated successfully!"];
        } else {
            $alert = ["type" => "danger", "msg" => "Database error updating leaderboard: " . mysqli_error($conn)];
        }
    } else {
        $alert = ["type" => "warning", "msg" => "Please fill in all required fields."];
    }
}

// Dashboard statistics
$stat_quizzes = 0;
$res = mysqli_query($conn, "SELECT COUNT(*) as cnt FROM quiz WHERE mail = '$username1'");
if ($res) { $stat_quizzes = mysqli_fetch_assoc($res)['cnt'] ?? 0; }

$stat_questions = 0;
$res = mysqli_query($conn, "SELECT COUNT(*) as cnt FROM questions qs JOIN quiz q ON qs.quizid = q.quizid WHERE q.mail = '$username1'");
if ($res) { $stat_questions = mysqli_fetch_assoc($res)['cnt'] ?? 0; }

$stat_attempts = 0;
$res = mysqli_query($conn, "SELECT COUNT(*) as cnt FROM score sc JOIN quiz q ON sc.quizid = q.quizid WHERE q.mail = '$username1'");
if ($res) { $stat_attempts = mysqli_fetch_assoc($res)['cnt'] ?? 0; }

$stat_max_score = 0;
$res = mysqli_query($conn, "SELECT MAX(sc.score) as mscore FROM score sc JOIN quiz q ON sc.quizid = q.quizid WHERE q.mail = '$username1'");
if ($res) { 
    $row = mysqli_fetch_assoc($res);
    $stat_max_score = $row['mscore'] ?? 0; 
}

// Fetch Staff's Quizzes
$quizzes_query = "SELECT q.quizid, q.quizname, q.date_created,
                  (SELECT COUNT(*) FROM questions qs WHERE qs.quizid = q.quizid) AS q_count,
                  (SELECT COUNT(*) FROM score sc WHERE sc.quizid = q.quizid) AS a_count
                  FROM quiz q 
                  WHERE q.mail = '$username1' 
                  ORDER BY q.quizid DESC";
$quizzes_res = mysqli_query($conn, $quizzes_query);

// Check / Add custom_rank column in score table if not present
$col_check_rank = mysqli_query($conn, "SHOW COLUMNS FROM score LIKE 'custom_rank'");
if ($col_check_rank && mysqli_num_rows($col_check_rank) == 0) {
    @mysqli_query($conn, "ALTER TABLE score ADD COLUMN custom_rank INT DEFAULT NULL");
}

// Fetch Leaderboard for Staff's Quizzes (ordered by custom_rank if set, then by score DESC)
$lead_query = "SELECT sc.slno, s.mail AS student_mail, s.name, sc.score, sc.totalscore, sc.remark, sc.custom_rank, q.quizname, q.quizid 
               FROM student s, staff st, score sc, quiz q 
               WHERE q.quizid = sc.quizid AND s.mail = sc.mail AND q.mail = st.mail AND q.mail = '$username1' 
               ORDER BY (CASE WHEN sc.custom_rank IS NOT NULL AND sc.custom_rank > 0 THEN sc.custom_rank ELSE 999999 END) ASC, sc.score DESC LIMIT 50";
$lead_res = mysqli_query($conn, $lead_query);
?>

<?php require_once __DIR__ . '/admin_sidebar.php'; ?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark font-weight-bold">
                        <i class="fas fa-tachometer-alt text-info mr-2"></i>Admin Dashboard
                    </h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="homestaff.php">Home</a></li>
                        <li class="breadcrumb-item active">Dashboard</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
        <div class="container-fluid">

            <!-- Alerts -->
            <?php if ($alert): ?>
                <div class="alert alert-<?= $alert['type'] ?> alert-dismissible fade show" role="alert">
                    <?= $alert['msg'] ?>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            <?php endif; ?>

            <!-- Small boxes (Stat box) -->
            <div class="row">
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-info stat-card-clickable" onclick="location.href='#quizzes-section';">
                        <div class="inner">
                            <h3><?= $stat_quizzes ?></h3>
                            <p class="font-weight-bold">My Quizzes</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-layer-group"></i>
                        </div>
                        <a href="#quizzes-section" class="small-box-footer">View Details <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                <div class="col-lg-3 col-6">
                    <div class="small-box bg-success stat-card-clickable" onclick="location.href='#quizzes-section';">
                        <div class="inner">
                            <h3><?= $stat_questions ?></h3>
                            <p class="font-weight-bold">Total Questions</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-question-circle"></i>
                        </div>
                        <a href="#quizzes-section" class="small-box-footer">View Details <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                <div class="col-lg-3 col-6">
                    <div class="small-box bg-warning stat-card-clickable" onclick="location.href='#leaderboard-section';">
                        <div class="inner">
                            <h3 class="text-white"><?= $stat_attempts ?></h3>
                            <p class="font-weight-bold text-white">Student Submissions</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-user-graduate"></i>
                        </div>
                        <a href="#leaderboard-section" class="small-box-footer text-white">View Leaderboard <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                <div class="col-lg-3 col-6">
                    <div class="small-box bg-danger stat-card-clickable" onclick="location.href='#leaderboard-section';">
                        <div class="inner">
                            <h3><?= $stat_max_score ?></h3>
                            <p class="font-weight-bold">Top Score Recorded</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-trophy"></i>
                        </div>
                        <a href="#leaderboard-section" class="small-box-footer">View Leaderboard <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
            </div>
            <!-- /.row -->

            <!-- Quick Action Toolbar -->
            <div class="card card-outline card-info mb-4">
                <div class="card-header">
                    <h3 class="card-title font-weight-bold">
                        <i class="fas fa-bolt text-warning mr-2"></i>Quick Actions
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-4 mb-2 mb-md-0">
                            <button type="button" class="btn btn-success btn-block shadow-sm" data-toggle="modal" data-target="#addQuizModal">
                                <i class="fas fa-plus-circle mr-1"></i> <strong>Create New Quiz</strong>
                            </button>
                        </div>

                        <!-- View Quiz Quick Form -->
                        <div class="col-md-4 mb-2 mb-md-0">
                            <form method="POST" class="d-flex">
                                <div class="input-group">
                                    <input type="number" name="quizid" class="form-control" placeholder="Enter Quiz ID to View" required min="1">
                                    <div class="input-group-append">
                                        <button class="btn btn-primary" type="submit" name="submit2">
                                            <i class="fas fa-eye mr-1"></i> View
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <!-- Delete Quiz Quick Form -->
                        <div class="col-md-4">
                            <form method="POST" class="d-flex" onsubmit="return confirm('Are you sure you want to permanently delete this Quiz? All questions and scores associated will also be deleted.');">
                                <div class="input-group">
                                    <input type="number" name="quizid" class="form-control" placeholder="Enter Quiz ID to Delete" required min="1">
                                    <div class="input-group-append">
                                        <button class="btn btn-danger" type="submit" name="submit1">
                                            <i class="fas fa-trash-alt mr-1"></i> Delete
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Manage Quizzes Section -->
            <div class="card card-primary card-outline shadow-sm mb-4" id="quizzes-section">
                <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
                    <h3 class="card-title font-weight-bold">
                        <i class="fas fa-book-reader text-info mr-2"></i>Quizzes Created by <?= $dbname ?>
                    </h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-sm btn-success" data-toggle="modal" data-target="#addQuizModal">
                            <i class="fas fa-plus mr-1"></i> New Quiz
                        </button>
                    </div>
                </div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover table-striped mb-0">
                        <thead class="thead-dark">
                            <tr>
                                <th style="width: 80px;">ID</th>
                                <th>Quiz Title</th>
                                <th>Date Created</th>
                                <th style="width: 140px;" class="text-center">Questions</th>
                                <th style="width: 140px;" class="text-center">Student Attempts</th>
                                <th style="width: 250px;" class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($quizzes_res && mysqli_num_rows($quizzes_res) > 0): ?>
                                <?php while ($row = mysqli_fetch_assoc($quizzes_res)): ?>
                                    <tr>
                                        <td>
                                            <span class="badge badge-secondary font-weight-bold">#<?= $row['quizid'] ?></span>
                                        </td>
                                        <td class="font-weight-bold text-dark text-capitalize">
                                            <?= htmlspecialchars($row['quizname']) ?>
                                        </td>
                                        <td class="text-muted text-sm">
                                            <i class="far fa-clock mr-1"></i><?= $row['date_created'] ?>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge badge-info px-2 py-1">
                                                <i class="fas fa-question mr-1"></i><?= $row['q_count'] ?> Questions
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge badge-warning text-white px-2 py-1">
                                                <i class="fas fa-user-check mr-1"></i><?= $row['a_count'] ?> Attempts
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group btn-group-sm">
                                                <a href="viewq.php?qid=<?= $row['quizid'] ?>" class="btn btn-info" title="View Quiz Questions">
                                                    <i class="fas fa-eye mr-1"></i> View
                                                </a>
                                                <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#editQuizModal<?= $row['quizid'] ?>" title="Edit Quiz Title">
                                                    <i class="fas fa-edit mr-1"></i> Edit
                                                </button>
                                                <a href="addq.php?qid=<?= $row['quizid'] ?>" class="btn btn-success" title="Add More Questions">
                                                    <i class="fas fa-plus mr-1"></i> Add Qs
                                                </a>
                                                <a href="homestaff.php?delete_quiz=<?= $row['quizid'] ?>" 
                                                   class="btn btn-danger" 
                                                   title="Delete Quiz"
                                                   onclick="return confirm('Are you sure you want to delete quiz \'<?= htmlspecialchars(addslashes($row['quizname'])) ?>\'?');">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                            </div>

                                            <!-- Edit Quiz Modal -->
                                            <div class="modal fade text-left" id="editQuizModal<?= $row['quizid'] ?>" tabindex="-1" role="dialog" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header bg-warning text-dark">
                                                            <h5 class="modal-title font-weight-bold">
                                                                <i class="fas fa-edit mr-2"></i>Edit Quiz #<?= $row['quizid'] ?>
                                                            </h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <form method="POST">
                                                            <div class="modal-body">
                                                                <input type="hidden" name="edit_quizid" value="<?= $row['quizid'] ?>">
                                                                <div class="form-group">
                                                                    <label class="font-weight-bold text-dark">Quiz Title <span class="text-danger">*</span></label>
                                                                    <input type="text" name="edit_quizname" class="form-control" value="<?= htmlspecialchars($row['quizname']) ?>" required maxlength="50">
                                                                    <small class="form-text text-muted">Update the display name of this quiz for students.</small>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer bg-light">
                                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                                                <button type="submit" name="edit_quiz_submit" class="btn btn-warning font-weight-bold">
                                                                    <i class="fas fa-save mr-1"></i> Save Changes
                                                                </button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" class="text-center py-4 text-muted">
                                        <i class="fas fa-folder-open fa-3x mb-3 text-secondary d-block"></i>
                                        <h5>No quizzes created yet!</h5>
                                        <p class="text-sm">Click the button below to create your first quiz.</p>
                                        <button type="button" class="btn btn-success mt-2" data-toggle="modal" data-target="#addQuizModal">
                                            <i class="fas fa-plus-circle mr-1"></i> Create Your First Quiz
                                        </button>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Leaderboard Section -->
            <div class="card card-warning card-outline shadow-sm mb-4" id="leaderboard-section">
                <div class="card-header">
                    <h3 class="card-title font-weight-bold">
                        <i class="fas fa-trophy text-warning mr-2"></i>Student Leaderboard (My Quizzes)
                    </h3>
                </div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th style="width: 70px;">Rank</th>
                                <th>Student Name</th>
                                <th>Quiz Title</th>
                                <th style="width: 140px;" class="text-center">Score / Total</th>
                                <th style="width: 180px;">Performance</th>
                                <th style="width: 110px;" class="text-center">Remark</th>
                                <th style="width: 100px;" class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($lead_res && mysqli_num_rows($lead_res) > 0): ?>
                                <?php 
                                $rank = 1;
                                while ($row = mysqli_fetch_assoc($lead_res)): 
                                    $score = intval($row['score']);
                                    $total = intval($row['totalscore']);
                                    $percent = ($total > 0) ? round(($score / $total) * 100) : 0;
                                    $display_rank = (!empty($row['custom_rank']) && intval($row['custom_rank']) > 0) ? intval($row['custom_rank']) : $rank;
                                ?>
                                    <tr>
                                        <td>
                                            <?php if ($display_rank === 1): ?>
                                                <span class="badge badge-warning text-dark font-weight-bold px-2 py-1"><i class="fas fa-medal mr-1"></i>1st</span>
                                            <?php elseif ($display_rank === 2): ?>
                                                <span class="badge badge-secondary font-weight-bold px-2 py-1"><i class="fas fa-medal mr-1"></i>2nd</span>
                                            <?php elseif ($display_rank === 3): ?>
                                                <span class="badge badge-info font-weight-bold px-2 py-1"><i class="fas fa-medal mr-1"></i>3rd</span>
                                            <?php else: ?>
                                                <span class="badge badge-light font-weight-bold px-2 py-1">#<?= $display_rank ?></span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="font-weight-bold text-dark">
                                            <i class="fas fa-user-circle text-secondary mr-1"></i><?= htmlspecialchars($row['name']) ?>
                                        </td>
                                        <td class="text-capitalize font-weight-500">
                                            <?= htmlspecialchars($row['quizname']) ?>
                                        </td>
                                        <td class="text-center font-weight-bold text-primary">
                                            <?= $score ?> / <?= $total ?>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="progress progress-xs flex-grow-1 mr-2" style="height: 8px;">
                                                    <div class="progress-bar <?= ($percent >= 70) ? 'bg-success' : (($percent >= 40) ? 'bg-warning' : 'bg-danger') ?>" style="width: <?= $percent ?>%"></div>
                                                </div>
                                                <span class="text-xs font-weight-bold text-muted"><?= $percent ?>%</span>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <?php if (strtolower($row['remark']) === 'good'): ?>
                                                <span class="badge badge-success px-2 py-1"><i class="fas fa-check-circle mr-1"></i>Good</span>
                                            <?php else: ?>
                                                <span class="badge badge-danger px-2 py-1"><i class="fas fa-exclamation-circle mr-1"></i><?= htmlspecialchars($row['remark'] ?? 'Needs Work') ?></span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-xs btn-outline-warning font-weight-bold px-2 py-1" data-toggle="modal" data-target="#editLeadModal<?= $row['slno'] ?>" title="Edit Rank, Name, Score">
                                                <i class="fas fa-edit mr-1"></i> Edit
                                            </button>

                                            <!-- Edit Leaderboard Modal -->
                                            <div class="modal fade text-left" id="editLeadModal<?= $row['slno'] ?>" tabindex="-1" role="dialog" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header bg-warning text-dark">
                                                            <h5 class="modal-title font-weight-bold">
                                                                <i class="fas fa-trophy mr-2"></i>Edit Leaderboard Entry
                                                            </h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <form method="POST" action="homestaff.php">
                                                            <div class="modal-body">
                                                                <input type="hidden" name="edit_slno" value="<?= $row['slno'] ?>">
                                                                <input type="hidden" name="edit_student_mail" value="<?= htmlspecialchars($row['student_mail']) ?>">

                                                                <div class="form-group">
                                                                    <label class="font-weight-bold text-dark"><i class="fas fa-medal text-warning mr-1"></i> Leaderboard Rank <span class="text-danger">*</span></label>
                                                                    <input type="number" name="edit_rank" class="form-control" value="<?= $display_rank ?>" min="1" max="9999" required>
                                                                    <small class="form-text text-muted">Specify the display rank for this student.</small>
                                                                </div>

                                                                <div class="form-group">
                                                                    <label class="font-weight-bold text-dark"><i class="fas fa-user text-primary mr-1"></i> Student Name <span class="text-danger">*</span></label>
                                                                    <input type="text" name="edit_name" class="form-control" value="<?= htmlspecialchars($row['name']) ?>" required maxlength="20">
                                                                    <small class="form-text text-muted">Email: <?= htmlspecialchars($row['student_mail']) ?></small>
                                                                </div>

                                                                <div class="row">
                                                                    <div class="col-sm-6">
                                                                        <div class="form-group">
                                                                            <label class="font-weight-bold text-dark"><i class="fas fa-star text-success mr-1"></i> Student Score <span class="text-danger">*</span></label>
                                                                            <input type="number" name="edit_score" class="form-control" value="<?= $score ?>" min="0" required>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-sm-6">
                                                                        <div class="form-group">
                                                                            <label class="font-weight-bold text-dark"><i class="fas fa-bullseye text-info mr-1"></i> Total Score <span class="text-danger">*</span></label>
                                                                            <input type="number" name="edit_totalscore" class="form-control" value="<?= $total ?>" min="1" required>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer bg-light">
                                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                                                <button type="submit" name="edit_leaderboard_submit" class="btn btn-warning font-weight-bold">
                                                                    <i class="fas fa-save mr-1"></i> Save Changes
                                                                </button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                <?php 
                                    $rank++;
                                endwhile; 
                                ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="7" class="text-center py-4 text-muted">
                                        <i class="fas fa-award fa-3x mb-2 text-secondary d-block"></i>
                                        <p class="mb-0">No student attempts recorded for your quizzes yet.</p>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Staff Profile Section -->
            <div class="card card-secondary card-outline shadow-sm mb-4" id="profile-section">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title font-weight-bold">
                        <i class="fas fa-id-card text-secondary mr-2"></i>Administrator Profile & System Settings
                    </h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-primary btn-sm font-weight-bold" data-toggle="modal" data-target="#editProfileModal">
                            <i class="fas fa-user-edit mr-1"></i> Edit Profile & Logo
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-3 text-center mb-3 mb-md-0">
                            <div class="position-relative d-inline-block">
                                <img src="<?= $staff_avatar ?>?v=<?= time() ?>" class="img-circle elevation-2" alt="Staff Avatar" style="width: 120px; height: 120px; object-fit: cover; border: 3px solid #17a2b8;">
                                <button type="button" class="btn btn-xs btn-primary position-absolute shadow-sm" style="bottom: 5px; right: 5px; border-radius: 50%; width: 32px; height: 32px; display: flex; align-items: center; justify-content: center;" data-toggle="modal" data-target="#editProfileModal" title="Change Avatar">
                                    <i class="fas fa-camera"></i>
                                </button>
                            </div>
                            <h5 class="mt-3 font-weight-bold mb-0 text-dark"><?= $dbname ?></h5>
                            <span class="badge badge-info mt-1">Quiz Administrator</span>

                            <!-- Current Site Logo Box -->
                            <div class="mt-3 p-2 bg-light rounded border text-left">
                                <small class="text-muted font-weight-bold d-block mb-1"><i class="fas fa-globe mr-1"></i> Website Logo:</small>
                                <div class="d-flex align-items-center">
                                    <?php if (!empty($site_logo) && file_exists(__DIR__ . '/' . $site_logo)): ?>
                                        <img src="<?= $site_logo ?>?v=<?= time() ?>" alt="Site Logo" style="max-height: 36px; max-width: 80px; object-fit: contain;" class="mr-2 border bg-white p-1 rounded">
                                        <span class="text-xs text-success font-weight-bold"><i class="fas fa-check-circle"></i> Custom Logo Active</span>
                                    <?php else: ?>
                                        <i class="fas fa-graduation-cap text-info mr-2" style="font-size: 1.5rem;"></i>
                                        <span class="text-xs text-muted">Default Icon Active</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-9">
                            <div class="row">
                                <div class="col-sm-6 mb-3">
                                    <div class="info-box bg-light shadow-none border">
                                        <span class="info-box-icon bg-info"><i class="fas fa-id-badge"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text text-muted">Staff ID</span>
                                            <span class="info-box-number text-dark"><?= $dbusn ?></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6 mb-3">
                                    <div class="info-box bg-light shadow-none border">
                                        <span class="info-box-icon bg-success"><i class="fas fa-building"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text text-muted">Department</span>
                                            <span class="info-box-number text-dark"><?= $dbdept ?></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6 mb-3">
                                    <div class="info-box bg-light shadow-none border">
                                        <span class="info-box-icon bg-primary"><i class="fas fa-envelope"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text text-muted">Email Address</span>
                                            <span class="info-box-number text-dark text-truncate" style="font-size: 0.95rem;"><?= $dbmail ?></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6 mb-3">
                                    <div class="info-box bg-light shadow-none border">
                                        <span class="info-box-icon bg-warning"><i class="fas fa-phone text-white"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text text-muted">Phone Number</span>
                                            <span class="info-box-number text-dark"><?= $dbphno ?></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6 mb-3">
                                    <div class="info-box bg-light shadow-none border">
                                        <span class="info-box-icon bg-danger"><i class="fas fa-venus-mars"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text text-muted">Gender</span>
                                            <span class="info-box-number text-dark"><?= ($dbgender === 'M' || $dbgender === 'm') ? 'Male' : (($dbgender === 'F' || $dbgender === 'f') ? 'Female' : 'Other') ?></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6 mb-3">
                                    <div class="info-box bg-light shadow-none border">
                                        <span class="info-box-icon bg-secondary"><i class="fas fa-calendar-alt"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text text-muted">Date of Birth</span>
                                            <span class="info-box-number text-dark"><?= $dbdob ?></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

<!-- Edit Profile & System Logo Modal -->
<div class="modal fade text-left" id="editProfileModal" tabindex="-1" role="dialog" aria-labelledby="editProfileModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <form method="POST" enctype="multipart/form-data" action="homestaff.php">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title font-weight-bold" id="editProfileModalLabel">
                        <i class="fas fa-user-edit mr-2"></i>Edit Administrator Profile & Website Logo
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <!-- Profile Picture Upload -->
                        <div class="col-md-6 mb-3">
                            <div class="card card-outline card-info h-100 mb-0">
                                <div class="card-header py-2">
                                    <h6 class="card-title font-weight-bold text-dark mb-0">
                                        <i class="fas fa-camera text-info mr-1"></i> Profile Picture
                                    </h6>
                                </div>
                                <div class="card-body text-center">
                                    <img src="<?= $staff_avatar ?>?v=<?= time() ?>" id="avatar_preview" class="img-circle elevation-2 mb-3" style="width: 100px; height: 100px; object-fit: cover; border: 2px solid #17a2b8;">
                                    <div class="custom-file text-left">
                                        <input type="file" name="profile_pic" class="custom-file-input" id="profilePicInput" accept="image/*" onchange="previewImage(this, 'avatar_preview');">
                                        <label class="custom-file-label" for="profilePicInput">Choose photo...</label>
                                    </div>
                                    <small class="text-muted d-block mt-1">Supported: JPG, PNG, WEBP, GIF (Max 5MB)</small>
                                </div>
                            </div>
                        </div>

                        <!-- Website Logo Upload -->
                        <div class="col-md-6 mb-3">
                            <div class="card card-outline card-success h-100 mb-0">
                                <div class="card-header py-2">
                                    <h6 class="card-title font-weight-bold text-dark mb-0">
                                        <i class="fas fa-globe text-success mr-1"></i> Website Logo
                                    </h6>
                                </div>
                                <div class="card-body text-center">
                                    <div class="mb-3 d-flex align-items-center justify-content-center" style="height: 100px; background: #f8f9fa; border-radius: 8px; border: 1px dashed #ced4da;">
                                        <?php if (!empty($site_logo) && file_exists(__DIR__ . '/' . $site_logo)): ?>
                                            <img src="<?= $site_logo ?>?v=<?= time() ?>" id="logo_preview" alt="Site Logo" style="max-height: 80px; max-width: 160px; object-fit: contain;">
                                        <?php else: ?>
                                            <div id="logo_preview_container">
                                                <i class="fas fa-graduation-cap text-info" style="font-size: 2.5rem;"></i>
                                                <span class="d-block text-xs text-muted mt-1">Default Icon</span>
                                            </div>
                                            <img id="logo_preview" src="" alt="New Logo Preview" style="max-height: 80px; max-width: 160px; object-fit: contain; display: none;">
                                        <?php endif; ?>
                                    </div>
                                    <div class="custom-file text-left">
                                        <input type="file" name="site_logo" class="custom-file-input" id="siteLogoInput" accept="image/*" onchange="previewImage(this, 'logo_preview', 'logo_preview_container');">
                                        <label class="custom-file-label" for="siteLogoInput">Upload new logo...</label>
                                    </div>
                                    <small class="text-muted d-block mt-1">Replaces logo across Admin & Login screens</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr class="mt-2 mb-3">

                    <h6 class="font-weight-bold text-dark mb-3"><i class="fas fa-id-card-alt text-primary mr-1"></i> Personal & Academic Details</h6>

                    <div class="row">
                        <!-- Department -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bold text-dark">Department <span class="text-danger">*</span></label>
                                <select name="dept" class="form-control" required>
                                    <?php 
                                    $dept_res = mysqli_query($conn, "SELECT * FROM dept ORDER BY dept_id ASC");
                                    if ($dept_res && mysqli_num_rows($dept_res) > 0) {
                                        while ($d_row = mysqli_fetch_assoc($dept_res)) {
                                            $selected = (strcasecmp($d_row['dept_name'], $dbdept) === 0) ? 'selected' : '';
                                            echo "<option value=\"" . htmlspecialchars($d_row['dept_name']) . "\" $selected>" . htmlspecialchars($d_row['dept_name']) . " (ID: " . $d_row['dept_id'] . ")</option>";
                                        }
                                    } else {
                                        foreach (['CSE', 'ISE', 'ECE', 'EEE'] as $dname) {
                                            $selected = (strcasecmp($dname, $dbdept) === 0) ? 'selected' : '';
                                            echo "<option value=\"$dname\" $selected>$dname</option>";
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>

                        <!-- Email -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bold text-dark">Email Address <span class="text-danger">*</span></label>
                                <input type="email" name="mail" class="form-control" value="<?= htmlspecialchars($dbmail) ?>" required maxlength="30">
                                <small class="text-muted">Used for login and quiz ownership.</small>
                            </div>
                        </div>

                        <!-- Phone Number -->
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="font-weight-bold text-dark">Phone Number <span class="text-danger">*</span></label>
                                <input type="tel" name="phno" class="form-control" value="<?= htmlspecialchars($dbphno) ?>" pattern="[0-9]{10}" maxlength="10" placeholder="10-digit number" required>
                                <small class="text-muted">Must be 10 digits.</small>
                            </div>
                        </div>

                        <!-- Gender -->
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="font-weight-bold text-dark">Gender <span class="text-danger">*</span></label>
                                <select name="gender" class="form-control" required>
                                    <option value="M" <?= (strtoupper($dbgender) === 'M') ? 'selected' : '' ?>>Male (M)</option>
                                    <option value="F" <?= (strtoupper($dbgender) === 'F') ? 'selected' : '' ?>>Female (F)</option>
                                    <option value="O" <?= (strtoupper($dbgender) === 'O') ? 'selected' : '' ?>>Other (O)</option>
                                </select>
                            </div>
                        </div>

                        <!-- Date of Birth -->
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="font-weight-bold text-dark">Date of Birth <span class="text-danger">*</span></label>
                                <input type="date" name="dob" class="form-control" value="<?= htmlspecialchars($dbdob) ?>" required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light d-flex justify-content-between">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" name="update_profile_submit" class="btn btn-primary font-weight-bold">
                        <i class="fas fa-save mr-1"></i> Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function previewImage(input, previewId, hideContainerId) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
            var img = document.getElementById(previewId);
            if (img) {
                img.src = e.target.result;
                img.style.display = 'block';
            }
            if (hideContainerId) {
                var container = document.getElementById(hideContainerId);
                if (container) {
                    container.style.display = 'none';
                }
            }
        };
        reader.readAsDataURL(input.files[0]);
        var nextLabel = input.nextElementSibling;
        if (nextLabel && nextLabel.classList.contains('custom-file-label')) {
            nextLabel.innerText = input.files[0].name;
        }
    }
}
</script>

        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<!-- Add Quiz Modal -->
<div class="modal fade" id="addQuizModal" tabindex="-1" role="dialog" aria-labelledby="addQuizModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <form method="POST" action="homestaff.php">
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title font-weight-bold" id="addQuizModalLabel">
                        <i class="fas fa-plus-circle mr-2"></i>Create New Quiz
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body py-4">
                    <div class="form-group">
                        <label for="quizNameInput" class="font-weight-bold text-dark">Quiz Name</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-tag"></i></span>
                            </div>
                            <input type="text" 
                                   class="form-control" 
                                   id="quizNameInput" 
                                   name="quizname" 
                                   placeholder="e.g. Python Basics, Java OOP, Web Development" 
                                   required 
                                   maxlength="20">
                        </div>
                        <small class="form-text text-muted">
                            <i class="fas fa-info-circle mr-1"></i>Enter a unique title. You will be redirected immediately to add questions.
                        </small>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" name="submit" class="btn btn-success font-weight-bold">
                        <i class="fas fa-check mr-1"></i> Proceed to Add Questions
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/admin_footer.php'; ?>