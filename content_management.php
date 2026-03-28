<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CODEX | Content Management</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        /* BASE LAYOUT */
        body { display: flex; margin: 0; background-color: #f8fafc; font-family: 'Inter', sans-serif; }
        
        /* SIDEBAR STYLES */
        .db-sidebar { width: 260px; background-color: #001f3f; color: white; height: 100vh; display: flex; flex-direction: column; position: sticky; top: 0; }
        .db-logo-section { padding: 30px 25px; }
        .db-logo-section h1 { margin: 0; font-size: 22px; letter-spacing: 1px; }
        .db-logo-section h1 span { color: #f39c12; }
        .db-logo-section p { margin: 0; font-size: 12px; color: #94a3b8; }
        .db-nav-list { flex-grow: 1; padding: 20px 0; }
        .db-nav-item { padding: 12px 25px; display: flex; align-items: center; transition: 0.2s; }
        .db-nav-item a { color: #94a3b8; text-decoration: none; font-size: 14px; display: flex; align-items: center; gap: 12px; width: 100%; }
        .db-nav-item:hover { background: rgba(255,255,255,0.05); }
        .db-nav-item.active { background: #1e293b; border-left: 4px solid #f39c12; }
        .db-nav-item.active a { color: white; font-weight: 600; }
        .db-section-header { padding: 20px 25px 10px; font-size: 11px; text-transform: uppercase; color: #475569; letter-spacing: 1px; }
        .db-user-footer { padding: 20px 25px; border-top: 1px solid rgba(255,255,255,0.1); display: flex; align-items: center; gap: 12px; }
        .db-avatar { width: 35px; height: 35px; background: #3b82f6; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold; font-size: 12px; }

        /* MAIN CONTENT AREA */
        .db-main { flex-grow: 1; padding: 40px; box-sizing: border-box; overflow-y: auto; height: 100vh; }
        .ct-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; }
        .ct-title h2 { margin: 0; font-size: 24px; color: #1e293b; }
        .ct-title p { margin: 5px 0 0; color: #94a3b8; font-size: 14px; }
        .ct-controls { display: flex; gap: 12px; }
        .ct-select { padding: 8px 16px; border-radius: 8px; border: 1px solid #e2e8f0; background: white; }
        .btn-add { background: #1e293b; color: white; border: none; padding: 10px 18px; border-radius: 8px; font-weight: 600; cursor: pointer; }

        /* SECTION BOXES (Shadow Effect) */
        .ct-section { 
            background: white; border-radius: 12px; padding: 24px; 
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); 
            margin-bottom: 24px; border: 1px solid #f1f5f9;
        }
        .lesson-label { font-size: 16px; font-weight: 700; color: #334155; margin-bottom: 20px; }

        /* CARDS */
        .as-card { display: flex; align-items: center; justify-content: space-between; padding: 12px 20px; border: 1px solid #f1f5f9; border-radius: 12px; margin-bottom: 12px; }
        .as-info { display: flex; align-items: center; gap: 16px; flex-grow: 1; }
        .as-icon { width: 40px; height: 40px; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 13px; }
        
        .ic-q  { background: #eff6ff; color: #3b82f6; } /* Blue */
        .ic-pt { background: #f0fdf4; color: #22c55e; } /* Green */
        .ic-se { background: #fff7ed; color: #f59e0b; } /* Orange */
        .ic-mp { background: #fef2f2; color: #ef4444; } /* Red */

        .as-details h4 { margin: 0; font-size: 14px; color: #1e293b; }
        .as-details p { margin: 2px 0 0; font-size: 12px; color: #94a3b8; }
        .as-status { font-size: 11px; font-weight: 700; padding: 4px 10px; border-radius: 20px; margin-left: 15px; }
        .st-active { background: #f0fdf4; color: #22c55e; }
        .st-optional { background: #fff7ed; color: #f59e0b; }

        .as-actions { display: flex; gap: 8px; }
        .btn-act { background: #1e293b; color: white; border: none; padding: 8px 16px; border-radius: 6px; font-size: 12px; font-weight: 600; cursor: pointer; }

        /* TABLE */
        .hint-table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        .hint-table th { text-align: left; font-size: 11px; color: #94a3b8; padding: 12px; border-bottom: 1px solid #f1f5f9; text-transform: uppercase; }
        .hint-table td { padding: 15px 12px; font-size: 13px; border-bottom: 1px solid #f8fafc; color: #475569; }
        .trigger-badge { background: #fff7ed; color: #f59e0b; padding: 3px 8px; border-radius: 4px; font-size: 11px; font-weight: 700; }
        .btn-edit-text { background: none; border: none; color: #cbd5e1; cursor: pointer; font-weight: 600; font-size: 12px; }
    </style>
</head>
<body>

    <aside class="db-sidebar">
        <div class="db-logo-section">
            <h1>CODE<span>X</span></h1>
            <p>SME PORTAL</p>
        </div>
        
        <nav class="db-nav-list">
            <div class="db-nav-item"><a href="dashboard.php"><i class="fa-solid fa-table-cells-large"></i> Dashboard</a></div>
            <div class="db-nav-item"><a href="learner_progress.php"><i class="fa-solid fa-user-group"></i> Learner progress</a></div>
            <div class="db-section-header">Content</div>
            <div class="db-nav-item"><a href="course_management.php"><i class="fa-solid fa-bars-staggered"></i> Course management</a></div>
            <div class="db-nav-item active"><a href="content_management.php"><i class="fa-solid fa-bookmark"></i> Content management</a></div>
            <div class="db-section-header">System</div>
            <div class="db-nav-item"><a href="#"><i class="fa-solid fa-circle-dot"></i> Admin</a></div>
        </nav>

        <div class="db-user-footer">
            <div class="db-avatar">MJ</div>
            <div class="db-user-info">
                <p style="margin:0; font-size:13px; font-weight:600;">Ma'am Joms</p>
                <p style="margin:0; font-size:11px; color:#94a3b8;">SME Portal</p>
            </div>
        </div>
    </aside>

    <main class="db-main">
        <header class="ct-header">
            <div class="ct-title">
                <h2>Content management</h2>
                <p>Manage assessment bank per lesson</p>
            </div>
            <div class="ct-controls">
                <select class="ct-select"><option>All modules</option></select>
                <button class="btn-add">+ Add question</button>
            </div>
        </header>

        <section class="ct-section">
            <div class="lesson-label">Lesson: Variables & Data Types</div>
            
            <div class="as-card">
                <div class="as-info">
                    <div class="as-icon ic-q">Q</div>
                    <div class="as-details"><h4>Quiz</h4><p>12 questions • Auto-graded</p></div>
                    <span class="as-status st-active">Active</span>
                </div>
                <div class="as-actions"><button class="btn-act">Edit</button><button class="btn-act">Archive</button></div>
            </div>

            <div class="as-card">
                <div class="as-info">
                    <div class="as-icon ic-pt">PT</div>
                    <div class="as-details"><h4>Program tracing</h4><p>5 questions • Auto-graded</p></div>
                    <span class="as-status st-active">Active</span>
                </div>
                <div class="as-actions"><button class="btn-act">Edit</button><button class="btn-act">Archive</button></div>
            </div>

            <div class="as-card">
                <div class="as-info">
                    <div class="as-icon ic-se">SE</div>
                    <div class="as-details"><h4>Syntax error finding</h4><p>4 questions • Auto-graded</p></div>
                    <span class="as-status st-active">Active</span>
                </div>
                <div class="as-actions"><button class="btn-act">Edit</button><button class="btn-act">Archive</button></div>
            </div>

            <div class="as-card">
                <div class="as-info">
                    <div class="as-icon ic-mp">MP</div>
                    <div class="as-details"><h4>Machine problem</h4><p>1 problem • Piston sandbox grading</p></div>
                    <span class="as-status st-optional">Optional</span>
                </div>
                <div class="as-actions"><button class="btn-act">Edit</button><button class="btn-act">Archive</button></div>
            </div>
        </section>

        <section class="ct-section">
            <div class="lesson-label">Hint configuration</div>
            <table class="hint-table">
                <thead>
                    <tr><th>ASSESSMENT</th><th>TRIGGER</th><th>HINT MESSAGE</th><th style="text-align:right">ACTION</th></tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Quiz Q3</td>
                        <td><span class="trigger-badge">On fail</span></td>
                        <td>Remember that int holds whole numbers only...</td>
                        <td style="text-align:right"><button class="btn-edit-text">Edit</button></td>
                    </tr>
                    <tr>
                        <td>Program tracing PT2</td>
                        <td><span class="trigger-badge">On fail</span></td>
                        <td>Trace line by line, check variable scope...</td>
                        <td style="text-align:right"><button class="btn-edit-text">Edit</button></td>
                    </tr>
                </tbody>
            </table>
        </section>
    </main>
</body>
</html>