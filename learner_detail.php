<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>CODEX | Learner Details</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"/>
    <link rel="stylesheet" href="dashboard.css">

    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f4f6f9;
            color: #1a1a2e;
            min-height: 100vh;
        }

        .back-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
            color: #6b7280;
            background: none;
            border: none;
            cursor: pointer;
            margin-bottom: 20px;
            padding: 0;
            transition: color .15s;
        }
        .back-btn:hover { color: #111827; }

        .ld-header {
            margin-bottom: 28px;
            display: flex;
            flex-direction: column;
            gap: 4px;
        }
        .ld-header h2 {
            font-size: 32px;
            font-weight: 700;
            color: black;
            line-height: 1.2;
        }
        .ld-header p {
            font-size: 20px;
            color: black;
            line-height: 1.4;
        }

        .ld-top {
            display: grid;
            grid-template-columns: 280px 1fr;
            gap: 20px;
            margin-bottom: 36px;
            align-items: start;
        }

        .profile-card {
            background: #fff;
            border: 1.5px solid #e5e7eb;
            border-radius: 14px;
            padding: 24px;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 4px;
            text-align: center;
        }
        .profile-avatar {
            width: 64px; height: 64px;
            border-radius: 50%;
            background: #e5e7eb;
            font-size: 22px;
            font-weight: 700;
            color: #374151;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 10px;
        }
        .profile-card h3 { font-size: 20px; font-weight: 700; color: #111827; }
        .class-pill {
            display: inline-block;
            padding: 3px 14px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 500;
            border: 1.5px solid;
            margin: 6px 0 14px;
            white-space: nowrap;
        }
        .class-pill.beginner        { color: #F59E0B; border-color: #F59E0B; }
        .class-pill.intermediate    { color: #3B82F6; border-color: #3B82F6; }
        .class-pill.advanced        { color: #A855F7; border-color: #A855F7; }
        .class-pill.notclassified   { color: #6b7280; border-color: #9ca3af; }

        .profile-email {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 13.5px;
            color: #6b7280;
            border-top: 1px solid #f3f4f6;
            padding-top: 14px;
            width: 100%;
            justify-content: center;
        }
        .profile-email i { color: #9ca3af; }

        .summary-card {
            background: #fff;
            border: 1.5px solid #e5e7eb;
            border-radius: 14px;
            padding: 0;
            overflow: hidden;
        }
        .summary-card .sum-title {
            background: #f9fafb;
            padding: 12px 20px;
            font-size: 11.5px;
            font-weight: 700;
            letter-spacing: .07em;
            color: #6b7280;
            border-bottom: 1.5px solid #e5e7eb;
        }
        .sum-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 14px 20px;
            border-bottom: 1px solid #f3f4f6;
            font-size: 14px;
            color: #374151;
        }
        .sum-row:last-child { border-bottom: none; }
        .sum-row .label {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .sum-row .label i { font-size: 15px; color: #6b7280; width: 18px; text-align: center; }
        .sum-row .value { font-weight: 600; color: #111827; }

        .mps-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 18px;
            flex-wrap: wrap;
            gap: 12px;
        }
        .mps-header h3 { font-size: 20px; font-weight: 700; color: #111827; }

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
            padding: 6px 16px;
            border-radius: 999px;
            font-size: 13px;
            font-weight: 500;
            color: #374151;
            cursor: pointer;
            transition: background .18s, color .18s;
        }
        .sort-btn.active { background: #111827; color: #fff; }
        .sort-btn:not(.active):hover { background: #f3f4f6; }

        .mod-card {
            background: #fff;
            border: 1.5px solid #e5e7eb;
            border-radius: 14px;
            overflow: hidden;
        }

        table.mod-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        .mod-table col.col-module  { width: 30%; }
        .mod-table col.col-quiz    { width: 16%; }
        .mod-table col.col-syntax  { width: 18%; }
        .mod-table col.col-trace   { width: 18%; }
        .mod-table col.col-machine { width: 18%; }

        .mod-table thead tr {
            background: #f9fafb;
            border-bottom: 1.5px solid #e5e7eb;
        }
        .mod-table th {
            padding: 13px 20px;
            font-size: 11.5px;
            font-weight: 700;
            letter-spacing: .06em;
            color: #6b7280;
            text-align: left;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .mod-table tbody tr {
            border-bottom: 1px solid #f3f4f6;
            transition: background .12s;
        }
        .mod-table tbody tr:last-child { border-bottom: none; }
        .mod-table tbody tr:hover { background: #f8faff; }
        .mod-table td {
            padding: 15px 20px;
            font-size: 14px;
            color: #374151;
            vertical-align: middle;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .mod-table td:first-child {
            white-space: normal;
            word-break: break-word;
        }

        .quiz-cell {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .bar-track {
            width: 50px;
            height: 6px;
            background: #e5e7eb;
            border-radius: 99px;
            flex-shrink: 0;
        }
        .bar-fill {
            height: 100%;
            border-radius: 99px;
            background: #3b82f6;
        }
        .bar-fill.empty { background: #d1d5db; }
        .quiz-score { font-size: 13px; color: #374151; white-space: nowrap; }

        .badge {
            display: inline-block;
            padding: 4px 14px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 500;
            white-space: nowrap;
        }
        .badge.complete   { background: #d1fae5; color: #065f46; }
        .badge.incomplete { background: transparent; color: #ef4444; font-weight: 600; }
        .badge.na         { background: #e5e7eb; color: #6b7280; font-size: 13px; }

        .mod-footer {
            display: flex;
            justify-content: flex-end;
            padding: 14px 20px;
            border-top: 1.5px solid #f3f4f6;
            gap: 8px;
        }
        .pag-btn {
            width: 34px; height: 34px;
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

        /* ── Loading / error states ── */
        .ld-loading {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            padding: 60px 20px;
            color: #6b7280;
            font-size: 14px;
        }
        .ld-spinner {
            width: 20px;
            height: 20px;
            border: 2.5px solid #e5e7eb;
            border-top-color: #3b82f6;
            border-radius: 50%;
            animation: spin .7s linear infinite;
        }
        @keyframes spin { to { transform: rotate(360deg); } }

        @media (max-width: 700px) {
            .ld-top { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>

<main class="db-main">

    <!-- Initial loading state — replaced once Firebase resolves -->
    <div id="pageLoading" class="ld-loading">
        <div class="ld-spinner"></div>
        Loading learner details…
    </div>

    <!-- Main content (hidden until data is ready) -->
    <div id="pageContent" style="display:none;">

        <header class="ld-header">
            <h2>Learner Details</h2>
            <p>View and monitor individual learner activity</p>
        </header>

        <div class="ld-top">
            <div class="profile-card">
                <div class="profile-avatar" id="avatarEl">—</div>
                <h3 id="nameEl">—</h3>
                <span class="class-pill" id="classEl">—</span>
                <div class="profile-email">
                    <i class="fa-regular fa-envelope"></i>
                    <span id="emailEl">—</span>
                </div>
            </div>

            <div class="summary-card">
                <div class="sum-title">SHORT SUMMARY</div>
                <div class="sum-rows">
                    <div class="sum-row">
                        <span class="label"><i class="fa-regular fa-file-lines"></i> Lessons Done:</span>
                        <span class="value" id="sumLessons">—</span>
                    </div>
                    <div class="sum-row">
                        <span class="label"><i class="fa-regular fa-lightbulb"></i> Quizzes Taken:</span>
                        <span class="value" id="sumQuizzes">—</span>
                    </div>
                    <div class="sum-row">
                        <span class="label"><i class="fa-solid fa-chart-bar"></i> Avg. Score:</span>
                        <span class="value" id="sumScore">—</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="mps-header">
            <h3>Module Performance Summary</h3>
            <div class="sort-btn-group">
                <button class="sort-btn active" data-filter="all">All</button>
                <button class="sort-btn" data-filter="beginner">Beginner</button>
                <button class="sort-btn" data-filter="intermediate">Intermediate</button>
                <button class="sort-btn" data-filter="advanced">Advanced</button>
            </div>
        </div>

        <div class="mod-card">
            <table class="mod-table">
                <colgroup>
                    <col class="col-module"/>
                    <col class="col-quiz"/>
                    <col class="col-syntax"/>
                    <col class="col-trace"/>
                    <col class="col-machine"/>
                </colgroup>
                <thead>
                    <tr>
                        <th>MODULE</th>
                        <th>QUIZ</th>
                        <th>FINDING SYNTAX ERROR</th>
                        <th>PROGRAM TRACING</th>
                        <th>MACHINE PROBLEM</th>
                    </tr>
                </thead>
                <tbody id="moduleTableBody">
                    <tr>
                        <td colspan="5" style="padding:30px;text-align:center;color:#9ca3af;font-size:14px;">
                            No modules found.
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="mod-footer">
                <button class="pag-btn" id="prevBtn" disabled><i class="fa-solid fa-chevron-left"></i></button>
                <button class="pag-btn" id="nextBtn" disabled><i class="fa-solid fa-chevron-right"></i></button>
            </div>
        </div>

    </div><!-- /pageContent -->

</main>



<script>
(function() {

function stripHtml(s) {
    return s ? s.replace(/<[^>]+>/g, '').trim() : '';
}

function normalizeClass(c) {
    if (!c) return 'notclassified';
    const lower = c.toLowerCase().replace(/\s+/g, '');
    return ['beginner', 'intermediate', 'advanced'].includes(lower) ? lower : 'notclassified';
}

function cap(s) {
    return s.charAt(0).toUpperCase() + s.slice(1);
}

async function loadLearnerDetail(learnerId) {
    const [usersSnap, quizSnap, lessonsSnap, syntaxSnap, tracingSnap, machineSnap] = await Promise.all([
        FirebaseCache.get('Users'),
        FirebaseCache.get('quizResults'),
        FirebaseCache.get('Lessons'),
        FirebaseCache.get('syntaxErrorResults'),
        FirebaseCache.get('tracingResults'),
        FirebaseCache.get('machineProblemResults'),
    ]);

    const usersRaw    = usersSnap.val()    || [];
    const quizData    = quizSnap.val()     || {};
    const lessonsRaw  = lessonsSnap.val()  || {};
    const syntaxData  = syntaxSnap.val()   || {};
    const tracingData = tracingSnap.val()  || {};
    const machineData = machineSnap.val()  || {};

    const usersArr = Array.isArray(usersRaw)
        ? usersRaw
        : Object.values(usersRaw);

    const user = usersArr.find(u => u && String(u.userId) === String(learnerId));
    if (!user) return null;

    const uid         = String(user.userId);
    const userQuiz    = quizData[uid]    || {};
    const userSyntax  = syntaxData[uid]  || {};
    const userTrace   = tracingData[uid] || {};
    const userMachine = machineData[uid] || {};

    const lessonIds    = Object.keys(userQuiz);
    const lessonsDone  = lessonIds.length;
    const quizzesTaken = lessonsDone;

    const totalLessons = Object.values(lessonsRaw)
        .filter(l => l && !l.archived).length;

    let avgScore = 0;
    if (lessonsDone > 0) {
        const sum = lessonIds.reduce((acc, lid) => {
            const q = userQuiz[lid] || {};
            return acc + (q.total ? (q.score / q.total) * 100 : 0);
        }, 0);
        avgScore = Math.round(sum / lessonsDone);
    }

    const modules = lessonIds.map(lid => {
        const lesson       = lessonsRaw[lid] || {};
        const q            = userQuiz[lid]   || {};
        const syntaxEntry  = userSyntax[lid];
        const traceEntry   = userTrace[lid];
        const machineEntry = userMachine[lid];

        return {
            lessonId:       lid,
            name:           stripHtml(lesson.main_title) || lid,
            level:          (lesson.difficulty || 'Beginner').toLowerCase(),
            quizScore:      q.score || 0,
            quizTotal:      q.total || 10,
            syntaxError:    syntaxEntry  && syntaxEntry.completed  ? 'complete' : null,
            programTracing: traceEntry   && traceEntry.completed   ? 'complete' : null,
            machineProblem: machineEntry && machineEntry.completed ? 'complete' : null,
        };
    });

    return {
        id:             user.userId,
        name:           `${user.firstName} ${user.lastName}`,
        email:          user.email,
        classification: normalizeClass(user.classification),
        lessonsDone,
        totalLessons,
        avgScore,
        quizzesTaken,
        modules,
    };
}

const PAGE_SIZE = 5;
let currentPage  = 1;
let activeFilter = 'all';
let learner      = null;

function getFilteredModules() {
    if (!learner) return [];
    return learner.modules.filter(m =>
        activeFilter === 'all' || m.level === activeFilter
    );
}

function renderModules() {
    const filtered   = getFilteredModules();
    const totalPages = Math.max(1, Math.ceil(filtered.length / PAGE_SIZE));
    currentPage      = Math.min(currentPage, totalPages);
    const slice      = filtered.slice(
        (currentPage - 1) * PAGE_SIZE,
        currentPage * PAGE_SIZE
    );

    const tbody = document.getElementById('moduleTableBody');
    if (slice.length === 0) {
        tbody.innerHTML = `<tr><td colspan="5" style="padding:30px;text-align:center;color:#9ca3af;font-size:14px;">No modules found.</td></tr>`;
    } else {
        tbody.innerHTML = slice.map(m => {
            const pct       = m.quizTotal ? (m.quizScore / m.quizTotal * 100) : 0;
            const fillClass = pct === 0 ? 'empty' : '';
            return `
                <tr>
                    <td>${m.name}</td>
                    <td>
                        <div class="quiz-cell">
                            <div class="bar-track">
                                <div class="bar-fill ${fillClass}" style="width:${pct.toFixed(1)}%"></div>
                            </div>
                            <span class="quiz-score">${m.quizScore}/${m.quizTotal}</span>
                        </div>
                    </td>
                    <td>${badgeCell(m.syntaxError)}</td>
                    <td>${badgeCell(m.programTracing)}</td>
                    <td>${badgeCell(m.machineProblem)}</td>
                </tr>`;
        }).join('');
    }

    document.getElementById('prevBtn').disabled = currentPage <= 1;
    document.getElementById('nextBtn').disabled = currentPage >= totalPages;
}

function badgeCell(val) {
    if (val === 'complete')   return `<span class="badge complete">Complete</span>`;
    if (val === 'incomplete') return `<span class="badge incomplete">Incomplete</span>`;
    return `<span class="badge na">--/--</span>`;
}

function populatePage(data) {
    const initials = data.name.split(' ').slice(0, 2).map(w => w[0]).join('').toUpperCase();
    document.getElementById('avatarEl').textContent = initials;
    document.getElementById('nameEl').textContent   = data.name;
    document.getElementById('emailEl').textContent  = data.email;

    const classEl = document.getElementById('classEl');
    classEl.textContent = data.classification === 'notclassified'
        ? 'Not Classified'
        : cap(data.classification);
    classEl.className = `class-pill ${data.classification}`;

    document.getElementById('sumLessons').textContent =
        `${data.lessonsDone}/${data.totalLessons}`;
    document.getElementById('sumQuizzes').textContent =
        `${data.quizzesTaken}/${data.totalLessons}`;
    document.getElementById('sumScore').textContent =
        data.avgScore > 0 ? `${data.avgScore}%` : '—';
}

document.querySelectorAll('.sort-btn').forEach(btn => {
    btn.addEventListener('click', () => {
        document.querySelectorAll('.sort-btn').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
        activeFilter = btn.dataset.filter;
        currentPage  = 1;
        renderModules();
    });
});

document.getElementById('prevBtn').addEventListener('click', () => { currentPage--; renderModules(); });
document.getElementById('nextBtn').addEventListener('click', () => { currentPage++; renderModules(); });

(async () => {
    let learnerId = null;

    const urlParams = new URLSearchParams(location.search);
    learnerId = urlParams.get('id');

    if (!learnerId) {
        const stored = sessionStorage.getItem('navParams_learner_detail');
        if (stored) {
            try { learnerId = JSON.parse(stored).id; } catch(e) {}
        }
    }

    if (!learnerId) {
        document.getElementById('pageLoading').innerHTML = `
            <i class="fa-solid fa-circle-exclamation" style="color:#ef4444;margin-right:6px;font-size:16px;"></i>
            No learner ID provided.`;
        return;
    }

    try {
        learner = await loadLearnerDetail(learnerId);

        if (!learner) {
            document.getElementById('pageLoading').innerHTML = `
                <i class="fa-solid fa-circle-exclamation" style="color:#ef4444;margin-right:6px;font-size:16px;"></i>
                Learner not found.`;
            return;
        }

        document.getElementById('pageLoading').style.display = 'none';
        document.getElementById('pageContent').style.display = 'block';

        populatePage(learner);
        renderModules();

    } catch (err) {
        console.error('Failed to load learner detail:', err);
        document.getElementById('pageLoading').innerHTML = `
            <i class="fa-solid fa-circle-exclamation" style="color:#ef4444;margin-right:6px;font-size:16px;"></i>
            Failed to load data. Please refresh the page.`;
    }
})();

})();
</script>
</body>
</html>