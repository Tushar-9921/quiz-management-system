    <!-- Main Sidebar Container -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
        <!-- Brand Logo -->
        <a href="homestaff.php" class="brand-link d-flex align-items-center">
            <?php if (!empty($site_logo) && file_exists(__DIR__ . '/' . $site_logo)): ?>
            <img src="<?= $site_logo ?>" alt="Quizzy Logo" class="brand-image img-circle elevation-3 ml-2"
                style="opacity: .9; max-height: 33px; width: 33px; object-fit: contain; background: white; padding: 2px;">
            <?php else: ?>
            <i class="fas fa-graduation-cap brand-image text-info ml-3" style="font-size: 1.6rem; opacity: .9;"></i>
            <?php endif; ?>
            <span class="brand-text font-weight-bold ml-2">Quiz <span
                    class="badge badge-info font-weight-normal">Admin</span></span>
        </a>

        <!-- Sidebar -->
        <div class="sidebar">
            <!-- Sidebar user panel (optional) -->
            <div class="user-panel mt-3 pb-3 mb-3 d-flex align-items-center">
                <div class="image">
                    <img src="<?= $staff_avatar ?>" class="img-circle elevation-2" alt="User Image"
                        style="width: 2.3rem; height: 2.3rem; object-fit: cover;">
                </div>
                <div class="info">
                    <a href="homestaff.php#profile-section" class="d-block font-weight-bold text-truncate"
                        style="max-width: 140px;"><?= $dbname ?></a>
                    <span class="text-xs text-muted"><i class="fas fa-circle text-success mr-1"></i> Quiz Admin</span>
                </div>
            </div>

            <!-- Sidebar Menu -->
            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                    data-accordion="false">
                    <li class="nav-header">CORE MANAGEMENT</li>

                    <li class="nav-item">
                        <a href="homestaff.php" class="nav-link <?= ($active_page === 'dashboard') ? 'active' : '' ?>">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>Dashboard</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="homestaff.php#quizzes-section"
                            class="nav-link <?= ($active_page === 'quizzes') ? 'active' : '' ?>">
                            <i class="nav-icon fas fa-layer-group"></i>
                            <p>Manage Quizzes</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="#" class="nav-link" data-toggle="modal" data-target="#addQuizModal">
                            <i class="nav-icon fas fa-plus-circle text-success"></i>
                            <p>Create New Quiz</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="homestaff.php#leaderboard-section"
                            class="nav-link <?= ($active_page === 'leaderboard') ? 'active' : '' ?>">
                            <i class="nav-icon fas fa-trophy text-warning"></i>
                            <p>Leaderboard</p>
                        </a>
                    </li>

                    <li class="nav-header">ACCOUNT & SYSTEM</li>

                    <li class="nav-item">
                        <a href="homestaff.php#profile-section"
                            class="nav-link <?= ($active_page === 'profile') ? 'active' : '' ?>">
                            <i class="nav-icon fas fa-id-card"></i>
                            <p>Staff Profile</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="logout.php" onclick="return confirm('Are you sure you want to sign out?');"
                            class="nav-link text-danger">
                            <i class="nav-icon fas fa-sign-out-alt"></i>
                            <p>Sign Out</p>
                        </a>
                    </li>
                </ul>
            </nav>
            <!-- /.sidebar-menu -->
        </div>
        <!-- /.sidebar -->
    </aside>