<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CODEX | Content Management</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="dashboard.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">

    <!-- Firebase SDK -->
    <script src="https://www.gstatic.com/firebasejs/10.12.0/firebase-app-compat.js"></script>
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
    <script src="firebase-cache.js"></script>

    <style>
        body {
            display: flex;
            margin: 0;
            background-color: #fff;
            font-family: 'Roboto', sans-serif;
        }

        .db-main {
            flex-grow: 1;
            padding: 40px;
            overflow-y: auto;
            height: 100vh;
            box-sizing: border-box;
        }

        /* ── DROPDOWN LOGIC ── */
        .cm-checkbox { display: none; }
        .cm-checkbox:checked ~ .cm-lesson-list { display: block !important; }
        .cm-checkbox:checked ~ .cm-dropdown-label .caret-icon { transform: rotate(90deg); }

        /* ── PAGE HEADER ── */
        .cm-page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }
        .cm-page-header h2 { margin: 0; font-size: 32px; color: black; }
        .cm-page-header p  { margin: 5px 0 0; color: black; font-size: 20px; }

        /* ── BUTTONS ── */
        .btn-cm {
            padding: 8px 18px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 13px;
            border: none;
            cursor: pointer;
            transition: 0.2s;
        }
        .btn-cm.dark { background: #1e293b; color: white; }
        .btn-cm.dark:hover { background: #0f172a; }

        /* ── MODULE CARD ── */
        .cm-module-card {
            background: white;
            border: 1px solid #f1f5f9;
            border-radius: 12px;
            margin-bottom: 16px;
            overflow: hidden;
            box-shadow: 0 1px 4px rgba(0,0,0,.04);
        }

        .cm-module-header {
            display: flex;
            align-items: center;
            padding: 20px 25px;
            cursor: pointer;
        }

        .cm-module-info {
            display: flex;
            align-items: center;
            gap: 15px;
            flex-grow: 1;
        }

        /* Difficulty badges */
        .cm-badge          { padding: 2px 12px; border-radius: 20px; font-size: 14px; font-weight: 700; }
        .cm-badge.blue     { border: 1px solid #E3AF64; color: #E3AF64; }
        .cm-badge.orange   { border: 1px solid #66ABF4; color: #66ABF4; }
        .cm-badge.purple   { border: 1px solid #A666F4; color: #A666F4; }

        .cm-lesson-list {
            display: none;
            background-color: #f8fafc;
            border-top: 1px solid #f1f5f9;
            padding: 25px;
        }

        .cm-lesson-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 20px;
            background: white;
            border: 1px solid #f1f5f9;
            border-radius: 10px;
            margin-bottom: 10px;
        }

        /* ── Assessment chips below title ── */
        .cm-assess-list {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 6px;
            margin-top: 5px;
        }
        .cm-assess-list .a-chip {
            display: inline-flex;
            align-items: center;
            padding: 2px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 500;
            background: #f1f5f9;
            color: #475569;
            border: 1px solid #e2e8f0;
            white-space: nowrap;
        }
        .cm-no-assess {
            font-size: 11px;
            color: #cbd5e1;
            margin-top: 4px;
            font-style: italic;
        }

        .caret-icon { transition: transform 0.2s ease; color: #94a3b8; }
        .cm-dropdown-label { cursor: pointer; display: block; width: 100%; }

        /* Skeleton */
        .skeleton {
            background: linear-gradient(90deg, #f1f5f9 25%, #e2e8f0 50%, #f1f5f9 75%);
            background-size: 200% 100%;
            animation: shimmer 1.4s infinite;
            border-radius: 6px;
            height: 14px;
        }
        @keyframes shimmer { to { background-position: -200% 0; } }
        .skeleton-lesson {
            height: 62px;
            border-radius: 10px;
            margin-left: 45px;
            margin-bottom: 10px;
        }

        /* Empty state */
        .empty-state {
            text-align: center;
            padding: 30px;
            color: #94a3b8;
            font-size: 13px;
            margin-left: 45px;
        }
        .empty-state i { display: block; font-size: 26px; margin-bottom: 8px; }

        /* ── MODAL — white card style matching screenshot ── */
        .modal-overlay {
            position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(0,0,0,.55);
            display: none; align-items: center; justify-content: center;
            z-index: 9999; backdrop-filter: blur(4px);
        }
        .modal-overlay.active { display: flex; }

        .modal-box {
            background: #001C30;
            padding: 48px 40px 36px;
            border-radius: 24px;
            width: 420px;
            text-align: center;
            box-shadow: 0 24px 64px rgba(0,0,0,.4);
        }
        .modal-title {
            color: white;
            font-size: 18px;
            font-weight: 700;
            margin-bottom: 28px;
            line-height: 1.4;
        }

        /* White pill buttons matching the screenshot */
        .assess-btn {
            display: block;
            width: 100%;
            padding: 16px 20px;
            margin-bottom: 14px;
            background: white;
            border: none;
            border-radius: 14px;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            color: #3b82f6;
            transition: all .15s ease;
            box-shadow: 0 2px 8px rgba(0,0,0,.08);
        }
        .assess-btn:hover  { background: #001C30; transform: scale(1.02); border: 2px solid #3b82f6; color: white; }
        .assess-btn:active { transform: scale(.98); }

        .cancel-link {
            color: #94A3B8;
            cursor: pointer;
            margin-top: 16px;
            font-size: 14px;
            font-weight: 600;
            display: inline-block;
        }
        .cancel-link:hover { color: white; }
    </style>
</head>
<body>

<!-- ══ ASSESSMENT TYPE MODAL ══════════════════════════════════════════════ -->
<div class="modal-overlay" id="assessModal">
    <div class="modal-box">
        <h2 class="modal-title">Select the type of assessment to modify:</h2>
        <button class="assess-btn" onclick="CONT_selectAssessment('Quiz')">Quiz</button>
        <button class="assess-btn" onclick="CONT_selectAssessment('Coding Exercise')">Coding Exercise</button>
        <p class="cancel-link" onclick="CONT_closeModal()">Cancel</p>
    </div>
</div>


<!-- ══ MAIN ════════════════════════════════════════════════════════════════ -->
<main class="db-main">
    <header class="cm-page-header">
        <div>
            <h2>Content management</h2>
            <p>Manage assessment bank per lesson</p>
        </div>
    </header>

    <div class="cm-container" id="contentMgmtContainer">
        <!-- Rendered by JS -->
    </div>
</main>

<!-- ══ JAVASCRIPT ══════════════════════════════════════════════════════════ -->
<script>
(function() {

/* ── Active lesson context for modal ───────────────────────────────────── */
let _activeLessonId = null;

function openAssessModal(lessonId) {
    _activeLessonId = lessonId;
    document.getElementById('assessModal').classList.add('active');
}
function closeModal() {
    document.getElementById('assessModal').classList.remove('active');
    _activeLessonId = null;
}
function selectAssessment(type) {
    if (!_activeLessonId) { closeModal(); return; }
    if (type === 'Coding Exercise') {
        const params = { lesson: _activeLessonId };
        sessionStorage.setItem('navParams_add_coding_exercise_form', JSON.stringify(params));
        if (window._panels) delete window._panels['add_coding_exercise_form'];
        history.replaceState(null, '', '?page=add_coding_exercise_form&lesson=' + encodeURIComponent(_activeLessonId));
        if (typeof window.navigate === 'function') window.navigate('add_coding_exercise_form');
    } else {
        const params = { lesson: _activeLessonId, type };
        sessionStorage.setItem('navParams_add_assessment_form', JSON.stringify(params));
        if (window._panels) delete window._panels['add_assessment_form'];
        history.replaceState(null, '', '?page=add_assessment_form&lesson=' + encodeURIComponent(_activeLessonId) + '&type=' + encodeURIComponent(type));
        if (typeof window.navigate === 'function') window.navigate('add_assessment_form');
    }
    closeModal();
}

/* ── Register globals IMMEDIATELY so buttons work as soon as DOM exists ── */
window.CONT_openAssessModal  = openAssessModal;
window.CONT_closeModal       = closeModal;
window.CONT_selectAssessment = selectAssessment;

/* ── Modal backdrop click ───────────────────────────────────────────────── */
const _modal = document.getElementById('assessModal');
if (_modal) _modal.addEventListener('click', e => { if (e.target === _modal) closeModal(); });

/* ── Module config ──────────────────────────────────────────────────────── */
const MODULES = [
    { num: 1, level: 'Beginner',     badgeClass: 'blue',   icon: 'fa-seedling',  color: '#3b82f6' },
    { num: 2, level: 'Intermediate', badgeClass: 'orange', icon: 'fa-bolt',      color: '#f97316' },
    { num: 3, level: 'Advanced',     badgeClass: 'purple', icon: 'fa-fire',      color: '#a855f7' },
];

function skeletonHTML() {
    return `
        <div class="skeleton skeleton-lesson"></div>
        <div class="skeleton skeleton-lesson" style="opacity:.6;"></div>
    `;
}

function stripHTML(html) {
    const tmp = document.createElement('div');
    tmp.innerHTML = html || '';
    return tmp.textContent || tmp.innerText || '';
}

function contentSummary(lesson) {
    const parts = [];
    const content = lesson.content || {};
    let hasImg = false, hasCode = false, hasText = false;

    Object.values(content).forEach(block => {
        const check = (obj) => {
            if (!obj) return;
            ['helper1Drawable','helper2Drawable','helper3Drawable',
             'helper4Drawable','helper5Drawable','helper6Drawable','helper7Drawable'].forEach(k => {
                if (obj[k] && obj[k].trim()) hasImg = true;
            });
            ['helper1Code','helper2Code','helper3Code',
             'helper4Code','helper5Code','helper6Code','helper7Code'].forEach(k => {
                if (obj[k] && obj[k].trim()) hasCode = true;
            });
            if (obj.description || obj.title || obj.exampleDescription) hasText = true;
        };
        if (block.TITLE) check(block.TITLE);
        if (block.EXAMPLE?.TYPES) check(block.EXAMPLE.TYPES);
        if (block.SUBTITLE?.OUTPUT) check(block.SUBTITLE.OUTPUT);
    });

    if (hasText)  parts.push('Text');
    if (hasImg)   parts.push('Images');
    if (hasCode)  parts.push('Code snippets');
    return parts.length ? parts.join(' · ') : 'No content';
}

/* ── Build assessment chips ─────────────────────────────────────────────── */
/*  assessTypes = object { Quiz:{...}, ProgramTracing:{...} }  from assessment/{lessonId}
    codingItems = array of exercise objects                    from coding_exercises/{lessonId}

    Display rules:
      - Finding Syntax Error  → chip "Finding Syntax Error (n)" where n = question count
      - Program Tracing       → chip "Program Tracing (n)"
      - Machine Problem       → chip "Machine Problem"  (no count)
      - Quiz                  → chip "Quiz"             (no count)
      - Coding exercises      → one chip per exercise showing the TYPE label "Coding Exercise",
                                NOT the exercise title
    Order: Finding Syntax Error · Program Tracing · Machine Problem · Quiz · Coding Exercise
*/
function buildAssessmentTags(assessTypes, codingItems) {
    const chips = [];

    const ORDER  = ['FindingSyntaxError', 'ProgramTracing', 'MachineProblem', 'Quiz'];
    const LABELS = {
        'FindingSyntaxError': 'Finding Syntax Error',
        'ProgramTracing':     'Program Tracing',
        'MachineProblem':     'Machine Problem',
        'Quiz':               'Quiz',
    };
    const SHOW_COUNT = new Set(['FindingSyntaxError', 'ProgramTracing']);

    if (assessTypes) {
        ORDER.forEach(type => {
            if (!assessTypes[type]) return;
            const questions = assessTypes[type];
            const label     = LABELS[type] || type;
            if (SHOW_COUNT.has(type) && typeof questions === 'object') {
                const items = Array.isArray(questions)
                    ? questions.filter(q => q != null)
                    : Object.values(questions).filter(q => q != null);
                const total = items.length;
                chips.push(`<span class="a-chip">${label} (${total})</span>`);
            } else {
                chips.push(`<span class="a-chip">${label}</span>`);
            }
        });
    }

    /* Coding exercises — show "Coding Exercise" type label, not individual titles */
    if (Array.isArray(codingItems)) {
        const validExercises = codingItems.filter(ex => ex != null);
        if (validExercises.length > 0) {
            chips.push(`<span class="a-chip">Coding Exercise</span>`);
        }
    }

    if (chips.length === 0) return `<p class="cm-no-assess">No assessments yet</p>`;
    return `<div class="cm-assess-list">${chips.join('')}</div>`;
}

/* ── Build lesson item row ──────────────────────────────────────────────── */
function buildLessonItem(lessonId, lesson, assessTypes, codingItems, index) {
    const title      = stripHTML(lesson.main_title) || lessonId;
    const assessTags = buildAssessmentTags(assessTypes, codingItems);

    return `
    <div class="cm-lesson-item" id="cont-lessonRow_${lessonId}">
        <div class="cm-lesson-info" style="flex:1;">
            <h4 style="margin:0;font-size:20px;">${index}. ${title}</h4>
            ${assessTags}
        </div>
        <div class="cm-actions" style="display:flex;gap:8px;margin-left:16px;font-size:10px;">
            <button class="btn-cm dark"
                onclick="CONT_openAssessModal('${lessonId}')">
                Modify assessment
            </button>
        </div>
    </div>`;
}

/* ── Build module card ──────────────────────────────────────────────────── */
function buildModuleCard(mod, lessons) {
    const checkId = `cont-mod${mod.num}`;
    const count   = lessons.length;

    const lessonRows = count === 0
        ? `<div class="empty-state"><i class="fa-solid fa-inbox"></i>No lessons yet for this level.</div>`
        : lessons.map((item, i) =>
            buildLessonItem(item.id, item.lesson, item.assessTypes, item.codingItems, i + 1)
          ).join('');

    return `
    <div class="cm-module-card">
        <input type="checkbox" id="${checkId}" class="cm-checkbox">
        <label for="${checkId}" class="cm-dropdown-label">
            <div class="cm-module-header">
                <div class="cm-module-info">
                    <i class="fa-solid fa-caret-right caret-icon"></i>
                    <h3 style="margin:0;font-size:24px;color:#1e293b;">
                        Module ${mod.num} — ${mod.level}
                    </h3>
                    <span class="cm-badge ${mod.badgeClass}">${count} lesson${count !== 1 ? 's' : ''}</span>
                </div>
            </div>
        </label>
        <div class="cm-lesson-list" id="cont-lessonList_${mod.num}">
            ${lessonRows}
        </div>
    </div>`;
}

/* ── Skeleton while loading ─────────────────────────────────────────────── */
function renderSkeletons() {
    const container = document.getElementById('contentMgmtContainer');
    container.innerHTML = MODULES.map(mod => `
        <div class="cm-module-card">
            <input type="checkbox" id="cont-mod${mod.num}_sk" class="cm-checkbox">
            <label for="cont-mod${mod.num}_sk" class="cm-dropdown-label">
                <div class="cm-module-header">
                    <div class="cm-module-info">
                        <i class="fa-solid fa-caret-right caret-icon"></i>
                        <h3 style="margin:0;font-size:20px;color:#1e293b;">Module ${mod.num} — ${mod.level}</h3>
                        <span class="cm-badge ${mod.badgeClass}">Loading…</span>
                    </div>
                </div>
            </label>
            <div class="cm-lesson-list" style="display:block;">
                ${skeletonHTML()}
            </div>
        </div>`).join('');
}

/* ── Fetch lessons + assessments from Firebase and render ───────────────── */
async function loadModules() {
    renderSkeletons();

    try {
        const db = firebase.database();

        /* Fetch all three refs in parallel */
        const [lessonSnap, assessSnap, codingSnap] = await Promise.all([
            FirebaseCache.get('Lessons'),
            FirebaseCache.get('assessment'),
            FirebaseCache.get('coding_exercises'),
        ]);

        const lessonsData  = lessonSnap.val()  || {};
        const assessData   = assessSnap.val()  || {};   // { L1: { Quiz:{...} }, L4: { Quiz:{...}, ProgramTracing:{...} } }
        const codingData   = codingSnap.val()  || {};   // { L2: [ null, {...}, {...} ] }

        const buckets = { Beginner: [], Intermediate: [], Advanced: [] };

        Object.entries(lessonsData).forEach(([id, lesson]) => {
            const diff = lesson.difficulty || '';
            const key  = diff.charAt(0).toUpperCase() + diff.slice(1).toLowerCase();
            if (buckets[key] !== undefined) {
                const assessTypes = assessData[id]  || null;   // e.g. { Quiz:{...}, ProgramTracing:{...} }
                const codingItems = codingData[id]  || null;   // e.g. [ null, {title:'...', ...} ]
                buckets[key].push({ id, lesson, assessTypes, codingItems });
            }
        });

        Object.keys(buckets).forEach(k => {
            buckets[k].sort((a, b) => {
                const numA = parseInt(a.id.replace(/\D/g, ''), 10) || 0;
                const numB = parseInt(b.id.replace(/\D/g, ''), 10) || 0;
                return numA - numB;
            });
        });

        const container = document.getElementById('contentMgmtContainer');
        container.innerHTML = MODULES.map(mod =>
            buildModuleCard(mod, buckets[mod.level])
        ).join('');

    } catch (e) {
        document.getElementById('contentMgmtContainer').innerHTML =
            `<p style="color:#ef4444;padding:20px;">Error loading lessons: ${e.message}</p>`;
    }
}

/* ── Boot — runs immediately when script is injected by the SPA ─────────── */
/* DOMContentLoaded has already fired by the time this panel is built,     */
/* so we call loadModules() directly instead.                               */

// Expose onclick handlers to global scope (called from HTML attributes)

loadModules();
})(); // ── end IIFE ──
</script>
</body>
</html>