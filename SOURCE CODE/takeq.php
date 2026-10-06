<?php
$page_title = "Take Quiz";
$active_page = "dashboard";
require_once __DIR__ . '/student_nav.php';

$qid = isset($_GET["qid"]) ? intval($_GET["qid"]) : 0;
$alert = null;

if ($qid <= 0) {
    echo "<script>window.location.href = 'homestud.php';</script>";
    exit();
}

// Fetch Quiz info
$quiz_sql = "SELECT * FROM quiz WHERE quizid = '$qid'";
$quiz_res = mysqli_query($conn, $quiz_sql);
$quiz_data = ($quiz_res && mysqli_num_rows($quiz_res) > 0) ? mysqli_fetch_assoc($quiz_res) : null;

if (!$quiz_data) {
    echo "<script>alert('Quiz not found!'); window.location.href = 'homestud.php';</script>";
    exit();
}

// Fetch questions
$qs_sql = "SELECT * FROM questions WHERE quizid = '$qid'";
$qs_res = mysqli_query($conn, $qs_sql);
$count = ($qs_res) ? mysqli_num_rows($qs_res) : 0;

// Handle Quiz Submission
if (isset($_POST["submit"])) {
    $score = 0;
    $post_answers = $_POST['correct_ans'] ?? [];

    for ($i = 1; $i <= $count; $i++) {
        if (isset($_POST["ans" . $i]) && isset($post_answers[$i])) {
            if (trim($_POST["ans" . $i]) === trim($post_answers[$i])) {
                $score++;
            }
        }
    }

    $insert_sql = "INSERT INTO score (score, mail, quizid, totalscore) VALUES ('$score', '$dbmail', '$qid', '$count')";
    $insert_res = mysqli_query($conn, $insert_sql);

    if ($insert_res) {
        echo "<script>
            alert('Quiz Completed! You scored $score out of $count.');
            window.location.replace('student_score.php');
        </script>";
        exit();
    } else {
        $alert = ["type" => "danger", "msg" => "Error recording your score: " . mysqli_error($conn)];
    }
}
?>

<!-- Hero Banner -->
<section class="stud-hero">
    <div class="stud-container">
        <div class="stud-hero-content">
            <div>
                <span class="stud-badge info" style="margin-bottom: 8px;">Quiz #<?= $qid ?></span>
                <h1 class="stud-hero-title"><?= htmlspecialchars(ucfirst($quiz_data['quizname'])) ?></h1>
                <p class="stud-hero-subtitle">Total Questions: <?= $count ?> &bull; Select the correct answer for each question and submit</p>
            </div>
            <div>
                <a href="homestud.php" class="stud-btn stud-btn-outline" style="color: #ffffff; border-color: rgba(255,255,255,0.4);" onclick="return confirm('Are you sure you want to exit? Your answers will not be saved.');">
                    &larr; Exit Quiz
                </a>
            </div>
        </div>
    </div>
</section>

<div class="stud-takeq-container">
    <?php if ($alert): ?>
        <div style="background-color: #fee2e2; color: #991b1b; padding: 14px 20px; border-radius: 8px; margin-bottom: 20px;">
            <?= $alert['msg'] ?>
        </div>
    <?php endif; ?>

    <?php if ($count > 0): ?>
        <form method="POST">
            <?php 
            $i = 1;
            while ($row = mysqli_fetch_assoc($qs_res)): 
                $options = [trim($row["op1"]), trim($row["op2"]), trim($row["op3"])];
                $correct_ans = trim($row["answer"]);

                // Ensure the correct answer is present in options without duplicates
                if (!in_array($correct_ans, $options)) {
                    $options[] = $correct_ans;
                }
                shuffle($options);
            ?>
                <div class="stud-question-card">
                    <div class="stud-question-header">
                        <span class="stud-q-number">Q<?= $i ?></span>
                        <div class="stud-q-text"><?= htmlspecialchars($row["qs"]) ?></div>
                    </div>

                    <!-- Hidden field to safely match correct answer on submit -->
                    <input type="hidden" name="correct_ans[<?= $i ?>]" value="<?= htmlspecialchars($correct_ans) ?>">

                    <div style="margin-top: 15px;">
                        <?php foreach ($options as $opt): ?>
                            <label class="stud-option-label">
                                <input type="radio" name="ans<?= $i ?>" value="<?= htmlspecialchars($opt) ?>" required>
                                <?= htmlspecialchars($opt) ?>
                            </label>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php 
                $i++;
            endwhile; 
            ?>

            <div style="text-align: center; margin: 35px 0 60px;">
                <button type="submit" name="submit" class="stud-btn stud-btn-success" style="font-size: 1.15rem; padding: 14px 40px;" onclick="return confirm('Are you sure you want to submit your answers?');">
                    &#9989; Submit Quiz Answers
                </button>
            </div>
        </form>
    <?php else: ?>
        <div class="stud-card" style="text-align: center; padding: 60px 20px;">
            <div style="font-size: 3rem; margin-bottom: 15px;">&#128269;</div>
            <h3 style="color: var(--dark); margin-bottom: 10px;">No Questions Found in this Quiz</h3>
            <p style="color: var(--text-muted); margin-bottom: 25px;">The instructor has not added questions to this quiz yet. Please check back later.</p>
            <a href="homestud.php" class="stud-btn">&larr; Return to Dashboard</a>
        </div>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/student_footer.php'; ?>