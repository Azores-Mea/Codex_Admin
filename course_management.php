<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CODEX | Course Management</title>
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
        /* DROPDOWN LOGIC */
        .cm-checkbox { display: none; }
        .cm-checkbox:checked ~ .cm-lesson-list { display: block !important; }
        .cm-checkbox:checked ~ .cm-dropdown-label .caret-icon { transform: rotate(90deg); }

        /* COURSE MANAGEMENT STYLING */
        .cm-page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }
        .cm-page-header h2 { margin: 0; font-size: 32px; color: black; }
        .cm-page-header p  { margin: 5px 0 0; color: black; font-size: 20px; font-weight: 500; }

        .btn-cm {
            padding: 8px 18px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 10px;
            border: none;
            cursor: pointer;
            transition: 0.2s;
        }
        .btn-cm.dark  { background: #1e293b; color: white; }
        .btn-cm.dark:hover { background: #0f172a; }

        .btn-cm.archive-btn {
            background: none;
            border: 1px solid #fca5a5;
            color: #ef4444;
        }
        .btn-cm.archive-btn:hover { background: #fee2e2; }

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
            transition: opacity .3s;
        }
        .cm-lesson-item.archived { opacity: .6; }
        .cm-lesson-item.archived .cm-lesson-info { opacity: .4; }
        .cm-lesson-item.archived .btn-cm.dark { opacity: .3; pointer-events: none; cursor: not-allowed; }

        .caret-icon { transition: transform 0.2s ease; color: #94a3b8; }
        .cm-dropdown-label { cursor: pointer; display: block; width: 100%; }

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

        .empty-state {
            text-align: center;
            padding: 30px;
            color: #94a3b8;
            font-size: 13px;
            margin-left: 45px;
        }
        .empty-state i { display: block; font-size: 26px; margin-bottom: 8px; }

        /* MODAL */
        .modal-overlay {
            position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(0,0,0,.7); display: none; align-items: center; justify-content: center;
            z-index: 9999; backdrop-filter: blur(4px);
        }
        .modal-overlay.active { display: flex; }
        .modal-box {
            background: #001C30; padding: 50px; border-radius: 20px; width: 550px; text-align: center;
        }
        .modal-title { color: white; font-size: 22px; font-weight: 700; margin-bottom: 35px; }
        .difficulty-btn {
            display: block; width: 100%; padding: 20px; margin-bottom: 15px;
            background: white; border: none; border-radius: 12px;
            font-size: 18px; font-weight: 800; cursor: pointer; text-decoration: none;
            transition: all .15s ease;
        }
        .difficulty-btn.beginner     { color: #F59E0B; }
        .difficulty-btn.intermediate { color: #3B82F6; }
        .difficulty-btn.advanced     { color: #A855F7; }
        .difficulty-btn.beginner:hover        { background: #F59E0B; transform: scale(1.02); border: 2px solid #F59E0B; color: white; }
        .difficulty-btn.intermediate:hover { background: #3B82F6; transform: scale(1.02); border: 2px solid #3B82F6; color: white; }
        .difficulty-btn.advanced:hover     { background: #A855F7; transform: scale(1.02); border: 2px solid #A855F7; color: white; }
        .difficulty-btn:active       { transform: scale(.98); }
    </style>
</head>
<body>

<!-- ══ ADD MODULE MODAL ═══════════════════════════════════════════════════ -->
<div class="modal-overlay" id="addModuleModal">
    <div class="modal-box">
        <h2 class="modal-title">Select which module to add on:</h2>
        <div class="modal-options">
            <button class="difficulty-btn beginner"     onclick="CM_navigateToAddModule('Beginner')">Beginner</button>
            <button class="difficulty-btn intermediate" onclick="CM_navigateToAddModule('Intermediate')">Intermediate</button>
            <button class="difficulty-btn advanced"     onclick="CM_navigateToAddModule('Advanced')">Advanced</button>
        </div>
        <p onclick="CM_toggleModal()" style="color:#94A3B8;cursor:pointer;margin-top:20px;font-size:14px;">Cancel</p>
    </div>
</div>

<!-- ══ MAIN ════════════════════════════════════════════════════════════════ -->
<main class="db-main">
    <header class="cm-page-header">
        <div>
            <h2>Course management</h2>
            <p>Organize modules and lessons hierarchically</p>
        </div>
        <button class="btn-cm dark" onclick="CM_toggleModal()">+ Add module</button>
    </header>

    <div class="cm-container" id="courseMgmtContainer">
        <!-- Modules are rendered by JS -->
    </div>
</main>

<!-- ══ JAVASCRIPT ══════════════════════════════════════════════════════════ -->
<script>
(function() {

/* ── Modal ─────────────────────────────────────────────────────────────── */
function toggleModal() {
    document.getElementById('addModuleModal').classList.toggle('active');
}

/* ── Module config ──────────────────────────────────────────────────────── */
const MODULES = [
    { num: 1, level: 'Beginner',     badgeClass: 'blue',   icon: 'fa-seedling',  color: '#3b82f6' },
    { num: 2, level: 'Intermediate', badgeClass: 'orange', icon: 'fa-bolt',      color: '#f97316' },
    { num: 3, level: 'Advanced',     badgeClass: 'purple', icon: 'fa-fire',      color: '#a855f7' },
];

/* ── Skeleton HTML while loading ───────────────────────────────────────── */
function skeletonHTML() {
    return `
        <div class="skeleton skeleton-lesson"></div>
        <div class="skeleton skeleton-lesson" style="opacity:.6;"></div>
    `;
}

/* ── Strip HTML tags for plain-text display ────────────────────────────── */
function stripHTML(html) {
    const tmp = document.createElement('div');
    tmp.innerHTML = html || '';
    return tmp.textContent || tmp.innerText || '';
}

/* ── Summarise content block types present in a lesson ─────────────────── */
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
            ['helper1Url','helper2Url','helper3Url',
             'helper4Url','helper5Url','helper6Url','helper7Url'].forEach(k => {
                if (obj[k] && obj[k].trim()) hasImg = true;
            });
            ['helper1Code','helper2Code','helper3Code',
             'helper4Code','helper5Code','helper6Code','helper7Code'].forEach(k => {
                if (obj[k] && obj[k].trim()) hasCode = true;
            });
            if (obj.description || obj.title || obj.exampleDescription) hasText = true;
        };
        if (block.TITLE)              check(block.TITLE);
        if (block.EXAMPLE?.TYPES)     check(block.EXAMPLE.TYPES);
        if (block.SUBTITLE?.OUTPUT)   check(block.SUBTITLE.OUTPUT);
    });

    if (hasText)  parts.push('Text');
    if (hasImg)   parts.push('Images');
    if (hasCode)  parts.push('Code snippets');
    return parts.length ? parts.join(' • ') : 'No content';
}

/* ── Build lesson item row ──────────────────────────────────────────────── */
function buildLessonItem(lessonId, lesson, index) {
    const title      = stripHTML(lesson.main_title) || lessonId;
    const summary    = contentSummary(lesson);
    const isArchived = lesson.archived === true;
    const archivedClass = isArchived ? ' archived' : '';

    return `
    <div class="cm-lesson-item${archivedClass}" id="cm-lessonRow_${lessonId}">
        <div class="cm-lesson-info">
            <h4 style="margin:0;font-size:20px;">${index}. ${title}</h4>
            <p style="margin:3px 0 0;font-size:10px;color:#94a3b8;">${summary}</p>
        </div>
        <div class="cm-actions" style="display:flex;gap:8px;">
            <button class="btn-cm dark"
                onclick="CM_navigateToEdit('${lessonId}')">
                Edit
            </button>
            <button class="btn-cm archive-btn"
                onclick="CM_archiveLesson('${lessonId}', this, ${isArchived})">
                ${isArchived ? 'Unarchive' : 'Archive'}
            </button>
        </div>
    </div>`;
}

/* ── Navigate to edit ───────────────────────────────────────────────────── 
   FIX: Invalidate the cache for this specific lesson before navigating so
   the edit form always fetches fresh data from Firebase, not a stale copy.
   ─────────────────────────────────────────────────────────────────────── */
function navigateToEdit(lessonId) {
    // Bust both the full list cache and the specific lesson cache
    if (typeof FirebaseCache !== 'undefined') {
        FirebaseCache.invalidate('Lessons');
        FirebaseCache.invalidate('Lessons/' + lessonId);
    }

    // Store params in sessionStorage so add_module_form reads them (SPA pattern)
    sessionStorage.setItem('navParams_add_module_form', JSON.stringify({ edit: lessonId }));

    // Bust the panel cache so it re-initialises cleanly with a blank DOM
    if (window._panels) delete window._panels['add_module_form'];

    if (typeof window.navigate === 'function') {
        history.replaceState(null, '', '?page=add_module_form&edit=' + encodeURIComponent(lessonId));
        window.navigate('add_module_form');
    } else {
        window.location.href = 'index.php?page=add_module_form&edit=' + encodeURIComponent(lessonId);
    }
    }
window.CM_navigateToEdit = navigateToEdit;

/* ── Navigate to add module ─────────────────────────────────────────────── */
function navigateToAddModule(level) {
    document.getElementById('addModuleModal').classList.remove('active');
    sessionStorage.setItem('navParams_add_module_form', JSON.stringify({ level: level }));
    if (window._panels) delete window._panels['add_module_form'];
    if (typeof window.navigate === 'function') {
        history.replaceState(null, '', '?page=add_module_form&level=' + encodeURIComponent(level));
        window.navigate('add_module_form');
    } else {
        window.location.href = 'index.php?page=add_module_form&level=' + encodeURIComponent(level);
    }
}
window.CM_navigateToAddModule = navigateToAddModule;

/* ── Build a full module card ───────────────────────────────────────────── */
function buildModuleCard(mod, lessons) {
    const checkId = `cm-mod${mod.num}`;
    const count   = lessons.length;

    const lessonRows = count === 0
        ? `<div class="empty-state"><i class="fa-solid fa-inbox"></i>No lessons yet for this level.</div>`
        : lessons.map((item, i) => buildLessonItem(item.id, item.lesson, i + 1)).join('');

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
        <div class="cm-lesson-list" id="cm-lessonList_${mod.num}">
            ${lessonRows}
        </div>
    </div>`;
}

/* ── Archive / Unarchive a lesson ───────────────────────────────────────── */
async function archiveLesson(lessonId, btn, currentlyArchived) {
    const row      = document.getElementById('cm-lessonRow_' + lessonId);
    const newState = !currentlyArchived;

    btn.disabled    = true;
    btn.textContent = 'Saving…';

    try {
        await FirebaseCache.set('Lessons/' + lessonId + '/archived', newState);

        if (newState) {
            row.classList.add('archived');
            btn.textContent = 'Unarchive';
        } else {
            row.classList.remove('archived');
            btn.textContent = 'Archive';
        }
        btn.setAttribute('onclick', `CM_archiveLesson('${lessonId}', this, ${newState})`);
    } catch (e) {
        alert('Error updating archive status: ' + e.message);
        btn.textContent = currentlyArchived ? 'Unarchive' : 'Archive';
    } finally {
        btn.disabled = false;
    }
}

/* ── Render skeleton frames while loading ──────────────────────────────── */
function renderSkeletons() {
    const container = document.getElementById('courseMgmtContainer');
    container.innerHTML = MODULES.map(mod => `
        <div class="cm-module-card">
            <input type="checkbox" id="cm-mod${mod.num}_sk" class="cm-checkbox">
            <label for="cm-mod${mod.num}_sk" class="cm-dropdown-label">
                <div class="cm-module-header">
                    <div class="cm-module-info">
                        <i class="fa-solid fa-caret-right caret-icon"></i>
                        <h3 style="margin:0;font-size:16px;color:#1e293b;">Module ${mod.num} — ${mod.level}</h3>
                        <span class="cm-badge ${mod.badgeClass}">Loading…</span>
                    </div>
                </div>
            </label>
            <div class="cm-lesson-list" style="display:block;">
                ${skeletonHTML()}
            </div>
        </div>`).join('');
}

/* ── Fetch all lessons and render ───────────────────────────────────────── */
async function loadModules() {
    renderSkeletons();

    try {
        const snapshot = await FirebaseCache.get('Lessons');
        const data     = snapshot.val() || {};

        const buckets = { Beginner: [], Intermediate: [], Advanced: [] };

        Object.entries(data).forEach(([id, lesson]) => {
            const diff = lesson.difficulty || '';
            const key  = diff.charAt(0).toUpperCase() + diff.slice(1).toLowerCase();
            if (buckets[key] !== undefined) {
                buckets[key].push({ id, lesson });
            }
        });

        Object.keys(buckets).forEach(k => {
            buckets[k].sort((a, b) => {
                const numA = parseInt(a.id.replace(/\D/g, ''), 10) || 0;
                const numB = parseInt(b.id.replace(/\D/g, ''), 10) || 0;
                return numA - numB;
            });
        });

        const container = document.getElementById('courseMgmtContainer');
        container.innerHTML = MODULES.map(mod => buildModuleCard(mod, buckets[mod.level])).join('');

    } catch (e) {
        document.getElementById('courseMgmtContainer').innerHTML =
            `<p style="color:#ef4444;padding:20px;">Error loading lessons: ${e.message}</p>`;
    }
}

/* ── Expose to global scope ─────────────────────────────────────────────── */
window.CM_toggleModal   = toggleModal;
window.CM_archiveLesson = archiveLesson;

loadModules();
})();
</script>
</body>
</html>