<div class="split-screen">
    <div class="branding-side">
        <span class="portal-tag">SME PORTAL</span>
        <div class="logo-box">
            <img src="logo.png" alt="CODEX: JAVA"> <h1 class="logo-text">CODEX: JAVA</h1>
            <p class="logo-sub">LEARN. CODE. GROW.</p>
        </div>
    </div>

    <div class="form-side">
        <div class="form-container">
            <h2>Welcome Back</h2>
            <p class="subtitle">Log in to you SME account</p>
            
            <form id="loginForm">
                <div class="field-group" id="emailGroup">
                    <label>Email Address *</label>
                    <input type="email" id="email" placeholder=" " required>
                    <span class="error-msg">Invalid email address. Please try again.</span>
                </div>

                <div class="field-group" id="passGroup">
                    <label>Password *</label>
                    <div class="pass-wrapper">
                        <input type="password" id="password" required>
                        <button type="button" class="eye-btn"></button>
                    </div>
                    <span class="error-msg">Incorrect password. Please try again.</span>
                </div>

                <button type="submit" id="submitBtn" disabled>Log in to SME Portal</button>
            </form>
            <p class="footer-note">This portal is restricted to authorized Subject Matter Experts only.</p>
        </div>
    </div>
</div>