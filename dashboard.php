<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CODEX | Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="dashboard.css">
</head>
<body>
    <aside class="db-sidebar">
        <div class="db-logo-section">
            <h1>CODE<span>X</span></h1>
            <p>SME PORTAL</p>
        </div>
        
        <nav class="db-nav-list">
            <div class="db-nav-item active">
                <a href="dashboard.php"><i class="fa-solid fa-table-cells-large"></i> Dashboard</a>
            </div>
            <div class="db-nav-item">
                <a href="learner_progress.php"><i class="fa-solid fa-user-group"></i> Learner progress</a>
            </div>
            <div class="db-section-header">Content</div>
            <div class="db-nav-item">
                <a href="course_management.php"><i class="fa-solid fa-bars-staggered"></i> Course management</a>
            </div>
            <div class="db-nav-item">
                <a href="content_management.php"><i class="fa-solid fa-bookmark"></i> Content management</a>
            </div>
            <div class="db-section-header">System</div>
            <div class="db-nav-item">
                <a href="#"><i class="fa-solid fa-circle-dot"></i> Admin</a>
            </div>
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
        <header class="db-header">
            <h2>Dashboard</h2>
            <p>Welcome back, Ma'am Joms. Here's your platform overview.</p>
        </header>

        <section class="db-stats-grid">
            <div class="db-card blue">
                <span class="label">Total Learners</span>
                <span class="value">124</span>
                <p class="subtext">+8 this week</p>
            </div>
            <div class="db-card orange">
                <span class="label">Avg. Completion</span>
                <span class="value">68%</span>
                <p class="subtext">across all modules</p>
            </div>
            <div class="db-card sky">
                <span class="label">Avg. Passing Rate</span>
                <span class="value">74%</span>
                <p class="subtext">quizzes & exercises</p>
            </div>
            <div class="db-card dark">
                <span class="label">Difficult Module</span>
                <span class="value" style="font-size: 1.1rem;">OOP — Inheritance</span>
                <p class="subtext">42% fail rate</p>
            </div>
        </section>

        <section class="db-charts-container">
            <div class="db-chart-box">
                <h3>Completion by difficulty level</h3>
                <div class="canvas-wrapper"><canvas id="barChart"></canvas></div>
            </div>
            <div class="db-chart-box">
                <h3>Learner classification breakdown</h3>
                <div class="canvas-wrapper"><canvas id="pieChart"></canvas></div>
            </div>
        </section>
    </main>

    <script>
        const barCtx = document.getElementById('barChart').getContext('2d');
        new Chart(barCtx, {
            type: 'bar',
            data: {
                labels: ['Bgnr', 'Int', 'Adv'],
                datasets: [
                    { label: 'Beginner', data: [65, 0, 0], backgroundColor: '#3B82F6', borderRadius: 6 },
                    { label: 'Intermediate', data: [0, 45, 0], backgroundColor: '#F59E0B', borderRadius: 6 },
                    { label: 'Advanced', data: [0, 0, 30], backgroundColor: '#0F172A', borderRadius: 6 }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { position: 'right', labels: { usePointStyle: true, pointStyle: 'circle', padding: 20 } } },
                scales: { x: { grid: { display: false } }, y: { beginAtZero: true, grid: { color: '#f1f5f9' } } }
            }
        });

        const pieCtx = document.getElementById('pieChart').getContext('2d');
        new Chart(pieCtx, {
            type: 'pie',
            data: {
                labels: ['Beginner — 60%', 'Intermediate — 20%', 'Advanced — 20%'],
                datasets: [{ data: [60, 20, 20], backgroundColor: ['#3B82F6', '#F59E0B', '#0F172A'], borderWidth: 0 }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { position: 'right', labels: { usePointStyle: true, pointStyle: 'circle', padding: 25 } } }
            }
        });
    </script>
</body>
</html>