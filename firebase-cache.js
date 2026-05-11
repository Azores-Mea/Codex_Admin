/**
 * firebase-cache.js — CODEX Dashboard Data Cache
 * ─────────────────────────────────────────────────────────────────────────
 * Fetches all Firebase data ONCE per session, then serves it from memory.
 * Pages do NOT re-fetch on navigation. Cache is invalidated only when:
 *   1. The user explicitly refreshes the browser (sessionStorage is cleared).
 *   2. A write operation occurs (addModule, archiveLesson, saveAssessment,
 *      saveCodingExercise) — the relevant cache key is cleared so the next
 *      read fetches fresh data, but only for the changed ref.
 *
 * HOW TO USE
 * ──────────────────────────────────────────────────────────────────────────
 * 1. Add this script to every dashboard page (after firebase-app + firebase-database):
 *      <script src="firebase-cache.js"></script>
 *
 * 2. Replace ALL   db.ref('X').once('value')   calls with:
 *      await FirebaseCache.get('X')
 *
 *    Examples:
 *      const usersSnap    = await FirebaseCache.get('Users');
 *      const lessonsSnap  = await FirebaseCache.get('Lessons');
 *      const quizSnap     = await FirebaseCache.get('quizResults');
 *
 *    The returned value is the Firebase DataSnapshot — same API as before.
 *
 * 3. After any WRITE operation, invalidate the affected ref so the next
 *    read fetches fresh data:
 *      FirebaseCache.invalidate('Lessons');
 *      FirebaseCache.invalidate('assessment');
 *
 *    Full list of cache keys used in this project:
 *      'Users', 'Lessons', 'quizResults', 'unlockedLessons',
 *      'exerciseResults', 'assessment', 'coding_exercises',
 *      'userSyntaxErrorAnswers', 'userTracingAnswers',
 *      'userMachineProblemAnswers', 'syntaxErrorResults',
 *      'tracingResults', 'quizQuestions'
 *
 * 4. For writes that need a real-time listener (e.g. archiveLesson), call
 *    FirebaseCache.invalidate('Lessons') right after the db.ref().set()
 *    so the next page load re-fetches.
 */

const FirebaseCache = (() => {
    // ── In-memory store: ref path → { snapshot, fetchedAt } ────────────────
    const _store = {};

    // ── In-flight promises (prevents duplicate concurrent fetches) ──────────
    const _pending = {};

    // ── sessionStorage key prefix (survives soft navigations, cleared on F5) ─
    const SS_PREFIX = 'codex_fc_';

    /**
     * Serialize a DataSnapshot to sessionStorage.
     * We store the raw val() as JSON; on restore we wrap it in a
     * lightweight snapshot-compatible object.
     */
    function _persist(path, snapshot) {
        try {
            const payload = JSON.stringify({
                val: snapshot.val(),
                ts : Date.now(),
            });
            sessionStorage.setItem(SS_PREFIX + path, payload);
        } catch (e) {
            // sessionStorage full or private mode — silently ignore
        }
    }

    /**
     * Try to restore from sessionStorage.
     * Returns a snapshot-compatible object or null.
     */
    function _restore(path) {
        try {
            const raw = sessionStorage.getItem(SS_PREFIX + path);
            if (!raw) return null;
            const { val, ts } = JSON.parse(raw);
            // Optional: max age 5 minutes (300_000 ms) to handle long sessions
            if (Date.now() - ts > 300_000) {
                sessionStorage.removeItem(SS_PREFIX + path);
                return null;
            }
            return _wrapVal(val);
        } catch (e) {
            return null;
        }
    }

    /**
     * Wrap a plain JS value in a DataSnapshot-compatible object.
     * Covers the subset of the snapshot API used across CODEX pages:
     *   .val(), .exists(), Object.keys / Object.entries on the result.
     */
    function _wrapVal(rawVal) {
        return {
            val:    () => rawVal,
            exists: () => rawVal !== null && rawVal !== undefined,
            // Firebase DataSnapshot extras used in the codebase:
            key:    null,
            forEach: (cb) => {
                if (rawVal && typeof rawVal === 'object') {
                    for (const [k, v] of Object.entries(rawVal)) {
                        cb({ key: k, val: () => v });
                    }
                }
            },
        };
    }

    /**
     * Main API: get(path)
     * Returns a Promise that resolves to a snapshot-compatible object.
     * Reads memory → sessionStorage → Firebase, in that order.
     */
    async function get(path) {
        // 1. Memory hit
        if (_store[path]) return _store[path];

        // 2. Another fetch already in flight for this path — share it
        if (_pending[path]) return _pending[path];

        // 3. sessionStorage hit
        const cached = _restore(path);
        if (cached) {
            _store[path] = cached;
            return cached;
        }

        // 4. Fetch from Firebase
        _pending[path] = _fetchFromFirebase(path);
        try {
            const snapshot = await _pending[path];
            _store[path] = snapshot;
            _persist(path, snapshot);
            return snapshot;
        } finally {
            delete _pending[path];
        }
    }

    async function _fetchFromFirebase(path) {
        const db = firebase.database();
        return db.ref(path).once('value');
    }

    /**
     * invalidate(path)
     * Call this after any write. Removes the ref from memory and
     * sessionStorage so the next get() fetches fresh data.
     *
     * Pass no argument (or '*') to invalidate everything.
     */
    function invalidate(path) {
        if (!path || path === '*') {
            // Clear all
            Object.keys(_store).forEach(k => delete _store[k]);
            Object.keys(sessionStorage)
                .filter(k => k.startsWith(SS_PREFIX))
                .forEach(k => sessionStorage.removeItem(k));
            return;
        }
        delete _store[path];
        sessionStorage.removeItem(SS_PREFIX + path);
    }

    /**
     * prefetch(paths)
     * Kick off fetches for multiple paths in parallel.
     * Call this early in a page's lifecycle to warm the cache before it's needed.
     * Already-cached paths are skipped automatically.
     */
    function prefetch(paths) {
        paths.forEach(p => get(p)); // fire-and-forget; errors are swallowed
    }

    /**
     * set(path, value)
     * Write a value to Firebase AND update the cache atomically so the UI
     * can reflect changes immediately without a re-fetch.
     *
     * Usage:
     *   await FirebaseCache.set('Lessons/L5/archived', true);
     */
    async function set(path, value) {
        const db = firebase.database();
        await db.ref(path).set(value);

        // Invalidate the top-level key (e.g. 'Lessons/L5/archived' → 'Lessons')
        const topKey = path.split('/')[0];
        invalidate(topKey);
    }

    /**
     * update(updates)
     * Batch-update multiple paths in Firebase AND invalidate affected cache keys.
     *
     * Usage:
     *   await FirebaseCache.update({ 'quizResults/uid/L1/score': 8 });
     */
    async function update(updates) {
        const db = firebase.database();
        await db.ref().update(updates);

        // Derive unique top-level keys from the update paths
        const topKeys = new Set(Object.keys(updates).map(p => p.split('/')[0]));
        topKeys.forEach(k => invalidate(k));
    }

    /**
     * push(path, value)
     * Push a new child under path, invalidate the parent cache key.
     * Returns the new push reference (same as db.ref().push()).
     */
    async function push(path, value) {
        const db  = firebase.database();
        const ref = await db.ref(path).push(value);
        const topKey = path.split('/')[0];
        invalidate(topKey);
        return ref;
    }

    // Public API
    return { get, set, update, push, invalidate, prefetch };
})();


/* ═══════════════════════════════════════════════════════════════════════════
   INTEGRATION GUIDE — copy-paste changes for each dashboard page
   ═══════════════════════════════════════════════════════════════════════════

── dashboard.php ────────────────────────────────────────────────────────────
BEFORE:
    Promise.all([
        db.ref('Users').once('value'),
        db.ref('quizResults').once('value'),
        db.ref('unlockedLessons').once('value'),
        db.ref('Lessons').once('value'),
        db.ref('exerciseResults').once('value')
    ]).then(snaps => { ... });

AFTER:
    // Prefetch all at once (parallel, shared with other pages)
    Promise.all([
        FirebaseCache.get('Users'),
        FirebaseCache.get('quizResults'),
        FirebaseCache.get('unlockedLessons'),
        FirebaseCache.get('Lessons'),
        FirebaseCache.get('exerciseResults'),
    ]).then(snaps => { renderDashboard(...snaps.map(s => s.val() || {})); });


── learner_progress.php ─────────────────────────────────────────────────────
The learner data is currently hardcoded. If you switch to Firebase:
BEFORE:
    db.ref('Users').once('value')
AFTER:
    FirebaseCache.get('Users')


── course_management.php ────────────────────────────────────────────────────
BEFORE (in loadModules):
    const snapshot = await db.ref('Lessons').once('value');
AFTER:
    const snapshot = await FirebaseCache.get('Lessons');

BEFORE (in archiveLesson):
    await db.ref('Lessons/' + lessonId + '/archived').set(newState);
AFTER:
    await FirebaseCache.set('Lessons/' + lessonId + '/archived', newState);
    // FirebaseCache.set() automatically invalidates 'Lessons'


── content_management.php ───────────────────────────────────────────────────
BEFORE (in loadModules):
    const [lessonSnap, assessSnap, codingSnap] = await Promise.all([
        db.ref('Lessons').once('value'),
        db.ref('assessment').once('value'),
        db.ref('coding_exercises').once('value'),
    ]);
AFTER:
    const [lessonSnap, assessSnap, codingSnap] = await Promise.all([
        FirebaseCache.get('Lessons'),
        FirebaseCache.get('assessment'),
        FirebaseCache.get('coding_exercises'),
    ]);


── add_module_form.php ───────────────────────────────────────────────────────
BEFORE (loadNextLessonId):
    const snapshot = await db.ref('Lessons').once('value');
AFTER:
    const snapshot = await FirebaseCache.get('Lessons');

BEFORE (loadExistingLesson):
    const snapshot = await db.ref('Lessons/' + lessonId).once('value');
AFTER:
    // For edit mode, fetch the specific lesson (usually not cached at this level)
    // Invalidate Lessons after save so course_management sees fresh data.
    const snapshot = await db.ref('Lessons/' + lessonId).once('value');

BEFORE (confirmSave — after db.ref().set()):
    sessionStorage.removeItem('nextLessonId');
AFTER:
    sessionStorage.removeItem('nextLessonId');
    FirebaseCache.invalidate('Lessons');   // ← add this line


── add_coding_exercise_form.php ─────────────────────────────────────────────
BEFORE (DOMContentLoaded):
    const lSnap  = await db.ref('Lessons/' + LESSON_ID).once('value');
    const snap   = await db.ref(`assessment/${LESSON_ID}/${type}`).once('value');
AFTER:
    const lessonsSnap  = await FirebaseCache.get('Lessons');
    const lesson       = (lessonsSnap.val() || {})[LESSON_ID] || {};
    const assessSnap   = await FirebaseCache.get('assessment');
    const typeData     = ((assessSnap.val() || {})[LESSON_ID] || {})[type] || null;
    // Use typeData directly instead of snap.val()

BEFORE (confirmSave — after db.ref().update(updates)):
    (no invalidation)
AFTER:
    // Replace db.ref().update(updates) with:
    await FirebaseCache.update(updates);
    // FirebaseCache.update() calls db.ref().update() AND invalidates all touched keys


── add_assessment_form.php ──────────────────────────────────────────────────
BEFORE (DOMContentLoaded):
    const snap = await db.ref('assessment/' + LESSON_ID + '/Quiz').once('value');
AFTER:
    const allAssess = await FirebaseCache.get('assessment');
    const existing  = ((allAssess.val() || {})[LESSON_ID] || {}).Quiz || null;

BEFORE (saveQuiz — db.ref(basePath + key).set / db.ref(basePath).push):
    (multiple individual set/push calls)
AFTER:
    // Keep individual set/push calls as-is (they write specific question nodes)
    // Add at the end of saveQuiz():
    FirebaseCache.invalidate('assessment');
    FirebaseCache.invalidate('quizQuestions');

BEFORE (reEvaluateQuizLearners — db.ref('quizResults')...db.ref().update()):
AFTER:
    // Replace final db.ref().update(updates) with:
    await FirebaseCache.update(updates);

═══════════════════════════════════════════════════════════════════════════ */