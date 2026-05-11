<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SME Portal Login</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">

    <script src="https://www.gstatic.com/firebasejs/10.12.0/firebase-app-compat.js"></script>
    <script src="https://www.gstatic.com/firebasejs/10.12.0/firebase-auth-compat.js"></script>
    <script src="https://www.gstatic.com/firebasejs/10.12.0/firebase-firestore-compat.js"></script>

    <script>
        const firebaseConfig = {
            apiKey: "AIzaSyBmFwQe51Sfkhr36aXXlw4NYv7jag-8OcY",
            authDomain: "codex-f1355.firebaseapp.com",
            databaseURL: "https://codex-f1355-default-rtdb.firebaseio.com",
            projectId: "codex-f1355",
            storageBucket: "codex-f1355.firebasestorage.app",
            messagingSenderId: "273276166035",
            appId: "1:273276166035:web:e1f895eeaa03200a975266"
        };
        firebase.initializeApp(firebaseConfig);
    </script>

    <style>
        /* ── Logging In Popup ── */
        .popup-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.45);
            z-index: 9999;
            align-items: center;
            justify-content: center;
        }
        .popup-overlay.visible {
            display: flex;
        }
        .popup-card {
            background: #ffffff;
            border-radius: 14px;
            padding: 20px 36px;
            display: flex;
            align-items: center;
            gap: 14px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.18);
            font-family: 'Roboto', sans-serif;
            font-weight: 500;
            font-size: 1.05rem;
            color: #1a1a6e;
        }
        .popup-spinner {
            width: 18px;
            height: 18px;
            border: 2.5px solid #d0d0e8;
            border-top-color: #1a1a6e;
            border-radius: 50%;
            animation: spin 0.75s linear infinite;
            flex-shrink: 0;
        }
        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        /* ── Checking state ── */
        .input-group.checking .field-wrapper {
            opacity: 0.7;
        }
        .input-group.checking label {
            color: #888;
        }
        .helper-text.checking-msg {
            display: none;
            color: #888;
            font-size: 0.78rem;
            margin-top: 4px;
        }
        .input-group.checking .checking-msg {
            display: block;
            color: #888;
        }
        .input-group.checking .error-msg,
        .input-group.checking .success-msg {
            display: none;
        }

        /* ── Max length reached ── */
        .helper-text.limit-msg {
            font-family: 'Roboto', sans-serif;
            display: none;
            color: #E12A19;
            font-size: 12px;
            margin-top: 4px;
        }
        .helper-text.limit-msg.visible {
            display: block;
        }
        input[type="password"]::-ms-reveal,
        input[type="password"]::-ms-clear,
        input[type="password"]::-webkit-contacts-auto-fill-button,
        input[type="password"]::-webkit-credentials-auto-fill-button {
            display: none;
        }
    </style>
</head>
<body>
    <main class="split-screen">
        <section class="branding-side">
            <p class="portal-label">SME PORTAL</p>
            <div class="logo-container">
                <img src="logo.png" alt="CODEX: JAVA" class="centered-logo">
            </div>
        </section>

        <section class="form-side">
            <div class="form-content">
                <header>
                    <h2>Welcome Back</h2>
                    <p class="subtitle">Log in to your SME account</p>
                </header>

                <form id="loginForm">
                    <div class="input-group" id="emailGroup">
                        <div class="field-wrapper">
                            <input type="email" id="email" placeholder=" " required
                                   autocomplete="off" maxlength="254"
                                   oninput="this.value = this.value.toLowerCase()">
                            <label for="email">Email Address *</label>
                        </div>
                        <span class="helper-text checking-msg">Checking account...</span>
                        <span class="helper-text error-msg">Invalid email address. Please try again.</span>
                        <span class="helper-text success-msg">SME account found!</span>
                    </div>

                    <div class="input-group" id="passGroup">
                        <div class="field-wrapper">
                            <input type="password" id="password" placeholder=" " required
                                   maxlength="13">
                            <label for="password">Password *</label>
                            <button type="button" class="eye-btn" id="eyeToggle">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                    <circle cx="12" cy="12" r="3"></circle>
                                </svg>
                            </button>
                        </div>
                        <span class="helper-text checking-msg">Verifying password...</span>
                        <span class="helper-text error-msg">Incorrect password. Please try again.</span>
                        <span class="helper-text success-msg">Password matched!</span>
                    </div>

                    <button type="submit" class="btn-submit" id="submitBtn" disabled>Log in to SME Portal</button>
                </form>

                <footer class="disclaimer">
                    <p>This portal is restricted to authorized Subject Matter Experts only.</p>
                </footer>
            </div>
        </section>
    </main>

    <!-- Logging In Popup -->
    <div id="loggingInPopup" class="popup-overlay">
        <div class="popup-card">
            <div class="popup-spinner"></div>
            <span>Logging in...</span>
        </div>
    </div>

    <script src="script.js"></script>
    <script>
const password = document.getElementById("password");

password.addEventListener("keydown", function(e) {
    const max = this.maxLength;

    // Allow control/navigation keys
    const allowedKeys = [
        "Backspace", "Delete", "ArrowLeft", "ArrowRight",
        "Tab", "Home", "End"
    ];

    if (
        this.value.length >= max &&
        !allowedKeys.includes(e.key) &&
        !e.ctrlKey && !e.metaKey // allow Ctrl+C, Ctrl+V, etc.
    ) {
        e.preventDefault();
    }
});
</script>
</body>
</html>