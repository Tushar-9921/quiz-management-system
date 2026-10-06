<?php
$page_title = "Leaderboard";
$active_page = "leaderboard";
require_once __DIR__ . '/student_nav.php';

// Fetch Leaderboard via procedure or fallback query
$lead_rows = [];
$lead_sql = "CALL leaderboard();";
$lead_res = @mysqli_query($conn, $lead_sql);

if ($lead_res) {
    while ($row = mysqli_fetch_assoc($lead_res)) {
        $lead_rows[] = $row;
    }
    mysqli_free_result($lead_res);
    while (mysqli_more_results($conn) && mysqli_next_result($conn)) {
        $extra = mysqli_use_result($conn);
        if ($extra instanceof mysqli_result) {
            mysqli_free_result($extra);
        }
    }
} else {
    // Fallback if procedure is unavailable
    $lead_sql2 = "SELECT q.quizname, s.score, s.totalscore, st.name, s.mail 
                  FROM score s, student st, quiz q 
                  WHERE s.mail = st.mail AND q.quizid = s.quizid 
                  ORDER BY s.score DESC LIMIT 100";
    $lead_res2 = mysqli_query($conn, $lead_sql2);
    if ($lead_res2) {
        while ($row = mysqli_fetch_assoc($lead_res2)) {
            $lead_rows[] = $row;
        }
    }
}
?>

<!-- Hero Banner -->
<section class="stud-hero">
    <div class="stud-container">
        <div class="stud-hero-content">
            <div>
                <h1 class="stud-hero-title">Student Leaderboard</h1>
                <p class="stud-hero-subtitle">Top performers and high scores across all quizzes</p>
            </div>
            <div>
                <a href="homestud.php" class="stud-btn stud-btn-outline" style="color: #ffffff; border-color: rgba(255,255,255,0.4);">
                    &larr; Back to Dashboard
                </a>
            </div>
        </div>
    </div>
</section>

<div class="stud-container">
    <div class="stud-card">
        <div class="stud-card-header">
            <h2 class="stud-card-title">Top Rankings</h2>
        </div>

        <?php if (!empty($lead_rows)): ?>
            <div class="stud-table-wrap">
                <table class="stud-table">
                    <thead>
                        <tr>
                            <th style="width: 90px;">Rank</th>
                            <th>Student Name</th>
                            <th>Quiz Title</th>
                            <th>Score / Total</th>
                            <th>Performance</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $rank = 1;
                        foreach ($lead_rows as $row): 
                            $score = intval($row['score']);
                            $total = intval($row['totalscore']);
                            $percent = ($total > 0) ? round(($score / $total) * 100) : 0;
                            $is_me = ($row['mail'] === $username1);
                        ?>
                            <tr style="<?= ($is_me) ? 'background-color: #eff6ff;' : '' ?>">
                                <td>
                                    <?php if ($rank === 1): ?>
                                        <span class="stud-badge gold">&#127942; 1st</span>
                                    <?php elseif ($rank === 2): ?>
                                        <span class="stud-badge silver">&#129352; 2nd</span>
                                    <?php elseif ($rank === 3): ?>
                                        <span class="stud-badge bronze">&#129353; 3rd</span>
                                    <?php else: ?>
                                        <span class="stud-badge" style="background-color: #f1f5f9; color: #475569;">#<?= $rank ?></span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <strong><?= htmlspecialchars($row['name']) ?></strong>
                                    <?php if ($is_me): ?>
                                        <span class="stud-badge info" style="margin-left: 6px;">You</span>
                                    <?php endif; ?>
                                </td>
                                <td><?= htmlspecialchars(ucfirst($row['quizname'])) ?></td>
                                <td>
                                    <strong style="color: var(--primary);"><?= $score ?></strong> / <?= $total ?>
                                </td>
                                <td>
                                    <div style="display: flex; align-items: center; gap: 10px;">
                                        <div style="background: #e2e8f0; border-radius: 6px; height: 8px; width: 120px; overflow: hidden;">
                                            <div style="background: <?= ($percent >= 70) ? 'var(--success)' : (($percent >= 40) ? 'var(--warning)' : 'var(--danger)') ?>; width: <?= $percent ?>%; height: 100%;"></div>
                                        </div>
                                        <span style="font-size: 0.85rem; font-weight: 700; color: var(--text-muted);"><?= $percent ?>%</span>
                                    </div>
                                </td>
                            </tr>
                        <?php 
                            $rank++;
                        endforeach; 
                        ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div style="text-align: center; padding: 50px 20px; color: var(--text-muted);">
                <div style="font-size: 3rem; margin-bottom: 12px;">&#127942;</div>
                <h3 style="color: var(--dark); margin-bottom: 8px;">No Leaderboard Data Yet</h3>
                <p>Be the first to complete a quiz and claim the #1 spot!</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once __DIR__ . '/student_footer.php'; ?>
