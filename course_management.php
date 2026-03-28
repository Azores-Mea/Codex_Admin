<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CODEX | Course Management</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="dashboard.css">
    <style>
        body {
            display: flex;
            margin: 0;
            background-color: #fff;
            font-family: 'Inter', sans-serif;
        }

        .db-main {
            flex-grow: 1;
            padding: 40px;
            overflow-y: auto;
            height: 100vh;
            box-sizing: border-box;
        }

        /* DROPDOWN LOGIC */
        .cm-checkbox { display: none; }
        .cm-checkbox:checked ~ .cm-lesson-list { display: block !important; }
        .cm-checkbox:checked ~ .cm-dropdown-label .caret-icon { transform: rotate(90deg); }

        /* COURSE MANAGEMENT STYLING */
        .cm-page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }
        .cm-page-header h2 { margin: 0; font-size: 24px; color: #1e293b; }
        .cm-page-header p { margin: 5px 0 0; color: #94a3b8; font-size: 14px; }

        .btn-cm {
            padding: 8px 18px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 13px;
            border: none;
            cursor: pointer;
            transition: 0.2s;
        }
        .btn-cm.dark { background: #1e293b; color: white; }

        .cm-module-card {
            background: white;
            border: 1px solid #f1f5f9;
            border-radius: 12px;
            margin-bottom: 16px;
            overflow: hidden;
        }

        .cm-module-header {
            display: flex;
            align-items: center;
            padding: 20px 25px;
            cursor: pointer;
        }

        .cm-module-info {
            display: flex;
            align-items: center;
            gap: 15px;
            flex-grow: 1;
        }

        .cm-badge {
            background: #eff6ff;
            color: #3b82f6;
            padding: 2px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 700;
        }

        /* Yellow/Orange badge for Intermediate */
        .cm-badge.orange { background: #fff7ed; color: #f97316; }
        /* Purple badge for Advanced */
        .cm-badge.purple { background: #faf5ff; color: #a855f7; }

        .cm-lesson-list {
            display: none;
            background-color: #f8fafc;
            border-top: 1px solid #f1f5f9;
            padding: 25px;
        }

        .cm-lesson-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 20px;
            margin-left: 45px;
            background: white;
            border: 1px solid #f1f5f9;
            border-radius: 10px;
            margin-bottom: 10px;
        }

        .caret-icon { transition: transform 0.2s ease; color: #94a3b8; }
        .cm-dropdown-label { cursor: pointer; display: block; width: 100%; }
    </style>
</head>
<body>
    <aside class="db-sidebar">
        <div class="db-logo-section">
            <h1>CODE<span>X</span></h1>
            <p>SME PORTAL</p>
        </div>
        
        <nav class="db-nav-list">
            <div class="db-nav-item">
                <a href="dashboard.php"><i class="fa-solid fa-table-cells-large"></i> Dashboard</a>
            </div>
            <div class="db-nav-item">
                <a href="learner_progress.php"><i class="fa-solid fa-user-group"></i> Learner progress</a>
            </div>
            <div class="db-section-header">Content</div>
            <div class="db-nav-item active">
                <a href="course_management.php"><i class="fa-solid fa-bars-staggered"></i> Course management</a>
            </div>
            <div class="db-nav-item"><a href="content_management.php"><i class="fa-solid fa-bookmark"></i> Content management</a></div>
            <div class="db-section-header">System</div>
            <div class="db-nav-item"><a href="#"><i class="fa-solid fa-circle-dot"></i> Admin</a></div>
        </nav>

        <div class="db-user-footer">
            <div class="db-avatar">MJ</div>
            <div class="db-user-info">
                <p>Ma'am Joms</p>
                <span>Subject Matter Expert</span>
            </div>
        </div>
    </aside>

    <main class="db-main">
        <header class="cm-page-header">
            <div>
                <h2>Course management</h2>
                <p>Organize modules and lessons hierarchically</p>
            </div>
            <button class="btn-cm dark">+ Add module</button>
        </header>

        <div class="cm-container">
            <div class="cm-module-card">
                <input type="checkbox" id="mod1" class="cm-checkbox">
                <label for="mod1" class="cm-dropdown-label">
                    <div class="cm-module-header">
                        <div class="cm-module-info">
                            <i class="fa-solid fa-caret-right caret-icon"></i>
                            <h3 style="margin:0; font-size:16px; color: #1e293b;">Module 1 — Beginner</h3>
                            <span class="cm-badge">2 lessons</span>
                        </div>
                    </div>
                </label>
                <div class="cm-lesson-list">
                    <div class="cm-lesson-item">
                        <div class="cm-lesson-info">
                            <h4 style="margin:0; font-size:14px;">1. Variables & Data Types</h4>
                            <p style="margin:3px 0 0; font-size:11px; color:#94a3b8;">Text • Images • Code snippets</p>
                        </div>
                        <div class="cm-actions">
                            <button class="btn-cm dark">Edit</button>
                            <button class="btn-cm dark">Archive</button>
                        </div>
                    </div>
                    <div class="cm-lesson-item">
                        <div class="cm-lesson-info">
                            <h4 style="margin:0; font-size:14px;">2. Control Flow (if/else, loops)</h4>
                            <p style="margin:3px 0 0; font-size:11px; color:#94a3b8;">Text • Code snippets</p>
                        </div>
                        <div class="cm-actions">
                            <button class="btn-cm dark">Edit</button>
                            <button class="btn-cm dark">Archive</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="cm-module-card">
                <input type="checkbox" id="mod2" class="cm-checkbox">
                <label for="mod2" class="cm-dropdown-label">
                    <div class="cm-module-header">
                        <div class="cm-module-info">
                            <i class="fa-solid fa-caret-right caret-icon"></i>
                            <h3 style="margin:0; font-size:16px; color: #1e293b;">Module 2 — Intermediate</h3>
                            <span class="cm-badge orange">2 lessons</span>
                        </div>
                    </div>
                </label>
                <div class="cm-lesson-list">
                    <div class="cm-lesson-item">
                        <div class="cm-lesson-info">
                            <h4 style="margin:0; font-size:14px;">1. Methods & Functions</h4>
                            <p style="margin:3px 0 0; font-size:11px; color:#94a3b8;">Text • Code snippets</p>
                        </div>
                        <div class="cm-actions">
                            <button class="btn-cm dark">Edit</button>
                            <button class="btn-cm dark">Archive</button>
                        </div>
                    </div>
                    <div class="cm-lesson-item">
                        <div class="cm-lesson-info">
                            <h4 style="margin:0; font-size:14px;">2. Arrays & Collections</h4>
                            <p style="margin:3px 0 0; font-size:11px; color:#94a3b8;">Text • Images • Code snippets</p>
                        </div>
                        <div class="cm-actions">
                            <button class="btn-cm dark">Edit</button>
                            <button class="btn-cm dark">Archive</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="cm-module-card">
                <input type="checkbox" id="mod3" class="cm-checkbox">
                <label for="mod3" class="cm-dropdown-label">
                    <div class="cm-module-header">
                        <div class="cm-module-info">
                            <i class="fa-solid fa-caret-right caret-icon"></i>
                            <h3 style="margin:0; font-size:16px; color: #1e293b;">Module 3 — Advanced</h3>
                            <span class="cm-badge purple">2 lessons</span>
                        </div>
                    </div>
                </label>
                <div class="cm-lesson-list">
                    <div class="cm-lesson-item">
                        <div class="cm-lesson-info">
                            <h4 style="margin:0; font-size:14px;">1. Object-Oriented Programming</h4>
                            <p style="margin:3px 0 0; font-size:11px; color:#94a3b8;">Text • Diagrams • Code snippets</p>
                        </div>
                        <div class="cm-actions">
                            <button class="btn-cm dark">Edit</button>
                            <button class="btn-cm dark">Archive</button>
                        </div>
                    </div>
                    <div class="cm-lesson-item">
                        <div class="cm-lesson-info">
                            <h4 style="margin:0; font-size:14px;">2. Error Handling & Debugging</h4>
                            <p style="margin:3px 0 0; font-size:11px; color:#94a3b8;">Text • Code snippets</p>
                        </div>
                        <div class="cm-actions">
                            <button class="btn-cm dark">Edit</button>
                            <button class="btn-cm dark">Archive</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>
</html>