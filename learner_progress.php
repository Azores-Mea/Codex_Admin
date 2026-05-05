<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>CODEX | Learner Progress</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"/>
    <link rel="stylesheet" href="dashboard.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">

  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    body {
        display: flex;
        margin: 0;
        background-color: #fff;
        font-family: 'Roboto', sans-serif;
    }

    /* ── Page header ── */
    .lp-header {
      margin-bottom: 28px;
      display: flex;
    justify-content: flex-start;
        align-items: flex-start;  /* push content to the left */
        text-align: left;         /* make text align left */
      flex-direction: column;   /* stack h2 and p vertically */
      gap: 4px;                 /* consistent gap between title & subtitle */
    }
    .lp-header h2 {
      font-size: 32px;
      font-weight: 700;
      color: black;
      line-height: 1.2;
    }
    .lp-header p {
      font-size: 20px;
      color: black;
      line-height: 1.4;
    }

    /* Controls row */
    .lp-controls {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-bottom: 24px;
      flex-wrap: wrap;
      gap: 12px;
    }

    .sort-bar {
      display: flex;
      align-items: center;
      gap: 8px;
    }
    .sort-bar .sort-label {
      font-size: 14px;
      color: #374151;
      font-weight: 500;
    }
    .sort-btn-group {
      display: flex;
      background: #fff;
      border: 1.5px solid #e5e7eb;
      border-radius: 999px;
      overflow: hidden;
      padding: 3px;
      gap: 2px;
    }
    .sort-btn {
      border: none;
      background: transparent;
      padding: 7px 18px;
      border-radius: 999px;
      font-size: 13.5px;
      font-weight: 500;
      color: #374151;
      cursor: pointer;
      transition: background .18s, color .18s;
    }
    .sort-btn.active {
      background: #111827;
      color: #fff;
    }
    .sort-btn:not(.active):hover { background: #f3f4f6; }

    .lp-search {
      display: flex;
      align-items: center;
      background: #fff;
      border: 1.5px solid #e5e7eb;
      border-radius: 10px;
      padding: 9px 14px;
      gap: 8px;
      min-width: 230px;
    }
    .lp-search i { color: #9ca3af; font-size: 14px; }
    .lp-search input {
      border: none;
      outline: none;
      font-size: 14px;
      color: #374151;
      background: transparent;
      width: 100%;
    }

    /* Table card */
    .lp-card {
      background: #fff;
      border-radius: 14px;
      border: 1.5px solid #e5e7eb;
      overflow: hidden;
    }

    /* Fixed-layout table — columns never shift between pages */
    table.lp-table {
      width: 100%;
      border-collapse: collapse;
      table-layout: fixed;   /* KEY: locks column widths */
    }

    /* Column widths (must sum to 100%) */
    .lp-table col.col-learner      { width: 36%; }
    .lp-table col.col-class        { width: 20%; }
    .lp-table col.col-lessons      { width: 26%; }
    .lp-table col.col-score        { width: 18%; }

    .lp-table thead tr {
      background: #f9fafb;
      border-bottom: 1.5px solid #e5e7eb;
    }
    .lp-table th {
      padding: 13px 20px;
      font-size: 11.5px;
      font-weight: 700;
      letter-spacing: .06em;
      color: #6b7280;
      text-align: left;
      white-space: nowrap;        /* headings never wrap */
    }
    .lp-table tbody tr {
      border-bottom: 1px solid #f3f4f6;
      cursor: pointer;
      transition: background .15s;
    }
    .lp-table tbody tr:last-child { border-bottom: none; }
    .lp-table tbody tr:hover { background: #f8faff; }
    .lp-table td {
      padding: 16px 20px;
      font-size: 14px;
      color: #374151;
      vertical-align: middle;
      overflow: hidden;           /* prevent overflow from breaking layout */
      text-overflow: ellipsis;
    }

    /* Learner cell */
    .lp-user strong {
      display: block;
      font-size: 14px;
      color: #111827;
      font-weight: 600;
      overflow: hidden;
      text-overflow: ellipsis;
      white-space: nowrap;
    }
    .lp-user span {
      font-size: 12.5px;
      color: #9ca3af;
      overflow: hidden;
      text-overflow: ellipsis;
      white-space: nowrap;
      display: block;
    }

    /* Classification pill */
    .class-pill {
      display: inline-block;
      padding: 4px 14px;
      border-radius: 999px;
      font-size: 12px;
      font-weight: 500;
      border: 1.5px solid;
      white-space: nowrap;
    }
    .class-pill.beginner        { color: #F59E0B; border-color: #F59E0B;}
    .class-pill.intermediate    { color: #3B82F6; border-color: #3B82F6;}
    .class-pill.advanced        { color: #A855F7; border-color: #A855F7;}
    .class-pill.notclassified   { color: #6b7280; border-color: #9ca3af;}

    /* Progress bar */
    .lp-progress-wrapper {
      display: flex;
      align-items: center;
      gap: 10px;
    }
    .lp-bar-track {
      flex: 1;
      height: 6px;
      background: #e5e7eb;
      border-radius: 99px;
      min-width: 60px;
      max-width: 100px;
    }
    .lp-bar-fill {
      height: 100%;
      border-radius: 99px;
      background: #3b82f6;
      transition: width .4s ease;
    }
    .lp-bar-fill.empty { background: #d1d5db; }
    .lp-bar-count { font-size: 13px; color: #374151; white-space: nowrap; }

    /* Avg score */
    .avg-score { font-size: 14px; font-weight: 600; color: #111827; }

    /* Pagination */
    .lp-footer {
      display: flex;
      justify-content: flex-end;
      align-items: center;
      padding: 14px 20px;
      border-top: 1.5px solid #f3f4f6;
      gap: 8px;
    }
    .pag-info {
      font-size: 13px;
      color: #6b7280;
      margin-right: auto;
    }
    .pag-btn {
      width: 34px;
      height: 34px;
      border-radius: 8px;
      border: 1.5px solid #e5e7eb;
      background: #fff;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      color: #374151;
      font-size: 14px;
      transition: background .15s;
    }
    .pag-btn:hover:not(:disabled) { background: #f3f4f6; }
    .pag-btn:disabled { opacity: .4; cursor: default; }

    /* Empty state */
    .lp-empty {
      padding: 40px;
      text-align: center;
      color: #9ca3af;
      font-size: 14px;
    }
  </style>
</head>
<body>

<!-- ══ SIDEBAR ══════════════════════════════════════════════════════════ -->
<?php
    $activePage = 'learner_progress';
    include 'sidebar.php';
?>

<main class="db-main">

  <header class="lp-header">
    <h2>Learner Progress</h2>
    <p>View and monitor individual learner activity</p>
  </header>

  <div class="lp-controls">
    <div class="sort-bar">
      <span class="sort-label">Sort by:</span>
      <div class="sort-btn-group">
        <button class="sort-btn active" data-filter="all">All</button>
        <button class="sort-btn" data-filter="beginner">Beginner</button>
        <button class="sort-btn" data-filter="intermediate">Intermediate</button>
        <button class="sort-btn" data-filter="advanced">Advanced</button>
        <button class="sort-btn" data-filter="notclassified">Unclassified</button>
      </div>
    </div>
    <div class="lp-search">
      <i class="fa-solid fa-magnifying-glass"></i>
      <input type="text" id="searchInput" placeholder="Search learners..."/>
    </div>
  </div>

  <div class="lp-card">
    <table class="lp-table">
      <!-- colgroup defines fixed column widths independent of content -->
      <colgroup>
        <col class="col-learner"/>
        <col class="col-class"/>
        <col class="col-lessons"/>
        <col class="col-score"/>
      </colgroup>
      <thead>
        <tr>
          <th>LEARNER</th>
          <th>CLASSIFICATION</th>
          <th>LESSONS DONE</th>
          <th>AVG. SCORE</th>
        </tr>
      </thead>
      <tbody id="learnerTableBody">
      </tbody>
    </table>
    <div class="lp-footer">
      <span class="pag-info" id="pagInfo"></span>
      <button class="pag-btn" id="prevBtn" disabled><i class="fa-solid fa-chevron-left"></i></button>
      <button class="pag-btn" id="nextBtn"><i class="fa-solid fa-chevron-right"></i></button>
    </div>
  </div>

</main><!-- /db-main -->

<script>
  // ── Learner data populated from Firebase export ───────────────────────────
  const learners = [
    { id: 1, name: "test1 acc", email: "test1@gmail.com", classification: "intermediate", lessonsDone: 1, totalLessons: 30, avgScore: 57 },
    { id: 2, name: "test2 acc", email: "test2@gmail.com", classification: "beginner", lessonsDone: 0, totalLessons: 30, avgScore: 0 },
    { id: 3, name: "test3 acc", email: "test3@gmail.com", classification: "intermediate", lessonsDone: 0, totalLessons: 30, avgScore: 0 },
    { id: 4, name: "test5 acc", email: "test5@gmail.com", classification: "beginner", lessonsDone: 0, totalLessons: 30, avgScore: 0 },
    { id: 5, name: "test6 acc", email: "test6@gmail.com", classification: "beginner", lessonsDone: 0, totalLessons: 30, avgScore: 0 },
    { id: 6, name: "Mea Azores", email: "meaaz@gmail.com", classification: "intermediate", lessonsDone: 0, totalLessons: 30, avgScore: 63 },
    { id: 7, name: "App Test", email: "apptest@gmail.com", classification: "beginner", lessonsDone: 0, totalLessons: 30, avgScore: 0 },
    { id: 8, name: "test app", email: "testapp@gmail.com", classification: "advanced", lessonsDone: 0, totalLessons: 30, avgScore: 65 },
    { id: 9, name: "testapp j", email: "testapp1@gmail.com", classification: "advanced", lessonsDone: 0, totalLessons: 30, avgScore: 60 },
    { id: 10, name: "tilay lomoljo", email: "kristelleilomoljo@gmail.com", classification: "intermediate", lessonsDone: 3, totalLessons: 30, avgScore: 73 },
    { id: 11, name: "Sarah Luzada", email: "luzada.sarah.bshs@gmail.com", classification: "beginner", lessonsDone: 0, totalLessons: 30, avgScore: 0 },
    { id: 12, name: "test account", email: "test11@gmail.com", classification: "beginner", lessonsDone: 0, totalLessons: 30, avgScore: 0 },
    { id: 13, name: "Rona Joy Danlog", email: "danlogronajoy@gmail.com", classification: "beginner", lessonsDone: 0, totalLessons: 30, avgScore: 60 },
    { id: 14, name: "Rona Joy Danlog", email: "kirmoretti@gmail.com", classification: "beginner", lessonsDone: 0, totalLessons: 30, avgScore: 70 },
    { id: 15, name: "tilay Lomoljo", email: "loms@gmail.com", classification: "advanced", lessonsDone: 0, totalLessons: 30, avgScore: 0 },
    { id: 16, name: "whoopiee nel", email: "whoopieenel@gmail.com", classification: "beginner", lessonsDone: 0, totalLessons: 30, avgScore: 0 },
    { id: 17, name: "asd sdfsdf", email: "test99@gmail.com", classification: "beginner", lessonsDone: 0, totalLessons: 30, avgScore: 58 },
    { id: 18, name: "dhgshfsdugyui dsiugh", email: "gfdsfg@gmail.com", classification: "notclassified", lessonsDone: 0, totalLessons: 30, avgScore: 0 },
    { id: 19, name: "John Rave", email: "jrave@gmail.com", classification: "beginner", lessonsDone: 0, totalLessons: 30, avgScore: 50 },
    { id: 20, name: "test 100", email: "test100@gmail.com", classification: "beginner", lessonsDone: 0, totalLessons: 30, avgScore: 55 },
    { id: 21, name: "ave feliciano", email: "ave@gmail.com", classification: "notclassified", lessonsDone: 0, totalLessons: 30, avgScore: 20 },
    { id: 22, name: "Juyuy Rawr", email: "juyjuy@gmail.com", classification: "beginner", lessonsDone: 0, totalLessons: 30, avgScore: 73 },
    { id: 23, name: "Tils Loms", email: "lomoljo@gmail.com", classification: "notclassified", lessonsDone: 0, totalLessons: 30, avgScore: 0 },
    { id: 24, name: "meh meee", email: "tin@gmail.com", classification: "notclassified", lessonsDone: 0, totalLessons: 30, avgScore: 0 },
    { id: 25, name: "wuwiw hssuia", email: "rawrw@gmail.com", classification: "beginner", lessonsDone: 0, totalLessons: 30, avgScore: 65 },
    { id: 26, name: "Mea Azores (test)", email: "mea@gmail.com", classification: "notclassified", lessonsDone: 0, totalLessons: 30, avgScore: 0 },
    { id: 27, name: "Era Gannaban", email: "eramarie.gannaban@gmail.com", classification: "beginner", lessonsDone: 0, totalLessons: 30, avgScore: 0 },
    { id: 28, name: "Era Gannaban", email: "era@gmail.com", classification: "beginner", lessonsDone: 0, totalLessons: 30, avgScore: 0 },
    { id: 29, name: "Qia acc", email: "test101@gmail.com", classification: "notclassified", lessonsDone: 0, totalLessons: 30, avgScore: 0 },
    { id: 30, name: "Juan Dela Cruz", email: "delacruz@gmail.com", classification: "beginner", lessonsDone: 0, totalLessons: 30, avgScore: 90 },
    { id: 31, name: "Subi", email: "mahpersonalemel@gmail.com", classification: "beginner", lessonsDone: 4, totalLessons: 30, avgScore: 75 },
    { id: 32, name: "Mine rawr", email: "qia123@gmail.com", classification: "notclassified", lessonsDone: 0, totalLessons: 30, avgScore: 0 },
    { id: 33, name: "Norine Tiyanak", email: "tiyanak@gmail.com", classification: "notclassified", lessonsDone: 0, totalLessons: 30, avgScore: 0 },
    { id: 34, name: "Mea Azores", email: "meacamillea@gmail.com", classification: "beginner", lessonsDone: 0, totalLessons: 30, avgScore: 58 },
    { id: 35, name: "Rawrs", email: "supotipay@gmail.com", classification: "beginner", lessonsDone: 1, totalLessons: 30, avgScore: 0 },
    { id: 36, name: "Accou Nt", email: "ntaccou34@gmail.com", classification: "advanced", lessonsDone: 0, totalLessons: 30, avgScore: 0 },
    { id: 37, name: "Testing Account", email: "test@gmail.com", classification: "beginner", lessonsDone: 0, totalLessons: 30, avgScore: 0 },
    { id: 38, name: "T_T O_o", email: "ghehe815@gmail.com", classification: "beginner", lessonsDone: 3, totalLessons: 30, avgScore: 73 },
    { id: 39, name: "Mea Camille Azores", email: "meacamille09@outlook.com", classification: "beginner", lessonsDone: 2, totalLessons: 30, avgScore: 70 },
    { id: 40, name: "Mea Camille Azores", email: "meacamillea@outlook.com", classification: "beginner", lessonsDone: 4, totalLessons: 30, avgScore: 85 },
    { id: 41, name: "Rona Joy Danlog", email: "ronajoy@gmail.com", classification: "advanced", lessonsDone: 2, totalLessons: 30, avgScore: 65 },
    { id: 42, name: "Mea Camille Azores", email: "meacamille@outlook.com", classification: "beginner", lessonsDone: 3, totalLessons: 30, avgScore: 72 },
    { id: 43, name: "Rona Rawr", email: "ronajoy1@gmail.com", classification: "notclassified", lessonsDone: 0, totalLessons: 30, avgScore: 0 },
    { id: 44, name: "WIN", email: "winthat5stargenshinchar@gmail.com", classification: "notclassified", lessonsDone: 0, totalLessons: 30, avgScore: 0 },
    { id: 45, name: "Fade", email: "omgreyal999@gmail.com", classification: "notclassified", lessonsDone: 0, totalLessons: 30, avgScore: 0 },
    { id: 46, name: "Kristel Lomoljo", email: "plukkristellomoljo@gmail.com", classification: "notclassified", lessonsDone: 0, totalLessons: 30, avgScore: 0 },
    { id: 47, name: "Azores Danlog Lomoljo", email: "azdanlo@gmail.com", classification: "notclassified", lessonsDone: 0, totalLessons: 30, avgScore: 0 },
    { id: 48, name: "Account Dzuh", email: "nameism728@gmail.com", classification: "notclassified", lessonsDone: 0, totalLessons: 30, avgScore: 0 },
    { id: 49, name: "Wiwiwi", email: "wiwiwiaccou987@gmail.com", classification: "notclassified", lessonsDone: 0, totalLessons: 30, avgScore: 0 },
    { id: 50, name: "Mea Camille", email: "azoresmeacamille@gmail.com", classification: "beginner", lessonsDone: 0, totalLessons: 30, avgScore: 0 },
    { id: 51, name: "Codex Test", email: "codex@gmail.com", classification: "beginner", lessonsDone: 0, totalLessons: 30, avgScore: 0 },
    { id: 52, name: "Progress Testing", email: "progresstest@gmail.com", classification: "beginner", lessonsDone: 1, totalLessons: 30, avgScore: 50 },
    { id: 53, name: "test acc", email: "testacc1231@gmail.com", classification: "notclassified", lessonsDone: 1, totalLessons: 30, avgScore: 90 },
  ];

  const PAGE_SIZE = 10;
  let currentPage = 1;
  let activeFilter = 'all';
  let searchQuery = '';

  function getFiltered() {
    return learners.filter(l => {
      const matchFilter = activeFilter === 'all' || l.classification === activeFilter;
      const q = searchQuery.toLowerCase();
      const matchSearch = !q || l.name.toLowerCase().includes(q) || l.email.toLowerCase().includes(q);
      return matchFilter && matchSearch;
    });
  }

  function pillLabel(c) {
    if (c === 'notclassified') return 'Unclassified';
    return c.charAt(0).toUpperCase() + c.slice(1);
  }

  function render() {
    const filtered = getFiltered();
    const totalPages = Math.max(1, Math.ceil(filtered.length / PAGE_SIZE));
    currentPage = Math.min(currentPage, totalPages);
    const slice = filtered.slice((currentPage - 1) * PAGE_SIZE, currentPage * PAGE_SIZE);

    const tbody = document.getElementById('learnerTableBody');
    if (slice.length === 0) {
      tbody.innerHTML = `<tr><td colspan="4"><div class="lp-empty">No learners found.</div></td></tr>`;
    } else {
      tbody.innerHTML = slice.map(l => {
        const pct = l.totalLessons ? (l.lessonsDone / l.totalLessons * 100) : 0;
        const fillClass = pct === 0 ? 'empty' : '';
        return `
          <tr onclick="openLearner(${l.id})">
            <td>
              <div class="lp-user">
                <strong>${l.name}</strong>
                <span>${l.email}</span>
              </div>
            </td>
            <td><span class="class-pill ${l.classification}">${pillLabel(l.classification)}</span></td>
            <td>
              <div class="lp-progress-wrapper">
                <div class="lp-bar-track">
                  <div class="lp-bar-fill ${fillClass}" style="width:${pct}%"></div>
                </div>
                <span class="lp-bar-count">${l.lessonsDone}/${l.totalLessons}</span>
              </div>
            </td>
                <td>
                <span class="avg-score">
                    ${Math.round((l.lessonsDone / l.totalLessons) * 100)}%
                </span>
                </td>          </tr>`;
      }).join('');
    }

    const start = filtered.length === 0 ? 0 : (currentPage - 1) * PAGE_SIZE + 1;
    const end   = Math.min(currentPage * PAGE_SIZE, filtered.length);
    document.getElementById('pagInfo').textContent =
      filtered.length > 0 ? `Showing ${start}–${end} of ${filtered.length} learners` : '';

    document.getElementById('prevBtn').disabled = currentPage <= 1;
    document.getElementById('nextBtn').disabled = currentPage >= totalPages;
  }

  function openLearner(id) {
    window.location.href = `learner_detail.php?id=${id}`;
  }

  // Filter buttons
  document.querySelectorAll('.sort-btn').forEach(btn => {
    btn.addEventListener('click', () => {
      document.querySelectorAll('.sort-btn').forEach(b => b.classList.remove('active'));
      btn.classList.add('active');
      activeFilter = btn.dataset.filter;
      currentPage = 1;
      render();
    });
  });

  // Search
  document.getElementById('searchInput').addEventListener('input', e => {
    searchQuery = e.target.value;
    currentPage = 1;
    render();
  });

  // Pagination
  document.getElementById('prevBtn').addEventListener('click', () => { currentPage--; render(); });
  document.getElementById('nextBtn').addEventListener('click', () => { currentPage++; render(); });

  render();
</script>
</body>
</html>