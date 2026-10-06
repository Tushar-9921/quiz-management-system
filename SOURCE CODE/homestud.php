<?php
$page_title = "Dashboard";
$active_page = "dashboard";
require_once __DIR__ . '/student_nav.php';

// Quick statistics for student
$stat_total_quizzes = 0;
$res = mysqli_query($conn, "SELECT COUNT(*) as cnt FROM quiz");
if ($res) { $stat_total_quizzes = mysqli_fetch_assoc($res)['cnt'] ?? 0; }

$stat_my_completed = 0;
$res = mysqli_query($conn, "SELECT COUNT(DISTINCT quizid) as cnt FROM score WHERE mail = '$username1'");
if ($res) { $stat_my_completed = mysqli_fetch_assoc($res)['cnt'] ?? 0; }

$stat_top_score = 0;
$res = mysqli_query($conn, "SELECT MAX(score) as mscore FROM score WHERE mail = '$username1'");
if ($res) { $stat_top_score = mysqli_fetch_assoc($res)['mscore'] ?? 0; }

// Fetch Available Quizzes with question count and completion status
$quizzes_sql = "SELECT q.quizid, q.quizname, q.date_created, q.mail,
                (SELECT COUNT(*) FROM questions qs WHERE qs.quizid = q.quizid) as q_count,
                (SELECT score FROM score sc WHERE sc.quizid = q.quizid AND sc.mail = '$username1' ORDER BY sc.slno DESC LIMIT 1) as my_score,
                (SELECT totalscore FROM score sc WHERE sc.quizid = q.quizid AND sc.mail = '$username1' ORDER BY sc.slno DESC LIMIT 1) as my_total
                FROM quiz q 
                ORDER BY q.quizid DESC";
$quizzes_res = mysqli_query($conn, $quizzes_sql);
?>

<!-- Hero Banner -->
<section class="stud-hero">
    <div class="stud-container">
        <div class="stud-hero-content">
            <div>
                <h1 class="stud-hero-title">Welcome back, <?= $dbname ?>!</h1>
                <p class="stud-hero-subtitle">USN: <?= $dbusn ?> &bull; Department: <?= $dbdept ?> &bull; <?= $dbmail ?></p>
            </div>
            <div>
                <a href="student_score.php" class="stud-btn stud-btn-outline" style="color: #ffffff; border-color: rgba(255,255,255,0.4);">
                    View My Scoreboard &rarr;
                </a>
            </div>
        </div>
    </div>
</section>

<div class="stud-container">
    <!-- Stats Row -->
    <div class="stud-stats-grid">
        <div class="stud-stat-card">
            <div class="stud-stat-icon blue">&#128214;</div>
            <div>
                <div class="stud-stat-number"><?= $stat_total_quizzes ?></div>
                <div class="stud-stat-label">Available Quizzes</div>
            </div>
        </div>

        <div class="stud-stat-card">
            <div class="stud-stat-icon green">&#9989;</div>
            <div>
                <div class="stud-stat-number"><?= $stat_my_completed ?></div>
                <div class="stud-stat-label">Quizzes Completed</div>
            </div>
        </div>

        <div class="stud-stat-card">
            <div class="stud-stat-icon orange">&#127942;</div>
            <div>
                <div class="stud-stat-number"><?= $stat_top_score ?></div>
                <div class="stud-stat-label">My Highest Score</div>
            </div>
        </div>
    </div>

    <!-- Available Quizzes Section -->
    <div class="stud-card">
        <div class="stud-card-header">
            <h2 class="stud-card-title">Available Quizzes to Take</h2>
        </div>

        <div class="stud-quiz-grid">
            <?php if ($quizzes_res && mysqli_num_rows($quizzes_res) > 0): ?>
                <?php while ($row = mysqli_fetch_assoc($quizzes_res)): 
                    $has_attempted = ($row['my_score'] !== null);
                ?>
                    <div class="stud-quiz-card">
                        <div>
                            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 8px;">
                                <span class="stud-badge info">Quiz #<?= $row['quizid'] ?></span>
                                <?php if ($has_attempted): ?>
                                    <span class="stud-badge success">Completed: <?= $row['my_score'] ?>/<?= $row['my_total'] ?></span>
                                <?php else: ?>
                                    <span class="stud-badge gold">Ready</span>
                                <?php endif; ?>
                            </div>
                            <h3 class="stud-quiz-title"><?= htmlspecialchars($row['quizname']) ?></h3>
                            <div class="stud-quiz-meta">
                                <div><strong>Questions:</strong> <?= $row['q_count'] ?> configured</div>
                                <div><strong>Created by:</strong> <?= htmlspecialchars($row['mail']) ?></div>
                                <div><strong>Date:</strong> <?= $row['date_created'] ?></div>
                            </div>
                        </div>
                        <div>
                            <a href="takeq.php?qid=<?= $row['quizid'] ?>" class="stud-btn <?= ($has_attempted) ? 'stud-btn-outline' : '' ?>" style="width: 100%;">
                                <?= ($has_attempted) ? 'Retake Quiz' : 'Start Quiz &rarr;' ?>
                            </a>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div style="text-align: center; padding: 40px 20px; grid-column: 1 / -1; color: var(--text-muted);">
                    <p style="font-size: 1.1rem; margin-bottom: 8px;">No quizzes available right now.</p>
                    <p style="font-size: 0.9rem;">Please check back later once staff members publish new quizzes.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/student_footer.php'; ?>