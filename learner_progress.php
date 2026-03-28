<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CODEX | Learner Progress</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="dashboard.css">
</head>
<body>
    <aside class="db-sidebar">
        <div class="db-logo-section">
            <h1>CODE<span>X</span></h1>
            <p>SME PORTAL</p>
        </div>
        
        <nav class="db-nav-list">
            <div class="db-nav-item">
                <a href="dashboard.php"><i class="fa-solid fa-table-cells-large"></i> Dashboard</a>
            </div>
            <div class="db-nav-item active">
                <a href="learner_progress.php"><i class="fa-solid fa-user-group"></i> Learner progress</a>
            </div>
            <div class="db-section-header">Content</div>
            <div class="db-nav-item"><a href="course_management.php"><i class="fa-solid fa-bars-staggered"></i> Course management</a></div>
            <div class="db-nav-item"><a href="content_management.php"><i class="fa-solid fa-bookmark"></i> Content management</a></div>
            <div class="db-section-header">System</div>
            <div class="db-nav-item"><a href="#"><i class="fa-solid fa-circle-dot"></i> Admin</a></div>
        </nav>

        <div class="db-user-footer">
            <div class="db-avatar">MJ</div>
            <div class="db-user-info">
                <p>Ma'am Joms</p>
                <span>Subject Matter Expert</span>
            </div>
        </div>
    </aside>

    <main class="db-main">
    <header class="lp-header-fixed">
        <div class="lp-title">
            <h2>Learner progress</h2>
            <p>View and monitor individual learner activity</p>
        </div>
        <div class="lp-search-wrapper">
            <i class="fa-solid fa-magnifying-glass"></i>
            <input type="text" placeholder="Search learners...">
        </div>
    </header>

    <div class="lp-card-compact">
        <table class="lp-main-table">
            <thead>
                <tr>
                    <th>LEARNER</th>
                    <th>CLASSIFICATION</th>
                    <th>LESSONS DONE</th>
                    <th>AVG. SCORE</th>
                    <th>STATUS</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <div class="lp-user">
                            <strong>Ana Reyes</strong>
                            <span>ana@email.com</span>
                        </div>
                    </td>
                    <td><span class="lp-tag blue">Beginner</span></td>
                    <td>
                        <div class="lp-progress-wrapper">
                            <div class="lp-progress-bar"><div class="fill blue" style="width: 80%;"></div></div>
                            <span class="lp-count">8/10</span>
                        </div>
                    </td>
                    <td><strong>82%</strong></td>
                    <td><span class="lp-status active">Active</span></td>
                </tr>
                </tbody>
        </table>
    </div>

    <div class="lp-card-compact">
        <h3 class="detail-title">Learner detail — Ana Reyes</h3>
        <div class="lp-detail-grid">
            <div class="lp-profile-box">
                <div class="lp-profile-main">
                    <div class="lp-avatar-circle">AR</div>
                    <h4>Ana Reyes</h4>
                    <p>Beginner</p>
                </div>
                <div class="lp-profile-actions">
                    <button class="btn-action">Enable</button>
                    <button class="btn-action disabled">Disable</button>
                </div>
                <div class="lp-profile-stats">
                    <div class="p-row">Lessons done <span>8 / 10</span></div>
                    <div class="p-row">Quizzes passed <span>18 / 22</span></div>
                    <div class="p-row text-blue">Avg. score <span>82%</span></div>
                    <div class="p-row">Last active <span>Today</span></div>
                </div>
            </div>

            <div class="lp-scores-container">
                <table class="lp-scores-table">
                    <thead>
                        <tr>
                            <th>LESSON</th>
                            <th>QUIZ</th>
                            <th>PROGRAM TRACING</th>
                            <th>SYNTAX ERROR</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Variables & Data Types</td>
                            <td><span class="pill green">90%</span></td>
                            <td><span class="pill green">85%</span></td>
                            <td><span class="pill green">90%</span></td>
                        </tr>
                        <tr>
                            <td>Control Flow</td>
                            <td><span class="pill green">70%</span></td>
                            <td><span class="pill orange">65%</span></td>
                            <td><span class="pill green">80%</span></td>
                        </tr>
                        <tr>
                            <td>Methods & Functions</td>
                            <td><span class="pill orange">60%</span></td>
                            <td><span class="pill red">45%</span></td>
                            <td><span class="pill orange">70%</span></td>
                        </tr>
                        <tr>
                            <td>OOP Basics</td>
                            <td><span class="pill-empty">—</span></td>
                            <td><span class="pill-empty">—</span></td>
                            <td><span class="pill-empty">—</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>
</body>
</html>