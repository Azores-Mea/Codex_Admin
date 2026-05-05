<?php
$lessonId = htmlspecialchars($_GET['lesson'] ?? '');
$type     = htmlspecialchars($_GET['type']   ?? 'Quiz'); // 'Quiz' or 'Coding Exercise'
$isQuiz   = $type !== 'Coding Exercise';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CODEX | <?= htmlspecialchars($type) ?> — <?= $lessonId ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="dashboard.css">

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

        const LESSON_ID  = <?= json_encode($lessonId) ?>;
        const ASSESS_TYPE = <?= json_encode($type) ?>;
        const IS_QUIZ    = <?= json_encode($isQuiz) ?>;

        /* ── Quiz limit ─────────────────────────────────────────────── */
        const QUIZ_LIMIT = 10;
    </script>

    <style>
        /* ── Base ─────────────────────────────────────────────────────── */
        body { display: flex; margin: 0; background: #f1f5f9; font-family: 'Inter', sans-serif; }

        .db-main {
            flex-grow: 1; padding: 36px 40px; overflow-y: auto;
            height: 100vh; box-sizing: border-box;
        }

        /* ── Page header ─────────────────────────────────────────────── */
        .af-header {
            display: flex; justify-content: space-between; align-items: flex-start;
            margin-bottom: 28px;
        }
        .af-title h2 { margin: 0; font-size: 22px; font-weight: 800; color: #1e293b; }
        .af-title p  { margin: 4px 0 0; font-size: 13px; color: #94a3b8; }

        /* Difficulty badge */
        .diff-badge {
            display: inline-block; padding: 3px 14px; border-radius: 20px;
            font-size: 11px; font-weight: 800; margin-top: 8px;
        }
        .diff-badge.Beginner     { background: #fef9c3; color: #854d0e; border: 1px solid #fde68a; }
        .diff-badge.Intermediate { background: #dbeafe; color: #1d4ed8; border: 1px solid #93c5fd; }
        .diff-badge.Advanced     { background: #f3e8ff; color: #7e22ce; border: 1px solid #d8b4fe; }

        /* ── Quiz item counter badge ─────────────────────────────────── */
        .quiz-limit-badge {
            display: inline-flex; align-items: center; gap: 6px;
            background: #eff6ff; border: 1.5px solid #bfdbfe;
            color: #1d4ed8; border-radius: 20px;
            padding: 5px 14px; font-size: 12px; font-weight: 700;
            margin-top: 8px; margin-left: 10px;
        }
        .quiz-limit-badge i { font-size: 11px; }

        /* ── Questions container ──────────────────────────────────────── */
        .questions-wrap {
            background: white; border-radius: 14px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 2px 8px rgba(0,0,0,.05);
            overflow: hidden;
            margin-bottom: 16px;
        }

        /* Content Sub-ID header */
        .sub-id-bar {
            background: #f8fafc; border-bottom: 1px solid #e2e8f0;
            padding: 14px 24px; display: flex; align-items: center;
            justify-content: space-between;
        }
        .sub-id-badge {
            background: #e0f2fe; color: #0369a1;
            font-size: 12px; font-weight: 800; padding: 4px 14px;
            border-radius: 20px; letter-spacing: .3px;
        }
        .item-count-pill {
            font-size: 12px; font-weight: 700; color: #475569;
            background: #f1f5f9; border: 1px solid #e2e8f0;
            padding: 3px 12px; border-radius: 20px;
        }
        .item-count-pill.full { background: #fef9c3; border-color: #fde68a; color: #854d0e; }

        /* ── Question card ────────────────────────────────────────────── */
        .question-card {
            border-bottom: 1px solid #f1f5f9;
        }
        .question-card:last-child { border-bottom: none; }

        .question-header {
            display: flex; align-items: center; justify-content: space-between;
            padding: 18px 24px; cursor: pointer; background: #f8fafc;
            border-bottom: 1px solid #e2e8f0; user-select: none;
        }
        .question-num {
            font-size: 13px; font-weight: 800; color: #3b82f6; text-transform: uppercase;
            letter-spacing: .6px;
        }
        .question-header-right { display: flex; align-items: center; gap: 10px; }
        .btn-remove-q {
            background: none; border: 1.5px solid #fca5a5; color: #ef4444;
            padding: 5px 14px; border-radius: 20px; font-size: 12px;
            font-weight: 700; cursor: pointer; display: flex; align-items: center; gap: 5px;
        }
        .btn-remove-q:hover { background: #fee2e2; }
        .chevron-icon { color: #94a3b8; transition: transform .2s; }
        .question-card.collapsed .chevron-icon { transform: rotate(-90deg); }

        .question-body { padding: 24px 28px; }
        .question-card.collapsed .question-body { display: none; }

        /* ── Labels ──────────────────────────────────────────────────── */
        .field-label {
            font-size: 11px; font-weight: 800; color: #64748b;
            text-transform: uppercase; letter-spacing: .6px; margin-bottom: 8px;
        }

        /* ── Question text area ──────────────────────────────────────── */
        .question-textarea {
            width: 100%; min-height: 110px; padding: 14px 16px;
            border: 1.5px solid #e2e8f0; border-radius: 10px;
            font-size: 14px; font-family: inherit; resize: vertical;
            box-sizing: border-box; color: #1e293b; line-height: 1.7;
            background: white; transition: border-color .2s;
        }
        .question-textarea:focus { outline: none; border-color: #3b82f6; }
        .question-textarea::placeholder { color: #cbd5e1; }

        /* ── Multiple choice grid ────────────────────────────────────── */
        .mc-grid {
            display: grid; grid-template-columns: 1fr 1fr;
            gap: 12px; margin-top: 0;
        }

        .mc-choice {
            display: flex; align-items: center; gap: 0;
            border: 1.5px solid #e2e8f0; border-radius: 10px;
            background: white; overflow: hidden;
            transition: border-color .2s;
        }
        .mc-choice:focus-within { border-color: #3b82f6; }

        .mc-choice.selected {
            border-color: #16a34a;
            background: #f0fdf4;
        }
        .mc-choice.selected .mc-letter {
            background: #dcfce7;
            border-right-color: #bbf7d0;
            color: #15803d;
        }
        .mc-choice.selected .mc-input {
            color: #15803d;
            font-weight: 600;
        }

        .mc-letter {
            width: 36px; min-width: 36px; height: 100%;
            display: flex; align-items: center; justify-content: center;
            background: #f1f5f9; border-right: 1.5px solid #e2e8f0;
            font-size: 13px; font-weight: 800; color: #3b82f6; align-self: stretch;
            transition: background .2s, color .2s, border-color .2s;
        }

        .mc-input {
            flex: 1; border: none; padding: 13px 12px;
            font-size: 13px; font-family: inherit; background: transparent;
            color: #1e293b; min-width: 0; transition: color .2s;
        }
        .mc-input:focus { outline: none; }
        .mc-input::placeholder { color: #cbd5e1; }

        .mc-radio {
            width: 36px; min-width: 36px; display: flex;
            align-items: center; justify-content: center;
            cursor: pointer;
        }
        .mc-radio input[type=radio] { display: none; }
        .mc-radio-dot {
            width: 20px; height: 20px; border-radius: 50%;
            border: 2px solid #cbd5e1; background: white;
            transition: all .18s; flex-shrink: 0;
            display: flex; align-items: center; justify-content: center;
            position: relative;
        }
        .mc-radio-dot::after {
            content: ''; display: block; width: 0; height: 0;
            transition: all .15s;
        }
        .mc-radio input[type=radio]:checked + .mc-radio-dot {
            border-color: #16a34a; background: #16a34a;
        }
        .mc-radio input[type=radio]:checked + .mc-radio-dot::after {
            content: ''; display: block;
            width: 5px; height: 9px;
            border: 2px solid white;
            border-top: none; border-left: none;
            transform: rotate(45deg) translate(-1px, -1px);
        }

        /* ── Coding Exercise fields ───────────────────────────────────── */
        .ce-field { margin-bottom: 16px; }
        .ce-field label { display: block; }

        .input-field {
            width: 100%; padding: 10px 12px; border: 1.5px solid #e2e8f0;
            border-radius: 8px; font-size: 14px; box-sizing: border-box;
            font-family: inherit; background: white; transition: border-color .2s;
        }
        .input-field:focus { outline: none; border-color: #3b82f6; }

        .code-textarea {
            width: 100%; min-height: 140px; font-family: 'Courier New', monospace;
            font-size: 13px; padding: 12px; border: 1.5px solid #e2e8f0;
            border-radius: 8px; box-sizing: border-box; resize: vertical;
            background: #0f172a; color: #e2e8f0; line-height: 1.7;
        }
        .code-textarea:focus { outline: none; border-color: #0ea5e9; }
        .code-textarea::placeholder { color: #64748b; }

        .desc-textarea {
            width: 100%; min-height: 100px; padding: 12px;
            border: 1.5px solid #e2e8f0; border-radius: 8px;
            font-size: 13px; font-family: inherit; resize: vertical;
            box-sizing: border-box; background: white; color: #1e293b;
            line-height: 1.7;
        }
        .desc-textarea:focus { outline: none; border-color: #3b82f6; }
        .desc-textarea::placeholder { color: #cbd5e1; }

        /* ── Add question button ─────────────────────────────────────── */
        .add-question-btn {
            display: flex; align-items: center; justify-content: center; gap: 8px;
            width: 100%; padding: 16px;
            border: 2px dashed #cbd5e1; border-radius: 0 0 14px 14px;
            background: none; color: #64748b; font-size: 14px; font-weight: 700;
            cursor: pointer; transition: all .2s;
        }
        .add-question-btn:hover { border-color: #3b82f6; color: #3b82f6; background: #eff6ff; }
        .add-question-btn:disabled,
        .add-question-btn[disabled] {
            opacity: .4; cursor: not-allowed; pointer-events: none;
        }

        /* ── Limit notice bar ────────────────────────────────────────── */
        .limit-notice {
            display: flex; align-items: center; justify-content: center; gap: 8px;
            padding: 14px 20px; background: #fefce8;
            border-top: 1px solid #fef08a;
            font-size: 13px; font-weight: 600; color: #854d0e;
        }
        .limit-notice i { font-size: 14px; }

        /* ── Footer ──────────────────────────────────────────────────── */
        .footer-bar {
            position: fixed; bottom: 0; left: 0; right: 0;
            background: white; border-top: 1px solid #e2e8f0;
            padding: 14px 40px; display: flex;
            justify-content: flex-end; align-items: center; gap: 16px;
            z-index: 100; box-shadow: 0 -2px 12px rgba(0,0,0,.06);
        }

        .btn-cancel {
            background: none; border: 1.5px solid #ef4444; color: #ef4444;
            padding: 10px 30px; border-radius: 25px; font-weight: 700;
            font-size: 14px; cursor: pointer; transition: .2s;
        }
        .btn-cancel:hover { background: #fee2e2; }

        .btn-save {
            background: #001c30; color: white; border: none;
            padding: 10px 30px; border-radius: 25px; font-weight: 700;
            font-size: 14px; cursor: pointer; display: flex; align-items: center; gap: 8px;
            transition: background .2s;
        }
        .btn-save:hover { background: #0f3460; }
        .btn-save:disabled { opacity: .5; cursor: not-allowed; }

        /* ── Toast ───────────────────────────────────────────────────── */
        .toast {
            position: fixed; bottom: 80px; right: 30px;
            padding: 13px 22px; border-radius: 10px; font-size: 14px;
            font-weight: 600; color: white; z-index: 2000;
            transform: translateY(20px); opacity: 0;
            transition: all .3s; pointer-events: none;
        }
        .toast.show { transform: translateY(0); opacity: 1; }
        .toast.success { background: #16a34a; }
        .toast.error   { background: #dc2626; }

        .spinner {
            display: inline-block; width: 14px; height: 14px;
            border: 2px solid rgba(255,255,255,.3); border-top-color: white;
            border-radius: 50%; animation: spin .7s linear infinite;
        }
        @keyframes spin { to { transform: rotate(360deg); } }

        .db-main { flex-grow: 1; padding: 36px 40px 100px;
            overflow-y: auto; height: 100vh; box-sizing: border-box; }

        .page-loading {
            position: fixed; inset: 0; background: rgba(248,250,252,.92);
            display: flex; flex-direction: column; align-items: center;
            justify-content: center; z-index: 500; gap: 14px;
        }
        .page-loading p { color: #1e293b; font-weight: 600; font-size: 15px; }
        .page-loading.hidden { display: none; }

        /* ── Save confirmation modal ─────────────────────────────────── */
        .modal-overlay {
            position: fixed; inset: 0;
            background: rgba(15,23,42,.55);
            backdrop-filter: blur(3px);
            display: flex; align-items: center; justify-content: center;
            z-index: 900; opacity: 0; pointer-events: none;
            transition: opacity .25s;
        }
        .modal-overlay.show { opacity: 1; pointer-events: all; }

        .modal-card {
            background: white; border-radius: 20px;
            padding: 36px 40px 32px;
            max-width: 420px; width: 90%;
            text-align: center;
            box-shadow: 0 20px 60px rgba(0,0,0,.18);
            transform: scale(.93) translateY(12px);
            transition: transform .25s, opacity .25s;
            opacity: 0;
        }
        .modal-overlay.show .modal-card {
            transform: scale(1) translateY(0);
            opacity: 1;
        }

        .modal-icon {
            width: 64px; height: 64px; background: #dbeafe;
            border-radius: 18px; display: flex; align-items: center;
            justify-content: center; margin: 0 auto 20px;
        }
        .modal-icon i { font-size: 28px; color: #3b82f6; }

        .modal-title {
            font-size: 20px; font-weight: 800; color: #0f172a;
            margin: 0 0 10px;
        }
        .modal-subtitle {
            font-size: 13px; color: #64748b; line-height: 1.6;
            margin: 0 0 28px;
        }
        .modal-subtitle strong { color: #1e293b; }

        .modal-actions {
            display: flex; gap: 12px; justify-content: center;
        }
        .modal-btn-cancel {
            flex: 1; background: none; border: 1.5px solid #e2e8f0;
            color: #64748b; padding: 11px 20px; border-radius: 25px;
            font-size: 14px; font-weight: 700; cursor: pointer;
            transition: .2s; font-family: inherit;
        }
        .modal-btn-cancel:hover { border-color: #ef4444; color: #ef4444; background: #fff5f5; }

        .modal-btn-save {
            flex: 1; background: #0f172a; color: white; border: none;
            padding: 11px 20px; border-radius: 25px;
            font-size: 14px; font-weight: 700; cursor: pointer;
            display: flex; align-items: center; justify-content: center; gap: 8px;
            transition: background .2s; font-family: inherit;
        }
        .modal-btn-save:hover { background: #1e3a5f; }
        .modal-btn-save:disabled { opacity: .6; cursor: not-allowed; }

        @media (max-width: 700px) {
            .mc-grid { grid-template-columns: 1fr; }
            .db-main { padding: 20px 16px 90px; }
            .modal-card { padding: 28px 22px 24px; }
        }
    </style>
</head>
<body>

<!-- Loading overlay (shown while fetching existing data) -->
<div class="page-loading hidden" id="pageLoading">
    <span class="spinner" style="width:28px;height:28px;border-width:3px;border-color:#dbeafe;border-top-color:#3b82f6;"></span>
    <p>Loading assessment…</p>
</div>

<!-- ══ SIDEBAR ════════════════════════════════════════════════════════════ -->
<?php
    $activePage = 'content_management';
    include 'sidebar.php';
?>

<!-- ══ MAIN ════════════════════════════════════════════════════════════════ -->
<main class="db-main">

    <!-- Page header -->
    <div class="af-header">
        <div class="af-title">
            <h2><?= $isQuiz ? 'Quiz' : 'Coding Exercise' ?> — <span id="lessonTitleSpan"><?= $lessonId ?></span></h2>
            <p><?= $isQuiz
                ? 'This quiz is fixed to exactly 10 questions.'
                : 'Add coding exercises for this lesson.' ?>
            </p>
            <span class="diff-badge" id="diffBadge">Loading…</span>
            <?php if ($isQuiz): ?>
            <span class="quiz-limit-badge" id="quizCountBadge">
                <i class="fa-solid fa-list-ol"></i>
                <span id="quizCountText">0 / 10 questions</span>
            </span>
            <?php endif; ?>
        </div>
    </div>

    <!-- ── QUIZ UI ───────────────────────────────────────────────────────── -->
    <?php if ($isQuiz): ?>
    <div class="questions-wrap" id="questionsWrap">
        <div class="sub-id-bar">
            <span class="sub-id-badge">Content Sub-ID: Q1</span>
            <span class="item-count-pill" id="quizPillCount">0 / 10</span>
        </div>
        <div id="questionsContainer">
            <!-- Rendered by JS -->
        </div>
        <button class="add-question-btn" id="addQuestionBtn" onclick="addQuestion()" style="display:none;">
            <i class="fa-solid fa-plus"></i> Add question
        </button>
        <div class="limit-notice" id="quizLimitNotice" style="display:none;">
            <i class="fa-solid fa-lock"></i>
            Quiz is fixed to exactly 10 questions — all slots are filled.
        </div>
    </div>

    <!-- ── CODING EXERCISE UI ────────────────────────────────────────────── -->
    <?php else: ?>
    <div class="questions-wrap" id="questionsWrap">
        <div class="sub-id-bar">
            <span class="sub-id-badge">Content Sub-ID: CE1</span>
        </div>
        <div id="questionsContainer">
            <!-- Rendered by JS -->
        </div>
        <button class="add-question-btn" onclick="addExercise()">
            <i class="fa-solid fa-plus"></i> Add exercise
        </button>
    </div>
    <?php endif; ?>

</main>

<!-- ══ SAVE CONFIRMATION MODAL ═════════════════════════════════════════════ -->
<div class="modal-overlay" id="saveModal">
    <div class="modal-card">
        <div class="modal-icon">
            <i class="fa-solid fa-floppy-disk"></i>
        </div>
        <h2 class="modal-title" id="modalTitle">Save this quiz?</h2>
        <p class="modal-subtitle" id="modalSubtitle">Your assessment will be saved.</p>
        <div class="modal-actions">
            <button class="modal-btn-cancel" onclick="closeModal()">Cancel</button>
            <button class="modal-btn-save" id="modalSaveBtn" onclick="confirmSave()">
                <i class="fa-solid fa-check"></i> Save
            </button>
        </div>
    </div>
</div>

<!-- ══ FIXED FOOTER ════════════════════════════════════════════════════════ -->
<div class="footer-bar">
    <button class="btn-cancel" onclick="window.location.href='content_management.php'">
        <i class="fa-solid fa-xmark" style="margin-right:4px;"></i> Cancel
    </button>
    <button class="btn-save" id="saveBtn" onclick="openSaveModal()">
        <i class="fa-solid fa-floppy-disk"></i>
        <?= $isQuiz ? 'Save Quiz' : 'Save Exercises' ?>
    </button>
</div>

<div class="toast" id="toast"></div>

<!-- ══ JAVASCRIPT ══════════════════════════════════════════════════════════ -->
<script>
/* ─── Globals ───────────────────────────────────────────────────────────── */
let questionCounter  = 0;
let lessonDifficulty = '';

/* ─── Helpers ───────────────────────────────────────────────────────────── */
function encodeText(text) {
    return (text || '').replace(/ /g, '&nbsp;').replace(/\n/g, '<br/>');
}
function decodeText(encoded) {
    return (encoded || '').replace(/&nbsp;/g, ' ').replace(/<br\/>/g, '\n').replace(/<br>/g, '\n');
}
function showToast(msg, type = 'success') {
    const el = document.getElementById('toast');
    el.textContent = msg;
    el.className = `toast ${type} show`;
    setTimeout(() => el.classList.remove('show'), 3500);
}

/* ─── Update quiz count display ─────────────────────────────────────────── */
function updateQuizCountDisplay() {
    if (!IS_QUIZ) return;
    const count  = document.querySelectorAll('#questionsContainer .question-card').length;
    const pill   = document.getElementById('quizPillCount');
    const text   = document.getElementById('quizCountText');
    const notice = document.getElementById('quizLimitNotice');

    if (pill)   { pill.textContent = `${count} / ${QUIZ_LIMIT}`; pill.className = count >= QUIZ_LIMIT ? 'item-count-pill full' : 'item-count-pill'; }
    if (text)   text.textContent = `${count} / ${QUIZ_LIMIT} questions`;
    if (notice) notice.style.display = count >= QUIZ_LIMIT ? 'flex' : 'none';
}

/* ─── Boot: load lesson info and existing assessment ────────────────────── */
document.addEventListener('DOMContentLoaded', async () => {
    if (!LESSON_ID) {
        showToast('No lesson ID provided.', 'error');
        return;
    }

    // Load lesson info (title + difficulty)
    try {
        const db = firebase.database();
        const snap = await db.ref('Lessons/' + LESSON_ID).once('value');
        const lesson = snap.val() || {};
        const diff = lesson.difficulty || '';
        lessonDifficulty = diff;

        const badge = document.getElementById('diffBadge');
        badge.textContent = diff || 'Unknown';
        badge.className = 'diff-badge ' + diff;

        const titleEl = document.getElementById('lessonTitleSpan');
        if (lesson.main_title) {
            const tmp = document.createElement('div');
            tmp.innerHTML = lesson.main_title;
            titleEl.textContent = tmp.textContent.trim() || LESSON_ID;
        }
    } catch (e) { /* non-fatal */ }

    // Load existing assessment
    try {
        document.getElementById('pageLoading').classList.remove('hidden');
        const db = firebase.database();

        if (IS_QUIZ) {
            const snap = await db.ref('assessment/' + LESSON_ID + '/Quiz').once('value');
            const existing = snap.val();
            let loadedCount = 0;

            if (existing && typeof existing === 'object') {
                const entries = Object.entries(existing);
                entries.slice(0, QUIZ_LIMIT).forEach(([key, q]) => {
                    addQuestion(key, q);
                    loadedCount++;
                });
            }

            // Pad with empty questions up to QUIZ_LIMIT
            while (loadedCount < QUIZ_LIMIT) {
                addQuestion();
                loadedCount++;
            }

        } else {
            const snap = await db.ref('coding_exercises/' + LESSON_ID).once('value');
            const existing = snap.val();
            if (Array.isArray(existing)) {
                const exercises = existing.slice(1).filter(Boolean);
                if (exercises.length > 0) {
                    exercises.forEach(ex => addExercise(ex));
                } else {
                    addExercise();
                }
            } else {
                addExercise();
            }
        }
    } catch (e) {
        showToast('Error loading assessment: ' + e.message, 'error');
        if (IS_QUIZ) {
            const current = document.querySelectorAll('#questionsContainer .question-card').length;
            for (let i = current; i < QUIZ_LIMIT; i++) addQuestion();
        } else {
            addExercise();
        }
    } finally {
        document.getElementById('pageLoading').classList.add('hidden');
        if (IS_QUIZ) updateQuizCountDisplay();
    }
});

/* ═══════════════════════════════════════════════════════════════════════════
   QUIZ SECTION
═══════════════════════════════════════════════════════════════════════════ */
function addQuestion(existingKey, existing) {
    if (IS_QUIZ) {
        const current = document.querySelectorAll('#questionsContainer .question-card').length;
        if (current >= QUIZ_LIMIT) {
            updateQuizCountDisplay();
            return;
        }
    }

    questionCounter++;
    const n   = questionCounter;
    const qId = 'q_' + n;

    const container = document.getElementById('questionsContainer');

    const html = `
    <div class="question-card" id="${qId}" data-push-key="${existingKey || ''}">
        <div class="question-header" onclick="toggleQuestion('${qId}')">
            <span class="question-num">Question ${n}</span>
            <div class="question-header-right">
                <i class="fa-solid fa-chevron-down chevron-icon"></i>
            </div>
        </div>
        <div class="question-body">
            <div class="field-label" style="margin-bottom:6px;">Question Text</div>
            <textarea class="question-textarea" id="${qId}_text"
                placeholder="Type question here…"></textarea>

            <div class="field-label" style="margin:20px 0 10px;">Multiple Choice Answer</div>
            <div class="mc-grid">
                ${['A','B','C','D'].map(letter => `
                <div class="mc-choice" id="${qId}_choice${letter}">
                    <div class="mc-letter">${letter}</div>
                    <input class="mc-input" id="${qId}_choice${letter}_input"
                           placeholder="Type answer here…">
                    <label class="mc-radio" title="Mark as correct answer">
                        <input type="radio" name="${qId}_answer" value="${letter}"
                               onchange="markCorrect('${qId}','${letter}')">
                        <div class="mc-radio-dot"></div>
                    </label>
                </div>`).join('')}
            </div>
        </div>
    </div>`;

    container.insertAdjacentHTML('beforeend', html);

    if (existing) {
        const qText = document.getElementById(qId + '_text');
        if (qText) qText.value = decodeText(existing.question || '');

        ['A','B','C','D'].forEach(letter => {
            const inp = document.getElementById(qId + '_choice' + letter + '_input');
            if (inp) inp.value = decodeText(existing['choice' + letter] || '');
        });

        if (existing.answer) {
            const radio = document.querySelector(`input[name="${qId}_answer"][value="${existing.answer}"]`);
            if (radio) {
                radio.checked = true;
                markCorrect(qId, existing.answer);
            }
        }
    }

    if (IS_QUIZ) updateQuizCountDisplay();
}

function toggleQuestion(qId) {
    document.getElementById(qId)?.classList.toggle('collapsed');
}

function markCorrect(qId, letter) {
    ['A','B','C','D'].forEach(l => {
        document.getElementById(qId + '_choice' + l)?.classList.remove('selected');
    });
    document.getElementById(qId + '_choice' + letter)?.classList.add('selected');
}

/* ═══════════════════════════════════════════════════════════════════════════
   CODING EXERCISE SECTION
═══════════════════════════════════════════════════════════════════════════ */
function addExercise(existing) {
    questionCounter++;
    const n    = questionCounter;
    const exId = 'ex_' + n;

    const container = document.getElementById('questionsContainer');

    const html = `
    <div class="question-card" id="${exId}">
        <div class="question-header" onclick="toggleQuestion('${exId}')">
            <span class="question-num">Exercise ${n}</span>
            <div class="question-header-right">
                ${n > 1 ? `<button class="btn-remove-q" onclick="removeExercise(event,'${exId}')">
                    <i class="fa-solid fa-trash-can"></i> Remove
                </button>` : ''}
                <i class="fa-solid fa-chevron-down chevron-icon"></i>
            </div>
        </div>
        <div class="question-body">
            <div class="ce-field">
                <div class="field-label" style="margin-bottom:6px;">Title</div>
                <input type="text" class="input-field" id="${exId}_title" placeholder="e.g. First Java Program">
            </div>
            <div class="ce-field">
                <div class="field-label" style="margin-bottom:6px;">Instructions / Description</div>
                <textarea class="desc-textarea" id="${exId}_desc"
                    placeholder="Describe what the learner should do…"></textarea>
            </div>
            <div class="ce-field">
                <div class="field-label" style="margin-bottom:6px;">Starter Code (with bug / incomplete)</div>
                <textarea class="code-textarea" id="${exId}_code"
                    placeholder="// Paste the starter code here…"></textarea>
            </div>
            <div class="ce-field">
                <div class="field-label" style="margin-bottom:6px;">Expected Output</div>
                <input type="text" class="input-field" id="${exId}_output"
                    placeholder="e.g. Hello World!">
            </div>
        </div>
    </div>`;

    container.insertAdjacentHTML('beforeend', html);

    if (existing) {
        const t = document.getElementById(exId + '_title');
        if (t) t.value = decodeText(existing.title || '');
        const d = document.getElementById(exId + '_desc');
        if (d) d.value = decodeText(existing.description || '').replace(/<b>Instructions:<\/b><br>/,'').replace(/<br><br><b>Hints:<\/b><br>/,'\n\nHints:\n');
        const c = document.getElementById(exId + '_code');
        if (c) c.value = decodeText(existing.code || '');
        const o = document.getElementById(exId + '_output');
        if (o) o.value = existing.expectedOutput || '';
    }
}

function removeExercise(event, exId) {
    event.stopPropagation();
    const el = document.getElementById(exId);
    if (!el) return;
    el.style.opacity = '0';
    el.style.transition = 'opacity .3s';
    setTimeout(() => {
        el.remove();
        renumberExercises();
    }, 300);
}

function renumberExercises() {
    document.querySelectorAll('#questionsContainer .question-card').forEach((card, i) => {
        const numEl = card.querySelector('.question-num');
        if (numEl) numEl.textContent = 'Exercise ' + (i + 1);
    });
}

/* ═══════════════════════════════════════════════════════════════════════════
   SAVE MODAL
═══════════════════════════════════════════════════════════════════════════ */
function openSaveModal() {
    const diff  = lessonDifficulty ? ` (${lessonDifficulty})` : '';
    const label = IS_QUIZ ? 'Quiz' : 'Coding Exercise';

    let count, unit;
    if (IS_QUIZ) {
        count = document.querySelectorAll('#questionsContainer .question-card').length;
        unit  = 'question(s)';
    } else {
        unit  = 'exercise(s)';
        count = 0;
        document.querySelectorAll('#questionsContainer .question-card').forEach(card => {
            const exId   = card.id;
            const title  = (document.getElementById(exId + '_title')?.value || '').trim();
            const desc   = (document.getElementById(exId + '_desc')?.value  || '').trim();
            const code   = (document.getElementById(exId + '_code')?.value  || '').trim();
            const output = (document.getElementById(exId + '_output')?.value || '').trim();
            if (title || desc || code || output) count++;
        });
    }

    document.getElementById('modalTitle').textContent = `Save this ${IS_QUIZ ? 'quiz' : 'coding exercise'}?`;
    document.getElementById('modalSubtitle').innerHTML =
        `<strong>${label} ${LESSON_ID}${diff}</strong> with <strong>${count} ${unit}</strong> will be saved.` +
        (IS_QUIZ ? `<br><small style="color:#94a3b8;font-size:11px;margin-top:6px;display:block;">Learner quiz results will be automatically re-scored against the updated answers.</small>` : '');

    document.getElementById('saveModal').classList.add('show');
}

function closeModal() {
    document.getElementById('saveModal').classList.remove('show');
}

// Close on overlay click
document.getElementById('saveModal').addEventListener('click', function(e) {
    if (e.target === this) closeModal();
});

/* ═══════════════════════════════════════════════════════════════════════════
   confirmSave — saves assessment, then re-scores quiz learners if IS_QUIZ
   ═══════════════════════════════════════════════════════════════════════════ */
async function confirmSave() {
    const modalBtn = document.getElementById('modalSaveBtn');
    modalBtn.disabled = true;
    modalBtn.innerHTML = '<span class="spinner"></span> Saving…';

    try {
        const db = firebase.database();

        if (IS_QUIZ) {
            /* ── Step 1: Write quiz questions ── */
            const savedKeys = await saveQuiz(db);

            /* ── Step 2: Re-score every learner who took this quiz ── */
            modalBtn.innerHTML = '<span class="spinner"></span> Re-scoring learners…';
            const reevalCount = await reEvaluateQuizLearners(db, savedKeys);

            closeModal();
            const msg = reevalCount > 0
                ? `Quiz saved! Re-scored ${reevalCount} learner submission(s).`
                : 'Quiz saved successfully!';
            showToast(msg, 'success');

        } else {
            await saveCodingExercises(db);
            closeModal();
            showToast('Assessment saved successfully!', 'success');
        }

        setTimeout(() => { window.location.href = 'content_management.php'; }, 1400);

    } catch (e) {
        closeModal();
        showToast('Error saving: ' + e.message, 'error');
        console.error(e);
    } finally {
        modalBtn.disabled = false;
        modalBtn.innerHTML = '<i class="fa-solid fa-check"></i> Save';
    }
}

/* ═══════════════════════════════════════════════════════════════════════════
   saveQuiz — validates, writes each question, returns ordered correct answers
   Returns: array of correct answer letters in question order (e.g. ['C','B',...])
   ═══════════════════════════════════════════════════════════════════════════ */
async function saveQuiz(db) {
    const cards = document.querySelectorAll('#questionsContainer .question-card');

    if (cards.length !== QUIZ_LIMIT) {
        const msg = `Quiz must have exactly ${QUIZ_LIMIT} questions (currently ${cards.length}).`;
        showToast(msg, 'error');
        throw new Error(msg);
    }

    const errors  = [];
    const toSave  = [];

    cards.forEach((card, i) => {
        const qId   = card.id;
        const num   = i + 1;
        const text  = (document.getElementById(qId + '_text')?.value || '').trim();
        const choiceA = (document.getElementById(qId + '_choiceA_input')?.value || '').trim();
        const choiceB = (document.getElementById(qId + '_choiceB_input')?.value || '').trim();
        const choiceC = (document.getElementById(qId + '_choiceC_input')?.value || '').trim();
        const choiceD = (document.getElementById(qId + '_choiceD_input')?.value || '').trim();
        const radio   = document.querySelector(`input[name="${qId}_answer"]:checked`);

        if (!text)    errors.push(`Q${num}: Question text is required.`);
        if (!choiceA || !choiceB || !choiceC || !choiceD) errors.push(`Q${num}: All four choices are required.`);
        if (!radio)   errors.push(`Q${num}: Please mark the correct answer.`);

        const pushKey = card.dataset.pushKey || null;
        toSave.push({
            key    : pushKey,
            answer : radio?.value || 'A',
            data   : {
                question: encodeText(text),
                choiceA : encodeText(choiceA),
                choiceB : encodeText(choiceB),
                choiceC : encodeText(choiceC),
                choiceD : encodeText(choiceD),
                answer  : radio?.value || 'A',
            }
        });
    });

    if (errors.length) { showToast(errors[0], 'error'); throw new Error(errors[0]); }

    const basePath = 'assessment/' + LESSON_ID + '/Quiz/';

    // ── Write to assessment/{lessonId}/Quiz/ and collect used keys ──────────
    const correctAnswers = [];
    const usedKeys = [];

    for (const item of toSave) {
        let savedKey;
        if (item.key) {
            await db.ref(basePath + item.key).set(item.data);
            savedKey = item.key;
        } else {
            const ref = await db.ref(basePath).push(item.data);
            savedKey = ref.key;
        }
        correctAnswers.push(item.answer);
        usedKeys.push({ key: savedKey, data: item.data });
    }

    // ── Dual-write to quizQuestions/{difficulty}/{lessonId}/ ────────────────
    // lessonDifficulty is set during DOMContentLoaded from the Lessons node.
    if (lessonDifficulty) {
        const qqPath = `quizQuestions/${lessonDifficulty}/${LESSON_ID}/`;

        // Clear the existing subtree first so stale questions are removed.
        await db.ref(qqPath).remove();

        // Re-write using the same push keys that assessment/ now uses,
        // so both trees stay in sync.
        for (const { key, data } of usedKeys) {
            await db.ref(qqPath + key).set(data);
        }
    }

    return correctAnswers;
}

/* ═══════════════════════════════════════════════════════════════════════════
   reEvaluateQuizLearners
   ─────────────────────────────────────────────────────────────────────────
   Fetches every quizResult for this lesson and re-scores it against the
   newly saved correct answers. Updates score, total, and passed in one
   batched db.update() call.

   correctAnswers: string[] — ordered array of correct letters, matching
   the 0-based index of quizResults[uid][lessonId].answers
   ═══════════════════════════════════════════════════════════════════════════ */
async function reEvaluateQuizLearners(db, correctAnswers) {
    // Re-read the quiz from Firebase to get the authoritative ordered answer key.
    // This handles edge cases where new push() keys changed the order.
    const quizSnap  = await db.ref('assessment/' + LESSON_ID + '/Quiz').once('value');
    const quizData  = quizSnap.val();

    // Build ordered correct-answer array from Firebase (source of truth)
    const authoritative = [];
    if (quizData && typeof quizData === 'object') {
        Object.values(quizData).forEach(q => {
            authoritative.push((q.answer || '').toUpperCase());
        });
    }

    // Fall back to the in-memory array if Firebase read returned nothing
    const answerKey = authoritative.length === QUIZ_LIMIT ? authoritative : correctAnswers;

    // Fetch all quiz results for this lesson
    const resultsSnap = await db.ref('quizResults').once('value');
    const allResults  = resultsSnap.val() || {};

    const updates = {};
    let touchedCount = 0;

    for (const uid of Object.keys(allResults)) {
        const lessonResult = allResults[uid]?.[LESSON_ID];
        if (!lessonResult) continue;

        // answers may be stored as array or object keyed by string index
        const storedAnswers = lessonResult.answers;
        if (!storedAnswers) continue;

        const answersArr = Array.isArray(storedAnswers)
            ? storedAnswers
            : Object.values(storedAnswers);

        // Re-calculate score
        let newScore = 0;
        const total  = answerKey.length;

        answersArr.forEach((userAnswer, i) => {
            if (i < total && userAnswer && userAnswer.toUpperCase() === answerKey[i]) {
                newScore++;
            }
        });

        const newPercent = total > 0 ? Math.round((newScore / total) * 100) : 0;
        const newPassed  = newPercent >= 50 ? 'Passed' : 'Failed';

        const basePath = `quizResults/${uid}/${LESSON_ID}/`;
        updates[basePath + 'score']  = newScore;
        updates[basePath + 'total']  = total;
        updates[basePath + 'passed'] = newPassed;

        touchedCount++;
    }

    // Write all score updates atomically
    if (Object.keys(updates).length > 0) {
        await db.ref().update(updates);
    }

    return touchedCount;
}

async function saveCodingExercises(db) {
    const cards     = document.querySelectorAll('#questionsContainer .question-card');
    const exercises = [null];

    cards.forEach((card) => {
        const exId   = card.id;
        const title  = (document.getElementById(exId + '_title')?.value || '').trim();
        const desc   = (document.getElementById(exId + '_desc')?.value  || '').trim();
        const code   = (document.getElementById(exId + '_code')?.value  || '').trim();
        const output = (document.getElementById(exId + '_output')?.value || '').trim();

        if (!title && !desc && !code && !output) return;

        exercises.push({
            title         : title,
            description   : `<b>Instructions:</b><br>${encodeText(desc)}`,
            code          : encodeText(code),
            expectedOutput: output,
            language      : 'java',
            versionIndex  : '4',
        });
    });

    if (exercises.length > 1) {
        await db.ref('coding_exercises/' + LESSON_ID).set(exercises);
    }
}
</script>
</body>
</html>