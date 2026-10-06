<?php
$page_title = "My Scores";
$active_page = "score";
require_once __DIR__ . '/student_nav.php';

// Fetch all scores for the logged in student
$score_sql = "SELECT s.slno, s.score, s.totalscore, s.remark, q.quizid, q.quizname 
              FROM score s
              JOIN quiz q ON s.quizid = q.quizid 
              WHERE s.mail = '$username1' 
              ORDER BY s.slno DESC";
$score_res = mysqli_query($conn, $score_sql);
$total_attempts = ($score_res) ? mysqli_num_rows($score_res) : 0;
?>

<!-- Hero Banner -->
<section class="stud-hero">
    <div class="stud-container">
        <div class="stud-hero-content">
            <div>
                <h1 class="stud-hero-title">My Scoreboard</h1>
                <p class="stud-hero-subtitle">Review your past performance and test results</p>
            </div>
            <div>
                <a href="homestud.php" class="stud-btn stud-btn-outline" style="color: #ffffff; border-color: rgba(255,255,255,0.4);">
                    &larr; Back to Available Quizzes
                </a>
            </div>
        </div>
    </div>
</section>

<div class="stud-container">
    <div class="stud-card">
        <div class="stud-card-header">
            <h2 class="stud-card-title">Completed Quizzes (<?= $total_attempts ?> Attempts)</h2>
        </div>

        <?php if ($total_attempts > 0): ?>
            <div class="stud-table-wrap">
                <table class="stud-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Quiz Title</th>
                            <th>Score Obtained</th>
                            <th>Total Questions</th>
                            <th>Percentage</th>
                            <th>Remarks</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $i = 1;
                        while ($row = mysqli_fetch_assoc($score_res)): 
                            $score = intval($row['score']);
                            $total = intval($row['totalscore']);
                            $percent = ($total > 0) ? round(($score / $total) * 100) : 0;
                        ?>
                            <tr>
                                <td><?= $i ?></td>
                                <td>
                                    <strong><?= htmlspecialchars(ucfirst($row['quizname'])) ?></strong>
                                    <span class="stud-badge info" style="margin-left: 6px;">ID #<?= $row['quizid'] ?></span>
                                </td>
                                <td><strong style="color: var(--primary); font-size: 1.1rem;"><?= $score ?></strong></td>
                                <td><?= $total ?></td>
                                <td>
                                    <strong><?= $percent ?>%</strong>
                                </td>
                                <td>
                                    <?php if (strtolower($row['remark']) === 'good'): ?>
                                        <span class="stud-badge success">&#10004; Good</span>
                                    <?php else: ?>
                                        <span class="stud-badge warning">&#9888; <?= htmlspecialchars($row['remark'] ?? 'Needs Work') ?></span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a href="takeq.php?qid=<?= $row['quizid'] ?>" class="stud-btn stud-btn-outline" style="padding: 6px 14px; font-size: 0.85rem;">
                                        Retake Quiz
                                    </a>
                                </td>
                            </tr>
                        <?php 
                            $i++;
                        endwhile; 
                        ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div style="text-align: center; padding: 50px 20px; color: var(--text-muted);">
                <div style="font-size: 3rem; margin-bottom: 12px;">&#128221;</div>
                <h3 style="color: var(--dark); margin-bottom: 8px;">No Quiz Attempts Recorded Yet</h3>
                <p style="margin-bottom: 20px;">You haven't completed any quizzes yet. Go to the dashboard to take your first quiz!</p>
                <a href="homestud.php" class="stud-btn">Browse Quizzes &rarr;</a>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once __DIR__ . '/student_footer.php'; ?>
