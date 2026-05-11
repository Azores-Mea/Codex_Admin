<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CODEX | SME Portal</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="dashboard.css" id="main-css">

    <script src="https://www.gstatic.com/firebasejs/10.12.0/firebase-app-compat.js"></script>
    <script src="https://www.gstatic.com/firebasejs/10.12.0/firebase-auth-compat.js"></script>
    <script src="https://www.gstatic.com/firebasejs/10.12.0/firebase-firestore-compat.js"></script>
    <script src="https://www.gstatic.com/firebasejs/10.12.0/firebase-database-compat.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@supabase/supabase-js@2/dist/umd/supabase.js"></script>
    <script>
        window.supabaseClient = supabase.createClient(
            'https://vcytslokgjnzlkpfxnno.supabase.co',
            'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6InZjeXRzbG9rZ2puemxrcGZ4bm5vIiwicm9sZSI6ImFub24iLCJpYXQiOjE3Nzc2MDc0NTQsImV4cCI6MjA5MzE4MzQ1NH0.OcjEWlm8Pxud5ryn3H9QpMjxwswO5a2oAJRbjQDrx7E'
        );
    </script>
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
    <script src="firebase-cache.js"></script>

    <style>
        #auth-loading {
            position: fixed;
            inset: 0;
            background: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 99999;
            font-family: 'Roboto', sans-serif;
            font-size: 14px;
            color: #64748b;
            transition: opacity 0.2s;
        }
        #auth-loading.hidden { display: none; }

        .page-panel { display: none; }
        .page-panel.active { display: block; }

        #page-content {
            margin-left: 280px;
            width: calc(100% - 280px);
            min-height: 100vh;
        }
        #page-content .db-main { margin-left: 0; }
        #page-content .db-sidebar,
        #page-content #logoutModal { display: none !important; }
    </style>
</head>
<body>

<div id="auth-loading">
    <div style="text-align:center;">
        <div style="font-size:28px;font-weight:800;color:#001c30;margin-bottom:8px;">
            CODE<span style="color:#3b82f6;">X</span>
        </div>
        <p>Verifying session…</p>
    </div>
</div>

<?php include 'sidebar.php'; ?>

<div id="page-content"></div>

<script>
const _injectedStyles = new Set();
const _panels = {};
let _currentPage = null;

const PAGE_TITLES = {
    dashboard:                'Dashboard',
    learner_progress:         'Learner Progress',
    learner_detail:           'Learner Details',
    course_management:        'Course Management',
    content_management:       'Content Management',
    add_module_form:          'Add / Edit Lesson',
    add_assessment_form:      'Assessment',
    add_coding_exercise_form: 'Coding Exercise',
};

const PAGE_NAV_ACTIVE = {
    dashboard:                'dashboard',
    learner_progress:         'learner_progress',
    learner_detail:           'learner_progress',
    course_management:        'course_management',
    content_management:       'content_management',
    add_module_form:          'course_management',
    add_assessment_form:      'content_management',
    add_coding_exercise_form: 'content_management',
};

// ── FIX: content_management added here so its panel is always destroyed and
//    rebuilt on navigation. Without this, after returning from a form page the
//    old panel's IIFE-scoped handlers are gone but the DOM still exists,
//    making the "Modify assessment" buttons silently unresponsive.
const ALWAYS_REBUILD = new Set([
    'content_management',           // ← ADDED
    'add_module_form',
    'add_assessment_form',
    'add_coding_exercise_form',
    'learner_detail',
]);

// ── Auth guard ───────────────────────────────────────────────────────────────
firebase.auth().onAuthStateChanged(function(user) {
    if (!user) {
        window.location.href = 'login.php';
        return;
    }
    document.getElementById('auth-loading').classList.add('hidden');
    const startQs   = new URLSearchParams(location.search);
    const startPage = startQs.get('page') || 'dashboard';

    startQs.delete('page');
    const extraParams = startQs.toString();
    if (extraParams && !sessionStorage.getItem('navParams_' + startPage)) {
        const paramsObj = Object.fromEntries(startQs.entries());
        sessionStorage.setItem('navParams_' + startPage, JSON.stringify(paramsObj));
    }

    navigate(startPage, true);
});

// ── navigate(page) ───────────────────────────────────────────────────────────
async function navigate(page, replaceState = false) {
    if (!PAGE_TITLES[page]) page = 'dashboard';

    // Update sidebar active state
    document.querySelectorAll('.db-nav-item').forEach(i => i.classList.remove('active'));
    const activeNavId = PAGE_NAV_ACTIVE[page] || page;
    const navItem = document.getElementById('nav-' + activeNavId);
    if (navItem) navItem.classList.add('active');

    // Update URL & title
    const storedForUrl = sessionStorage.getItem('navParams_' + page);
    let urlParams = '?page=' + page;
    if (storedForUrl) {
        try {
            const p = JSON.parse(storedForUrl);
            const qs = new URLSearchParams(p).toString();
            if (qs) urlParams += '&' + qs;
        } catch(e) {}
    }
    history[replaceState ? 'replaceState' : 'pushState']({ page }, '', urlParams);
    document.title = 'CODEX | ' + PAGE_TITLES[page];

    // Hide current panel
    if (_currentPage && _panels[_currentPage]) {
        _panels[_currentPage].classList.remove('active');
    }

    // Destroy and rebuild panels that must always re-initialise
    if (ALWAYS_REBUILD.has(page) && _panels[page]) {
    // Clean up any delegated document listeners this panel registered
    if (_panels[page]._docListener) {
        document.removeEventListener('click', _panels[page]._docListener);
    }
    _panels[page].remove();
    delete _panels[page];
}

    // If panel already exists (and is not in ALWAYS_REBUILD), just show it
    if (_panels[page]) {
        _panels[page].classList.add('active');
        _currentPage = page;
        document.dispatchEvent(new CustomEvent('panel-shown-' + page));
        return;
    }

    // First visit (or forced rebuild): fetch and build the panel
    let fetchUrl = page + '.php';
    const storedParams = sessionStorage.getItem('navParams_' + page);
    if (storedParams) {
        try {
            const params = JSON.parse(storedParams);
            const qs = new URLSearchParams(params).toString();
            if (qs) fetchUrl = page + '.php?' + qs;
        } catch(e) {}
    } else {
        const currentQs = new URLSearchParams(location.search);
        currentQs.delete('page');
        const extra = currentQs.toString();
        if (extra) fetchUrl = page + '.php?' + extra;
    }

    try {
        const res  = await fetch(fetchUrl);
        if (!res.ok) throw new Error('HTTP ' + res.status);
        const html = await res.text();

        _buildPanel(page, html);
        _panels[page].classList.add('active');
        _currentPage = page;
    } catch(e) {
        console.error('Failed to load page:', page, e);
        const err = document.createElement('div');
        err.className = 'page-panel';
        err.style.padding = '40px';
        err.style.color   = '#ef4444';
        err.textContent   = 'Failed to load page. Please refresh.';
        document.getElementById('page-content').appendChild(err);
        _panels[page] = err;
        err.classList.add('active');
        _currentPage = page;
    }
}

// ── _buildPanel ──────────────────────────────────────────────────────────────
function _buildPanel(page, html) {
    const container = document.getElementById('page-content');

    const parser = new DOMParser();
    const doc    = parser.parseFromString(html, 'text/html');

    // Hoist <style> blocks into <head>
    doc.querySelectorAll('style').forEach(s => {
        const key = page + ':' + s.textContent.trim().slice(0, 80);
        if (_injectedStyles.has(key)) return;
        _injectedStyles.add(key);
        const tag = document.createElement('style');
        tag.textContent = s.textContent;
        document.head.appendChild(tag);
    });

    // Hoist <link rel="stylesheet"> tags
    doc.querySelectorAll('link[rel="stylesheet"]').forEach(l => {
        const href = l.getAttribute('href');
        if (!href || document.querySelector(`link[href="${href}"]`)) return;
        const tag = document.createElement('link');
        tag.rel  = 'stylesheet';
        tag.href = href;
        document.head.appendChild(tag);
    });

    // Build the panel div from <body> content
    const panel = document.createElement('div');
    panel.className = 'page-panel';
    const body = doc.body;
    while (body.firstChild) panel.appendChild(body.firstChild);

    container.appendChild(panel);
    _panels[page] = panel;

    // Run <script> tags exactly once
    const runScripts = () => {
        panel.querySelectorAll('script').forEach(oldScript => {
            if (oldScript.src) {
                const alreadyLoaded = !!document.querySelector(`script[src="${oldScript.src}"]`);
                if (alreadyLoaded) { oldScript.remove(); return; }
            }
            const newScript = document.createElement('script');
            Array.from(oldScript.attributes).forEach(a => newScript.setAttribute(a.name, a.value));
            newScript.textContent = oldScript.textContent;
            document.body.appendChild(newScript);
            oldScript.remove();
        });
        setTimeout(() => sessionStorage.removeItem('navParams_' + page), 0);
    };
    if (typeof firebase !== 'undefined' && firebase.apps && firebase.apps.length > 0) {
        runScripts();
    } else {
        // Poll until Firebase is ready (max 3s)
        let attempts = 0;
        const poll = setInterval(() => {
            attempts++;
            if ((typeof firebase !== 'undefined' && firebase.apps && firebase.apps.length > 0) || attempts > 30) {
                clearInterval(poll);
                runScripts();
            }
        }, 100);
    }
}

// ── Sidebar click interception ───────────────────────────────────────────────
document.querySelectorAll('.db-nav-item a[data-page]').forEach(link => {
    link.addEventListener('click', function(e) {
        e.preventDefault();
        navigate(this.dataset.page);
    });
});

// ── Browser back/forward ─────────────────────────────────────────────────────
window.addEventListener('popstate', function(e) {
    if (e.state?.page) navigate(e.state.page, true);
});

window.navigate = navigate;
window.navigateWithParams = function(page, params) {
    if (params) sessionStorage.setItem('navParams_' + page, JSON.stringify(params));
    navigate(page);
};
</script>
</body>
</html>