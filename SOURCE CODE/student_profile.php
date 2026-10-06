<?php
$page_title = "My Profile";
$active_page = "profile";
require_once __DIR__ . '/student_nav.php';

// Fetch student activity summary
$stat_completed = 0;
$res = mysqli_query($conn, "SELECT COUNT(*) as cnt FROM score WHERE mail = '$username1'");
if ($res) { $stat_completed = mysqli_fetch_assoc($res)['cnt'] ?? 0; }

$stat_avg = 0;
$res = mysqli_query($conn, "SELECT AVG(score) as avg_s FROM score WHERE mail = '$username1'");
if ($res) { 
    $val = mysqli_fetch_assoc($res)['avg_s'];
    $stat_avg = ($val !== null) ? round($val, 1) : 0; 
}

$stat_best = 0;
$res = mysqli_query($conn, "SELECT MAX(score) as mscore FROM score WHERE mail = '$username1'");
if ($res) { $stat_best = mysqli_fetch_assoc($res)['mscore'] ?? 0; }
?>

<!-- Hero Banner -->
<section class="stud-hero">
    <div class="stud-container">
        <div class="stud-hero-content">
            <div>
                <h1 class="stud-hero-title">Student Profile</h1>
                <p class="stud-hero-subtitle">Personal information and academic details</p>
            </div>
            <div>
                <a href="homestud.php" class="stud-btn stud-btn-outline"
                    style="color: #ffffff; border-color: rgba(255,255,255,0.4);">
                    &larr; Back to Dashboard
                </a>
            </div>
        </div>
    </div>
</section>

<div class="stud-container">
    <div class="stud-card">
        <div class="stud-profile-grid">
            <!-- Left Column: Avatar & Summary -->
            <div class="stud-profile-avatar-box">
                <img src="images/user.png" alt="Student Avatar" class="stud-profile-avatar">
                <h3 style="font-size: 1.3rem; font-weight: 800; margin-bottom: 4px; color: var(--dark);"><?= $dbname ?>
                </h3>
                <span class="stud-badge info" style="margin-bottom: 20px;">Student</span>

                <div style="border-top: 1px solid var(--border-color); padding-top: 20px; text-align: left;">
                    <div style="margin-bottom: 12px;">
                        <span
                            style="font-size: 0.8rem; font-weight: 700; color: var(--text-muted); text-transform: uppercase;">Quizzes
                            Taken</span>
                        <div style="font-size: 1.25rem; font-weight: 800; color: var(--dark);"><?= $stat_completed ?>
                        </div>
                    </div>
                    <div style="margin-bottom: 12px;">
                        <span
                            style="font-size: 0.8rem; font-weight: 700; color: var(--text-muted); text-transform: uppercase;">Average
                            Score</span>
                        <div style="font-size: 1.25rem; font-weight: 800; color: var(--primary);"><?= $stat_avg ?></div>
                    </div>
                    <div>
                        <span
                            style="font-size: 0.8rem; font-weight: 700; color: var(--text-muted); text-transform: uppercase;">Highest
                            Score</span>
                        <div style="font-size: 1.25rem; font-weight: 800; color: var(--success);"><?= $stat_best ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Personal Information Grid -->
            <div>
                <h2 class="stud-card-title" style="margin-bottom: 20px;">Academic & Contact Details</h2>
                <div class="stud-profile-info-grid">
                    <div class="stud-info-item">
                        <div class="stud-info-label">University Seat Number (USN)</div>
                        <div class="stud-info-value"><?= $dbusn ?></div>
                    </div>

                    <div class="stud-info-item">
                        <div class="stud-info-label">Department</div>
                        <div class="stud-info-value"><?= $dbdept ?></div>
                    </div>

                    <div class="stud-info-item">
                        <div class="stud-info-label">Email Address</div>
                        <div class="stud-info-value"><?= $dbmail ?></div>
                    </div>

                    <div class="stud-info-item">
                        <div class="stud-info-label">Phone Number</div>
                        <div class="stud-info-value"><?= $dbphno ?></div>
                    </div>

                    <div class="stud-info-item">
                        <div class="stud-info-label">Gender</div>
                        <div class="stud-info-value">
                            <?= ($dbgender === 'M' || $dbgender === 'm') ? 'Male' : (($dbgender === 'F' || $dbgender === 'f') ? 'Female' : $dbgender) ?>
                        </div>
                    </div>

                    <div class="stud-info-item">
                        <div class="stud-info-label">Date of Birth</div>
                        <div class="stud-info-value"><?= $dbdob ?></div>
                    </div>
                </div>

                <div style="margin-top: 30px; display: flex; gap: 12px; flex-wrap: wrap;">
                    <a href="student_score.php" class="stud-btn">View My Quiz Results &rarr;</a>
                    <a href="homestud.php" class="stud-btn stud-btn-outline">Browse Quizzes</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/student_footer.php'; ?>