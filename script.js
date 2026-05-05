let attemptCount      = 0;
const MAX_ATTEMPTS    = 3;
const LOCKOUT_SECONDS = 30;

let verifiedCredential = null;
let emailIsValidSME    = false;

const email      = document.getElementById('email');
const password   = document.getElementById('password');
const submitBtn  = document.getElementById('submitBtn');
const emailGroup = document.getElementById('emailGroup');
const passGroup  = document.getElementById('passGroup');

// ── Popup ──────────────────────────────────────────────────────
const showPopup = () => document.getElementById('loggingInPopup').classList.add('visible');
const hidePopup = () => document.getElementById('loggingInPopup').classList.remove('visible');

// ── State helpers ──────────────────────────────────────────────
const clearStates = (group) => {
    group.classList.remove('error', 'success', 'checking');
    // Also hide limit message when clearing
    const limitMsg = group.querySelector('.limit-msg');
    if (limitMsg) limitMsg.classList.remove('visible');
};

function setError(group, msg) {
    clearStates(group);
    group.classList.add('error');
    if (msg) {
        const el = group.querySelector('.error-msg');
        if (el) el.textContent = msg;
    }
}

function setSuccess(group, msg) {
    clearStates(group);
    group.classList.add('success');
    if (msg) {
        const el = group.querySelector('.success-msg');
        if (el) el.textContent = msg;
    }
}

function setChecking(group) {
    clearStates(group);
    group.classList.add('checking');
}

// ── Submit button state ────────────────────────────────────────
function refreshSubmitState() {
    const isReady = email.value.trim() !== '' && password.value.trim() !== '';
    submitBtn.disabled = !isReady || attemptCount >= MAX_ATTEMPTS;
    isReady && attemptCount < MAX_ATTEMPTS
        ? submitBtn.classList.add('active')
        : submitBtn.classList.remove('active');
}

// ── Attempt counter & lockout ──────────────────────────────────
function registerFailedAttempt() {
    attemptCount++;
    if (attemptCount >= MAX_ATTEMPTS) triggerLockout();
}

function triggerLockout() {
    submitBtn.disabled = true;
    submitBtn.classList.remove('active');
    let remaining = LOCKOUT_SECONDS;

    const tick = () => {
        submitBtn.textContent = `Too many attempts. Wait ${remaining}s`;
        if (remaining <= 0) {
            attemptCount = 0;
            submitBtn.textContent = 'Log in to SME Portal';
            refreshSubmitState();
            return;
        }
        remaining--;
        setTimeout(tick, 1000);
    };
    tick();
}

// ── Max length shake feedback ──────────────────────────────────
function addLimitFeedback(input) {
    const group = input.closest('.input-group');
    const limitMsg = document.createElement('span');
    limitMsg.classList.add('helper-text', 'limit-msg');
    limitMsg.textContent = 'Character limit reached.';
    group.appendChild(limitMsg);

    input.addEventListener('keydown', (e) => {
    const max = parseInt(input.getAttribute('maxlength'));

    const allowedKeys = [
        'Backspace', 'Delete', 'ArrowLeft', 'ArrowRight',
        'Tab', 'Home', 'End'
    ];

    // Current selection (so replacing text still works)
    const selectedLength =
        input.selectionEnd - input.selectionStart;

    const currentLength = input.value.length;

    const isAtMax = currentLength - selectedLength >= max;

    const isAllowed =
        allowedKeys.includes(e.key) ||
        e.ctrlKey ||
        e.metaKey;

    if (isAtMax && !isAllowed) {
        e.preventDefault();

        const group = input.closest('.input-group');
        group.classList.remove('error', 'success', 'checking');

        const limitMsg = group.querySelector('.limit-msg');
        if (limitMsg) {
            limitMsg.classList.add('visible');
        }
    }
});

    input.addEventListener('input', () => {
    const max = parseInt(input.getAttribute('maxlength'));

    if (input.value.length > max) {
        input.value = input.value.slice(0, max);
    }
});
}

addLimitFeedback(email);
addLimitFeedback(password);

// ── Invalidate cache on field change ──────────────────────────
[email, password].forEach(input => {
    input.addEventListener('input', () => {
        if (verifiedCredential) {
            verifiedCredential = null;
            firebase.auth().signOut();
        }
        clearStates(input.closest('.input-group'));
        refreshSubmitState();
    });
});

email.addEventListener('input', () => {
    emailIsValidSME = false;
});

// ── Eye toggle ─────────────────────────────────────────────────
document.getElementById('eyeToggle').addEventListener('click', function () {
    const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
    password.setAttribute('type', type);
});

// ── Email blur: check SME account immediately ──────────────────
email.addEventListener('blur', async () => {
    const val = email.value.trim().toLowerCase();
    clearStates(emailGroup);
    emailIsValidSME    = false;
    verifiedCredential = null;

    if (!val) return;

    // Basic format check
    if (!val.includes('@') || !val.includes('.')) {
        setError(emailGroup, 'Invalid email address. Please try again.');
        return;
    }

    // Show checking state
    setChecking(emailGroup);
    submitBtn.disabled = true;
    submitBtn.classList.remove('active');

    try {
        const snapshot = await firebase.firestore()
            .collection('sme')
            .where('email', '==', val)
            .limit(1)
            .get();

        clearStates(emailGroup);

        if (snapshot.empty) {
            setError(emailGroup, 'Invalid email. Not an SME account. Please try again.');
        } else {
            emailIsValidSME = true;
            setSuccess(emailGroup, 'SME account found!');
            refreshSubmitState();

            // Trigger password check immediately if already filled
            if (password.value.trim().length >= 6) {
                password.dispatchEvent(new Event('blur'));
            }
        }

    } catch (err) {
        clearStates(emailGroup);
        const code = err.code || '';
        const isNetworkError = !navigator.onLine
            || code === 'unavailable'
            || code === 'firestore/unavailable'
            || code === 'network-request-failed'
            || code === 'auth/network-request-failed';

        if (isNetworkError) {
            emailGroup.classList.add('checking');
            const msg = emailGroup.querySelector('.checking-msg');
            if (msg) msg.textContent = 'No internet connection, unable to validate.';
        } else if (code === 'permission-denied' || code === 'firestore/permission-denied') {
            setError(emailGroup, 'Unable to verify account. Check your connection.');
        } else {
            setError(emailGroup, 'Unable to verify email. Please try again.');
        }

        console.warn('SME email pre-check failed:', code, err.message);
        refreshSubmitState();
    }
});

// ── Password blur: silently sign in to validate against email ──
password.addEventListener('blur', async () => {
    const emailVal = email.value.trim().toLowerCase();
    const passVal  = password.value.trim();
    clearStates(passGroup);
    verifiedCredential = null;

    if (!passVal) return;

    if (passVal.length < 6) {
        setError(passGroup, 'Password must be at least 6 characters.');
        return;
    }

    if (!emailIsValidSME) {
        setError(passGroup, 'Please enter a valid SME email first.');
        return;
    }

    if (attemptCount >= MAX_ATTEMPTS) return;

    // Show checking state
    setChecking(passGroup);
    submitBtn.disabled = true;
    submitBtn.classList.remove('active');

    try {
        const credential = await firebase.auth()
            .signInWithEmailAndPassword(emailVal, passVal);

        const doc = await firebase.firestore()
            .collection('sme')
            .doc(credential.user.uid)
            .get();

        if (doc.exists) {
            verifiedCredential = credential;
            clearStates(passGroup);
            setSuccess(passGroup, 'Password matched!');
            refreshSubmitState();
        } else {
            await firebase.auth().signOut();
            emailIsValidSME    = false;
            verifiedCredential = null;
            clearStates(passGroup);
            setError(emailGroup, 'Invalid email. Not an SME account. Please try again.');
            setError(passGroup, 'Password does not match an SME account.');
            registerFailedAttempt();
            refreshSubmitState();
        }

    } catch (err) {
        clearStates(passGroup);
        verifiedCredential = null;
        const code = err.code || '';
        const isNetworkError = !navigator.onLine
            || code === 'unavailable'
            || code === 'firestore/unavailable'
            || code === 'network-request-failed'
            || code === 'auth/network-request-failed';

        if (isNetworkError) {
            passGroup.classList.add('checking');
            const msg = passGroup.querySelector('.checking-msg');
            if (msg) msg.textContent = 'No internet connection, unable to validate.';
        } else if (code === 'auth/wrong-password' || code === 'auth/invalid-credential') {
            const left = MAX_ATTEMPTS - attemptCount - 1;
            setError(
                passGroup,
                left > 0
                    ? `Incorrect password. ${left} attempt${left !== 1 ? 's' : ''} left.`
                    : 'Incorrect password. No attempts left.'
            );
            registerFailedAttempt();
        } else if (code === 'auth/user-not-found' || code === 'auth/invalid-email') {
            emailIsValidSME = false;
            setError(emailGroup, 'Invalid email. Not an SME account. Please try again.');
            clearStates(passGroup);
            registerFailedAttempt();
        } else {
            setError(passGroup, 'Could not verify password. Please try again.');
        }

        refreshSubmitState();
    }
});

// ── Submit ─────────────────────────────────────────────────────
document.getElementById('loginForm').addEventListener('submit', async function (e) {
    e.preventDefault();
    if (attemptCount >= MAX_ATTEMPTS) return;

    showPopup();

    try {
        let uid;

        if (verifiedCredential) {
            // Reuse cached credential — no second sign-in needed
            uid = verifiedCredential.user.uid;
        } else {
            // Fallback: user skipped tabbing through fields
            const credential = await firebase.auth()
                .signInWithEmailAndPassword(
                    email.value.trim().toLowerCase(),
                    password.value.trim()
                );
            uid = credential.user.uid;
        }

        const doc = await firebase.firestore().collection('sme').doc(uid).get();

        if (doc.exists) {
            setSuccess(emailGroup, 'SME account found!');
            setSuccess(passGroup, 'Password matched!');
            // Popup stays visible during redirect
            window.location.href = 'dashboard.php';
        } else {
            await firebase.auth().signOut();
            hidePopup();
            emailIsValidSME    = false;
            verifiedCredential = null;
            setError(emailGroup, 'Invalid email. Not an SME account. Please try again.');
            clearStates(passGroup);
            registerFailedAttempt();
        }

    } catch (err) {
        hidePopup();
        verifiedCredential = null;
        const code = err.code || '';

        if (code === 'auth/wrong-password' || code === 'auth/invalid-credential') {
            const left = MAX_ATTEMPTS - attemptCount - 1;
            setError(
                passGroup,
                left > 0
                    ? `Incorrect password. ${left} attempt${left !== 1 ? 's' : ''} left.`
                    : 'Incorrect password. No attempts left.'
            );
        } else if (code === 'auth/user-not-found' || code === 'auth/invalid-email') {
            emailIsValidSME = false;
            setError(emailGroup, 'Invalid email. Not an SME account. Please try again.');
        } else {
            setError(emailGroup, 'Login failed. Please try again.');
        }

        registerFailedAttempt();
    }
});