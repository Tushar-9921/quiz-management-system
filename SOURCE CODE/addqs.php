<?php
$page_title = "Add Questions to Quiz";
$active_page = "quizzes";
require_once __DIR__ . '/admin_header.php';

$alert = null;

// Identify Quiz from session
$qname = isset($_SESSION['qname']) ? trim($_SESSION['qname']) : '';
$qid = 0;

if (!empty($qname)) {
    $qname_clean = mysqli_real_escape_string($conn, $qname);
    $sql_quiz = "SELECT quizid, quizname FROM quiz WHERE quizname = '$qname_clean'";
    $res_quiz = mysqli_query($conn, $sql_quiz);
    if ($res_quiz && mysqli_num_rows($res_quiz) > 0) {
        $row_q = mysqli_fetch_assoc($res_quiz);
        $qid = $row_q['quizid'];
    }
}

// Fallback: If session expired or not set, check if qid passed via GET
if ($qid == 0 && isset($_GET['qid'])) {
    $qid = intval($_GET['qid']);
    $sql_quiz = "SELECT quizid, quizname FROM quiz WHERE quizid = '$qid'";
    $res_quiz = mysqli_query($conn, $sql_quiz);
    if ($res_quiz && mysqli_num_rows($res_quiz) > 0) {
        $row_q = mysqli_fetch_assoc($res_quiz);
        $qname = $row_q['quizname'];
    }
}

if ($qid == 0) {
    echo "<script>alert('No active quiz found! Returning to dashboard.'); window.location.href = 'homestaff.php';</script>";
    exit();
}

// Handle Form Submissions
if (isset($_POST['submit']) || isset($_POST['submit1'])) {
    $qs = trim($_POST["qs"]);
    $opt_a = trim($_POST["op_a"]);
    $opt_b = trim($_POST["op_b"]);
    $opt_c = trim($_POST["op_c"]);
    $opt_d = trim($_POST["op_d"]);
    $correct_choice = $_POST["correct_opt"] ?? 'A';

    if (!empty($qs) && !empty($opt_a) && !empty($opt_b) && !empty($opt_c) && !empty($opt_d)) {
        if ($correct_choice === 'A') {
            $ans = $opt_a;
            $op1 = $opt_b;
            $op2 = $opt_c;
            $op3 = $opt_d;
        } elseif ($correct_choice === 'B') {
            $ans = $opt_b;
            $op1 = $opt_a;
            $op2 = $opt_c;
            $op3 = $opt_d;
        } elseif ($correct_choice === 'C') {
            $ans = $opt_c;
            $op1 = $opt_a;
            $op2 = $opt_b;
            $op3 = $opt_d;
        } else {
            $ans = $opt_d;
            $op1 = $opt_a;
            $op2 = $opt_b;
            $op3 = $opt_c;
        }

        $qs_c = mysqli_real_escape_string($conn, $qs);
        $op1_c = mysqli_real_escape_string($conn, $op1);
        $op2_c = mysqli_real_escape_string($conn, $op2);
        $op3_c = mysqli_real_escape_string($conn, $op3);
        $ans_c = mysqli_real_escape_string($conn, $ans);

        // Check if question already exists in this quiz
        $check_sql = "SELECT qs FROM questions WHERE quizid = '$qid' AND qs = '$qs_c'";
        $check_res = mysqli_query($conn, $check_sql);

        if ($check_res && mysqli_num_rows($check_res) > 0) {
            $alert = ["type" => "danger", "msg" => "This exact question already exists in this quiz."];
        } else {
            $insert_sql = "INSERT INTO questions (qs, op1, op2, op3, answer, quizid) VALUES ('$qs_c', '$op1_c', '$op2_c', '$op3_c', '$ans_c', '$qid')";
            $insert_res = mysqli_query($conn, $insert_sql);

            if ($insert_res) {
                if (isset($_POST['submit1'])) {
                    // Finish and return to dashboard
                    echo "<script>window.location.href = 'homestaff.php';</script>";
                    exit();
                } else {
                    $alert = ["type" => "success", "msg" => "Question added successfully! You can now add another question below."];
                }
            } else {
                $alert = ["type" => "danger", "msg" => "Database error: " . mysqli_error($conn)];
            }
        }
    } else {
        $alert = ["type" => "warning", "msg" => "Please fill in all question fields and all 4 options."];
    }
}

// Fetch existing questions for display
$existing_sql = "SELECT * FROM questions WHERE quizid = '$qid' ORDER BY rowid DESC 2>/dev/null"; // standard fallback
$existing_sql = "SELECT * FROM questions WHERE quizid = '$qid'";
$existing_res = mysqli_query($conn, $existing_sql);
$existing_count = ($existing_res) ? mysqli_num_rows($existing_res) : 0;
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
                        <i class="fas fa-edit text-info mr-2"></i>Add Questions to Quiz
                    </h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="homestaff.php">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="homestaff.php#quizzes-section">Quizzes</a></li>
                        <li class="breadcrumb-item active">Add Questions</li>
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

            <!-- Quiz Target Banner -->
            <div class="callout callout-info shadow-sm mb-4 bg-white">
                <div class="d-flex justify-content-between align-items-center flex-wrap">
                    <div>
                        <h5 class="font-weight-bold mb-1 text-dark">
                            Target Quiz: <span class="text-info text-capitalize"><?= htmlspecialchars($qname) ?></span>
                            <span class="badge badge-secondary ml-2 font-weight-normal">ID #<?= $qid ?></span>
                        </h5>
                        <p class="text-muted mb-0 text-sm">
                            <i class="fas fa-layer-group mr-1"></i> <?= $existing_count ?> question(s) currently configured in this quiz.
                        </p>
                    </div>
                    <div class="mt-2 mt-sm-0">
                        <a href="viewq.php?qid=<?= $qid ?>" class="btn btn-outline-info btn-sm font-weight-bold mr-1">
                            <i class="fas fa-eye mr-1"></i> View All Questions
                        </a>
                        <a href="homestaff.php" class="btn btn-outline-secondary btn-sm font-weight-bold">
                            <i class="fas fa-arrow-left mr-1"></i> Dashboard
                        </a>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Left: Question Input Form -->
                <div class="col-lg-7">
                    <div class="card card-primary card-outline shadow-sm mb-4">
                        <div class="card-header">
                            <h3 class="card-title font-weight-bold text-dark">
                                <i class="fas fa-plus-circle text-primary mr-2"></i>Question Details
                            </h3>
                        </div>
                        <form method="POST">
                            <div class="card-body">
                                <div class="form-group">
                                    <label class="font-weight-bold text-dark">Question Statement <span class="text-danger">*</span></label>
                                    <textarea name="qs" 
                                              class="form-control" 
                                              rows="3" 
                                              placeholder="Type the question here (e.g., What is the output of 2 + 2 in Python?)" 
                                              required 
                                              autofocus></textarea>
                                </div>

                                <div class="form-group mb-2">
                                    <label class="font-weight-bold text-dark mb-1">
                                        Options & Correct Answer Selection:
                                    </label>
                                    <p class="text-muted text-xs mb-2">Enter all 4 choices and select the radio button next to the choice that is the correct answer.</p>
                                </div>

                                <!-- Option A -->
                                <div class="form-group">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text font-weight-bold">A</span>
                                        </div>
                                        <input type="text" name="op_a" class="form-control" placeholder="Option A choice" required maxlength="50">
                                        <div class="input-group-append">
                                            <div class="input-group-text bg-white">
                                                <input type="radio" name="correct_opt" value="A" id="correct_a" required checked>
                                                <label for="correct_a" class="mb-0 ml-1 font-weight-bold text-success text-sm" style="cursor: pointer;">Correct</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Option B -->
                                <div class="form-group">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text font-weight-bold">B</span>
                                        </div>
                                        <input type="text" name="op_b" class="form-control" placeholder="Option B choice" required maxlength="50">
                                        <div class="input-group-append">
                                            <div class="input-group-text bg-white">
                                                <input type="radio" name="correct_opt" value="B" id="correct_b" required>
                                                <label for="correct_b" class="mb-0 ml-1 font-weight-bold text-success text-sm" style="cursor: pointer;">Correct</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Option C -->
                                <div class="form-group">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text font-weight-bold">C</span>
                                        </div>
                                        <input type="text" name="op_c" class="form-control" placeholder="Option C choice" required maxlength="50">
                                        <div class="input-group-append">
                                            <div class="input-group-text bg-white">
                                                <input type="radio" name="correct_opt" value="C" id="correct_c" required>
                                                <label for="correct_c" class="mb-0 ml-1 font-weight-bold text-success text-sm" style="cursor: pointer;">Correct</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Option D -->
                                <div class="form-group">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text font-weight-bold">D</span>
                                        </div>
                                        <input type="text" name="op_d" class="form-control" placeholder="Option D choice" required maxlength="50">
                                        <div class="input-group-append">
                                            <div class="input-group-text bg-white">
                                                <input type="radio" name="correct_opt" value="D" id="correct_d" required>
                                                <label for="correct_d" class="mb-0 ml-1 font-weight-bold text-success text-sm" style="cursor: pointer;">Correct</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer bg-light d-flex justify-content-between flex-wrap">
                                <button type="submit" name="submit" class="btn btn-primary font-weight-bold mb-2 mb-sm-0">
                                    <i class="fas fa-plus mr-1"></i> Save & Add Another Question
                                </button>
                                <div>
                                    <button type="submit" name="submit1" class="btn btn-success font-weight-bold mb-2 mb-sm-0">
                                        <i class="fas fa-check-double mr-1"></i> Save & Finish Quiz
                                    </button>
                                    <a href="homestaff.php" class="btn btn-secondary font-weight-bold ml-1 mb-2 mb-sm-0">
                                        Done
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Right: Existing Questions in Quiz -->
                <div class="col-lg-5">
                    <div class="card card-secondary card-outline shadow-sm mb-4">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h3 class="card-title font-weight-bold text-dark">
                                <i class="fas fa-list-check text-secondary mr-2"></i>Questions Added (<?= $existing_count ?>)
                            </h3>
                            <div class="card-tools">
                                <span class="badge badge-info"><?= $existing_count ?> Total</span>
                            </div>
                        </div>
                        <div class="card-body p-0" style="max-height: 600px; overflow-y: auto;">
                            <?php if ($existing_count > 0): ?>
                                <ul class="list-group list-group-flush">
                                    <?php 
                                    $num = 1;
                                    while ($q_item = mysqli_fetch_assoc($existing_res)): 
                                    ?>
                                        <li class="list-group-item">
                                            <div class="d-flex justify-content-between align-items-start">
                                                <div class="font-weight-bold text-dark mb-1">
                                                    <span class="badge badge-secondary mr-1"><?= $num ?></span>
                                                    <?= htmlspecialchars($q_item['qs']) ?>
                                                </div>
                                            </div>
                                            <div class="text-xs text-muted mt-1">
                                                <span class="mr-2">A: <?= htmlspecialchars($q_item['op1']) ?></span>
                                                <span class="mr-2">B: <?= htmlspecialchars($q_item['op2']) ?></span>
                                                <span class="mr-2">C: <?= htmlspecialchars($q_item['op3']) ?></span>
                                                <span class="text-success font-weight-bold"><i class="fas fa-check"></i> <?= htmlspecialchars($q_item['answer']) ?></span>
                                            </div>
                                        </li>
                                    <?php 
                                        $num++;
                                    endwhile; 
                                    ?>
                                </ul>
                            <?php else: ?>
                                <div class="text-center py-5 text-muted">
                                    <i class="fas fa-inbox fa-3x mb-2 text-secondary"></i>
                                    <p class="mb-0">No questions added yet.<br>Use the form on the left to add your first question.</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<?php require_once __DIR__ . '/admin_footer.php'; ?>