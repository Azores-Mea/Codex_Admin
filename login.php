<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SME Portal Login</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
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
                            <input type="email" id="email" placeholder=" " required autocomplete="off">
                            <label for="email">Email Address *</label>
                        </div>
                        <span class="helper-text error-msg">Invalid email address. Please try again.</span>
                        <span class="helper-text success-msg">Email verified!</span>
                    </div>

                    <div class="input-group" id="passGroup">
                        <div class="field-wrapper">
                            <input type="password" id="password" placeholder=" " required>
                            <label for="password">Password *</label>
                            <button type="button" class="eye-btn" id="eyeToggle">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                            </button>
                        </div>
                        <span class="helper-text error-msg">Incorrect password. Please try again.</span>
                        <span class="helper-text success-msg">Secure password.</span>
                    </div>

                    <button type="submit" class="btn-submit" id="submitBtn" disabled>Log in to SME Portal</button>
                </form>

                <footer class="disclaimer">
                    <p>This portal is restricted to authorized Subject Matter Experts only.</p>
                </footer>
            </div>
        </section>
    </main>
    <script src="script.js"></script>
</body>
</html>