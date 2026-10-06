<?php
$page_title = "View Quiz Questions";
$active_page = "quizzes";
require_once __DIR__ . '/admin_header.php';

$qid = isset($_GET["qid"]) ? intval($_GET["qid"]) : 0;
$alert = null;

if ($qid <= 0) {
    echo "<script>window.location.href = 'homestaff.php';</script>";
    exit();
}

// Fetch Quiz Info
$quiz_sql = "SELECT * FROM quiz WHERE quizid = '$qid' AND mail = '$username1'";
$quiz_res = mysqli_query($conn, $quiz_sql);
$quiz_data = ($quiz_res && mysqli_num_rows($quiz_res) > 0) ? mysqli_fetch_assoc($quiz_res) : null;

// Allow viewing if quiz exists
if (!$quiz_data) {
    // If not owned by staff, check if quiz exists at all
    $quiz_sql2 = "SELECT * FROM quiz WHERE quizid = '$qid'";
    $quiz_res2 = mysqli_query($conn, $quiz_sql2);
    $quiz_data = ($quiz_res2 && mysqli_num_rows($quiz_res2) > 0) ? mysqli_fetch_assoc($quiz_res2) : null;
}

if (!$quiz_data) {
    echo "<script>alert('Quiz not found!'); window.location.href = 'homestaff.php';</script>";
    exit();
}

// Handle Edit Quiz Title via POST
if (isset($_POST['edit_quiz_title'])) {
    $new_name = trim($_POST['new_quizname']);
    if (!empty($new_name)) {
        $new_name_c = mysqli_real_escape_string($conn, $new_name);
        $up_q = "UPDATE quiz SET quizname = '$new_name_c' WHERE quizid = '$qid' AND mail = '$username1'";
        if (mysqli_query($conn, $up_q)) {
            $quiz_data['quizname'] = $new_name;
            $alert = ["type" => "success", "msg" => "Quiz title updated successfully to '<strong>" . htmlspecialchars($new_name) . "</strong>'."];
        } else {
            $alert = ["type" => "danger", "msg" => "Failed to update quiz title: " . mysqli_error($conn)];
        }
    }
}

// Handle Edit Question via POST
if (isset($_POST['edit_question_submit'])) {
    $old_qs = trim($_POST['old_qs']);
    $new_qs = trim($_POST['edit_qs']);
    $opt_a = trim($_POST['edit_op_a']);
    $opt_b = trim($_POST['edit_op_b']);
    $opt_c = trim($_POST['edit_op_c']);
    $opt_d = trim($_POST['edit_op_d']);
    $correct_choice = $_POST['edit_correct_opt'] ?? 'A';

    if (!empty($new_qs) && !empty($opt_a) && !empty($opt_b) && !empty($opt_c) && !empty($opt_d)) {
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

        $old_qs_c = mysqli_real_escape_string($conn, $old_qs);
        $new_qs_c = mysqli_real_escape_string($conn, $new_qs);
        $ans_c = mysqli_real_escape_string($conn, $ans);
        $op1_c = mysqli_real_escape_string($conn, $op1);
        $op2_c = mysqli_real_escape_string($conn, $op2);
        $op3_c = mysqli_real_escape_string($conn, $op3);

        $up_qs_sql = "UPDATE questions SET qs = '$new_qs_c', op1 = '$op1_c', op2 = '$op2_c', op3 = '$op3_c', answer = '$ans_c' WHERE quizid = '$qid' AND qs = '$old_qs_c'";
        if (mysqli_query($conn, $up_qs_sql)) {
            $alert = ["type" => "success", "msg" => "Question updated successfully!"];
        } else {
            $alert = ["type" => "danger", "msg" => "Failed to update question: " . mysqli_error($conn)];
        }
    } else {
        $alert = ["type" => "warning", "msg" => "Please fill in all question fields and options."];
    }
}

// Handle Delete Single Question
if (isset($_POST['delete_question'])) {
    $del_qs = mysqli_real_escape_string($conn, $_POST['question_text']);
    $del_sql = "DELETE FROM questions WHERE quizid = '$qid' AND qs = '$del_qs'";
    if (mysqli_query($conn, $del_sql) && mysqli_affected_rows($conn) > 0) {
        $alert = ["type" => "success", "msg" => "Question deleted successfully."];
    } else {
        $alert = ["type" => "warning", "msg" => "Could not delete question."];
    }
}

// Fetch questions for this quiz
$qs_sql = "SELECT * FROM questions WHERE quizid = '$qid'";
$qs_res = mysqli_query($conn, $qs_sql);
$total_questions = ($qs_res) ? mysqli_num_rows($qs_res) : 0;
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
                        <i class="fas fa-list-ol text-info mr-2"></i>Quiz Questions
                    </h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="homestaff.php">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="homestaff.php#quizzes-section">Quizzes</a></li>
                        <li class="breadcrumb-item active"><?= htmlspecialchars($quiz_data['quizname']) ?></li>
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

            <!-- Quiz Overview Header Card -->
            <div class="card card-primary card-outline shadow-sm mb-4">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-7 mb-3 mb-md-0">
                            <span class="badge badge-secondary px-2 py-1 mb-2">Quiz ID #<?= $qid ?></span>
                            <h2 class="font-weight-bold text-dark text-capitalize mb-1">
                                <?= htmlspecialchars($quiz_data['quizname']) ?>
                                <button type="button" class="btn btn-outline-warning btn-xs font-weight-bold ml-2" data-toggle="modal" data-target="#editQuizTitleModal" title="Edit Quiz Title">
                                    <i class="fas fa-edit mr-1"></i> Edit Title
                                </button>
                            </h2>
                            <p class="text-muted mb-0">
                                <i class="far fa-calendar-alt mr-1"></i> Created on <?= $quiz_data['date_created'] ?>
                                <span class="mx-2">•</span>
                                <span class="badge badge-info px-2 py-1">
                                    <i class="fas fa-question-circle mr-1"></i><?= $total_questions ?> Total Questions
                                </span>
                            </p>
                        </div>
                        <div class="col-md-5 text-md-right">
                            <a href="addq.php?qid=<?= $qid ?>" class="btn btn-success font-weight-bold shadow-sm mr-2 mb-2 mb-sm-0">
                                <i class="fas fa-plus-circle mr-1"></i> Add Questions
                            </a>
                            <a href="homestaff.php" class="btn btn-outline-secondary font-weight-bold">
                                <i class="fas fa-arrow-left mr-1"></i> Back to Dashboard
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Edit Quiz Title Modal -->
            <div class="modal fade" id="editQuizTitleModal" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header bg-warning text-dark">
                            <h5 class="modal-title font-weight-bold"><i class="fas fa-edit mr-2"></i>Edit Quiz Title</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form method="POST">
                            <div class="modal-body">
                                <div class="form-group">
                                    <label class="font-weight-bold text-dark">Quiz Title <span class="text-danger">*</span></label>
                                    <input type="text" name="new_quizname" class="form-control" value="<?= htmlspecialchars($quiz_data['quizname']) ?>" required maxlength="50">
                                </div>
                            </div>
                            <div class="modal-footer bg-light">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                <button type="submit" name="edit_quiz_title" class="btn btn-warning font-weight-bold">
                                    <i class="fas fa-save mr-1"></i> Save Changes
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Questions List -->
            <?php if ($total_questions > 0): ?>
                <div class="row">
                    <?php 
                    $i = 1;
                    while ($q_row = mysqli_fetch_assoc($qs_res)): 
                        $correct_answer = trim($q_row['answer']);
                        $op1 = trim($q_row['op1']);
                        $op2 = trim($q_row['op2']);
                        $op3 = trim($q_row['op3']);
                        
                        // Determine which option corresponds to the correct answer
                        $opt_a = $op1;
                        $opt_b = $op2;
                        $opt_c = $op3;
                        $opt_d = $correct_answer;
                        $cur_correct = 'D';
                    ?>
                        <div class="col-12 mb-3">
                            <div class="card card-outline card-secondary shadow-sm">
                                <div class="card-header d-flex justify-content-between align-items-center bg-light">
                                    <h5 class="card-title font-weight-bold text-dark mb-0">
                                        <span class="badge badge-primary mr-2">Q<?= $i ?></span>
                                        <?= htmlspecialchars($q_row['qs']) ?>
                                    </h5>
                                    <div class="card-tools">
                                        <button type="button" class="btn btn-tool text-primary mr-2" data-toggle="modal" data-target="#editQuestionModal<?= $i ?>" title="Edit Question">
                                            <i class="fas fa-edit mr-1"></i> Edit
                                        </button>
                                        <form method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this question?');">
                                            <input type="hidden" name="question_text" value="<?= htmlspecialchars($q_row['qs']) ?>">
                                            <button type="submit" name="delete_question" class="btn btn-tool text-danger" title="Delete Question">
                                                <i class="fas fa-trash-alt"></i> Delete
                                            </button>
                                        </form>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <!-- Option A -->
                                        <div class="col-md-6 mb-2">
                                            <div class="p-2 border rounded <?= ($opt_a === $correct_answer) ? 'border-success bg-light text-success font-weight-bold' : 'text-dark' ?>">
                                                <span class="font-weight-bold mr-2">A.</span> <?= htmlspecialchars($opt_a) ?>
                                                <?php if ($opt_a === $correct_answer): ?>
                                                    <span class="badge badge-success float-right"><i class="fas fa-check-circle mr-1"></i>Correct Answer</span>
                                                <?php endif; ?>
                                            </div>
                                        </div>

                                        <!-- Option B -->
                                        <div class="col-md-6 mb-2">
                                            <div class="p-2 border rounded <?= ($opt_b === $correct_answer) ? 'border-success bg-light text-success font-weight-bold' : 'text-dark' ?>">
                                                <span class="font-weight-bold mr-2">B.</span> <?= htmlspecialchars($opt_b) ?>
                                                <?php if ($opt_b === $correct_answer): ?>
                                                    <span class="badge badge-success float-right"><i class="fas fa-check-circle mr-1"></i>Correct Answer</span>
                                                <?php endif; ?>
                                            </div>
                                        </div>

                                        <!-- Option C -->
                                        <div class="col-md-6 mb-2">
                                            <div class="p-2 border rounded <?= ($opt_c === $correct_answer) ? 'border-success bg-light text-success font-weight-bold' : 'text-dark' ?>">
                                                <span class="font-weight-bold mr-2">C.</span> <?= htmlspecialchars($opt_c) ?>
                                                <?php if ($opt_c === $correct_answer): ?>
                                                    <span class="badge badge-success float-right"><i class="fas fa-check-circle mr-1"></i>Correct Answer</span>
                                                <?php endif; ?>
                                            </div>
                                        </div>

                                        <!-- Option D -->
                                        <div class="col-md-6 mb-2">
                                            <div class="p-2 border rounded <?= ($opt_d === $correct_answer) ? 'border-success bg-light text-success font-weight-bold' : 'text-dark' ?>">
                                                <span class="font-weight-bold mr-2">D.</span> <?= htmlspecialchars($opt_d) ?>
                                                <?php if ($opt_d === $correct_answer): ?>
                                                    <span class="badge badge-success float-right"><i class="fas fa-check-circle mr-1"></i>Correct Answer</span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Edit Question Modal -->
                        <div class="modal fade" id="editQuestionModal<?= $i ?>" tabindex="-1" role="dialog" aria-hidden="true">
                            <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                                <div class="modal-content text-left">
                                    <div class="modal-header bg-primary text-white">
                                        <h5 class="modal-title font-weight-bold">
                                            <i class="fas fa-edit mr-2"></i>Edit Question #<?= $i ?>
                                        </h5>
                                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <form method="POST">
                                        <div class="modal-body">
                                            <input type="hidden" name="old_qs" value="<?= htmlspecialchars($q_row['qs']) ?>">
                                            
                                            <div class="form-group">
                                                <label class="font-weight-bold text-dark">Question Statement <span class="text-danger">*</span></label>
                                                <textarea name="edit_qs" class="form-control" rows="3" required><?= htmlspecialchars($q_row['qs']) ?></textarea>
                                            </div>

                                            <label class="font-weight-bold text-dark mb-2">Options & Correct Answer Selection (Check the radio/box of the correct answer):</label>

                                            <!-- Option A -->
                                            <div class="form-group">
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text font-weight-bold">A</span>
                                                    </div>
                                                    <input type="text" name="edit_op_a" class="form-control" value="<?= htmlspecialchars($opt_a) ?>" required maxlength="50">
                                                    <div class="input-group-append">
                                                        <div class="input-group-text bg-white">
                                                            <input type="radio" name="edit_correct_opt" value="A" id="edit_opt_a_<?= $i ?>" <?= ($opt_a === $correct_answer) ? 'checked' : '' ?> required>
                                                            <label for="edit_opt_a_<?= $i ?>" class="mb-0 ml-1 font-weight-bold text-success text-sm" style="cursor: pointer;">Correct</label>
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
                                                    <input type="text" name="edit_op_b" class="form-control" value="<?= htmlspecialchars($opt_b) ?>" required maxlength="50">
                                                    <div class="input-group-append">
                                                        <div class="input-group-text bg-white">
                                                            <input type="radio" name="edit_correct_opt" value="B" id="edit_opt_b_<?= $i ?>" <?= ($opt_b === $correct_answer) ? 'checked' : '' ?> required>
                                                            <label for="edit_opt_b_<?= $i ?>" class="mb-0 ml-1 font-weight-bold text-success text-sm" style="cursor: pointer;">Correct</label>
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
                                                    <input type="text" name="edit_op_c" class="form-control" value="<?= htmlspecialchars($opt_c) ?>" required maxlength="50">
                                                    <div class="input-group-append">
                                                        <div class="input-group-text bg-white">
                                                            <input type="radio" name="edit_correct_opt" value="C" id="edit_opt_c_<?= $i ?>" <?= ($opt_c === $correct_answer) ? 'checked' : '' ?> required>
                                                            <label for="edit_opt_c_<?= $i ?>" class="mb-0 ml-1 font-weight-bold text-success text-sm" style="cursor: pointer;">Correct</label>
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
                                                    <input type="text" name="edit_op_d" class="form-control" value="<?= htmlspecialchars($opt_d) ?>" required maxlength="50">
                                                    <div class="input-group-append">
                                                        <div class="input-group-text bg-white">
                                                            <input type="radio" name="edit_correct_opt" value="D" id="edit_opt_d_<?= $i ?>" <?= ($opt_d === $correct_answer) ? 'checked' : '' ?> required>
                                                            <label for="edit_opt_d_<?= $i ?>" class="mb-0 ml-1 font-weight-bold text-success text-sm" style="cursor: pointer;">Correct</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer bg-light">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                            <button type="submit" name="edit_question_submit" class="btn btn-primary font-weight-bold">
                                                <i class="fas fa-save mr-1"></i> Update Question
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php 
                        $i++;
                    endwhile; 
                    ?>
                </div>
            <?php else: ?>
                <div class="card shadow-sm text-center py-5">
                    <div class="card-body">
                        <i class="fas fa-question-circle fa-4x text-secondary mb-3"></i>
                        <h4 class="font-weight-bold text-dark">No questions found under this quiz!</h4>
                        <p class="text-muted">Start creating multiple choice questions for your students now.</p>
                        <a href="addq.php?qid=<?= $qid ?>" class="btn btn-success btn-lg font-weight-bold mt-2 shadow-sm">
                            <i class="fas fa-plus-circle mr-1"></i> Add First Question
                        </a>
                    </div>
                </div>
            <?php endif; ?>

        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<?php require_once __DIR__ . '/admin_footer.php'; ?>