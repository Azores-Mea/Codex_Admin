<?php
$lessonId = htmlspecialchars($_GET['lesson'] ?? '');
$type     = htmlspecialchars($_GET['type']   ?? 'Quiz');
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

    

    <style>
        body { display: flex; margin: 0; background: #f1f5f9; font-family: 'Inter', sans-serif; }

        .db-main { flex-grow: 1; padding: 36px 40px 100px; overflow-y: auto; height: 100vh; box-sizing: border-box; }

        .af-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 28px; }
        .af-title h2 { margin: 0; font-size: 22px; font-weight: 800; color: #1e293b; }
        .af-title p  { margin: 4px 0 0; font-size: 13px; color: #94a3b8; }

        .diff-badge { display: inline-block; padding: 3px 14px; border-radius: 20px; font-size: 11px; font-weight: 800; margin-top: 8px; }
        .diff-badge.Beginner     { background: #fef9c3; color: #854d0e; border: 1px solid #fde68a; }
        .diff-badge.Intermediate { background: #dbeafe; color: #1d4ed8; border: 1px solid #93c5fd; }
        .diff-badge.Advanced     { background: #f3e8ff; color: #7e22ce; border: 1px solid #d8b4fe; }

        .quiz-limit-badge {
            display: inline-flex; align-items: center; gap: 6px;
            background: #eff6ff; border: 1.5px solid #bfdbfe; color: #1d4ed8;
            border-radius: 20px; padding: 5px 14px; font-size: 12px; font-weight: 700;
            margin-top: 8px; margin-left: 10px;
        }

        .questions-wrap { background: white; border-radius: 14px; border: 1px solid #e2e8f0; box-shadow: 0 2px 8px rgba(0,0,0,.05); overflow: hidden; margin-bottom: 16px; }

        .sub-id-bar { background: #f8fafc; border-bottom: 1px solid #e2e8f0; padding: 14px 24px; display: flex; align-items: center; justify-content: space-between; }
        .sub-id-badge { background: #e0f2fe; color: #0369a1; font-size: 12px; font-weight: 800; padding: 4px 14px; border-radius: 20px; }
        .item-count-pill { font-size: 12px; font-weight: 700; color: #475569; background: #f1f5f9; border: 1px solid #e2e8f0; padding: 3px 12px; border-radius: 20px; }
        .item-count-pill.full { background: #fef9c3; border-color: #fde68a; color: #854d0e; }

        .question-card { border-bottom: 1px solid #f1f5f9; }
        .question-card:last-child { border-bottom: none; }

        .question-header { display: flex; align-items: center; justify-content: space-between; padding: 18px 24px; cursor: pointer; background: #f8fafc; border-bottom: 1px solid #e2e8f0; user-select: none; }
        .question-num { font-size: 13px; font-weight: 800; color: #3b82f6; text-transform: uppercase; letter-spacing: .6px; }
        .question-header-right { display: flex; align-items: center; gap: 10px; }
        .btn-remove-q { background: none; border: 1.5px solid #fca5a5; color: #ef4444; padding: 5px 14px; border-radius: 20px; font-size: 12px; font-weight: 700; cursor: pointer; display: flex; align-items: center; gap: 5px; }
        .btn-remove-q:hover { background: #fee2e2; }
        .chevron-icon { color: #94a3b8; transition: transform .2s; }
        .question-card.collapsed .chevron-icon { transform: rotate(-90deg); }
        .question-body { padding: 24px 28px; }
        .question-card.collapsed .question-body { display: none; }

        .field-label { font-size: 11px; font-weight: 800; color: #64748b; text-transform: uppercase; letter-spacing: .6px; margin-bottom: 8px; }

        .question-textarea { width: 100%; min-height: 110px; padding: 14px 16px; border: 1.5px solid #e2e8f0; border-radius: 10px; font-size: 14px; font-family: inherit; resize: vertical; box-sizing: border-box; color: #1e293b; line-height: 1.7; background: white; transition: border-color .2s; }
        .question-textarea:focus { outline: none; border-color: #3b82f6; }
        .question-textarea::placeholder { color: #cbd5e1; }

        .mc-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }
        .mc-choice { display: flex; align-items: center; border: 1.5px solid #e2e8f0; border-radius: 10px; background: white; overflow: hidden; transition: border-color .2s; }
        .mc-choice:focus-within { border-color: #3b82f6; }
        .mc-choice.selected { border-color: #16a34a; background: #f0fdf4; }
        .mc-choice.selected .mc-letter { background: #dcfce7; border-right-color: #bbf7d0; color: #15803d; }
        .mc-choice.selected .mc-input  { color: #15803d; font-weight: 600; }
        .mc-letter { width: 36px; min-width: 36px; display: flex; align-items: center; justify-content: center; background: #f1f5f9; border-right: 1.5px solid #e2e8f0; font-size: 13px; font-weight: 800; color: #3b82f6; align-self: stretch; transition: background .2s, color .2s, border-color .2s; }
        .mc-input { flex: 1; border: none; padding: 13px 12px; font-size: 13px; font-family: inherit; background: transparent; color: #1e293b; min-width: 0; }
        .mc-input:focus { outline: none; }
        .mc-input::placeholder { color: #cbd5e1; }
        .mc-radio { width: 36px; min-width: 36px; display: flex; align-items: center; justify-content: center; cursor: pointer; }
        .mc-radio input[type=radio] { display: none; }
        .mc-radio-dot { width: 20px; height: 20px; border-radius: 50%; border: 2px solid #cbd5e1; background: white; transition: all .18s; display: flex; align-items: center; justify-content: center; position: relative; }
        .mc-radio-dot::after { content: ''; display: block; width: 0; height: 0; transition: all .15s; }
        .mc-radio input[type=radio]:checked + .mc-radio-dot { border-color: #16a34a; background: #16a34a; }
        .mc-radio input[type=radio]:checked + .mc-radio-dot::after { content: ''; display: block; width: 5px; height: 9px; border: 2px solid white; border-top: none; border-left: none; transform: rotate(45deg) translate(-1px, -1px); }

        .ce-field { margin-bottom: 16px; }
        .input-field { width: 100%; padding: 10px 12px; border: 1.5px solid #e2e8f0; border-radius: 8px; font-size: 14px; box-sizing: border-box; font-family: inherit; background: white; transition: border-color .2s; }
        .input-field:focus { outline: none; border-color: #3b82f6; }
        .code-textarea { width: 100%; min-height: 140px; font-family: 'Courier New', monospace; font-size: 13px; padding: 12px; border: 1.5px solid #e2e8f0; border-radius: 8px; box-sizing: border-box; resize: vertical; background: #0f172a; color: #e2e8f0; line-height: 1.7; }
        .code-textarea:focus { outline: none; border-color: #0ea5e9; }
        .code-textarea::placeholder { color: #64748b; }
        .desc-textarea { width: 100%; min-height: 100px; padding: 12px; border: 1.5px solid #e2e8f0; border-radius: 8px; font-size: 13px; font-family: inherit; resize: vertical; box-sizing: border-box; background: white; color: #1e293b; line-height: 1.7; }
        .desc-textarea:focus { outline: none; border-color: #3b82f6; }
        .desc-textarea::placeholder { color: #cbd5e1; }

        .add-question-btn { display: flex; align-items: center; justify-content: center; gap: 8px; width: 100%; padding: 16px; border: 2px dashed #cbd5e1; border-radius: 0 0 14px 14px; background: none; color: #64748b; font-size: 14px; font-weight: 700; cursor: pointer; transition: all .2s; }
        .add-question-btn:hover { border-color: #3b82f6; color: #3b82f6; background: #eff6ff; }

        .limit-notice { display: flex; align-items: center; justify-content: center; gap: 8px; padding: 14px 20px; background: #fefce8; border-top: 1px solid #fef08a; font-size: 13px; font-weight: 600; color: #854d0e; }

        .footer-bar { position: fixed; bottom: 0; left: 0; right: 0; background: white; border-top: 1px solid #e2e8f0; padding: 14px 40px; display: flex; justify-content: flex-end; align-items: center; gap: 16px; z-index: 100; box-shadow: 0 -2px 12px rgba(0,0,0,.06); }
        .btn-cancel { background: none; border: 1.5px solid #ef4444; color: #ef4444; padding: 10px 30px; border-radius: 25px; font-weight: 700; font-size: 14px; cursor: pointer; transition: .2s; }
        .btn-cancel:hover { background: #fee2e2; }
        .btn-save { background: #001c30; color: white; border: none; padding: 10px 30px; border-radius: 25px; font-weight: 700; font-size: 14px; cursor: pointer; display: flex; align-items: center; gap: 8px; transition: background .2s; }
        .btn-save:hover { background: #0f3460; }
        .btn-save:disabled { opacity: .5; cursor: not-allowed; }

        .toast { position: fixed; bottom: 80px; right: 30px; padding: 13px 22px; border-radius: 10px; font-size: 14px; font-weight: 600; color: white; z-index: 2000; transform: translateY(20px); opacity: 0; transition: all .3s; pointer-events: none; }
        .toast.show { transform: translateY(0); opacity: 1; }
        .toast.success { background: #16a34a; }
        .toast.error   { background: #dc2626; }

        .spinner { display: inline-block; width: 14px; height: 14px; border: 2px solid rgba(255,255,255,.3); border-top-color: white; border-radius: 50%; animation: spin .7s linear infinite; }
        @keyframes spin { to { transform: rotate(360deg); } }

        .page-loading { position: fixed; inset: 0; background: rgba(248,250,252,.92); display: flex; flex-direction: column; align-items: center; justify-content: center; z-index: 500; gap: 14px; }
        .page-loading p { color: #1e293b; font-weight: 600; font-size: 15px; }
        .page-loading.hidden { display: none; }

        .modal-overlay { position: fixed; inset: 0; background: rgba(15,23,42,.55); backdrop-filter: blur(3px); display: flex; align-items: center; justify-content: center; z-index: 900; opacity: 0; pointer-events: none; transition: opacity .25s; }
        .modal-overlay.show { opacity: 1; pointer-events: all; }
        .modal-card { background: white; border-radius: 20px; padding: 36px 40px 32px; max-width: 420px; width: 90%; text-align: center; box-shadow: 0 20px 60px rgba(0,0,0,.18); transform: scale(.93) translateY(12px); transition: transform .25s, opacity .25s; opacity: 0; }
        .modal-overlay.show .modal-card { transform: scale(1) translateY(0); opacity: 1; }
        .modal-icon { width: 64px; height: 64px; background: #dbeafe; border-radius: 18px; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px; }
        .modal-icon i { font-size: 28px; color: #3b82f6; }
        .modal-title { font-size: 20px; font-weight: 800; color: #0f172a; margin: 0 0 10px; }
        .modal-subtitle { font-size: 13px; color: #64748b; line-height: 1.6; margin: 0 0 28px; }
        .modal-subtitle strong { color: #1e293b; }
        .modal-actions { display: flex; gap: 12px; justify-content: center; }
        .modal-btn-cancel { flex: 1; background: none; border: 1.5px solid #e2e8f0; color: #64748b; padding: 11px 20px; border-radius: 25px; font-size: 14px; font-weight: 700; cursor: pointer; transition: .2s; font-family: inherit; }
        .modal-btn-cancel:hover { border-color: #ef4444; color: #ef4444; background: #fff5f5; }
        .modal-btn-save { flex: 1; background: #0f172a; color: white; border: none; padding: 11px 20px; border-radius: 25px; font-size: 14px; font-weight: 700; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px; transition: background .2s; font-family: inherit; }
        .modal-btn-save:hover { background: #1e3a5f; }
        .modal-btn-save:disabled { opacity: .6; cursor: not-allowed; }

        @media (max-width: 700px) { .mc-grid { grid-template-columns: 1fr; } .db-main { padding: 20px 16px 90px; } .modal-card { padding: 28px 22px 24px; } }
    </style>
</head>
<body>
<script>
        /* ── Firebase guard (SPA reuses parent's instance) ── */
        if (typeof firebase !== 'undefined' && !firebase.apps.length) {
            firebase.initializeApp({ /* your config */ });
        }

    const LESSON_ID   = <?= json_encode($lessonId) ?>;
    const ASSESS_TYPE = <?= json_encode($type) ?>;
    const IS_QUIZ     = <?= json_encode($isQuiz) ?>;
    const QUIZ_LIMIT  = 10;
    </script>
<div class="page-loading" id="pageLoading">
    <span class="spinner" style="width:28px;height:28px;border-width:3px;border-color:#dbeafe;border-top-color:#3b82f6;"></span>
    <p>Loading assessment…</p>
</div>

<main class="db-main">
    <div class="af-header">
        <div class="af-title">
            <h2><?= $isQuiz ? 'Quiz' : 'Coding Exercise' ?> — <span id="lessonTitleSpan"><?= $lessonId ?></span></h2>
            <p><?= $isQuiz ? 'This quiz is fixed to exactly 10 questions.' : 'Add coding exercises for this lesson.' ?></p>
            <span class="diff-badge" id="diffBadge">Loading…</span>
            <?php if ($isQuiz): ?>
            <span class="quiz-limit-badge" id="quizCountBadge">
                <i class="fa-solid fa-list-ol"></i>
                <span id="quizCountText">0 / 10 questions</span>
            </span>
            <?php endif; ?>
        </div>
    </div>

    <?php if ($isQuiz): ?>
    <div class="questions-wrap" id="questionsWrap">
        <div class="sub-id-bar">
            <span class="sub-id-badge">Content Sub-ID: Q1</span>
            <span class="item-count-pill" id="quizPillCount">0 / 10</span>
        </div>
        <div id="questionsContainer"></div>
        <button class="add-question-btn" id="addQuestionBtn" onclick="addQuestion()" style="display:none;">
            <i class="fa-solid fa-plus"></i> Add question
        </button>
        <div class="limit-notice" id="quizLimitNotice" style="display:none;">
            <i class="fa-solid fa-lock"></i> Quiz is fixed to exactly 10 questions — all slots are filled.
        </div>
    </div>
    <?php else: ?>
    <div class="questions-wrap" id="questionsWrap">
        <div class="sub-id-bar">
            <span class="sub-id-badge">Content Sub-ID: CE1</span>
        </div>
        <div id="questionsContainer"></div>
        <button class="add-question-btn" onclick="addExercise()">
            <i class="fa-solid fa-plus"></i> Add exercise
        </button>
    </div>
    <?php endif; ?>
</main>

<div class="modal-overlay" id="saveModal">
    <div class="modal-card">
        <div class="modal-icon"><i class="fa-solid fa-floppy-disk"></i></div>
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

<div class="footer-bar">
    <button class="btn-cancel" onclick="window.location.href='index.php?page=content_management';">
    <i class="fa-solid fa-xmark" style="margin-right:4px;"></i> Cancel
</button>
    <button class="btn-save" id="saveBtn" onclick="openSaveModal()">
        <i class="fa-solid fa-floppy-disk"></i>
        <?= $isQuiz ? 'Save Quiz' : 'Save Exercises' ?>
    </button>
</div>

<div class="toast" id="toast"></div>

<script>
/* ─── Globals ───────────────────────────────────────────────────────────── */
let questionCounter  = 0;
let lessonDifficulty = '';

/* ─── Helpers ───────────────────────────────────────────────────────────── */
function encodeText(t) { return (t||'').replace(/ /g,'&nbsp;').replace(/\n/g,'<br/>'); }
function decodeText(t) { return (t||'').replace(/&nbsp;/g,' ').replace(/<br\/>/g,'\n').replace(/<br>/g,'\n'); }

function showToast(msg, type='success') {
    const el = document.getElementById('toast');
    el.textContent = msg;
    el.className = `toast ${type} show`;
    setTimeout(() => el.classList.remove('show'), 3500);
}

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

/* ─── Boot — fetch DIRECTLY from Firebase, bypass cache ────────────────── */
async function init() {
    if (!LESSON_ID) {
        showToast('No lesson ID provided.', 'error');
        return;
    }

    const loadingEl = document.getElementById('pageLoading');

    try {
        const db = firebase.database();

        const lessonSnap = await db.ref('Lessons/' + LESSON_ID).once('value');
        const lesson     = lessonSnap.val() || {};
        lessonDifficulty = lesson.difficulty || '';

        const badge = document.getElementById('diffBadge');
        if (badge) { badge.textContent = lessonDifficulty || 'Unknown'; badge.className = 'diff-badge ' + lessonDifficulty; }

        const titleEl = document.getElementById('lessonTitleSpan');
        if (titleEl && lesson.main_title) {
            const tmp = document.createElement('div');
            tmp.innerHTML = lesson.main_title;
            titleEl.textContent = tmp.textContent.trim() || LESSON_ID;
        }

        if (IS_QUIZ) {
            const snap     = await db.ref('assessment/' + LESSON_ID + '/Quiz').once('value');
            const existing = snap.val();
            let loadedCount = 0;

            if (existing && typeof existing === 'object') {
                Object.entries(existing).slice(0, QUIZ_LIMIT).forEach(([key, q]) => {
                    addQuestion(key, q);
                    loadedCount++;
                });
            }

            while (loadedCount < QUIZ_LIMIT) { addQuestion(); loadedCount++; }

        } else {
            const snap     = await db.ref('coding_exercises/' + LESSON_ID).once('value');
            const existing = snap.val();

            if (Array.isArray(existing)) {
                const exercises = existing.slice(1).filter(Boolean);
                exercises.length > 0 ? exercises.forEach(ex => addExercise(ex)) : addExercise();
            } else if (existing && typeof existing === 'object') {
                const exercises = Object.values(existing).filter(Boolean);
                exercises.length > 0 ? exercises.forEach(ex => addExercise(ex)) : addExercise();
            } else {
                addExercise();
            }
        }

    } catch (e) {
        console.error('Load failed:', e);
        showToast('Error loading: ' + e.message, 'error');
        if (IS_QUIZ) {
            const cur = document.querySelectorAll('#questionsContainer .question-card').length;
            for (let i = cur; i < QUIZ_LIMIT; i++) addQuestion();
        } else {
            addExercise();
        }
    } finally {
        if (loadingEl) loadingEl.classList.add('hidden');
        if (IS_QUIZ) updateQuizCountDisplay();
    }
}

/* ── Boot: same pattern as add_module_form.php ── */
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
} else {
    init();
}

/* ═══════════════════════════════════════════════════════════════════════════
   QUIZ
═══════════════════════════════════════════════════════════════════════════ */
function addQuestion(existingKey, existing) {
    if (IS_QUIZ) {
        const cur = document.querySelectorAll('#questionsContainer .question-card').length;
        if (cur >= QUIZ_LIMIT) { updateQuizCountDisplay(); return; }
    }
    questionCounter++;
    const n = questionCounter, qId = 'q_' + n;
    const container = document.getElementById('questionsContainer');

    container.insertAdjacentHTML('beforeend', `
    <div class="question-card" id="${qId}" data-push-key="${existingKey || ''}">
        <div class="question-header" onclick="toggleQuestion('${qId}')">
            <span class="question-num">Question ${n}</span>
            <div class="question-header-right">
                <i class="fa-solid fa-chevron-down chevron-icon"></i>
            </div>
        </div>
        <div class="question-body">
            <div class="field-label" style="margin-bottom:6px;">Question Text</div>
            <textarea class="question-textarea" id="${qId}_text" placeholder="Type question here…"></textarea>
            <div class="field-label" style="margin:20px 0 10px;">Multiple Choice Answer</div>
            <div class="mc-grid">
                ${['A','B','C','D'].map(l => `
                <div class="mc-choice" id="${qId}_choice${l}">
                    <div class="mc-letter">${l}</div>
                    <input class="mc-input" id="${qId}_choice${l}_input" placeholder="Type answer here…">
                    <label class="mc-radio">
                        <input type="radio" name="${qId}_answer" value="${l}" onchange="markCorrect('${qId}','${l}')">
                        <div class="mc-radio-dot"></div>
                    </label>
                </div>`).join('')}
            </div>
        </div>
    </div>`);

    if (existing) {
        const qText = document.getElementById(qId + '_text');
        if (qText) qText.value = decodeText(existing.question || '');
        ['A','B','C','D'].forEach(l => {
            const inp = document.getElementById(qId + '_choice' + l + '_input');
            if (inp) inp.value = decodeText(existing['choice' + l] || '');
        });
        if (existing.answer) {
            const radio = document.querySelector(`input[name="${qId}_answer"][value="${existing.answer}"]`);
            if (radio) { radio.checked = true; markCorrect(qId, existing.answer); }
        }
    }
    if (IS_QUIZ) updateQuizCountDisplay();
}

function toggleQuestion(qId) { document.getElementById(qId)?.classList.toggle('collapsed'); }

function markCorrect(qId, letter) {
    ['A','B','C','D'].forEach(l => document.getElementById(qId+'_choice'+l)?.classList.remove('selected'));
    document.getElementById(qId+'_choice'+letter)?.classList.add('selected');
}

/* ═══════════════════════════════════════════════════════════════════════════
   CODING EXERCISE
═══════════════════════════════════════════════════════════════════════════ */
function addExercise(existing) {
    questionCounter++;
    const n = questionCounter, exId = 'ex_' + n;
    const container = document.getElementById('questionsContainer');

    container.insertAdjacentHTML('beforeend', `
    <div class="question-card" id="${exId}">
        <div class="question-header" onclick="toggleQuestion('${exId}')">
            <span class="question-num">Exercise ${n}</span>
            <div class="question-header-right">
                ${n > 1 ? `<button class="btn-remove-q" onclick="removeExercise(event,'${exId}')"><i class="fa-solid fa-trash-can"></i> Remove</button>` : ''}
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
                <textarea class="desc-textarea" id="${exId}_desc" placeholder="Describe what the learner should do…"></textarea>
            </div>
            <div class="ce-field">
                <div class="field-label" style="margin-bottom:6px;">Starter Code</div>
                <textarea class="code-textarea" id="${exId}_code" placeholder="// Paste the starter code here…"></textarea>
            </div>
            <div class="ce-field">
                <div class="field-label" style="margin-bottom:6px;">Expected Output</div>
                <input type="text" class="input-field" id="${exId}_output" placeholder="e.g. Hello World!">
            </div>
        </div>
    </div>`);

    if (existing) {
        const t = document.getElementById(exId+'_title');  if (t) t.value = decodeText(existing.title||'');
        const d = document.getElementById(exId+'_desc');   if (d) d.value = decodeText(existing.description||'').replace(/<b>Instructions:<\/b><br>/,'').replace(/<br><br><b>Hints:<\/b><br>/,'\n\nHints:\n');
        const c = document.getElementById(exId+'_code');   if (c) c.value = decodeText(existing.code||'');
        const o = document.getElementById(exId+'_output'); if (o) o.value = existing.expectedOutput||'';
    }
}

function removeExercise(event, exId) {
    event.stopPropagation();
    const el = document.getElementById(exId);
    if (!el) return;
    el.style.opacity='0'; el.style.transition='opacity .3s';
    setTimeout(() => { el.remove(); renumberExercises(); }, 300);
}

function renumberExercises() {
    document.querySelectorAll('#questionsContainer .question-card').forEach((card, i) => {
        const numEl = card.querySelector('.question-num');
        if (numEl) numEl.textContent = 'Exercise ' + (i+1);
    });
}

/* ═══════════════════════════════════════════════════════════════════════════
   SAVE MODAL
═══════════════════════════════════════════════════════════════════════════ */
function openSaveModal() {
    const diff = lessonDifficulty ? ` (${lessonDifficulty})` : '';
    const label = IS_QUIZ ? 'Quiz' : 'Coding Exercise';
    let count, unit;

    if (IS_QUIZ) {
        count = document.querySelectorAll('#questionsContainer .question-card').length;
        unit  = 'question(s)';
    } else {
        unit = 'exercise(s)'; count = 0;
        document.querySelectorAll('#questionsContainer .question-card').forEach(card => {
            const id = card.id;
            const t=(document.getElementById(id+'_title')?.value||'').trim();
            const d=(document.getElementById(id+'_desc')?.value||'').trim();
            const c=(document.getElementById(id+'_code')?.value||'').trim();
            const o=(document.getElementById(id+'_output')?.value||'').trim();
            if (t||d||c||o) count++;
        });
    }

    document.getElementById('modalTitle').textContent = `Save this ${IS_QUIZ ? 'quiz' : 'coding exercise'}?`;
    document.getElementById('modalSubtitle').innerHTML =
        `<strong>${label} ${LESSON_ID}${diff}</strong> with <strong>${count} ${unit}</strong> will be saved.` +
        (IS_QUIZ ? `<br><small style="color:#94a3b8;font-size:11px;margin-top:6px;display:block;">Learner quiz results will be automatically re-scored.</small>` : '');

    document.getElementById('saveModal').classList.add('show');
}

function closeModal() { document.getElementById('saveModal').classList.remove('show'); }

document.getElementById('saveModal').addEventListener('click', function(e) { if (e.target===this) closeModal(); });

/* ═══════════════════════════════════════════════════════════════════════════
   confirmSave
═══════════════════════════════════════════════════════════════════════════ */
async function confirmSave() {
    const btn = document.getElementById('modalSaveBtn');
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner"></span> Saving…';

    try {
        const db = firebase.database();
        if (IS_QUIZ) {
            const keys = await saveQuiz(db);
            btn.innerHTML = '<span class="spinner"></span> Re-scoring…';
            const n = await reEvaluateQuizLearners(db, keys);
            closeModal();
            showToast(n > 0 ? `Quiz saved! Re-scored ${n} learner(s).` : 'Quiz saved!', 'success');
        } else {
            await saveCodingExercises(db);
            closeModal();
            showToast('Saved successfully!', 'success');
        }

        // Invalidate cache so content_management reloads fresh data
        if (typeof FirebaseCache !== 'undefined') {
            FirebaseCache.invalidate('assessment');
            FirebaseCache.invalidate('coding_exercises');
        }

        setTimeout(() => {
            window.location.href = 'index.php?page=content_management';
        }, 1400);
    } catch (e) {
        closeModal();
        showToast('Error: ' + e.message, 'error');
        console.error(e);
    } finally {
        btn.disabled = false;
        btn.innerHTML = '<i class="fa-solid fa-check"></i> Save';
    }
}

/* ═══════════════════════════════════════════════════════════════════════════
   saveQuiz
═══════════════════════════════════════════════════════════════════════════ */
async function saveQuiz(db) {
    const cards = document.querySelectorAll('#questionsContainer .question-card');
    if (cards.length !== QUIZ_LIMIT) {
        const msg = `Quiz must have exactly ${QUIZ_LIMIT} questions (currently ${cards.length}).`;
        showToast(msg, 'error'); throw new Error(msg);
    }

    const errors = [], toSave = [];
    cards.forEach((card, i) => {
        const qId = card.id, num = i+1;
        const text = (document.getElementById(qId+'_text')?.value||'').trim();
        const cA   = (document.getElementById(qId+'_choiceA_input')?.value||'').trim();
        const cB   = (document.getElementById(qId+'_choiceB_input')?.value||'').trim();
        const cC   = (document.getElementById(qId+'_choiceC_input')?.value||'').trim();
        const cD   = (document.getElementById(qId+'_choiceD_input')?.value||'').trim();
        const radio = document.querySelector(`input[name="${qId}_answer"]:checked`);
        if (!text)             errors.push(`Q${num}: Question text is required.`);
        if (!cA||!cB||!cC||!cD) errors.push(`Q${num}: All four choices are required.`);
        if (!radio)            errors.push(`Q${num}: Please mark the correct answer.`);
        toSave.push({
            key: card.dataset.pushKey || null,
            answer: radio?.value || 'A',
            data: { question:encodeText(text), choiceA:encodeText(cA), choiceB:encodeText(cB), choiceC:encodeText(cC), choiceD:encodeText(cD), answer:radio?.value||'A' }
        });
    });
    if (errors.length) { showToast(errors[0], 'error'); throw new Error(errors[0]); }

    const basePath = 'assessment/' + LESSON_ID + '/Quiz/';
    const correctAnswers = [], usedKeys = [];

    for (const item of toSave) {
        let savedKey;
        if (item.key) { await db.ref(basePath + item.key).set(item.data); savedKey = item.key; }
        else          { const ref = await db.ref(basePath).push(item.data); savedKey = ref.key; }
        correctAnswers.push(item.answer);
        usedKeys.push({ key: savedKey, data: item.data });
    }

    if (lessonDifficulty) {
        const qqPath = `quizQuestions/${lessonDifficulty}/${LESSON_ID}/`;
        await db.ref(qqPath).remove();
        for (const { key, data } of usedKeys) await db.ref(qqPath + key).set(data);
    }
    return correctAnswers;
}

/* ═══════════════════════════════════════════════════════════════════════════
   reEvaluateQuizLearners
═══════════════════════════════════════════════════════════════════════════ */
async function reEvaluateQuizLearners(db, correctAnswers) {
    const quizSnap = await db.ref('assessment/' + LESSON_ID + '/Quiz').once('value');
    const quizData = quizSnap.val();
    const authoritative = [];
    if (quizData && typeof quizData === 'object') {
        Object.values(quizData).forEach(q => authoritative.push((q.answer||'').toUpperCase()));
    }
    const answerKey = authoritative.length === QUIZ_LIMIT ? authoritative : correctAnswers;

    const resultsSnap = await db.ref('quizResults').once('value');
    const allResults  = resultsSnap.val() || {};
    const updates = {}; let touched = 0;

    for (const uid of Object.keys(allResults)) {
        const lr = allResults[uid]?.[LESSON_ID];
        if (!lr?.answers) continue;
        const arr = Array.isArray(lr.answers) ? lr.answers : Object.values(lr.answers);
        let score = 0;
        arr.forEach((ua, i) => { if (i < answerKey.length && ua && ua.toUpperCase() === answerKey[i]) score++; });
        const pct = answerKey.length > 0 ? Math.round((score/answerKey.length)*100) : 0;
        const base = `quizResults/${uid}/${LESSON_ID}/`;
        updates[base+'score']  = score;
        updates[base+'total']  = answerKey.length;
        updates[base+'passed'] = pct >= 50 ? 'Passed' : 'Failed';
        touched++;
    }

    if (Object.keys(updates).length > 0) await db.ref().update(updates);
    return touched;
}

/* ═══════════════════════════════════════════════════════════════════════════
   saveCodingExercises
═══════════════════════════════════════════════════════════════════════════ */
async function saveCodingExercises(db) {
    const cards = document.querySelectorAll('#questionsContainer .question-card');
    const exercises = [null];
    cards.forEach(card => {
        const id = card.id;
        const title = (document.getElementById(id+'_title')?.value||'').trim();
        const desc  = (document.getElementById(id+'_desc')?.value||'').trim();
        const code  = (document.getElementById(id+'_code')?.value||'').trim();
        const out   = (document.getElementById(id+'_output')?.value||'').trim();
        if (!title&&!desc&&!code&&!out) return;
        exercises.push({ title, description:`<b>Instructions:</b><br>${encodeText(desc)}`, code:encodeText(code), expectedOutput:out, language:'java', versionIndex:'4' });
    });
    if (exercises.length > 1) {
        await db.ref('coding_exercises/' + LESSON_ID).set(exercises);
        if (typeof FirebaseCache !== 'undefined') FirebaseCache.invalidate('coding_exercises');
    }
}
</script>
</body>
</html>