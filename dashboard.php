<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CODEX | Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="dashboard.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">

    <script src="https://www.gstatic.com/firebasejs/10.12.0/firebase-app-compat.js"></script>
    <script src="https://www.gstatic.com/firebasejs/10.12.0/firebase-auth-compat.js"></script>
    <script src="https://www.gstatic.com/firebasejs/10.12.0/firebase-firestore-compat.js"></script>
    <script src="https://www.gstatic.com/firebasejs/10.12.0/firebase-database-compat.js"></script>
    <script>
        firebase.initializeApp({
            apiKey: "AIzaSyBmFwQe51Sfkhr36aXXlw4NYv7jag-8OcY",
            authDomain: "codex-f1355.firebaseapp.com",
            databaseURL: "https://codex-f1355-default-rtdb.firebaseio.com",
            projectId: "codex-f1355",
            storageBucket: "codex-f1355.firebasestorage.app",
            messagingSenderId: "273276166035",
            appId: "1:273276166035:web:e1f895eeaa03200a975266"
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>

    
    <main class="db-main">
        <header class="db-header">
            <h2>Dashboard</h2>
            <p>Welcome back, Ma'am Joms. Here's your platform overview.</p>
        </header>
        <section class="db-stats-grid">
            <div class="db-card blue">
                <span class="label">Total Learners</span>
                <span class="value">—</span>
            </div>
            <div class="db-card orange">
                <span class="label">Avg. Completion</span>
                <span class="value">—</span>
                <p class="subtext">across all modules</p>
            </div>
            <div class="db-card sky">
                <span class="label">Avg. Passing Rate</span>
                <span class="value">—</span>
                <p class="subtext">quizzes & exercises</p>
            </div>
            <div class="db-card dark">
                <span class="label">Difficult Module</span>
                <span class="value" style="font-size: 1.1rem;">—</span>
                <p class="subtext">—</p>
            </div>
        </section>
        <section class="db-charts-container">
            <div class="db-chart-box">
                <h3>Completion by difficulty level</h3>
                <div class="canvas-wrapper"><canvas id="barChart" height="260"></canvas></div>
            </div>
            <div class="db-chart-box">
                <h3>Learner classification breakdown</h3>
                <div class="canvas-wrapper"><canvas id="pieChart" height="260"></canvas></div>
            </div>
        </section>
    </main>

<script>
let barChartInstance = null;
let pieChartInstance = null;

const CACHE_KEY = 'codex_dashboard_data';
const CACHE_TIME_KEY = "codex_dashboard_time";
const CACHE_EXPIRY = 10 * 60 * 1000; // 10 minutes
function saveToCache(data) {
    try { localStorage.setItem(CACHE_KEY, JSON.stringify(data)); } catch(e) {}
}

function loadFromCache() {
    const raw = localStorage.getItem(CACHE_KEY);
    const time = localStorage.getItem(CACHE_TIME_KEY);

    if (!raw || !time) return null;
    if (Date.now() - parseInt(time) > CACHE_EXPIRY) return null;

    return JSON.parse(raw);
}

function showSkeletons() {
    document.querySelector('.db-card.blue .value').textContent     = '—';
    document.querySelector('.db-card.orange .value').textContent   = '—';
    document.querySelector('.db-card.sky .value').textContent      = '—';
    document.querySelector('.db-card.dark .value').textContent     = '—';
    document.querySelector('.db-card.dark .subtext').textContent   = '—';
}

function destroyCharts() {
    if (barChartInstance) { barChartInstance.destroy(); barChartInstance = null; }
    if (pieChartInstance) { pieChartInstance.destroy(); pieChartInstance = null; }
}

async function loadDashboard() {
    const cached = loadFromCache();
    if (cached) {
        renderDashboard(cached[0], cached[1], cached[2], cached[3], cached[4]);
        return;
    }

    showSkeletons();
    const db = firebase.database();
    const snaps = await Promise.all([
        db.ref('Users').once('value'),
        db.ref('quizResults').once('value'),
        db.ref('unlockedLessons').once('value'),
        db.ref('Lessons').once('value'),
        db.ref('exerciseResults').once('value')
    ]);
    const data = snaps.map(s => s.val() || {});
    saveToCache(data);
    renderDashboard(data[0], data[1], data[2], data[3], data[4]);
}

if (typeof window.navigate === 'function') {
    // Running inside SPA shell — just load
    loadDashboard();
} else {
    // Accessed directly — check auth ourselves
    firebase.auth().onAuthStateChanged(function(user) {
        if (!user) { window.location.href = 'login.php'; return; }
        loadDashboard();
    });
}

function renderDashboard(Users, quizResults, unlocked, Lessons, exerciseResults) {
    Users           = Users           || {};
    quizResults     = quizResults     || {};
    unlocked        = unlocked        || {};
    Lessons         = Lessons         || {};
    exerciseResults = exerciseResults || {};

    destroyCharts();

    const allUserIds   = Object.keys(Users);
    const totalLessons = Object.keys(Lessons).length;

    document.querySelector('.db-card.blue .value').textContent = allUserIds.length;

    const compVals = allUserIds.map(uid => {
        const userUnlocked  = unlocked[uid] || {};
        const unlockedCount = Object.values(userUnlocked).filter(v => typeof v === 'object').length;
        return (1 + unlockedCount) / totalLessons;
    });
    const avgComp = compVals.length
        ? Math.round(compVals.reduce((a, b) => a + b, 0) / compVals.length * 100)
        : 0;
    document.querySelector('.db-card.orange .value').textContent = avgComp + '%';

    const userPassRates = [];
    allUserIds.forEach(uid => {
        const quizUser      = quizResults[uid]     || {};
        const exerciseUser  = exerciseResults[uid] || {};
        const attemptedLids = new Set([...Object.keys(quizUser), ...Object.keys(exerciseUser)]);
        if (!attemptedLids.size) return;
        let passedCount = 0;
        attemptedLids.forEach(lid => {
            const qData  = quizUser[lid];
            const eData  = exerciseUser[lid];
            const quizOk = !qData || qData.passed === 'Passed';
            const exOk   = !eData || eData.correctCount === eData.totalExercises;
            if (quizOk && exOk) passedCount++;
        });
        userPassRates.push(passedCount / attemptedLids.size);
    });
    const avgPass = userPassRates.length
        ? Math.round(userPassRates.reduce((a, b) => a + b, 0) / userPassRates.length * 100)
        : 0;
    document.querySelector('.db-card.sky .value').textContent = avgPass + '%';

    const moduleAttempts = {}, moduleFails = {};
    allUserIds.forEach(uid => {
        const quizUser      = quizResults[uid]     || {};
        const exerciseUser  = exerciseResults[uid] || {};
        const attemptedLids = new Set([...Object.keys(quizUser), ...Object.keys(exerciseUser)]);
        attemptedLids.forEach(lid => {
            moduleAttempts[lid] = (moduleAttempts[lid] || 0) + 1;
            const qData  = quizUser[lid];
            const eData  = exerciseUser[lid];
            const quizOk = !qData || qData.passed === 'Passed';
            const exOk   = !eData || eData.correctCount === eData.totalExercises;
            if (!quizOk || !exOk) moduleFails[lid] = (moduleFails[lid] || 0) + 1;
        });
    });
    const hardestLid = Object.keys(moduleAttempts).sort((a, b) => {
        const rateA = (moduleFails[a] || 0) / moduleAttempts[a];
        const rateB = (moduleFails[b] || 0) / moduleAttempts[b];
        return rateB - rateA;
    })[0];
    if (hardestLid) {
        const rawTitle = (Lessons[hardestLid]?.main_title || hardestLid).replace(/<[^>]+>/g, '').trim();
        const failRate = Math.round((moduleFails[hardestLid] || 0) / moduleAttempts[hardestLid] * 100);
        document.querySelector('.db-card.dark .value').textContent   = rawTitle;
        document.querySelector('.db-card.dark .subtext').textContent = failRate + '% fail rate';
    }

    const diffLessons = {};
    Object.entries(Lessons).forEach(([lid, l]) => {
        const d = l.difficulty;
        if (d) { diffLessons[d] = diffLessons[d] || []; diffLessons[d].push(lid); }
    });
    const diffAvg = (difficulty) => {
        const ids = diffLessons[difficulty] || [];
        if (!ids.length) return 0;
        const vals = allUserIds.map(uid => {
            const userUnlocked   = unlocked[uid] || {};
            const accessedInTier = ids.filter(id => id === 'L1' || typeof userUnlocked[id] === 'object').length;
            return accessedInTier / ids.length;
        });
        return vals.length ? Math.round(vals.reduce((a, b) => a + b, 0) / vals.length * 100) : 0;
    };

    const barCtx = document.getElementById('barChart').getContext('2d');
    barChartInstance = new Chart(barCtx, {
        type: 'bar',
        data: {
            labels: ['Bgnr', 'Int', 'Adv'],
            datasets: [
                { label: 'Beginner',     data: [diffAvg('Beginner'), 0, 0],     backgroundColor: '#E3AF64', borderRadius: 6 },
                { label: 'Intermediate', data: [0, diffAvg('Intermediate'), 0], backgroundColor: '#4398F2', borderRadius: 6 },
                { label: 'Advanced',     data: [0, 0, diffAvg('Advanced')],     backgroundColor: '#A666F4', borderRadius: 6 }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { position: 'right', labels: { usePointStyle: true, pointStyle: 'circle', padding: 20 } } },
            scales: {
                x: { grid: { display: false } },
                y: { beginAtZero: true, max: 100, grid: { color: '#f1f5f9' }, ticks: { callback: v => v + '%' } }
            }
        }
    });

    const counts = { Beginner: 0, Intermediate: 0, Advanced: 0 };
    Object.values(Users).forEach(u => { const c = u.classification; if (counts[c] !== undefined) counts[c]++; });
    const classTotal = Object.values(counts).reduce((a, b) => a + b, 0);
    const pct = n => classTotal ? Math.round(n / classTotal * 100) : 0;

    const pieCtx = document.getElementById('pieChart').getContext('2d');
    pieChartInstance = new Chart(pieCtx, {
        type: 'pie',
        data: {
            labels: [
                `Beginner — ${pct(counts.Beginner)}%`,
                `Intermediate — ${pct(counts.Intermediate)}%`,
                `Advanced — ${pct(counts.Advanced)}%`
            ],
            datasets: [{
                data: [counts.Beginner, counts.Intermediate, counts.Advanced],
                backgroundColor: ['#E3AF64', '#4398F2', '#A666F4'],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { position: 'right', labels: { usePointStyle: true, pointStyle: 'circle', padding: 25 } } }
        }
    });
}
</script>
</body>
</html>