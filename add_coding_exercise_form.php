<?php
$lessonId = htmlspecialchars($_GET['lesson'] ?? '');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CODEX | Coding Exercise — <?= $lessonId ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="dashboard.css">
    
    <style>
        body { display: flex; margin: 0; background: #f1f5f9; font-family: 'Inter', sans-serif; }

        .db-main {
            flex-grow: 1; padding: 36px 40px 100px;
            overflow-y: auto; height: 100vh; box-sizing: border-box;
        }

        .af-title h2 { margin: 0 0 4px; font-size: 22px; font-weight: 800; color: #1e293b; }
        .af-title p  { margin: 0 0 10px; font-size: 13px; color: #94a3b8; }

        .diff-badge {
            display: inline-block; padding: 3px 14px; border-radius: 20px;
            font-size: 11px; font-weight: 800; margin-bottom: 24px;
        }
        .diff-badge.Beginner     { background: #fef9c3; color: #854d0e; border: 1px solid #fde68a; }
        .diff-badge.Intermediate { background: #dbeafe; color: #1d4ed8; border: 1px solid #93c5fd; }
        .diff-badge.Advanced     { background: #f3e8ff; color: #7e22ce; border: 1px solid #d8b4fe; }

        .ex-section {
            background: white; border-radius: 14px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 2px 8px rgba(0,0,0,.05);
            margin-bottom: 20px; overflow: hidden;
        }

        .ex-section-header {
            display: flex; align-items: center; justify-content: space-between;
            padding: 16px 24px; cursor: pointer; user-select: none;
            background: #f8fafc; border-bottom: 1px solid #e2e8f0;
        }
        .ex-section-header:hover { background: #f1f5f9; }

        .ex-section-header-left { display: flex; align-items: center; gap: 12px; }

        .ex-type-badge {
            font-size: 13px; font-weight: 700; padding: 4px 16px;
            border-radius: 20px; letter-spacing: .2px;
        }
        .badge-syntax  { background: #fff7ed; color: #c2410c; border: 1.5px solid #fed7aa; }
        .badge-tracing { background: #eff6ff; color: #1d4ed8; border: 1.5px solid #bfdbfe; }
        .badge-machine { background: #f0fdf4; color: #15803d; border: 1.5px solid #bbf7d0; }

        .block-count-pill {
            font-size: 11px; font-weight: 700; padding: 3px 11px;
            border-radius: 20px; border: 1px solid #e2e8f0;
            background: #f1f5f9; color: #475569; transition: all .2s;
        }
        .block-count-pill.full { background: #fef9c3; border-color: #fde68a; color: #854d0e; }

        .section-chevron { color: #94a3b8; transition: transform .2s; font-size: 14px; }
        .ex-section.collapsed .section-chevron { transform: rotate(-90deg); }
        .ex-section.collapsed .ex-section-body { display: none; }

        .ex-section-body { padding: 20px 24px; }

        .code-block-card {
            border: 1.5px solid #e2e8f0; border-radius: 10px;
            margin-bottom: 14px; overflow: hidden;
            background: white;
        }

        .code-block-header {
            display: flex; align-items: center; justify-content: space-between;
            padding: 13px 20px; background: #f8fafc;
            border-bottom: 1.5px solid #e2e8f0;
            cursor: pointer; user-select: none;
        }
        .code-block-num {
            font-size: 12px; font-weight: 800; color: #1e293b; letter-spacing: .5px;
            text-transform: uppercase;
        }
        .block-header-right { display: flex; align-items: center; gap: 10px; }
        .btn-remove-block {
            background: none; border: 1.5px solid #fca5a5; color: #ef4444;
            padding: 4px 12px; border-radius: 20px; font-size: 11px;
            font-weight: 700; cursor: pointer; display: flex; align-items: center; gap: 5px;
        }
        .btn-remove-block:hover { background: #fee2e2; }
        .block-chevron { color: #94a3b8; transition: transform .2s; font-size: 13px; }
        .code-block-card.collapsed .block-chevron { transform: rotate(-90deg); }
        .code-block-card.collapsed .code-block-body { display: none; }

        .code-block-body { padding: 20px 22px; display: flex; flex-direction: column; gap: 14px; }

        .field-label {
            font-size: 10px; font-weight: 800; color: #94a3b8;
            text-transform: uppercase; letter-spacing: .8px; margin-bottom: 6px;
            display: block;
        }

        .input-field {
            width: 100%; padding: 10px 14px; border: 1.5px solid #e2e8f0;
            border-radius: 8px; font-size: 13px; font-family: inherit;
            box-sizing: border-box; background: white; color: #1e293b;
            transition: border-color .2s;
        }
        .input-field:focus { outline: none; border-color: #3b82f6; }
        .input-field::placeholder { color: #cbd5e1; }

        .instruction-textarea {
            width: 100%; min-height: 80px; padding: 10px 14px;
            border: 1.5px solid #e2e8f0; border-radius: 8px;
            font-size: 13px; font-family: inherit; resize: vertical;
            box-sizing: border-box; background: white; color: #1e293b;
            line-height: 1.65; transition: border-color .2s;
        }
        .instruction-textarea:focus { outline: none; border-color: #3b82f6; }
        .instruction-textarea::placeholder { color: #cbd5e1; }

        .code-compiler-wrap {
            border: 1.5px solid #e2e8f0; border-radius: 8px; overflow: hidden;
        }
        .code-editor-topbar {
            display: flex; align-items: center; gap: 8px;
            padding: 10px 16px; background: #1e3a5f;
        }
        .code-editor-topbar i { color: #93c5fd; font-size: 13px; }
        .code-editor-topbar span {
            font-size: 11px; font-weight: 800; color: #93c5fd;
            text-transform: uppercase; letter-spacing: 1px;
        }
        .code-textarea {
            width: 100%; min-height: 160px; padding: 16px;
            font-family: 'Courier New', monospace; font-size: 13px;
            border: none; background: #0f172a; color: #e2e8f0;
            line-height: 1.8; resize: vertical; box-sizing: border-box;
            display: block;
        }
        .code-textarea:focus { outline: none; }
        .code-textarea::placeholder { color: #475569; }

        .add-block-btn {
            display: flex; align-items: center; justify-content: center; gap: 8px;
            width: 100%; padding: 14px;
            border: 2px dashed #cbd5e1; border-radius: 8px;
            background: none; color: #64748b; font-size: 13px; font-weight: 700;
            cursor: pointer; transition: all .2s; margin-top: 4px;
        }
        .add-block-btn:hover { border-color: #3b82f6; color: #3b82f6; background: #eff6ff; }

        .limit-notice {
            display: flex; align-items: center; justify-content: center; gap: 8px;
            padding: 12px 16px; background: #fefce8;
            border: 1.5px dashed #fde68a; border-radius: 8px;
            font-size: 12px; font-weight: 600; color: #854d0e;
            margin-top: 4px;
        }
        .limit-notice i { font-size: 13px; }

        .footer-bar {
            position: fixed; bottom: 0; left: 0; right: 0;
            background: white; border-top: 1px solid #e2e8f0;
            padding: 14px 40px; display: flex;
            justify-content: flex-end; align-items: center; gap: 14px;
            z-index: 100; box-shadow: 0 -2px 12px rgba(0,0,0,.06);
        }
        .btn-cancel {
            background: none; border: 1.5px solid #ef4444; color: #ef4444;
            padding: 10px 28px; border-radius: 25px; font-weight: 700;
            font-size: 13px; cursor: pointer; transition: .2s;
        }
        .btn-cancel:hover { background: #fee2e2; }
        .btn-save {
            background: #001c30; color: white; border: none;
            padding: 10px 28px; border-radius: 25px; font-weight: 700;
            font-size: 13px; cursor: pointer; display: flex; align-items: center; gap: 8px;
            transition: background .2s;
        }
        .btn-save:hover { background: #0f3460; }
        .btn-save:disabled { opacity: .5; cursor: not-allowed; }

        .toast {
            position: fixed; bottom: 80px; right: 30px;
            padding: 12px 20px; border-radius: 10px; font-size: 13px;
            font-weight: 600; color: white; z-index: 2000;
            transform: translateY(16px); opacity: 0;
            transition: all .3s; pointer-events: none;
        }
        .toast.show { transform: translateY(0); opacity: 1; }
        .toast.success { background: #16a34a; }
        .toast.error   { background: #dc2626; }

        .spinner {
            display: inline-block; width: 13px; height: 13px;
            border: 2px solid rgba(255,255,255,.3); border-top-color: white;
            border-radius: 50%; animation: spin .7s linear infinite;
        }
        @keyframes spin { to { transform: rotate(360deg); } }

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

        .page-loading {
            position: fixed; inset: 0; background: rgba(248,250,252,.93);
            display: flex; flex-direction: column; align-items: center;
            justify-content: center; z-index: 500; gap: 14px;
        }
        .page-loading p { color: #1e293b; font-weight: 600; font-size: 14px; }
        .page-loading.hidden { display: none; }
    </style>
</head>
<body>
<script>
    /* ── Firebase guard (SPA reuses parent's instance) ── */
        if (typeof firebase !== 'undefined' && !firebase.apps.length) {
            firebase.initializeApp({ /* your config */ });
        }
        const LESSON_ID = <?= json_encode($lessonId) ?>;
        const BLOCK_LIMITS = {
            FindingSyntaxError : 2,
            ProgramTracing     : 2,
            MachineProblem     : 1,
        };
    </script>
<div class="page-loading" id="pageLoading">
    <span class="spinner" style="width:26px;height:26px;border-width:3px;border-color:#dbeafe;border-top-color:#3b82f6;"></span>
    <p>Loading exercises…</p>
</div>

<main class="db-main">

    <div class="af-title">
        <h2>Coding Exercise — <span id="lessonTitleSpan"><?= $lessonId ?></span></h2>
        <p>Add code blocks for each exercise type for this lesson.</p>
        <span class="diff-badge" id="diffBadge"> </span>
    </div>

    <!-- ── Finding Syntax Error (max 2) ─────────────────────────────────── -->
    <div class="ex-section" id="section_FindingSyntaxError">
        <div class="ex-section-header" onclick="toggleSection('FindingSyntaxError')">
            <div class="ex-section-header-left">
                <span class="ex-type-badge badge-syntax">Finding Syntax Error</span>
                <span class="block-count-pill" id="pill_FindingSyntaxError">0 / 2</span>
            </div>
            <i class="fa-solid fa-chevron-down section-chevron"></i>
        </div>
        <div class="ex-section-body">
            <div id="blocks_FindingSyntaxError"></div>
            <button class="add-block-btn" id="addBtn_FindingSyntaxError"
                    onclick="addBlock('FindingSyntaxError')">
                <i class="fa-solid fa-plus"></i> Add code block
            </button>
            <div class="limit-notice" id="limitNotice_FindingSyntaxError" style="display:none;">
                <i class="fa-solid fa-lock"></i>
                Maximum of 2 code blocks reached for Finding Syntax Error.
            </div>
        </div>
    </div>

    <!-- ── Program Tracing (max 2) ──────────────────────────────────────── -->
    <div class="ex-section" id="section_ProgramTracing">
        <div class="ex-section-header" onclick="toggleSection('ProgramTracing')">
            <div class="ex-section-header-left">
                <span class="ex-type-badge badge-tracing">Program Tracing</span>
                <span class="block-count-pill" id="pill_ProgramTracing">0 / 2</span>
            </div>
            <i class="fa-solid fa-chevron-down section-chevron"></i>
        </div>
        <div class="ex-section-body">
            <div id="blocks_ProgramTracing"></div>
            <button class="add-block-btn" id="addBtn_ProgramTracing"
                    onclick="addBlock('ProgramTracing')">
                <i class="fa-solid fa-plus"></i> Add code block
            </button>
            <div class="limit-notice" id="limitNotice_ProgramTracing" style="display:none;">
                <i class="fa-solid fa-lock"></i>
                Maximum of 2 code blocks reached for Program Tracing.
            </div>
        </div>
    </div>

    <!-- ── Machine Problem (max 1) ──────────────────────────────────────── -->
    <div class="ex-section" id="section_MachineProblem">
        <div class="ex-section-header" onclick="toggleSection('MachineProblem')">
            <div class="ex-section-header-left">
                <span class="ex-type-badge badge-machine">Machine Problem</span>
                <span class="block-count-pill" id="pill_MachineProblem">0 / 1</span>
            </div>
            <i class="fa-solid fa-chevron-down section-chevron"></i>
        </div>
        <div class="ex-section-body">
            <div id="blocks_MachineProblem"></div>
            <button class="add-block-btn" id="addBtn_MachineProblem"
                    onclick="addBlock('MachineProblem')">
                <i class="fa-solid fa-plus"></i> Add code block
            </button>
            <div class="limit-notice" id="limitNotice_MachineProblem" style="display:none;">
                <i class="fa-solid fa-lock"></i>
                Maximum of 1 code block reached for Machine Problem.
            </div>
        </div>
    </div>

</main>

<!-- ══ SAVE CONFIRMATION MODAL ═════════════════════════════════════════════ -->
<div class="modal-overlay" id="saveModal">
    <div class="modal-card">
        <div class="modal-icon">
            <i class="fa-solid fa-floppy-disk"></i>
        </div>
        <h2 class="modal-title">Save this coding exercise?</h2>
        <p class="modal-subtitle" id="modalSubtitle">Your coding exercises will be saved.</p>
        <div class="modal-actions">
            <button class="modal-btn-cancel" onclick="closeModal()">Cancel</button>
            <button class="modal-btn-save" id="modalSaveBtn" onclick="confirmSave()">
                <i class="fa-solid fa-check"></i> Save
            </button>
        </div>
    </div>
</div>

<!-- ══ FOOTER ══════════════════════════════════════════════════════════════ -->
<div class="footer-bar">
    <button class="btn-cancel" onclick="window.location.href='index.php?page=content_management';">
        <i class="fa-solid fa-xmark" style="margin-right:4px;"></i> Cancel
    </button>
    <button class="btn-save" id="saveBtn" onclick="openSaveModal()">
        <i class="fa-solid fa-floppy-disk"></i> Save Coding Exercise
    </button>
</div>

<div class="toast" id="toast"></div>

<script>
function goToPage(page) {
    if (typeof window.navigate === 'function') {
        window.navigate(page);
    } else {
        window.location.href = 'index.php?page=' + page;
    }
}

/* ─── Per-section block counters ────────────────────────────────────────── */
const counters = {
    FindingSyntaxError: 0,
    ProgramTracing:     0,
    MachineProblem:     0,
};

const OUTPUT_LABEL = {
    FindingSyntaxError: 'Expected Output',
    ProgramTracing:     'Correct Output',
    MachineProblem:     'Expected Output',
};

const TITLE_PLACEHOLDER = {
    FindingSyntaxError: 'e.g. Finding Syntax Error',
    ProgramTracing:     'e.g. Program Tracing',
    MachineProblem:     'e.g. Machine Problem',
};

/* ─── Encode / decode helpers ───────────────────────────────────────────── */
function encodeText(text) {
    if (!text) return '';
    return text
        .replace(/&/g, '&amp;')
        .replace(/ /g, '&nbsp;')
        .replace(/\n/g, '<br/>');
}
function decodeText(encoded) {
    if (!encoded) return '';
    return encoded
        .replace(/<br\/>/g, '\n')
        .replace(/<br>/g, '\n')
        .replace(/&nbsp;/g, ' ')
        .replace(/&amp;/g, '&')
        .replace(/&lt;/g, '<')
        .replace(/&gt;/g, '>');
}

/* ─── Toast ─────────────────────────────────────────────────────────────── */
function showToast(msg, type = 'success') {
    const el = document.getElementById('toast');
    el.textContent = msg;
    el.className = `toast ${type} show`;
    setTimeout(() => el.classList.remove('show'), 3500);
}

/* ─── Toggle section collapse ───────────────────────────────────────────── */
function toggleSection(type) {
    document.getElementById('section_' + type).classList.toggle('collapsed');
}

/* ─── Toggle block collapse ─────────────────────────────────────────────── */
function toggleBlock(blockId) {
    document.getElementById(blockId).classList.toggle('collapsed');
}

/* ─── Update the add-button / limit-notice visibility ───────────────────── */
function updateLimitUI(type) {
    const count  = document.querySelectorAll(`#blocks_${type} .code-block-card`).length;
    const limit  = BLOCK_LIMITS[type];
    const addBtn = document.getElementById('addBtn_' + type);
    const notice = document.getElementById('limitNotice_' + type);
    const pill   = document.getElementById('pill_' + type);

    if (addBtn) addBtn.style.display  = count >= limit ? 'none' : 'flex';
    if (notice) notice.style.display  = count >= limit ? 'flex' : 'none';
    if (pill) {
        pill.textContent  = `${count} / ${limit}`;
        pill.className    = count >= limit ? 'block-count-pill full' : 'block-count-pill';
    }
}

/* ─── Add a code block ──────────────────────────────────────────────────── */
function addBlock(type, existing) {
    const currentCount = document.querySelectorAll(`#blocks_${type} .code-block-card`).length;
    const limit = BLOCK_LIMITS[type];

    if (currentCount >= limit) {
        updateLimitUI(type);
        return;
    }

    counters[type]++;
    const n       = counters[type];
    const blockId = `block_${type}_${n}`;
    const outLabel = OUTPUT_LABEL[type];
    const titlePH  = TITLE_PLACEHOLDER[type];

    const html = `
    <div class="code-block-card" id="${blockId}">
        <div class="code-block-header" onclick="toggleBlock('${blockId}')">
            <span class="code-block-num">Code Block ${currentCount + 1}</span>
            <div class="block-header-right">
                ${currentCount > 0 ? `<button class="btn-remove-block" onclick="removeBlock(event,'${blockId}','${type}')">
                    <i class="fa-solid fa-trash-can"></i> Remove
                </button>` : ''}
                <i class="fa-solid fa-chevron-down block-chevron"></i>
            </div>
        </div>
        <div class="code-block-body">
            <div>
                <span class="field-label">Exercise Title</span>
                <input type="text" class="input-field"
                       id="${blockId}_title"
                       placeholder="${titlePH}">
            </div>
            <div>
                <span class="field-label">Instructions</span>
                <textarea class="instruction-textarea"
                          id="${blockId}_instructions"
                          placeholder="Write the instructions here…"></textarea>
            </div>
            ${type !== 'MachineProblem' ? `
            <div>
                <span class="field-label">Code Compiler</span>
                <div class="code-compiler-wrap">
                    <div class="code-editor-topbar">
                        <i class="fa-solid fa-code"></i>
                        <span>Java Code Editor</span>
                    </div>
                    <textarea class="code-textarea"
                            id="${blockId}_code"
                            placeholder="// Type your code here..."></textarea>
                </div>
            </div>` : ''}
            <div>
                <span class="field-label">${outLabel}</span>
                <input type="text" class="input-field"
                       id="${blockId}_output"
                       placeholder="Type the ${outLabel.toLowerCase()} here…">
            </div>
        </div>
    </div>`;

    document.getElementById('blocks_' + type).insertAdjacentHTML('beforeend', html);

    if (existing) {
        const f = (id) => document.getElementById(id);
        if (f(blockId + '_title'))        f(blockId + '_title').value        = decodeText(existing.title       || '');
        if (f(blockId + '_instructions')) f(blockId + '_instructions').value = decodeText(existing.description || '');
        if (f(blockId + '_code'))         f(blockId + '_code').value         = decodeText(existing.code        || '');
        if (f(blockId + '_output'))       f(blockId + '_output').value       = existing.expectedOutput || '';
    }

    updateLimitUI(type);
}

/* ─── Remove a code block ───────────────────────────────────────────────── */
function removeBlock(event, blockId, type) {
    event.stopPropagation();
    const el = document.getElementById(blockId);
    if (!el) return;
    el.style.opacity = '0';
    el.style.transition = 'opacity .25s';
    setTimeout(() => {
        el.remove();
        renumberBlocks(type);
        updateLimitUI(type);
    }, 250);
}

/* ─── Renumber block headers after removal ──────────────────────────────── */
function renumberBlocks(type) {
    document.querySelectorAll(`#blocks_${type} .code-block-card`).forEach((card, i) => {
        const numEl = card.querySelector('.code-block-num');
        if (numEl) numEl.textContent = 'Code Block ' + (i + 1);

        const removeBtn = card.querySelector('.btn-remove-block');
        if (i === 0 && removeBtn) {
            removeBtn.style.display = 'none';
        } else if (i > 0 && !removeBtn) {
            const right = card.querySelector('.block-header-right');
            if (right) {
                const btn = document.createElement('button');
                btn.className = 'btn-remove-block';
                btn.innerHTML = '<i class="fa-solid fa-trash-can"></i> Remove';
                btn.onclick = (e) => removeBlock(e, card.id, type);
                right.insertBefore(btn, right.firstChild);
            }
        }
    });
}

/* ─── Boot — fetch DIRECTLY from Firebase, bypass cache ────────────────── */
async function init() {
    Object.keys(BLOCK_LIMITS).forEach(type => updateLimitUI(type));

    if (!LESSON_ID) {
        document.getElementById('pageLoading').classList.add('hidden');
        showToast('No lesson ID.', 'error');
        Object.keys(BLOCK_LIMITS).forEach(t => addBlock(t));
        return;
    }

    try {
        const db = firebase.database();

        const lessonSnap = await db.ref('Lessons/' + LESSON_ID).once('value');
        const lesson     = lessonSnap.val() || {};
        const diff       = lesson.difficulty || '';

        const badge = document.getElementById('diffBadge');
        badge.textContent = diff;
        badge.className   = 'diff-badge ' + diff;
        lessonDifficulty  = diff;

        const titleEl = document.getElementById('lessonTitleSpan');
        if (lesson.main_title) {
            const tmp = document.createElement('div');
            tmp.innerHTML = lesson.main_title;
            titleEl.textContent = tmp.textContent.trim() || LESSON_ID;
        }

        const codingSnap = await db.ref('assessment/' + LESSON_ID).once('value');
        const lessonData = codingSnap.val();

        const TYPES = ['FindingSyntaxError', 'ProgramTracing', 'MachineProblem'];

        if (!lessonData) {
            // ── No data at all — start empty ──────────────────────────
            TYPES.forEach(t => addBlock(t));

        } else if (!Array.isArray(lessonData) && typeof lessonData === 'object') {
            // ── New keyed format: { FindingSyntaxError:[...], ... } ───
            for (const type of TYPES) {
                const data  = lessonData[type] || null;
                const limit = BLOCK_LIMITS[type];

                if (Array.isArray(data)) {
                    const exercises = data.slice(1).filter(Boolean).slice(0, limit);
                    exercises.length > 0
                        ? exercises.forEach(ex => addBlock(type, ex))
                        : addBlock(type);
                } else if (data && typeof data === 'object') {
                    const exercises = Object.values(data).filter(Boolean).slice(0, limit);
                    exercises.length > 0
                        ? exercises.forEach(ex => addBlock(type, ex))
                        : addBlock(type);
                } else {
                    addBlock(type);
                }
            }

        } else if (Array.isArray(lessonData)) {
            // ── Old flat array format: [null, {...}, {...}, {...}] ─────
            // These were saved without type keys — treat them as
            // FindingSyntaxError entries (the original legacy format).
            // Show them all under FindingSyntaxError so nothing is lost.
            const exercises = lessonData.slice(1).filter(Boolean);
            const limit     = BLOCK_LIMITS['FindingSyntaxError'];
            const toLoad    = exercises.slice(0, limit);

            toLoad.length > 0
                ? toLoad.forEach(ex => addBlock('FindingSyntaxError', ex))
                : addBlock('FindingSyntaxError');

            // Other sections start empty
            addBlock('ProgramTracing');
            addBlock('MachineProblem');
        }

    } catch (e) {
        showToast('Error loading: ' + e.message, 'error');
        console.error(e);
        Object.keys(BLOCK_LIMITS).forEach(t => addBlock(t));
    } finally {
        document.getElementById('pageLoading').classList.add('hidden');
    }
}

/* ── Boot: same pattern as add_module_form.php ── */
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
} else {
    init();
}

/* ─── Collect blocks for one section ───────────────────────────────────── */
function collectBlocks(type) {
    const cards = document.querySelectorAll(`#blocks_${type} .code-block-card`);
    const result = [null];

    cards.forEach(card => {
        const id    = card.id;
        const title = (document.getElementById(id + '_title')?.value || '').trim();
        const instr = (document.getElementById(id + '_instructions')?.value || '').trim();
        const code  = (document.getElementById(id + '_code')?.value || '').trim();
        const out   = (document.getElementById(id + '_output')?.value || '').trim();

        if (!title && !instr && !code && !out) return;

        result.push({
            title          : title,
            description    : encodeText(instr),
            code           : encodeText(code),
            expectedOutput : out,
            language       : 'java',
            versionIndex   : '4',
        });
    });

    return result;
}

/* ─── Save modal ────────────────────────────────────────────────────────── */
let lessonDifficulty = '';

function openSaveModal() {
    const TYPES = ['FindingSyntaxError', 'ProgramTracing', 'MachineProblem'];
    let totalBlocks = 0;
    TYPES.forEach(t => {
        totalBlocks += collectBlocks(t).length - 1;
    });

    const diff = lessonDifficulty ? ` (${lessonDifficulty})` : '';
    document.getElementById('modalSubtitle').innerHTML =
        totalBlocks > 0
            ? `<strong>Coding Exercise ${LESSON_ID}${diff}</strong> with <strong>${totalBlocks} code block(s)</strong> will be saved.<br><small style="color:#94a3b8;font-size:11px;margin-top:6px;display:block;">Learner answers will be automatically re-evaluated against the updated content.</small>`
            : `<strong>Coding Exercise ${LESSON_ID}${diff}</strong> — no content entered. Saving will clear existing data.`;

    document.getElementById('saveModal').classList.add('show');
}

function closeModal() {
    document.getElementById('saveModal').classList.remove('show');
}

document.getElementById('saveModal').addEventListener('click', function(e) {
    if (e.target === this) closeModal();
});

/* ─── confirmSave ───────────────────────────────────────────────────────── */
async function confirmSave() {
    const modalBtn = document.getElementById('modalSaveBtn');
    modalBtn.disabled = true;
    modalBtn.innerHTML = '<span class="spinner"></span> Saving…';

    try {
        const db = firebase.database();
        const TYPES = ['FindingSyntaxError', 'ProgramTracing', 'MachineProblem'];

        const savedBlocks = {};
        for (const type of TYPES) {
            const blocks = collectBlocks(type);
            if (blocks.length > 1) {
                await db.ref(`assessment/${LESSON_ID}/${type}`).set(blocks);
                savedBlocks[type] = blocks;
            }
        }

        modalBtn.innerHTML = '<span class="spinner"></span> Re-evaluating learners…';
        const reevalCount = await reEvaluateAllLearners(db, savedBlocks);

        // Invalidate cache after write
        if (typeof FirebaseCache !== 'undefined') {
            FirebaseCache.invalidate('assessment');
            FirebaseCache.invalidate('assessment');
        }

        closeModal();

        const msg = reevalCount > 0
            ? `Saved! Re-evaluated ${reevalCount} learner submission(s).`
            : 'Coding exercises saved!';
        showToast(msg, 'success');

setTimeout(() => { window.location.href = 'index.php?page=content_management'; }, 1400);
    } catch (e) {
        closeModal();
        showToast('Error: ' + e.message, 'error');
        console.error(e);
    } finally {
        modalBtn.disabled = false;
        modalBtn.innerHTML = '<i class="fa-solid fa-check"></i> Save';
    }
}

/* ─── reEvaluateAllLearners ─────────────────────────────────────────────── */
async function reEvaluateAllLearners(db, savedBlocks) {
    const updates = {};
    let touchedCount = 0;

    if (savedBlocks.FindingSyntaxError) {
        const newExercises = savedBlocks.FindingSyntaxError;
        const snap = await db.ref('userSyntaxErrorAnswers').once('value');
        const allAnswers = snap.val() || {};

        for (const uid of Object.keys(allAnswers)) {
            const lessonAnswers = allAnswers[uid]?.[LESSON_ID];
            if (!lessonAnswers) continue;

            const answersArr = Array.isArray(lessonAnswers)
                ? lessonAnswers
                : [null, ...Object.values(lessonAnswers)];

            let correctCount = 0;
            for (let i = 1; i < answersArr.length; i++) {
                const entry = answersArr[i];
                if (!entry) continue;
                const newExpected = newExercises[i]?.expectedOutput?.trim();
                const userOutput  = (entry.output || '').trim();
                const isCorrect   = newExpected !== undefined && newExpected !== '' && userOutput === newExpected;
                updates[`userSyntaxErrorAnswers/${uid}/${LESSON_ID}/${i}/isCorrect`] = isCorrect;
                if (isCorrect) correctCount++;
            }
            updates[`syntaxErrorResults/${uid}/${LESSON_ID}/correctCount`]   = correctCount;
            updates[`syntaxErrorResults/${uid}/${LESSON_ID}/totalExercises`] = newExercises.length - 1;
            touchedCount++;
        }
    }

    if (savedBlocks.ProgramTracing) {
        const newExercises = savedBlocks.ProgramTracing;
        const snap = await db.ref('userTracingAnswers').once('value');
        const allAnswers = snap.val() || {};

        for (const uid of Object.keys(allAnswers)) {
            const lessonAnswers = allAnswers[uid]?.[LESSON_ID];
            if (!lessonAnswers) continue;

            const answersArr = Array.isArray(lessonAnswers)
                ? lessonAnswers
                : [null, ...Object.values(lessonAnswers)];

            let correctCount = 0;
            for (let i = 1; i < answersArr.length; i++) {
                const entry = answersArr[i];
                if (!entry) continue;
                const newExpected = newExercises[i]?.expectedOutput?.trim();
                const userAnswer  = (entry.answer || '').trim();
                const isCorrect   = newExpected !== undefined && newExpected !== '' && userAnswer === newExpected;
                updates[`userTracingAnswers/${uid}/${LESSON_ID}/${i}/isCorrect`] = isCorrect;
                if (isCorrect) correctCount++;
            }
            updates[`tracingResults/${uid}/${LESSON_ID}/correctCount`]   = correctCount;
            updates[`tracingResults/${uid}/${LESSON_ID}/totalExercises`] = newExercises.length - 1;
            touchedCount++;
        }
    }

    if (savedBlocks.MachineProblem && savedBlocks.MachineProblem[1]) {
        const newExpected = (savedBlocks.MachineProblem[1].expectedOutput || '').trim();
        const snap = await db.ref('userMachineProblemAnswers').once('value');
        const allAnswers = snap.val() || {};

        for (const uid of Object.keys(allAnswers)) {
            const entry = allAnswers[uid]?.[LESSON_ID];
            if (!entry || typeof entry !== 'object') continue;
            const userOutput = (entry.output || '').trim();
            const isCorrect  = newExpected !== '' && userOutput === newExpected;
            updates[`userMachineProblemAnswers/${uid}/${LESSON_ID}/isCorrect`] = isCorrect;
            touchedCount++;
        }
    }

    if (Object.keys(updates).length > 0) {
        await db.ref().update(updates);
    }

    return touchedCount;
}
</script>
</body>
</html>