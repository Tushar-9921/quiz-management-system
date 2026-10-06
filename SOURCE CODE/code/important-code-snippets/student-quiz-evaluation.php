<?php
// Automated Scoring & Grade Insertion (takeq.php)
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
        header("Location: student_score.php");
        exit();
    }
}
?>