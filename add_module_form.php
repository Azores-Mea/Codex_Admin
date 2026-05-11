<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CODEX | Add / Edit Lesson</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="dashboard.css">

    <!-- Firebase SDK -->
    <script src="https://www.gstatic.com/firebasejs/10.12.0/firebase-app-compat.js"></script>
    <script src="https://www.gstatic.com/firebasejs/10.12.0/firebase-database-compat.js"></script>
    <script src="https://www.gstatic.com/firebasejs/10.12.0/firebase-auth-compat.js"></script>
    <script src="https://www.gstatic.com/firebasejs/10.12.0/firebase-firestore-compat.js"></script>
    
    <script>
        // Only init Firebase if not already initialised (SPA re-uses the same page)
        if (!firebase.apps.length) {
            firebase.initializeApp({
                apiKey: "AIzaSyBmFwQe51Sfkhr36aXXlw4NYv7jag-8OcY",
                authDomain: "codex-f1355.firebaseapp.com",
                databaseURL: "https://codex-f1355-default-rtdb.firebaseio.com",
                projectId: "codex-f1355",
                storageBucket: "codex-f1355.firebasestorage.app",
                messagingSenderId: "273276166035",
                appId: "1:273276166035:web:e1f895eeaa03200a975266"
            });
        }
    </script>
    <script src="firebase-cache.js"></script>

    <style>
        .db-nav-item a           { display:flex; align-items:center; gap:12px; text-decoration:none; color:inherit; }
        .db-nav-item i           { width:20px; text-align:center; }
        .db-main                 { padding:32px 36px; background:#f1f5f9; overflow-y:auto; position:relative; width: 100%; }
        .form-container          { width: 100%; margin:0 auto; padding-bottom:80px; }

        .section-card            { background:#fff; border-radius:14px; padding:32px 36px; margin-bottom:20px; border:1.5px solid #d1d5db; }
        .section-card.blue-border{ border:2.5px solid #3b82f6; }
        .section-card h3         { margin-top:0; font-size:15px; color:#1e293b; }

        .edit-banner {
            background: linear-gradient(135deg, #fff7ed, #fffbeb);
            border: 2px solid #f59e0b; border-radius: 10px;
            padding: 12px 20px; margin-bottom: 22px;
            display: flex; align-items: center; gap: 10px;
            font-size: 13px; color: #92400e; font-weight: 600;
        }
        .edit-banner i { color: #f59e0b; font-size: 18px; }

        .lesson-id-row           { display:flex; align-items:center; gap:14px; margin-bottom:22px; flex-wrap:wrap; }
        .id-badge                { background:#001C30; color:#eab308; font-weight:800; font-size:15px; padding:8px 18px; border-radius:8px; letter-spacing:.5px; min-width:80px; text-align:center; }
        .id-badge.loading        { opacity:.5; }

        .difficulty-selector     { display:flex; gap:10px; flex-wrap:wrap; }
        .diff-pill               { padding:8px 22px; border-radius:50px; border:2px solid #e2e8f0; background:#fff; font-size:13px; font-weight:700; cursor:pointer; transition:all .2s; color:#64748b; }
        .diff-pill:hover         { border-color:#94a3b8; }
        .diff-pill.beginner.active   { background:#fef9c3; border-color:#eab308; color:#854d0e; }
        .diff-pill.intermediate.active { background:#dbeafe; border-color:#3b82f6; color:#1d4ed8; }
        .diff-pill.advanced.active   { background:#f3e8ff; border-color:#a855f7; color:#7e22ce; }

        .input-group             { margin-bottom:16px; }
        .input-group label       { display:block; font-size:12px; font-weight:700; color:#64748b; margin-bottom:5px; text-transform:uppercase; letter-spacing:.4px; }
        .input-field             { width:100%; padding:10px 12px; border:1.5px solid #cbd5e1; border-radius:8px; font-size:14px; box-sizing:border-box; background:#fff; transition:border-color .2s; }
        .input-field:focus       { outline:none; border-color:#3b82f6; }

        .rte-wrapper             { border:1.5px solid #cbd5e1; border-radius:8px; overflow:hidden; transition:border-color .2s; }
        .rte-wrapper:focus-within{ border-color:#3b82f6; }
        .rte-toolbar             { display:flex; gap:2px; padding:6px 8px; background:#f1f5f9; border-bottom:1px solid #e2e8f0; }
        .rte-btn                 { border:none; background:none; padding:4px 9px; border-radius:5px; cursor:pointer; font-size:13px; color:#475569; font-weight:700; transition:background .15s; }
        .rte-btn:hover           { background:#e2e8f0; }
        .rte-content             { min-height:90px; padding:10px 12px; font-size:14px; outline:none; line-height:1.65; color:#1e293b; }
        .rte-content:empty::before { content:attr(data-placeholder); color:#94a3b8; pointer-events:none; }
        .rte-content ol, .rte-content ul { padding-left:20px; margin:4px 0; }
        .rte-content p           { margin:3px 0; }

        .content-block           { background:#fff; border:1.5px solid #d1d5db; border-radius:14px; margin-bottom:24px; overflow:hidden; transition:opacity .3s, transform .3s; }
        .content-block-header    { display:flex; align-items:center; justify-content:space-between; padding:16px 28px; background:#f8fafc; border-bottom:1.5px solid #e2e8f0; }
        .sub-id-badge            { font-size:13px; font-weight:800; color:#001c30; background:#e0f2fe; padding:5px 14px; border-radius:20px; letter-spacing:.3px; }
        .remove-block-btn        { background:none; border:1px solid #fca5a5; color:#ef4444; padding:4px 12px; border-radius:20px; font-size:12px; cursor:pointer; font-weight:600; }
        .remove-block-btn:hover  { background:#fee2e2; }
        .content-block-body      { padding:28px 32px; }

        .sub-panel               { border-radius:10px; margin-bottom:18px; border:1.5px solid; overflow:hidden; }
        .sub-panel.title-panel   { border-color:#bfdbfe; }
        .sub-panel.example-panel { border-color:#fde68a; }
        .sub-panel.output-panel  { border-color:#bbf7d0; }
        .sub-panel.tooltip-panel { border-color:#e9d5ff; }

        .sub-panel-header        { display:flex; align-items:center; justify-content:space-between; padding:12px 20px; cursor:pointer; user-select:none; }
        .sub-panel.title-panel   .sub-panel-header { background:#eff6ff; }
        .sub-panel.example-panel .sub-panel-header { background:#fffbeb; }
        .sub-panel.output-panel  .sub-panel-header { background:#f0fdf4; }
        .sub-panel.tooltip-panel .sub-panel-header { background:#faf5ff; }

        .sub-panel-label         { font-size:12px; font-weight:800; text-transform:uppercase; letter-spacing:.8px; display:flex; align-items:center; gap:8px; }
        .sub-panel.title-panel   .sub-panel-label { color:#2563eb; }
        .sub-panel.example-panel .sub-panel-label { color:#d97706; }
        .sub-panel.output-panel  .sub-panel-label { color:#16a34a; }
        .sub-panel.tooltip-panel .sub-panel-label { color:#9333ea; }

        .sub-panel-toggle        { font-size:12px; color:#94a3b8; transition:transform .2s; }
        .sub-panel.collapsed     .sub-panel-toggle { transform:rotate(-90deg); }
        .sub-panel-body          { padding:20px 24px; border-top:1.5px solid; }
        .sub-panel.title-panel   .sub-panel-body { border-color:#bfdbfe; background:#fff; }
        .sub-panel.example-panel .sub-panel-body { border-color:#fde68a; background:#fff; }
        .sub-panel.output-panel  .sub-panel-body { border-color:#bbf7d0; background:#fff; }
        .sub-panel.tooltip-panel .sub-panel-body { border-color:#e9d5ff; background:#fff; }
        .sub-panel.collapsed     .sub-panel-body { display:none; }

        .media-slots-row         { display:flex; flex-wrap:wrap; gap:16px; margin-top:14px; }
        .add-media-btn           { display:flex; align-items:center; gap:7px; padding:9px 18px; border-radius:8px; border:1.5px dashed; background:none; font-size:12px; font-weight:700; cursor:pointer; transition:all .2s; }
        .add-media-btn.img-btn   { border-color:#6366f1; color:#6366f1; }
        .add-media-btn.img-btn:hover  { background:#eef2ff; }
        .add-media-btn.code-btn  { border-color:#0ea5e9; color:#0ea5e9; }
        .add-media-btn.code-btn:hover { background:#e0f2fe; }
        .add-media-btn:disabled  { opacity:.35; cursor:not-allowed; }

        .img-slot                { border:1.5px solid #c7d2fe; border-radius:12px; background:#f5f3ff; overflow:hidden; width:45%; flex-shrink:0; }
        .img-slot-header         { display:flex; align-items:center; justify-content:space-between; padding:10px 14px; background:#ede9fe; }
        .img-slot-label          { font-size:11px; font-weight:800; color:#6d28d9; text-transform:uppercase; letter-spacing:.5px; }
        .img-slot-remove         { background:none; border:none; color:#a78bfa; cursor:pointer; font-size:14px; }
        .img-slot-body           { padding:14px; display:flex; flex-direction:column; gap:10px; }

        .upload-area             { border:2px dashed #a5b4fc; border-radius:10px; padding:28px 14px; text-align:center; cursor:pointer; background:#fff; transition:all .2s; position:relative; }
        .upload-area:hover       { border-color:#6366f1; background:#eef2ff; }
        .upload-area input[type=file] { position:absolute; inset:0; opacity:0; cursor:pointer; width:100%; height:100%; }
        .upload-area-icon        { font-size:30px; color:#a5b4fc; margin-bottom:6px; }
        .upload-area-text        { font-size:12px; color:#94a3b8; }

        .img-preview-wrap        { position:relative; border-radius:10px; overflow:hidden; display:none; }
        .img-preview-wrap img    { width:100%; height:160px; object-fit:cover; display:block; border-radius:10px; }
        .img-preview-replace     {
            position:absolute; inset:0; background:rgba(0,0,0,.45); display:flex;
            flex-direction:column; align-items:center; justify-content:center;
            gap:4px; opacity:0; transition:opacity .2s; cursor:pointer; border-radius:10px;
        }
        .img-preview-wrap:hover .img-preview-replace { opacity:1; }
        .img-preview-replace input[type=file] { position:absolute; inset:0; opacity:0; cursor:pointer; width:100%; height:100%; }
        .img-preview-replace i   { color:#fff; font-size:22px; pointer-events:none; }
        .img-preview-replace span{ color:#fff; font-size:12px; font-weight:700; pointer-events:none; }

        .drawable-placeholder    {
            border-radius:10px; background:linear-gradient(135deg,#ede9fe,#ddd6fe);
            border:1.5px solid #c4b5fd; height:160px; display:none; flex-direction:column;
            align-items:center; justify-content:center; gap:6px; position:relative; cursor:pointer;
        }
        .drawable-placeholder i.dp-icon { font-size:30px; color:#7c3aed; pointer-events:none; }
        .drawable-placeholder .dp-name  { font-size:11px; font-weight:700; color:#6d28d9; text-align:center; word-break:break-all; padding:0 10px; pointer-events:none; }
        .drawable-placeholder .dp-hint  { font-size:10px; color:#a78bfa; pointer-events:none; }
        .drawable-placeholder input[type=file] { position:absolute; inset:0; opacity:0; cursor:pointer; width:100%; height:100%; }

        .file-chosen-badge {
            display:none; align-items:center; gap:6px; padding:6px 10px;
            background:#fef9c3; border-radius:6px; border:1px solid #fde68a;
            font-size:11px; color:#854d0e; font-weight:600;
        }
        .file-chosen-badge.show { display:flex; }

        .img-loading-shimmer     {
            border-radius:10px; height:160px; display:none; align-items:center;
            justify-content:center; background:linear-gradient(90deg,#f1f5f9 25%,#e2e8f0 50%,#f1f5f9 75%);
            background-size:200% 100%; animation:shimmer 1.4s infinite; font-size:12px; color:#94a3b8;
            flex-direction:column; gap:6px;
        }
        .img-loading-shimmer i   { font-size:22px; color:#cbd5e1; }
        @keyframes shimmer        { 0%{background-position:200% 0} 100%{background-position:-200% 0} }

        .img-helper-input        { width:100%; padding:8px 10px; border:1.5px solid #c4b5fd; border-radius:7px; font-size:12px; box-sizing:border-box; background:#fff; }
        .img-helper-input:focus  { outline:none; border-color:#6366f1; }

        .drawable-placeholder    {
            border-radius:10px; background:linear-gradient(135deg,#ede9fe,#ddd6fe);
            border:1.5px solid #c4b5fd; height:160px; display:none; flex-direction:column;
            align-items:center; justify-content:center; gap:6px; position:relative; cursor:pointer;
        }

        .code-slot               { border:1.5px solid #bae6fd; border-radius:12px; background:#f0f9ff; overflow:hidden; width:420px; flex-shrink:0; }
        .code-slot-header        { display:flex; align-items:center; justify-content:space-between; padding:10px 14px; background:#e0f2fe; }
        .code-slot-label         { font-size:11px; font-weight:800; color:#0369a1; text-transform:uppercase; letter-spacing:.5px; }
        .code-slot-remove        { background:none; border:none; color:#7dd3fc; cursor:pointer; font-size:14px; }
        .code-slot-body          { padding:14px; display:flex; flex-direction:column; gap:10px; }
        .code-textarea           { width:100%; min-height:160px; font-family:'Courier New', monospace; font-size:13px; padding:12px; border:1.5px solid #bae6fd; border-radius:10px; box-sizing:border-box; resize:vertical; background:#0f172a; color:#e2e8f0; line-height:1.7; }
        .code-textarea:focus     { outline:none; border-color:#0ea5e9; }
        .code-textarea::placeholder { color:#64748b; }
        .code-helper-input       { width:100%; padding:8px 10px; border:1.5px solid #7dd3fc; border-radius:7px; font-size:12px; box-sizing:border-box; background:#fff; }
        .code-helper-input:focus { outline:none; border-color:#0ea5e9; }

        .slots-container         { display:flex; flex-wrap:wrap; gap:16px; margin-top:14px; }
        .two-col                 { display:grid; grid-template-columns:1fr 1fr; gap:20px; }

        .add-content-btn         { display:flex; align-items:center; justify-content:center; gap:8px; width:100%; padding:15px; border:2px dashed #94a3b8; border-radius:12px; background:none; color:#64748b; font-size:14px; font-weight:700; cursor:pointer; transition:all .2s; margin-bottom:24px; }
        .add-content-btn:hover   { border-color:#3b82f6; color:#3b82f6; background:#eff6ff; }

        .footer-bar              { position:fixed; bottom:0; left:0; right:0; background:#fff; border-top:1px solid #e2e8f0; padding:14px 40px; display:flex; justify-content:flex-end; align-items:center; gap:16px; z-index:100; box-shadow:0 -2px 12px rgba(0,0,0,.06); }
        .btn-cancel              { background:none; border:1.5px solid #ef4444; color:#ef4444; padding:10px 30px; border-radius:25px; font-weight:700; font-size:14px; cursor:pointer; transition:.2s; }
        .btn-cancel:hover        { background:#fee2e2; }
        .btn-save-trigger        { background:#001c30; color:#fff; border:none; padding:10px 30px; border-radius:25px; font-weight:700; font-size:14px; cursor:pointer; display:flex; align-items:center; gap:8px; transition:background .2s; }
        .btn-save-trigger:hover  { background:#1e3a5f; }
        .btn-save-trigger:disabled{ opacity:.5; cursor:not-allowed; }

        .modal-overlay           { display:none; position:fixed; inset:0; background:rgba(15,23,42,.55); z-index:1000; align-items:center; justify-content:center; }
        .modal-overlay.show      { display:flex; }
        .save-modal              { background:#fff; padding:40px; border-radius:24px; width:400px; text-align:center; box-shadow:0 20px 60px rgba(0,0,0,.25); }
        .save-modal h2           { color:#001c30; font-size:24px; font-weight:800; margin-bottom:10px; }
        .save-modal p            { color:#64748b; font-size:14px; margin-bottom:28px; }
        .modal-buttons           { display:flex; gap:15px; }
        .btn-modal-cancel        { flex:1; padding:12px; border:1.5px solid #3b82f6; background:#fff; color:#3b82f6; border-radius:10px; font-weight:700; cursor:pointer; }
        .btn-modal-save          { flex:1; padding:12px; background:#001c30; color:#fff; border:none; border-radius:10px; font-weight:700; cursor:pointer; }
        .btn-modal-save:disabled { opacity:.5; cursor:not-allowed; }

        .save-progress-overlay {
            display:none; position:fixed; inset:0; background:rgba(15,23,42,.6);
            z-index:1100; align-items:center; justify-content:center;
        }
        .save-progress-overlay.show { display:flex; }
        .save-progress-box {
            background:#fff; padding:32px 40px; border-radius:20px; min-width:320px;
            text-align:center; box-shadow:0 20px 60px rgba(0,0,0,.3);
        }
        .save-progress-box h3 { color:#001c30; font-size:17px; font-weight:800; margin:0 0 8px; }
        .save-progress-box p  { color:#64748b; font-size:13px; margin:0 0 20px; }
        .save-progress-bar-wrap { background:#e2e8f0; border-radius:20px; height:8px; overflow:hidden; }
        .save-progress-bar      { background:linear-gradient(90deg,#3b82f6,#6366f1); height:100%; width:0%; border-radius:20px; transition:width .3s ease; }
        .save-progress-label    { margin-top:10px; font-size:12px; color:#94a3b8; font-weight:600; }

        .toast                   { position:fixed; bottom:30px; right:30px; padding:14px 22px; border-radius:10px; font-size:14px; font-weight:600; color:#fff; z-index:2000; transform:translateY(20px); opacity:0; transition:all .3s; pointer-events:none; }
        .toast.show              { transform:translateY(0); opacity:1; }
        .toast.success           { background:#16a34a; }
        .toast.error             { background:#dc2626; }

        .spinner                 { display:inline-block; width:14px; height:14px; border:2px solid rgba(255,255,255,.3); border-top-color:#fff; border-radius:50%; animation:spin .7s linear infinite; }
        @keyframes spin          { to { transform:rotate(360deg); } }

        .media-add-bar           { display:flex; align-items:center; gap:12px; margin-top:14px; flex-wrap:wrap; }
        .media-count-note        { font-size:11px; color:#94a3b8; font-style:italic; }

        .tooltip-textarea        { width:100%; min-height:90px; padding:10px 12px; border:1.5px solid #e9d5ff; border-radius:8px; font-size:14px; box-sizing:border-box; resize:vertical; background:#fff; color:#1e293b; line-height:1.65; font-family:inherit; transition:border-color .2s; }
        .tooltip-textarea:focus  { outline:none; border-color:#a855f7; }
        .tooltip-textarea::placeholder { color:#94a3b8; }

        .page-loading {
            position:fixed; inset:0; background:rgba(248,250,252,.92);
            display:flex; flex-direction:column; align-items:center; justify-content:center;
            z-index:500; gap:16px;
        }
        .page-loading p { color:#1e293b; font-weight:600; font-size:15px; }
        .page-loading.hidden { display:none; }

        @media (max-width:1100px) {
            .db-main  { padding:24px 20px; }
            .two-col  { grid-template-columns:1fr; }
            .code-slot { width:100%; }
            .img-slot  { width:260px; }
        }
        @media (max-width:700px) {
            .img-slot  { width:100%; }
            .code-slot { width:100%; }
        }
    </style>
</head>
<body>

<!-- ── Page loading overlay ────── -->
<div class="page-loading hidden" id="pageLoading">
    <span class="spinner" style="width:28px;height:28px;border-width:3px;border-color:#dbeafe;border-top-color:#3b82f6;"></span>
    <p>Loading lesson data…</p>
</div>

<!-- ── Save progress overlay ────── -->
<div class="save-progress-overlay" id="saveProgressOverlay">
    <div class="save-progress-box">
        <h3>Saving Lesson…</h3>
        <p id="saveProgressDetail">Uploading images and saving data</p>
        <div class="save-progress-bar-wrap">
            <div class="save-progress-bar" id="saveProgressBar"></div>
        </div>
        <div class="save-progress-label" id="saveProgressLabel">Please wait…</div>
    </div>
</div>

<!-- ══ MAIN ══════════════════════════════════════════════════════════════ -->
<main class="db-main">
<div class="form-container">

    <!-- Edit banner — shown/hidden by JS -->
    <div class="edit-banner" id="editBanner" style="display:none;">
        <i class="fa-solid fa-pen-to-square"></i>
        Editing Lesson <strong id="editBannerId"></strong> — changes will overwrite the existing content.
    </div>

    <h2 style="margin-bottom:24px;font-weight:800;font-size:20px;color:#001c30;" id="formHeading">
        <i class="fa-solid fa-plus-circle" style="color:#3b82f6;margin-right:8px;" id="formHeadingIcon"></i>
        <span id="formHeadingText">Add New Lesson</span>
    </h2>

    <!-- ── Lesson Header ──────────────────────────────────────────────── -->
    <div class="section-card blue-border">
        <h3>Lesson Identity &amp; Overview</h3>

        <div class="lesson-id-row">
            <div>
                <div style="font-size:11px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:.4px;margin-bottom:5px;">Lesson ID</div>
                <div class="id-badge loading" id="lessonIdBadge">Loading…</div>
            </div>
            <div>
                <div style="font-size:11px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:.4px;margin-bottom:8px;">Difficulty Level</div>
                <div class="difficulty-selector">
                    <button type="button" class="diff-pill beginner"     data-value="Beginner"     onclick="AMF_selectDifficulty(this)">Beginner</button>
                    <button type="button" class="diff-pill intermediate" data-value="Intermediate" onclick="AMF_selectDifficulty(this)">Intermediate</button>
                    <button type="button" class="diff-pill advanced"     data-value="Advanced"     onclick="AMF_selectDifficulty(this)">Advanced</button>
                </div>
                <input type="hidden" id="difficulty" value="">
            </div>
        </div>

        <div class="two-col">
            <div class="input-group">
                <label>Main Title</label>
                <input type="text" class="input-field" id="mainTitle" placeholder="e.g. Introduction to Java">
            </div>
            <div></div>
        </div>

        <div class="input-group">
            <label>Title Description</label>
            <div class="rte-wrapper">
                <div class="rte-toolbar">
                    <button type="button" class="rte-btn" onclick="AMF_rteFormat('titleDesc','bold')"><b>B</b></button>
                    <button type="button" class="rte-btn" onclick="AMF_rteFormat('titleDesc','italic')"><i>I</i></button>
                    <button type="button" class="rte-btn" onclick="AMF_rteFormat('titleDesc','insertUnorderedList')"><i class="fa-solid fa-list-ul"></i></button>
                    <button type="button" class="rte-btn" onclick="AMF_rteFormat('titleDesc','insertOrderedList')"><i class="fa-solid fa-list-ol"></i></button>
                </div>
                <div class="rte-content" id="rte_titleDesc" contenteditable="true"
                     data-placeholder="Briefly describe what this lesson covers…"
                     data-rte-id="titleDesc"></div>
            </div>
        </div>
    </div>

    <!-- ── Content Blocks ─────────────────────────────────────────────── -->
    <div id="contentBlocksContainer"></div>

    <button class="add-content-btn" id="addContentBtn" onclick="AMF_addContentBlock()">
        <i class="fa-solid fa-plus"></i> Add Content Block
    </button>
</div>


</main>

<!-- ══ STICKY FOOTER ═════════════════════════════════════════════════════ -->
<div class="footer-bar">
    <button class="btn-cancel" onclick="AMF_goToPage('course_management')">
        <i class="fa-solid fa-xmark" style="margin-right:4px;"></i> Cancel
    </button>
    <button class="btn-save-trigger" id="saveTriggerBtn" onclick="AMF_openSaveModal()">
        <i class="fa-solid fa-floppy-disk"></i>
        <span id="saveBtnLabel">Save Lesson</span>
    </button>
</div>

<!-- ══ SAVE MODAL ════════════════════════════════════════════════════════ -->
<div id="saveModal" class="modal-overlay">
    <div class="save-modal">
        <i class="fa-solid fa-floppy-disk" style="font-size:32px;color:#3b82f6;margin-bottom:14px;display:block;"></i>
        <h2 id="saveModalTitle">Save this lesson?</h2>
        <p id="saveModalSummary">This will save the lesson to the database.</p>
        <div class="modal-buttons">
            <button class="btn-modal-cancel" onclick="AMF_closeSaveModal()">Cancel</button>
            <button class="btn-modal-save" id="confirmSaveBtn" onclick="AMF_confirmSave()">
                <i class="fa-solid fa-check" style="margin-right:6px;"></i>
                <span id="confirmSaveBtnLabel">Save</span>
            </button>
        </div>
    </div>
</div>

<!-- Toast -->
<div class="toast" id="toast"></div>


<!-- ══ JAVASCRIPT ════════════════════════════════════════════════════════ -->
<script>
(function () {  // ── IIFE to avoid global collision ──

/* ════════════════════════════════════════════════════════════════════════
   FIX 2: Always read supabaseClient from window so the IIFE picks up the
   instance regardless of whether this script runs inline or is injected
   by the SPA after the outer <script> tags have already executed.
   ════════════════════════════════════════════════════════════════════════ */
function getSupabase() {
    if (window.supabaseClient) return window.supabaseClient;
    throw new Error('Supabase not initialized — check index.php.');
}

/* ════════════════════════════════════════════════════════════════════════
   READ PARAMS FROM sessionStorage (SPA pattern)
   ════════════════════════════════════════════════════════════════════════ */
function readNavParams() {
    let raw = sessionStorage.getItem('navParams_add_module_form');
    if (raw) {
        // Do NOT remove here — keep it so page reloads survive
        try { return JSON.parse(raw); } catch(e) {}
    }
    const params = new URLSearchParams(window.location.search);
    const edit   = params.get('edit')  || '';
    const level  = params.get('level') || '';
    if (edit)  return { edit };
    if (level) return { level };
    return {};
}

const navParams      = readNavParams();
const EDIT_LESSON_ID = navParams.edit  || '';
const IS_EDIT_MODE   = EDIT_LESSON_ID !== '';
const PRESET_LEVEL   = navParams.level || '';

/* ─── Apply edit mode UI immediately ────────────────────────────────────── */
if (IS_EDIT_MODE) {
    document.getElementById('pageLoading').classList.remove('hidden');
    document.getElementById('editBanner').style.display  = 'flex';
    document.getElementById('editBannerId').textContent  = EDIT_LESSON_ID;
    document.getElementById('lessonIdBadge').textContent = EDIT_LESSON_ID;
    document.getElementById('lessonIdBadge').classList.remove('loading');
    document.getElementById('formHeadingIcon').className = 'fa-solid fa-pen-to-square';
    document.getElementById('formHeadingText').textContent = 'Edit Lesson';
    document.getElementById('saveModalTitle').textContent  = 'Update this lesson?';
    document.getElementById('saveBtnLabel').textContent    = 'Update Lesson';
    document.getElementById('confirmSaveBtnLabel').textContent = 'Update';
}

/* ─── Apply preset difficulty immediately ────────────────────────────────── */
if (PRESET_LEVEL) {
    document.querySelectorAll('.diff-pill').forEach(p => {
        p.classList.toggle('active', p.dataset.value === PRESET_LEVEL);
    });
    document.getElementById('difficulty').value = PRESET_LEVEL;
}

/* ─── Shared state ───────────────────────────────────────────────────────── */
let currentLessonId = IS_EDIT_MODE ? EDIT_LESSON_ID : null;

const MAX_MEDIA = {
    title:   { img: 3, code: 3 },
    example: { img: 2, code: 2 },
    output:  { img: 2, code: 2 },
};

/* ─── Navigation ─────────────────────────────────────────────────────────── */
function goToPage(page) {
    if (typeof window.navigate === 'function') {
        window.navigate(page);
    } else {
        window.location.href = 'index.php?page=' + page;
    }
}
window.AMF_goToPage = goToPage;

/* ─── Difficulty selector ────────────────────────────────────────────────── */
function selectDifficulty(btn) {
    document.querySelectorAll('.diff-pill').forEach(p => p.classList.remove('active'));
    btn.classList.add('active');
    document.getElementById('difficulty').value = btn.dataset.value;
}
window.AMF_selectDifficulty = selectDifficulty;

/* ─── Boot ───────────────────────────────────────────────────────────────── */
async function init() {
    // Ensure supabase client is ready before anything else
    if (!window.supabaseClient) {
        const lib = window.supabase || window.Supabase;
        if (lib) {
            window.supabaseClient = lib.createClient(
                'https://vcytslokgjnzlkpfxnno.supabase.co',
                'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...'
            );
        }
    }

    if (IS_EDIT_MODE) {
        await loadExistingLesson(EDIT_LESSON_ID);
    } else {
        await loadNextLessonId();
        addContentBlock();
    }
    attachAllRteListeners(document.querySelector('.section-card.blue-border'));
}
window.AMF_init = init;

/* ─── Load next lesson ID (add mode) ────────────────────────────────────── */
async function loadNextLessonId() {
    const badge = document.getElementById('lessonIdBadge');

    try {
        const snapshot = await (typeof FirebaseCache !== 'undefined'
            ? FirebaseCache.get('Lessons')
            : firebase.database().ref('Lessons').once('value'));
        const data = snapshot.val() || {};
        const nums = Object.keys(data)
            .filter(k => /^L\d+$/.test(k))
            .map(k => parseInt(k.slice(1), 10));
        const nextNum = nums.length ? Math.max(...nums) + 1 : 1;
        currentLessonId = 'L' + nextNum;
        badge.textContent = currentLessonId;
        badge.classList.remove('loading');
    } catch (e) {
        badge.textContent = 'Error';
        showToast('Firebase error: ' + e.message, 'error');
    }
}

/* ─── Load existing lesson (edit mode) ───────────────────────────────────── 
   FIX 3: Always fetch directly from Firebase (bypass cache) so editing
   always shows the latest saved data, not whatever was cached from the
   course management list.
   ─────────────────────────────────────────────────────────────────────── */
async function loadExistingLesson(lessonId) {
    try {
        // ── Direct read, no cache ──────────────────────────────────────
        const snapshot = await firebase.database()
            .ref('Lessons/' + lessonId)
            .once('value');
        const data = snapshot.val();

        if (!data) {
            showToast('Lesson not found: ' + lessonId, 'error');
            document.getElementById('pageLoading').classList.add('hidden');
            addContentBlock();
            return;
        }

        const diff = data.difficulty || '';
        document.getElementById('difficulty').value = diff;
        document.querySelectorAll('.diff-pill').forEach(p => {
            p.classList.toggle('active', p.dataset.value === diff);
        });

        document.getElementById('mainTitle').value = stripHTML(data.main_title || '');
        document.getElementById('rte_titleDesc').innerHTML = asteriskToStrong(data.title_desc || '');

        const content  = data.content || {};
        const blockIds = Object.keys(content).sort((a, b) =>
            parseInt(a.replace(/\D/g, ''), 10) - parseInt(b.replace(/\D/g, ''), 10)
        );

        if (blockIds.length === 0) {
            addContentBlock();
        } else {
            blockIds.forEach(blockId => {
                addContentBlock(blockId);
                populateContentBlock(blockId, content[blockId]);
            });
        }

    } catch (e) {
        showToast('Error loading lesson: ' + e.message, 'error');
    } finally {
        document.getElementById('pageLoading').classList.add('hidden');
    }
}

/* ─── Populate a content block with existing data ────────────────────────── */
function populateContentBlock(blockId, block) {
    const pfxBase    = 'cb_' + blockId;
    const pfxTitle   = pfxBase + '_TITLE';
    const pfxExample = pfxBase + '_EXAMPLE';
    const pfxOutput  = pfxBase + '_OUTPUT';

    const titleData = block.TITLE || {};
    setField(pfxTitle + '_title', stripHTML(titleData.title       || ''));
    setRTE(pfxTitle + '_desc',    titleData.description || '');

    restoreImgCodeSlots(pfxTitle, 'title', [
        { helperUrl: titleData.helper1Url, drawable: titleData.helper1Drawable, helper: titleData.helper1, code: titleData.helper1Code },
        { helperUrl: titleData.helper2Url, drawable: titleData.helper2Drawable, helper: titleData.helper2, code: titleData.helper2Code },
        { helperUrl: titleData.helper3Url, drawable: titleData.helper3Drawable, helper: titleData.helper3, code: titleData.helper3Code },
    ]);

    const exTypes = block.EXAMPLE?.TYPES || {};
    if (exTypes.exampleTitle || exTypes.exampleDescription ||
        exTypes.helper4 || exTypes.helper4Code || exTypes.helper4Url) {
        setField(pfxExample + '_title', stripHTML(exTypes.exampleTitle       || ''));
        setRTE(pfxExample + '_desc',    exTypes.exampleDescription || '');
        restoreImgCodeSlots(pfxExample, 'example', [
            { helperUrl: exTypes.helper4Url, drawable: exTypes.helper4Drawable, helper: exTypes.helper4, code: exTypes.helper4Code },
            { helperUrl: exTypes.helper5Url, drawable: exTypes.helper5Drawable, helper: exTypes.helper5, code: exTypes.helper5Code },
        ]);
    }

    const outData = block.SUBTITLE?.OUTPUT || {};
    if (outData.subtitle || outData.helper6 || outData.helper6Code || outData.helper6Url) {
        setField(pfxOutput + '_subtitle', stripHTML(outData.subtitle || ''));
        restoreImgCodeSlots(pfxOutput, 'output', [
            { helperUrl: outData.helper6Url, drawable: outData.helper6Drawable, helper: outData.helper6, code: outData.helper6Code },
            { helperUrl: outData.helper7Url, drawable: outData.helper7Drawable, helper: outData.helper7, code: outData.helper7Code },
        ]);
    }

    const tooltipEl = document.getElementById(pfxBase + '_tooltipText');
    if (tooltipEl) tooltipEl.value = stripHTML(block.TOOLTIP?.tooltip || '');
}

/* ─── Restore image + code slots from existing data ─────────────────────── */
function restoreImgCodeSlots(prefix, sectionType, slots) {
    const maxConfig = MAX_MEDIA[sectionType];
    let imgCount = 0, codeCount = 0;

    slots.forEach(slot => {
        const imageValue = slot.helperUrl || slot.drawable || '';
        const hasImage   = imageValue.trim() !== '';
        const hasHelper  = slot.helper && slot.helper.trim() && slot.helper.trim() !== '<br/>';
        const hasCode    = slot.code   && slot.code.trim();

        if ((hasImage || hasHelper) && imgCount < maxConfig.img) {
            imgCount++;
            const slotId = addImgSlot(prefix, sectionType);
            if (!slotId) return;
            if (hasImage) showDrawablePlaceholder(slotId, imageValue);
            const helperEl = document.getElementById('imgHelper_' + slotId);
            if (helperEl) helperEl.value = stripHTML(slot.helper || '');
        }

        if (hasCode && codeCount < maxConfig.code) {
            codeCount++;
            const slotId = addCodeSlot(prefix, sectionType);
            if (!slotId) return;
            const codeEl   = document.getElementById('codeSnippet_' + slotId);
            if (codeEl) codeEl.value = decodeCode(slot.code);
            const helperEl = document.getElementById('codeHelper_' + slotId);
            if (helperEl) helperEl.value = stripHTML(slot.helper || '');
        }
    });
}

function decodeCode(encoded) {
    if (!encoded) return '';
    return encoded
        .replace(/<br\/>/g, '\n')
        .replace(/&nbsp;/g, ' ')
        .replace(/&amp;/g, '&')
        .replace(/&lt;/g, '<')
        .replace(/&gt;/g, '>');
}

/* ─── Helpers ────────────────────────────────────────────────────────────── */
function stripHTML(html) {
    const tmp = document.createElement('div');
    tmp.innerHTML = html || '';
    return (tmp.textContent || tmp.innerText || '').trim();
}
function setField(id, value) { const el = document.getElementById(id); if (el) el.value = value; }
function setRTE(fullId, html) {
    const el = document.getElementById('rte_' + fullId);
    if (!el) return;
    el.innerHTML = asteriskToStrong(html);
}
function asteriskToStrong(html) {
    if (!html) return html;
    return html.replace(/\*([^*<>\n]+?)\*/g, '<strong>$1</strong>');
}

function attachAsteriskListener(el) {
    el.addEventListener('input', () => {
        const sel = window.getSelection();
        if (!sel.rangeCount) return;
        const walker = document.createTreeWalker(el, NodeFilter.SHOW_TEXT);
        let node;
        while ((node = walker.nextNode())) {
            const val = node.nodeValue;
            if (!val.includes('*')) continue;
            const match = /\*([^*\n]+?)\*/.exec(val);
            if (!match) continue;
            const before = val.slice(0, match.index);
            const inner  = match[1];
            const after  = val.slice(match.index + match[0].length);
            const strong = document.createElement('strong');
            strong.textContent = inner;
            const parent = node.parentNode;
            if (before) parent.insertBefore(document.createTextNode(before), node);
            parent.insertBefore(strong, node);
            const afterNode = document.createTextNode(after);
            parent.insertBefore(afterNode, node);
            parent.removeChild(node);
            const range = document.createRange();
            range.setStart(afterNode, 0);
            range.collapse(true);
            sel.removeAllRanges();
            sel.addRange(range);
            break;
        }
    });
}

function attachAllRteListeners(root) {
    (root || document).querySelectorAll('.rte-content[data-rte-id]').forEach(el => {
        if (el.dataset.asteriskAttached) return;
        el.dataset.asteriskAttached = '1';
        attachAsteriskListener(el);
    });
}

/* ─── Rich text ──────────────────────────────────────────────────────────── */
function rteFormat(id, command) {
    const el = document.getElementById('rte_' + id);
    if (!el) return;
    el.focus();
    document.execCommand(command, false, null);
}
window.AMF_rteFormat = rteFormat;

function getRTE(fullId) {
    const el = document.getElementById('rte_' + fullId);
    if (!el) return '<br/>';
    return el.innerHTML.trim() || '<br/>';
}

function buildRTE(fullId, placeholder = 'Enter text…') {
    return `
    <div class="rte-wrapper">
        <div class="rte-toolbar">
            <button type="button" class="rte-btn" onclick="AMF_rteFormat('${fullId}','bold')"><b>B</b></button>
            <button type="button" class="rte-btn" onclick="AMF_rteFormat('${fullId}','italic')"><i>I</i></button>
            <button type="button" class="rte-btn" onclick="AMF_rteFormat('${fullId}','insertUnorderedList')"><i class="fa-solid fa-list-ul"></i></button>
            <button type="button" class="rte-btn" onclick="AMF_rteFormat('${fullId}','insertOrderedList')"><i class="fa-solid fa-list-ol"></i></button>
        </div>
        <div class="rte-content" id="rte_${fullId}" contenteditable="true"
             data-placeholder="${placeholder}"
             data-rte-id="${fullId}"></div>
    </div>`;
}

/* ─── Image slot builder ─────────────────────────────────────────────────── */
function buildImgSlot(slotId, label) {
    return `
    <div class="img-slot" id="imgSlot_${slotId}">
        <div class="img-slot-header">
            <span class="img-slot-label"><i class="fa-solid fa-image" style="margin-right:4px;"></i>${label}</span>
            <button type="button" class="img-slot-remove" onclick="AMF_removeSlot('imgSlot_${slotId}','${slotId}','img')" title="Remove"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <div class="img-slot-body">
            <div class="img-loading-shimmer" id="imgShimmer_${slotId}">
                <i class="fa-solid fa-image"></i><span>Loading…</span>
            </div>
            <div class="upload-area" id="uploadArea_${slotId}">
                <input type="file" accept="image/*" id="imgFile_${slotId}"
                       onchange="AMF_handleFileSelect(event, '${slotId}')">
                <div class="upload-area-icon"><i class="fa-solid fa-cloud-arrow-up"></i></div>
                <div class="upload-area-text">Click to upload image</div>
            </div>
            <div class="img-preview-wrap" id="imgPreviewWrap_${slotId}">
                <img id="imgPreview_${slotId}" alt="preview">
                <div class="img-preview-replace">
                    <input type="file" accept="image/*"
                           onchange="AMF_handleFileSelect(event, '${slotId}')">
                    <i class="fa-solid fa-arrow-up-from-bracket"></i>
                    <span>Replace</span>
                </div>
            </div>
            <div class="drawable-placeholder" id="drawablePlaceholder_${slotId}">
                <input type="file" accept="image/*"
                       onchange="AMF_handleFileSelect(event, '${slotId}')">
                <i class="fa-solid fa-image dp-icon"></i>
                <div class="dp-name" id="drawableName_${slotId}"></div>
                <div class="dp-hint">Click to replace</div>
            </div>
            <div class="file-chosen-badge" id="fileChosenBadge_${slotId}">
                <i class="fa-solid fa-check-circle" style="color:#d97706;"></i>
                <span id="fileChosenName_${slotId}">Image ready — will upload on save</span>
            </div>
            <input type="text" class="img-helper-input" id="imgHelper_${slotId}" placeholder="Helper description for this image…">
        </div>
    </div>`;
}

/* ─── Code slot builder ──────────────────────────────────────────────────── */
function buildCodeSlot(slotId, label) {
    return `
    <div class="code-slot" id="codeSlot_${slotId}">
        <div class="code-slot-header">
            <span class="code-slot-label"><i class="fa-solid fa-code" style="margin-right:4px;"></i>${label}</span>
            <button type="button" class="code-slot-remove" onclick="AMF_removeSlot('codeSlot_${slotId}','${slotId}','code')" title="Remove"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <div class="code-slot-body">
            <textarea class="code-textarea" id="codeSnippet_${slotId}" placeholder="// Paste your code here…"></textarea>
            <input type="text" class="code-helper-input" id="codeHelper_${slotId}" placeholder="Helper description for this code…">
        </div>
    </div>`;
}

/* ─── File select handler ────────────────────────────────────────────────── */
function handleFileSelect(event, slotId) {
    const file = event.target.files && event.target.files[0];
    if (!file) return;
    const slotEl = document.getElementById('imgSlot_' + slotId);
    if (slotEl) slotEl._pendingFile = file;

    const uploadArea  = document.getElementById('uploadArea_'          + slotId);
    const previewWrap = document.getElementById('imgPreviewWrap_'      + slotId);
    const previewImg  = document.getElementById('imgPreview_'          + slotId);
    const placeholder = document.getElementById('drawablePlaceholder_' + slotId);
    const shimmer     = document.getElementById('imgShimmer_'          + slotId);
    const badge       = document.getElementById('fileChosenBadge_'     + slotId);
    const badgeName   = document.getElementById('fileChosenName_'      + slotId);

    const reader = new FileReader();
    reader.onload = e => {
        previewImg.src                = e.target.result;
        previewImg.dataset.storageUrl = '';
        previewWrap.style.display     = 'block';
        if (uploadArea)  uploadArea.style.display  = 'none';
        if (placeholder) placeholder.style.display = 'none';
        if (shimmer)     shimmer.style.display     = 'none';
        if (badge)       badge.classList.add('show');
        if (badgeName)   badgeName.textContent     = file.name + ' — will upload on save';
    };
    reader.readAsDataURL(file);
}
window.AMF_handleFileSelect = handleFileSelect;

/* ─── Compress image ─────────────────────────────────────────────────────── */
function compressImage(file, maxPx = 360, maxKB = 50) {
    return new Promise(resolve => {
        const reader = new FileReader();
        reader.onload = e => {
            const img = new Image();
            img.onload = () => {
                let { width, height } = img;
                if (width > maxPx || height > maxPx) {
                    if (width >= height) { height = Math.round(height * maxPx / width); width = maxPx; }
                    else { width = Math.round(width * maxPx / height); height = maxPx; }
                }
                const canvas = document.createElement('canvas');
                canvas.width = width; canvas.height = height;
                canvas.getContext('2d').drawImage(img, 0, 0, width, height);
                let quality = 0.6;
                let dataUrl = canvas.toDataURL('image/jpeg', quality);
                while (dataUrl.length > maxKB * 1024 * 1.37 && quality > 0.3) {
                    quality -= 0.1;
                    dataUrl = canvas.toDataURL('image/jpeg', quality);
                }
                const byteStr = atob(dataUrl.split(',')[1]);
                const arr = new Uint8Array(byteStr.length);
                for (let i = 0; i < byteStr.length; i++) arr[i] = byteStr.charCodeAt(i);
                resolve(new Blob([arr], { type: 'image/jpeg' }));
            };
            img.src = e.target.result;
        };
        reader.readAsDataURL(file);
    });
}

async function uploadInBatches(jobs, lessonId, batchSize = 3) {
    const results = [];
    for (let i = 0; i < jobs.length; i += batchSize) {
        const batch = jobs.slice(i, i + batchSize);
        const batchResults = await Promise.all(batch.map(img => resolveImageUrl(img, lessonId)));
        results.push(...batchResults);
    }
    return results;
}

async function uploadImageToStorage(file, lessonId) {
    // ── FIX 4: use getSupabase() instead of bare supabaseClient ──
    const client = getSupabase();
    const path       = `L/${lessonId}/${Date.now()}_${Math.random().toString(36).slice(2)}.jpg`;
    const compressed = await compressImage(file);
    const { data, error } = await client.storage.from('lesson-images')
        .upload(path, compressed, { contentType: 'image/jpeg', upsert: false });
    if (error) throw new Error('Supabase upload failed: ' + error.message);
    const { data: urlData } = client.storage.from('lesson-images').getPublicUrl(data.path);
    return urlData.publicUrl;
}

function showDrawablePlaceholder(slotId, drawableName) {
    const uploadArea  = document.getElementById('uploadArea_'          + slotId);
    const placeholder = document.getElementById('drawablePlaceholder_' + slotId);
    const previewWrap = document.getElementById('imgPreviewWrap_'      + slotId);
    const previewImg  = document.getElementById('imgPreview_'          + slotId);
    const nameEl      = document.getElementById('drawableName_'        + slotId);
    const shimmer     = document.getElementById('imgShimmer_'          + slotId);
    if (!drawableName) return;
    if (placeholder) placeholder.dataset.drawableName = drawableName;

    if (drawableName.startsWith('https://') || drawableName.startsWith('http://')) {
        if (uploadArea)  uploadArea.style.display  = 'none';
        if (shimmer)     shimmer.style.display     = 'none';
        if (placeholder) placeholder.style.display = 'none';
        previewImg.src                             = drawableName;
        previewImg.dataset.storageUrl              = drawableName;
        previewWrap.style.display                  = 'block';
        return;
    }
    if (shimmer)     shimmer.style.display     = 'none';
    if (uploadArea)  uploadArea.style.display  = 'none';
    if (placeholder) {
        placeholder.style.display = 'flex';
        if (nameEl) nameEl.textContent = drawableName;
    }
}

/* ─── Remove a slot ──────────────────────────────────────────────────────── */
function removeSlot(domId, slotId, type) {
    const el = document.getElementById(domId);
    if (!el) return;
    const sectionEl  = el.closest('[data-section-prefix]');
    if (!sectionEl) { el.remove(); return; }
    const prefix      = sectionEl.dataset.sectionPrefix;
    const sectionType = sectionEl.dataset.sectionType;
    el.remove();
    updateAddButtons(prefix, sectionType, MAX_MEDIA[sectionType]);
}
window.AMF_removeSlot = removeSlot;

/* ─── Add Image slot ─────────────────────────────────────────────────────── */
function addImgSlot(prefix, sectionType) {
    const maxConfig  = MAX_MEDIA[sectionType];
    const container  = document.getElementById('imgContainer_' + prefix);
    if (!container) return null;
    const existing = container.querySelectorAll('.img-slot').length;
    if (existing >= maxConfig.img) return null;
    const n      = existing + 1;
    const slotId = prefix + '_img' + n;
    container.insertAdjacentHTML('beforeend', buildImgSlot(slotId, 'Image ' + n));
    updateAddButtons(prefix, sectionType, maxConfig);
    return slotId;
}
window.AMF_addImgSlot = addImgSlot;

/* ─── Add Code slot ──────────────────────────────────────────────────────── */
function addCodeSlot(prefix, sectionType) {
    const maxConfig  = MAX_MEDIA[sectionType];
    const container  = document.getElementById('codeContainer_' + prefix);
    if (!container) return null;
    const existing = container.querySelectorAll('.code-slot').length;
    if (existing >= maxConfig.code) return null;
    const n      = existing + 1;
    const slotId = prefix + '_code' + n;
    container.insertAdjacentHTML('beforeend', buildCodeSlot(slotId, 'Code Snippet ' + n));
    updateAddButtons(prefix, sectionType, maxConfig);
    return slotId;
}
window.AMF_addCodeSlot = addCodeSlot;

/* ─── Update add button disabled state ──────────────────────────────────── */
function updateAddButtons(prefix, sectionType, maxConfig) {
    const imgContainer  = document.getElementById('imgContainer_'  + prefix);
    const codeContainer = document.getElementById('codeContainer_' + prefix);
    const imgBtn        = document.getElementById('imgAddBtn_'  + prefix);
    const codeBtn       = document.getElementById('codeAddBtn_' + prefix);
    const imgNote       = document.getElementById('imgNote_'  + prefix);
    const codeNote      = document.getElementById('codeNote_' + prefix);
    if (imgContainer && imgBtn) {
        const cnt = imgContainer.querySelectorAll('.img-slot').length;
        imgBtn.disabled = cnt >= maxConfig.img;
        if (imgNote) imgNote.textContent = `${cnt}/${maxConfig.img} images`;
    }
    if (codeContainer && codeBtn) {
        const cnt = codeContainer.querySelectorAll('.code-slot').length;
        codeBtn.disabled = cnt >= maxConfig.code;
        if (codeNote) codeNote.textContent = `${cnt}/${maxConfig.code} snippets`;
    }
}

/* ─── Build a section panel ──────────────────────────────────────────────── */
function buildSectionPanel(panelClass, iconClass, panelLabel, prefix, sectionType, includeSubtitle, panelId) {
    const maxConfig   = MAX_MEDIA[sectionType];
    const subtitleRow = includeSubtitle ? `
        <div class="input-group" style="margin-bottom:10px;">
            <label>Subtitle / Output Label</label>
            <input type="text" class="input-field" id="${prefix}_subtitle" placeholder="e.g. Output:">
        </div>` : '';

    return `
    <div class="sub-panel ${panelClass}" id="${panelId}">
        <div class="sub-panel-header" onclick="AMF_togglePanel('${panelId}')">
            <span class="sub-panel-label"><i class="${iconClass}"></i> ${panelLabel}</span>
            <i class="fa-solid fa-chevron-down sub-panel-toggle"></i>
        </div>
        <div class="sub-panel-body">
            ${subtitleRow}
            <div class="input-group" style="margin-bottom:8px;">
                <label>${includeSubtitle ? 'Output Title' : 'Title'}</label>
                <input type="text" class="input-field" id="${prefix}_title" placeholder="${includeSubtitle ? 'e.g. Output:' : 'e.g. What is Java?'}">
            </div>
            <div class="input-group" style="margin-bottom:14px;">
                <label>Description</label>
                ${buildRTE(prefix + '_desc', 'Explain this section in detail…')}
            </div>
            <div data-section-prefix="${prefix}" data-section-type="${sectionType}">
                <div class="media-add-bar">
                    <button type="button" class="add-media-btn img-btn" id="imgAddBtn_${prefix}" onclick="AMF_addImgSlot('${prefix}','${sectionType}')">
                        <i class="fa-solid fa-image"></i> Add Image
                    </button>
                    <span class="media-count-note" id="imgNote_${prefix}">0/${maxConfig.img} images</span>
                    <button type="button" class="add-media-btn code-btn" id="codeAddBtn_${prefix}" onclick="AMF_addCodeSlot('${prefix}','${sectionType}')">
                        <i class="fa-solid fa-code"></i> Add Code Snippet
                    </button>
                    <span class="media-count-note" id="codeNote_${prefix}">0/${maxConfig.code} snippets</span>
                </div>
                <div class="slots-container" id="imgContainer_${prefix}"></div>
                <div class="slots-container" id="codeContainer_${prefix}" style="margin-top:8px;"></div>
            </div>
        </div>
    </div>`;
}

/* ─── Get next available T number ────────────────────────────────────────── */
function getNextSubNum() {
    const existing = new Set(
        [...document.querySelectorAll('.content-block')]
            .map(el => parseInt(el.id.replace('block_T', ''), 10))
    );
    let n = 1;
    while (existing.has(n)) n++;
    return n;
}

/* ─── Add content block ──────────────────────────────────────────────────── */
function addContentBlock(forcedBlockId) {
    const subId   = forcedBlockId || ('T' + getNextSubNum());
    const num     = parseInt(subId.replace(/\D/g, ''), 10);
    const pfxBase = 'cb_' + subId;

    const pfxTitle   = pfxBase + '_TITLE';
    const pfxExample = pfxBase + '_EXAMPLE';
    const pfxOutput  = pfxBase + '_OUTPUT';

    const html = `
    <div class="content-block" id="block_${subId}">
        <div class="content-block-header">
            <span class="sub-id-badge">Content Sub-ID: ${subId}</span>
            ${(IS_EDIT_MODE || num > 1)
                ? `<button class="remove-block-btn" onclick="AMF_removeContentBlock('${subId}')"><i class="fa-solid fa-trash-can"></i> Remove</button>`
                : ''}
        </div>
        <div class="content-block-body">
            ${buildSectionPanel('title-panel',   'fa-solid fa-heading',      'Title Section',            pfxTitle,   'title',   false, pfxBase + '_titlePanel')}
            ${buildSectionPanel('example-panel', 'fa-solid fa-flask',        'Example / Types Section',  pfxExample, 'example', false, pfxBase + '_examplePanel')}
            ${buildSectionPanel('output-panel',  'fa-solid fa-terminal',     'Subtitle / Output Section',pfxOutput,  'output',  true,  pfxBase + '_outputPanel')}
            <div class="sub-panel tooltip-panel" id="${pfxBase}_tooltipPanel">
                <div class="sub-panel-header" onclick="AMF_togglePanel('${pfxBase}_tooltipPanel')">
                    <span class="sub-panel-label"><i class="fa-solid fa-circle-question"></i> Tooltip</span>
                    <i class="fa-solid fa-chevron-down sub-panel-toggle"></i>
                </div>
                <div class="sub-panel-body">
                    <div class="input-group" style="margin-bottom:0;">
                        <label>Tooltip Text</label>
                        <textarea class="tooltip-textarea" id="${pfxBase}_tooltipText" placeholder="Add a helpful tip or note… (plain text)"></textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>`;

    document.getElementById('contentBlocksContainer').insertAdjacentHTML('beforeend', html);
    const newBlock = document.getElementById('block_' + subId);
    if (newBlock) attachAllRteListeners(newBlock);

    if (!forcedBlockId && num > 1) {
        setTimeout(() => {
            document.getElementById('block_' + subId)?.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }, 100);
    }
}
window.AMF_addContentBlock = addContentBlock;

/* ─── Remove a content block ─────────────────────────────────────────────── */
function removeContentBlock(subId) {
    const el = document.getElementById('block_' + subId);
    if (!el) return;
    el.style.opacity   = '0';
    el.style.transform = 'scale(.97)';
    setTimeout(() => el.remove(), 300);
}
window.AMF_removeContentBlock = removeContentBlock;

/* ─── Toggle panel ────────────────────────────────────────────────────────── */
function togglePanel(panelId) {
    document.getElementById(panelId)?.classList.toggle('collapsed');
}
window.AMF_togglePanel = togglePanel;

/* ─── Encode code for storage ────────────────────────────────────────────── */
function encodeCode(raw) {
    if (!raw || !raw.trim()) return '';
    return raw.replace(/ /g, '&nbsp;').replace(/\n/g, '<br/>');
}

function fieldVal(id) {
    const el = document.getElementById(id);
    return el ? (el.value || '').trim() : '';
}

function wrapP(text) {
    return text ? `<p>${text}</p>` : '<br/>';
}

/* ─── Collect slot data ──────────────────────────────────────────────────── */
function collectSlots(prefix, sectionType) {
    const imgContainer  = document.getElementById('imgContainer_'  + prefix);
    const codeContainer = document.getElementById('codeContainer_' + prefix);
    const images = [], codes = [];

    if (imgContainer) {
        imgContainer.querySelectorAll('.img-slot').forEach(slot => {
            const slotId      = slot.id.replace('imgSlot_', '');
            const previewWrap = document.getElementById('imgPreviewWrap_'      + slotId);
            const previewImg  = document.getElementById('imgPreview_'          + slotId);
            const placeholder = document.getElementById('drawablePlaceholder_' + slotId);
            const helper      = fieldVal('imgHelper_' + slotId);
            const newFile     = slot._pendingFile || null;
            const existingUrl = (previewWrap && previewWrap.style.display !== 'none' && previewImg)
                ? (previewImg.dataset.storageUrl || '') : '';
            const drawableName = placeholder?.dataset?.drawableName || '';
            images.push({ newFile, existingUrl, drawableName, helper });
        });
    }

    if (codeContainer) {
        codeContainer.querySelectorAll('.code-slot').forEach(slot => {
            const slotId  = slot.id.replace('codeSlot_', '');
            const snippet = encodeCode(fieldVal('codeSnippet_' + slotId));
            const helper  = fieldVal('codeHelper_' + slotId);
            codes.push({ snippet, helper });
        });
    }

    return { images, codes };
}

/* ─── Collect all form data ──────────────────────────────────────────────── */
function collectLessonData() {
    const content = {};
    document.querySelectorAll('.content-block').forEach(blockEl => {
        const blockId    = blockEl.id.replace('block_', '');
        const pfxBase    = 'cb_' + blockId;
        const pfxTitle   = pfxBase + '_TITLE';
        const pfxExample = pfxBase + '_EXAMPLE';
        const pfxOutput  = pfxBase + '_OUTPUT';

        content[blockId] = {
            TITLE: {
                title      : wrapP(fieldVal(pfxTitle + '_title')),
                description: getRTE(pfxTitle + '_desc'),
                ...collectSlots(pfxTitle, 'title'),
            },
            EXAMPLE: {
                title      : wrapP(fieldVal(pfxExample + '_title')),
                description: getRTE(pfxExample + '_desc'),
                ...collectSlots(pfxExample, 'example'),
            },
            SUBTITLE: {
                subtitle: wrapP(fieldVal(pfxOutput + '_subtitle')),
                ...collectSlots(pfxOutput, 'output'),
            },
            TOOLTIP: { tooltip: fieldVal(pfxBase + '_tooltipText') },
        };
    });

    return {
        difficulty : document.getElementById('difficulty').value,
        main_title : wrapP(fieldVal('mainTitle')),
        title_desc : getRTE('titleDesc'),
        content,
    };
}

/* ─── Validate ───────────────────────────────────────────────────────────── */
function validateForm() {
    const errors = [];
    if (!document.getElementById('difficulty').value)
        errors.push('Please select a difficulty level.');
    if (!fieldVal('mainTitle'))
        errors.push('Main title is required.');
    const blocks = document.querySelectorAll('.content-block');
    if (!blocks.length)
        errors.push('At least one content block is required.');
    blocks.forEach(b => {
        const blockId  = b.id.replace('block_', '');
        const pfxTitle = 'cb_' + blockId + '_TITLE';
        if (!fieldVal(pfxTitle + '_title'))
            errors.push(`Content block ${blockId}: Title text is required.`);
    });
    return errors;
}

/* ─── Modal ──────────────────────────────────────────────────────────────── */
function openSaveModal() {
    const errors = validateForm();
    if (errors.length) { showToast('⚠ ' + errors[0], 'error'); return; }
    const blockCount = document.querySelectorAll('.content-block').length;
    const diff       = document.getElementById('difficulty').value;

    document.getElementById('saveModalSummary').textContent = IS_EDIT_MODE
        ? `Lesson ${currentLessonId} (${diff}) will be overwritten with ${blockCount} content block(s).`
        : `Lesson ${currentLessonId} (${diff}) with ${blockCount} content block(s) will be saved.`;
    document.getElementById('saveModal').classList.add('show');
}
window.AMF_openSaveModal = openSaveModal;

function closeSaveModal() {
    document.getElementById('saveModal').classList.remove('show');
}
window.AMF_closeSaveModal = closeSaveModal;

/* ─── Save progress UI ───────────────────────────────────────────────────── */
function showSaveProgress(detail, pct) {
    const overlay = document.getElementById('saveProgressOverlay');
    const bar     = document.getElementById('saveProgressBar');
    const label   = document.getElementById('saveProgressLabel');
    const detailEl= document.getElementById('saveProgressDetail');
    overlay.classList.add('show');
    if (detailEl) detailEl.textContent = detail;
    if (bar)      bar.style.width      = pct + '%';
    if (label)    label.textContent    = Math.round(pct) + '% complete';
}
function hideSaveProgress() {
    document.getElementById('saveProgressOverlay').classList.remove('show');
}

/* ─── Resolve image URL ──────────────────────────────────────────────────── */
async function resolveImageUrl(imgData, lessonId) {
    if (imgData.newFile) return await uploadImageToStorage(imgData.newFile, lessonId);
    if (imgData.existingUrl && imgData.existingUrl.startsWith('http')) return imgData.existingUrl;
    return imgData.drawableName || '';
}

/* ─── Confirm save ───────────────────────────────────────────────────────── */
async function confirmSave() {
    const btn = document.getElementById('confirmSaveBtn');
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner"></span> Saving…';
    closeSaveModal();

    try {
        showSaveProgress('Collecting form data...', 10);
        const lessonData = collectLessonData();

        const uploadJobs = [];
        for (const block of Object.values(lessonData.content || {})) {
            for (const section of ['TITLE', 'EXAMPLE', 'SUBTITLE']) {
                for (const img of (block[section]?.images || [])) {
                    uploadJobs.push(img);
                }
            }
        }

        if (uploadJobs.filter(j => j.newFile || j.existingUrl || j.drawableName).length > 0) {
            showSaveProgress(`Uploading ${uploadJobs.filter(j => j.newFile).length} new image(s)…`, 30);
            const urls = await uploadInBatches(uploadJobs, currentLessonId, 3);
            urls.forEach((url, i) => {
                uploadJobs[i].resolvedUrl = url;
                showSaveProgress(`Processed image ${i + 1}/${urls.length}`, 30 + ((i + 1) / urls.length) * 45);
            });
        }

        showSaveProgress('Writing to database...', 80);
        const firebaseLesson = buildFirebaseLesson(lessonData);
        await firebase.database().ref('Lessons/' + currentLessonId).set(firebaseLesson);

        sessionStorage.removeItem('nextLessonId');
        sessionStorage.removeItem('navParams_add_module_form');
        // ── FIX 5: Invalidate the full Lessons cache after save so the
        //    course management list always reflects the latest data.
        if (typeof FirebaseCache !== 'undefined') {
            FirebaseCache.invalidate('Lessons');
            FirebaseCache.invalidate('Lessons/' + currentLessonId);
        }

        showSaveProgress('Done!', 100);
        setTimeout(() => hideSaveProgress(), 400);
        showToast(`Lesson ${currentLessonId} saved successfully!`, 'success');
        setTimeout(() => goToPage('course_management'), 1200);

    } catch (e) {
        hideSaveProgress();
        showToast('Error: ' + e.message, 'error');
        console.error(e);
    } finally {
        btn.disabled = false;
        btn.innerHTML = '<i class="fa-solid fa-check" style="margin-right:6px;"></i>' +
                        (IS_EDIT_MODE ? 'Update' : 'Save');
    }
}
window.AMF_confirmSave = confirmSave;

/* ─── Build Firebase lesson object ──────────────────────────────────────── */
function buildFirebaseLesson(data) {
    const lesson = {
        difficulty : data.difficulty || '',
        main_title : data.main_title  || '<br/>',
        title_desc : data.title_desc  || '<br/>',
        content    : {},
    };

    Object.entries(data.content || {}).forEach(([blockId, block]) => {
        const fbBlock = {};

        function imgFields(img) {
            if (!img) return { url: '', drawable: '', helper: htmlOrBlank('') };
            const url      = img.resolvedUrl  || img.existingUrl || '';
            const drawable = url.startsWith('http') ? '' : (img.drawableName || '');
            return { url, drawable, helper: htmlOrBlank(img.helper || '') };
        }

        const title  = block.TITLE   || {};
        const tImgs  = title.images  || [];
        const tCodes = title.codes   || [];
        const ti = [0, 1, 2].map(i => imgFields(tImgs[i]));
        fbBlock.TITLE = {
            title       : title.title       || '<br/>',
            description : title.description || '<br/>',
            helper1     : ti[0].helper, helper1Url: ti[0].url, helper1Drawable: ti[0].drawable,
            helper1Code : tCodes[0]?.snippet || '',
            helper2     : ti[1].helper, helper2Url: ti[1].url, helper2Drawable: ti[1].drawable,
            helper2Code : tCodes[1]?.snippet || '',
            helper3     : ti[2].helper, helper3Url: ti[2].url, helper3Drawable: ti[2].drawable,
            helper3Code : tCodes[2]?.snippet || '',
        };

        const example = block.EXAMPLE || {};
        const eImgs   = example.images || [];
        const eCodes  = example.codes  || [];
        const hasEx   = example.title || example.description || eImgs.length || eCodes.length;
        if (hasEx) {
            const ei = [0, 1].map(i => imgFields(eImgs[i]));
            fbBlock.EXAMPLE = {
                TYPES: {
                    exampleTitle       : example.title       || '<br/>',
                    exampleDescription : example.description || '<br/>',
                    helper4     : ei[0].helper, helper4Url: ei[0].url, helper4Drawable: ei[0].drawable,
                    helper4Code : eCodes[0]?.snippet || '',
                    helper5     : ei[1].helper, helper5Url: ei[1].url, helper5Drawable: ei[1].drawable,
                    helper5Code : eCodes[1]?.snippet || '',
                },
            };
        }

        const output  = block.SUBTITLE || {};
        const oImgs   = output.images  || [];
        const oCodes  = output.codes   || [];
        const hasOut  = output.subtitle || oImgs.length || oCodes.length;
        if (hasOut) {
            const oi = [0, 1].map(i => imgFields(oImgs[i]));
            fbBlock.SUBTITLE = {
                OUTPUT: {
                    subtitle    : output.subtitle || '<br/>',
                    helper6     : oi[0].helper, helper6Url: oi[0].url, helper6Drawable: oi[0].drawable,
                    helper6Code : oCodes[0]?.snippet || '',
                    helper7     : oi[1].helper, helper7Url: oi[1].url, helper7Drawable: oi[1].drawable,
                    helper7Code : oCodes[1]?.snippet || '',
                },
            };
        }

        const tooltipText = (block.TOOLTIP?.tooltip || '').trim();
        if (tooltipText) fbBlock.TOOLTIP = { tooltip: tooltipText };

        lesson.content[blockId] = fbBlock;
    });

    return lesson;
}

function htmlOrBlank(text) {
    text = (text || '').trim();
    if (!text) return '<br/>';
    if (/<[^>]+>/.test(text)) return text;
    return '<p>' + text.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;') + '</p>';
}

/* ─── Toast ──────────────────────────────────────────────────────────────── */
function showToast(msg, type = 'success') {
    const el      = document.getElementById('toast');
    el.textContent = msg;
    el.className   = `toast ${type} show`;
    setTimeout(() => el.classList.remove('show'), 3500);
}

document.getElementById('saveModal').addEventListener('click', function(e) {
    if (e.target === this) closeSaveModal();
});

/* ════════════════════════════════════════════════════════════════════════
   BOOT
   ════════════════════════════════════════════════════════════════════════ */
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
} else {
    init();
}

})(); // ── end IIFE ──
</script>
</body>
</html>