<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>CODEX | Learner Progress</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"/>
    <link rel="stylesheet" href="dashboard.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">

    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            display: flex;
            margin: 0;
            background-color: #fff;
            font-family: 'Roboto', sans-serif;
        }

        /* ── Page header ── */
        .lp-header {
            margin-bottom: 28px;
            display: flex;
            justify-content: flex-start;
            align-items: flex-start;
            text-align: left;
            flex-direction: column;
            gap: 4px;
        }
        .lp-header h2 {
            font-size: 32px;
            font-weight: 700;
            color: black;
            line-height: 1.2;
        }
        .lp-header p {
            font-size: 20px;
            color: black;
            line-height: 1.4;
        }

        /* Controls row */
        .lp-controls {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 24px;
            flex-wrap: wrap;
            gap: 12px;
        }

        .sort-bar {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .sort-bar .sort-label {
            font-size: 14px;
            color: #374151;
            font-weight: 500;
        }
        .sort-btn-group {
            display: flex;
            background: #fff;
            border: 1.5px solid #e5e7eb;
            border-radius: 999px;
            overflow: hidden;
            padding: 3px;
            gap: 2px;
        }
        .sort-btn {
            border: none;
            background: transparent;
            padding: 7px 18px;
            border-radius: 999px;
            font-size: 13.5px;
            font-weight: 500;
            color: #374151;
            cursor: pointer;
            transition: background .18s, color .18s;
        }
        .sort-btn.active {
            background: #111827;
            color: #fff;
        }
        .sort-btn:not(.active):hover { background: #f3f4f6; }

        .lp-search {
            display: flex;
            align-items: center;
            background: #fff;
            border: 1.5px solid #e5e7eb;
            border-radius: 10px;
            padding: 9px 14px;
            gap: 8px;
            min-width: 230px;
        }
        .lp-search i { color: #9ca3af; font-size: 14px; }
        .lp-search input {
            border: none;
            outline: none;
            font-size: 14px;
            color: #374151;
            background: transparent;
            width: 100%;
        }

        /* Table card */
        .lp-card {
            background: #fff;
            border-radius: 14px;
            border: 1.5px solid #e5e7eb;
            overflow: hidden;
        }

        table.lp-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        .lp-table col.col-learner   { width: 36%; }
        .lp-table col.col-class     { width: 20%; }
        .lp-table col.col-lessons   { width: 26%; }
        .lp-table col.col-score     { width: 18%; }

        .lp-table thead tr {
            background: #f9fafb;
            border-bottom: 1.5px solid #e5e7eb;
        }
        .lp-table th {
            padding: 13px 20px;
            font-size: 11.5px;
            font-weight: 700;
            letter-spacing: .06em;
            color: #6b7280;
            text-align: left;
            white-space: nowrap;
        }
        .lp-table tbody tr {
            border-bottom: 1px solid #f3f4f6;
            cursor: pointer;
            transition: background .15s;
        }
        .lp-table tbody tr:last-child { border-bottom: none; }
        .lp-table tbody tr:hover { background: #f8faff; }
        .lp-table td {
            padding: 16px 20px;
            font-size: 14px;
            color: #374151;
            vertical-align: middle;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        /* Learner cell */
        .lp-user strong {
            display: block;
            font-size: 14px;
            color: #111827;
            font-weight: 600;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        .lp-user span {
            font-size: 12.5px;
            color: #9ca3af;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            display: block;
        }

        /* Classification pill */
        .class-pill {
            display: inline-block;
            padding: 4px 14px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 500;
            border: 1.5px solid;
            white-space: nowrap;
        }
        .class-pill.beginner        { color: #F59E0B; border-color: #F59E0B; }
        .class-pill.intermediate    { color: #3B82F6; border-color: #3B82F6; }
        .class-pill.advanced        { color: #A855F7; border-color: #A855F7; }
        .class-pill.notclassified   { color: #6b7280; border-color: #9ca3af; }

        /* Progress bar */
        .lp-progress-wrapper {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .lp-bar-track {
            flex: 1;
            height: 6px;
            background: #e5e7eb;
            border-radius: 99px;
            min-width: 60px;
            max-width: 100px;
        }
        .lp-bar-fill {
            height: 100%;
            border-radius: 99px;
            background: #3b82f6;
            transition: width .4s ease;
        }
        .lp-bar-fill.empty { background: #d1d5db; }
        .lp-bar-count { font-size: 13px; color: #374151; white-space: nowrap; }

        /* Avg score */
        .avg-score { font-size: 14px; font-weight: 600; color: #111827; }

        /* Pagination */
        .lp-footer {
            display: flex;
            justify-content: flex-end;
            align-items: center;
            padding: 14px 20px;
            border-top: 1.5px solid #f3f4f6;
            gap: 8px;
        }
        .pag-info {
            font-size: 13px;
            color: #6b7280;
            margin-right: auto;
        }
        .pag-btn {
            width: 34px;
            height: 34px;
            border-radius: 8px;
            border: 1.5px solid #e5e7eb;
            background: #fff;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #374151;
            font-size: 14px;
            transition: background .15s;
        }
        .pag-btn:hover:not(:disabled) { background: #f3f4f6; }
        .pag-btn:disabled { opacity: .4; cursor: default; }

        /* Empty / loading state */
        .lp-empty {
            padding: 40px;
            text-align: center;
            color: #9ca3af;
            font-size: 14px;
        }

        /* ── Loading overlay ── */
        .lp-loading {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            padding: 48px 20px;
            color: #6b7280;
            font-size: 14px;
        }
        .lp-spinner {
            width: 20px;
            height: 20px;
            border: 2.5px solid #e5e7eb;
            border-top-color: #3b82f6;
            border-radius: 50%;
            animation: spin .7s linear infinite;
        }
        @keyframes spin { to { transform: rotate(360deg); } }
    </style>
</head>
<body>

<main class="db-main">

    <header class="lp-header">
        <h2>Learner Progress</h2>
        <p>View and monitor individual learner activity</p>
    </header>

    <div class="lp-controls">
        <div class="sort-bar">
            <span class="sort-label">Sort by:</span>
            <div class="sort-btn-group">
                <button class="sort-btn active" data-filter="all">All</button>
                <button class="sort-btn" data-filter="beginner">Beginner</button>
                <button class="sort-btn" data-filter="intermediate">Intermediate</button>
                <button class="sort-btn" data-filter="advanced">Advanced</button>
                <button class="sort-btn" data-filter="notclassified">Unclassified</button>
            </div>
        </div>
        <div class="lp-search">
            <i class="fa-solid fa-magnifying-glass"></i>
            <input type="text" id="searchInput" placeholder="Search learners..."/>
        </div>
    </div>

    <div class="lp-card">
        <table class="lp-table">
            <colgroup>
                <col class="col-learner"/>
                <col class="col-class"/>
                <col class="col-lessons"/>
                <col class="col-score"/>
            </colgroup>
            <thead>
                <tr>
                    <th>LEARNER</th>
                    <th>CLASSIFICATION</th>
                    <th>LESSONS DONE</th>
                    <th>AVG. SCORE</th>
                </tr>
            </thead>
            <tbody id="learnerTableBody">
                <!-- Loading state shown while Firebase data is fetched -->
                <tr>
                    <td colspan="4">
                        <div class="lp-loading">
                            <div class="lp-spinner"></div>
                            Loading learners…
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
        <div class="lp-footer">
            <span class="pag-info" id="pagInfo"></span>
            <button class="pag-btn" id="prevBtn" disabled><i class="fa-solid fa-chevron-left"></i></button>
            <button class="pag-btn" id="nextBtn" disabled><i class="fa-solid fa-chevron-right"></i></button>
        </div>
    </div>

</main><!-- /db-main -->



<script>
(function() {

const PAGE_SIZE = 10;
let currentPage  = 1;
let activeFilter = 'all';
let searchQuery  = '';
let learners     = [];

function normalizeClass(c) {
    if (!c) return 'notclassified';
    const lower = c.toLowerCase().replace(/\s+/g, '');
    const known = ['beginner', 'intermediate', 'advanced'];
    return known.includes(lower) ? lower : 'notclassified';
}

function pillLabel(c) {
    if (c === 'notclassified') return 'Unclassified';
    return c.charAt(0).toUpperCase() + c.slice(1);
}

async function loadLearners() {
    const [usersSnap, quizSnap, lessonsSnap] = await Promise.all([
        FirebaseCache.get('Users'),
        FirebaseCache.get('quizResults'),
        FirebaseCache.get('Lessons'),
    ]);

    const usersRaw   = usersSnap.val()   || [];
    const quizData   = quizSnap.val()    || {};
    const lessonsRaw = lessonsSnap.val() || {};

    const totalLessons = Object.values(lessonsRaw)
        .filter(l => l && !l.archived).length;

    const result = [];

    const usersArr = Array.isArray(usersRaw)
        ? usersRaw
        : Object.values(usersRaw);

    usersArr.forEach(user => {
        if (!user || user.usertype !== 'Learner') return;

        const uid       = String(user.userId);
        const userQuiz  = quizData[uid] || {};
        const lessonIds = Object.keys(userQuiz);
        const lessonsDone = lessonIds.length;

        let avgScore = 0;
        if (lessonsDone > 0) {
            const sum = lessonIds.reduce((acc, lid) => {
                const q = userQuiz[lid] || {};
                return acc + (q.total ? (q.score / q.total) * 100 : 0);
            }, 0);
            avgScore = Math.round(sum / lessonsDone);
        }

        result.push({
            id:             user.userId,
            name:           `${user.firstName} ${user.lastName}`,
            email:          user.email,
            classification: normalizeClass(user.classification),
            lessonsDone,
            totalLessons,
            avgScore,
        });
    });

    return result;
}

function getFiltered() {
    return learners.filter(l => {
        const matchFilter = activeFilter === 'all' || l.classification === activeFilter;
        const q = searchQuery.toLowerCase();
        const matchSearch = !q
            || l.name.toLowerCase().includes(q)
            || l.email.toLowerCase().includes(q);
        return matchFilter && matchSearch;
    });
}

function render() {
    const filtered   = getFiltered();
    const totalPages = Math.max(1, Math.ceil(filtered.length / PAGE_SIZE));
    currentPage      = Math.min(currentPage, totalPages);
    const slice      = filtered.slice(
        (currentPage - 1) * PAGE_SIZE,
        currentPage * PAGE_SIZE
    );

    const tbody = document.getElementById('learnerTableBody');

    if (slice.length === 0) {
        tbody.innerHTML = `<tr><td colspan="4"><div class="lp-empty">No learners found.</div></td></tr>`;
    } else {
        tbody.innerHTML = slice.map(l => {
            const pct       = l.totalLessons ? (l.lessonsDone / l.totalLessons * 100) : 0;
            const fillClass = pct === 0 ? 'empty' : '';
            return `
                <tr onclick="LP_openLearner(${l.id})">
                    <td>
                        <div class="lp-user">
                            <strong>${l.name}</strong>
                            <span>${l.email}</span>
                        </div>
                    </td>
                    <td><span class="class-pill ${l.classification}">${pillLabel(l.classification)}</span></td>
                    <td>
                        <div class="lp-progress-wrapper">
                            <div class="lp-bar-track">
                                <div class="lp-bar-fill ${fillClass}" style="width:${pct.toFixed(1)}%"></div>
                            </div>
                            <span class="lp-bar-count">${l.lessonsDone}/${l.totalLessons}</span>
                        </div>
                    </td>
                    <td>
                        <span class="avg-score">${l.avgScore > 0 ? l.avgScore + '%' : '—'}</span>
                    </td>
                </tr>`;
        }).join('');
    }

    const start = filtered.length === 0 ? 0 : (currentPage - 1) * PAGE_SIZE + 1;
    const end   = Math.min(currentPage * PAGE_SIZE, filtered.length);
    document.getElementById('pagInfo').textContent =
        filtered.length > 0 ? `Showing ${start}–${end} of ${filtered.length} learners` : '';

    document.getElementById('prevBtn').disabled = currentPage <= 1;
    document.getElementById('nextBtn').disabled = currentPage >= totalPages;
}

function openLearner(id) {
    if (typeof window.navigateWithParams === 'function') {
        window.navigateWithParams('learner_detail', { id: id });
    } else {
        window.location.href = 'learner_detail.php?id=' + id;
    }
}
window.LP_openLearner = openLearner;

document.querySelectorAll('.sort-btn').forEach(btn => {
    btn.addEventListener('click', () => {
        document.querySelectorAll('.sort-btn').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
        activeFilter = btn.dataset.filter;
        currentPage  = 1;
        render();
    });
});

document.getElementById('searchInput').addEventListener('input', e => {
    searchQuery = e.target.value;
    currentPage = 1;
    render();
});

document.getElementById('prevBtn').addEventListener('click', () => { currentPage--; render(); });
document.getElementById('nextBtn').addEventListener('click', () => { currentPage++; render(); });

(async () => {
    try {
        learners = await loadLearners();
        render();
    } catch (err) {
        console.error('Failed to load learner data:', err);
        document.getElementById('learnerTableBody').innerHTML = `
            <tr><td colspan="4">
                <div class="lp-empty">
                    <i class="fa-solid fa-circle-exclamation" style="color:#ef4444;margin-right:6px;"></i>
                    Failed to load data. Please refresh the page.
                </div>
            </td></tr>`;
    }
})();

document.addEventListener('panel-shown-learner_progress', () => {
    render();
});

})();
</script>
</body>
</html>