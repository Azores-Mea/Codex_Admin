<!-- Logout Confirmation Modal -->
<div id="logoutModal" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.45); z-index:9999; align-items:center; justify-content:center;">
    <div style="background:#fff; border-radius:16px; border:0.5px solid #e2e8f0; padding:2rem; width:340px; text-align:center; box-shadow:0 20px 60px rgba(0,0,0,0.15);">

        <div style="width:52px; height:52px; border-radius:50%; background:#fee2e2; display:flex; align-items:center; justify-content:center; margin:0 auto 1.25rem;">
            <i class="fa-solid fa-arrow-right-from-bracket" style="color:#dc2626; font-size:18px;"></i>
        </div>

        <h2 style="font-size:17px; font-weight:700; color:#001c30; margin:0 0 6px;">Log out of CODEX - ADMIN?</h2>
        <p style="font-size:13px; color:#64748b; margin:0 0 1.75rem; line-height:1.6;">
            You're logged in as <strong style="color:#001c30;">Subject Matter Expert</strong>.<br>Any unsaved changes will be lost.
        </p>

        <div style="display:flex; gap:10px; justify-content:center;">
            <button onclick="closeLogoutModal()"
                style="padding:10px 28px; font-size:14px; font-weight:600; border-radius:50px;
                    border:2px solid #ef4444; background:transparent; color:#ef4444; cursor:pointer; min-width:110px;">
                Cancel
            </button>
            <button onclick="window.location.href='logout.php'"
                style="padding:10px 28px; font-size:14px; font-weight:600; border-radius:50px;
                    border:none; background:#001c30; color:#fff; cursor:pointer;
                    display:flex; align-items:center; gap:8px; min-width:130px; justify-content:center;">
                <i class="fa-solid fa-arrow-right-from-bracket"></i>
                Log out
            </button>
        </div>

    </div>
</div>

<!-- Sidebar -->
<aside class="db-sidebar" id="dbSidebar">

    <div class="db-logo-section">
        <h1>CODE<span>X</span></h1>
        <p style="font-size:11px; opacity:0.5; margin-top:4px;">SME PORTAL</p>
    </div>

    <nav class="db-nav-list">

        <div class="db-nav-item" id="nav-dashboard">
            <a href="#" data-page="dashboard">
                <i class="fa-solid fa-table-cells-large"></i> Dashboard
            </a>
        </div>

        <div class="db-nav-item" id="nav-learner_progress">
            <a href="#" data-page="learner_progress">
                <i class="fa-solid fa-user-group"></i> Learner Progress
            </a>
        </div>

        <div class="db-section-header">Content</div>

        <div class="db-nav-item" id="nav-course_management">
            <a href="#" data-page="course_management">
                <i class="fa-solid fa-bars-staggered"></i> Course Management
            </a>
        </div>

        <div class="db-nav-item" id="nav-content_management">
            <a href="#" data-page="content_management">
                <i class="fa-solid fa-bookmark"></i> Content Management
            </a>
        </div>

        <div class="db-section-header">System</div>

        <div class="db-nav-item" id="nav-admin">
            <a href="#" data-page="admin">
                <i class="fa-solid fa-circle-dot"></i> Admin
            </a>
        </div>

    </nav>

    <div class="db-user-footer">
        <div class="db-user-info">
            <p style="font-weight:700; margin:0; font-size:14px; color:white;">Ma'am Joms</p>
            <span style="font-size:11px; opacity:0.6; color:white;">Subject Matter Expert</span>
        </div>
        <div class="logout-btn" onclick="openLogoutModal()" title="Logout">
            <i class="fa-solid fa-arrow-right-from-bracket"></i>
        </div>
    </div>

</aside>

<script>
    function openLogoutModal() { document.getElementById('logoutModal').style.display = 'flex'; }
    function closeLogoutModal() { document.getElementById('logoutModal').style.display = 'none'; }
    document.getElementById('logoutModal').addEventListener('click', function(e) {
        if (e.target === this) closeLogoutModal();
    });
</script>