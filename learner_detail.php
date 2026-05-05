<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>CODEX | Learner Details</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"/>
  <link rel="stylesheet" href="dashboard.css">

  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    body {
      font-family: 'Segoe UI', sans-serif;
      background: #f4f6f9;
      color: #1a1a2e;
      min-height: 100vh;
    }

    .back-btn {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      font-size: 14px;
      color: #6b7280;
      background: none;
      border: none;
      cursor: pointer;
      margin-bottom: 20px;
      padding: 0;
      transition: color .15s;
    }
    .back-btn:hover { color: #111827; }

    .ld-header {
      margin-bottom: 28px;
      display: flex;
      flex-direction: column;
      gap: 4px;
    }
    .ld-header h2 {
      font-size: 32px;
      font-weight: 700;
      color: black;
      line-height: 1.2;
    }
    .ld-header p {
      font-size: 20px;
      color: black;
      line-height: 1.4;
    }

    .ld-top {
      display: grid;
      grid-template-columns: 280px 1fr;
      gap: 20px;
      margin-bottom: 36px;
      align-items: start;
    }

    .profile-card {
      background: #fff;
      border: 1.5px solid #e5e7eb;
      border-radius: 14px;
      padding: 24px;
      display: flex;
      flex-direction: column;
      align-items: center;
      gap: 4px;
      text-align: center;
    }
    .profile-avatar {
      width: 64px; height: 64px;
      border-radius: 50%;
      background: #e5e7eb;
      font-size: 22px;
      font-weight: 700;
      color: #374151;
      display: flex;
      align-items: center;
      justify-content: center;
      margin-bottom: 10px;
    }
    .profile-card h3 { font-size: 20px; font-weight: 700; color: #111827; }
    .class-pill {
      display: inline-block;
      padding: 3px 14px;
      border-radius: 999px;
      font-size: 12px;
      font-weight: 500;
      border: 1.5px solid;
      margin: 6px 0 14px;
      white-space: nowrap;
    }
    .class-pill.beginner        { color: #F59E0B; border-color: #F59E0B;}
    .class-pill.intermediate    { color: #3B82F6; border-color: #3B82F6;}
    .class-pill.advanced        { color: #A855F7; border-color: #A855F7;}
    .class-pill.notclassified   { color: #6b7280; border-color: #9ca3af;}

    .profile-email {
      display: flex;
      align-items: center;
      gap: 8px;
      font-size: 13.5px;
      color: #6b7280;
      border-top: 1px solid #f3f4f6;
      padding-top: 14px;
      width: 100%;
      justify-content: center;
    }
    .profile-email i { color: #9ca3af; }

    .summary-card {
      background: #fff;
      border: 1.5px solid #e5e7eb;
      border-radius: 14px;
      padding: 0;
      overflow: hidden;
    }
    .summary-card .sum-title {
      background: #f9fafb;
      padding: 12px 20px;
      font-size: 11.5px;
      font-weight: 700;
      letter-spacing: .07em;
      color: #6b7280;
      border-bottom: 1.5px solid #e5e7eb;
    }
    .sum-row {
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 14px 20px;
      border-bottom: 1px solid #f3f4f6;
      font-size: 14px;
      color: #374151;
    }
    .sum-row:last-child { border-bottom: none; }
    .sum-row .label {
      display: flex;
      align-items: center;
      gap: 10px;
    }
    .sum-row .label i { font-size: 15px; color: #6b7280; width: 18px; text-align: center; }
    .sum-row .value { font-weight: 600; color: #111827; }

    .mps-header {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-bottom: 18px;
      flex-wrap: wrap;
      gap: 12px;
    }
    .mps-header h3 { font-size: 20px; font-weight: 700; color: #111827; }

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
      padding: 6px 16px;
      border-radius: 999px;
      font-size: 13px;
      font-weight: 500;
      color: #374151;
      cursor: pointer;
      transition: background .18s, color .18s;
    }
    .sort-btn.active { background: #111827; color: #fff; }
    .sort-btn:not(.active):hover { background: #f3f4f6; }

    .mod-card {
      background: #fff;
      border: 1.5px solid #e5e7eb;
      border-radius: 14px;
      overflow: hidden;
    }

    table.mod-table {
      width: 100%;
      border-collapse: collapse;
      table-layout: fixed;
    }

    .mod-table col.col-module  { width: 30%; }
    .mod-table col.col-quiz    { width: 16%; }
    .mod-table col.col-syntax  { width: 18%; }
    .mod-table col.col-trace   { width: 18%; }
    .mod-table col.col-machine { width: 18%; }

    .mod-table thead tr {
      background: #f9fafb;
      border-bottom: 1.5px solid #e5e7eb;
    }
    .mod-table th {
      padding: 13px 20px;
      font-size: 11.5px;
      font-weight: 700;
      letter-spacing: .06em;
      color: #6b7280;
      text-align: left;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
    }
    .mod-table tbody tr {
      border-bottom: 1px solid #f3f4f6;
      transition: background .12s;
    }
    .mod-table tbody tr:last-child { border-bottom: none; }
    .mod-table tbody tr:hover { background: #f8faff; }
    .mod-table td {
      padding: 15px 20px;
      font-size: 14px;
      color: #374151;
      vertical-align: middle;
      overflow: hidden;
      text-overflow: ellipsis;
    }

    .mod-table td:first-child {
      white-space: normal;
      word-break: break-word;
    }

    .quiz-cell {
      display: flex;
      align-items: center;
      gap: 8px;
    }
    .bar-track {
      width: 50px;
      height: 6px;
      background: #e5e7eb;
      border-radius: 99px;
      flex-shrink: 0;
    }
    .bar-fill {
      height: 100%;
      border-radius: 99px;
      background: #3b82f6;
    }
    .bar-fill.empty { background: #d1d5db; }
    .quiz-score { font-size: 13px; color: #374151; white-space: nowrap; }

    .badge {
      display: inline-block;
      padding: 4px 14px;
      border-radius: 8px;
      font-size: 13px;
      font-weight: 500;
      white-space: nowrap;
    }
    .badge.complete    { background: #d1fae5; color: #065f46; }
    .badge.incomplete  { background: transparent; color: #ef4444; font-weight: 600; }
    .badge.na          { background: #e5e7eb; color: #6b7280; font-size: 13px; }

    .mod-footer {
      display: flex;
      justify-content: flex-end;
      padding: 14px 20px;
      border-top: 1.5px solid #f3f4f6;
      gap: 8px;
    }
    .pag-btn {
      width: 34px; height: 34px;
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

    @media (max-width: 700px) {
      .ld-top { grid-template-columns: 1fr; }
    }
  </style>
</head>
<body>

<?php
    $activePage = 'learner_progress';
    include 'sidebar.php';
?>

<main class="db-main">

  <header class="ld-header">
    <h2>Learner Details</h2>
    <p>View and monitor individual learner activity</p>
  </header>

  <div class="ld-top">
    <div class="profile-card">
      <h3 id="nameEl">—</h3>
      <span class="class-pill" id="classEl">—</span>
      <div class="profile-email">
        <i class="fa-regular fa-envelope"></i>
        <span id="emailEl">—</span>
      </div>
    </div>

    <div class="summary-card">
      <div class="sum-title">SHORT SUMMARY</div>
      <div class="sum-rows">
        <div class="sum-row">
          <span class="label"><i class="fa-regular fa-file-lines"></i> Lessons Done:</span>
          <span class="value" id="sumLessons">—</span>
        </div>
        <div class="sum-row">
          <span class="label"><i class="fa-regular fa-lightbulb"></i> Quizzes Taken:</span>
          <span class="value" id="sumQuizzes">—</span>
        </div>
        <div class="sum-row">
          <span class="label"><i class="fa-solid fa-chart-bar"></i> Avg. Score:</span>
          <span class="value" id="sumScore">—</span>
        </div>
      </div>
    </div>
  </div>

  <div class="mps-header">
    <h3>Module Performance Summary</h3>
    <div class="sort-btn-group">
      <button class="sort-btn active" data-filter="all">All</button>
      <button class="sort-btn" data-filter="beginner">Beginner</button>
      <button class="sort-btn" data-filter="intermediate">Intermediate</button>
      <button class="sort-btn" data-filter="advanced">Advanced</button>
    </div>
  </div>

  <div class="mod-card">
    <table class="mod-table">
      <colgroup>
        <col class="col-module"/>
        <col class="col-quiz"/>
        <col class="col-syntax"/>
        <col class="col-trace"/>
        <col class="col-machine"/>
      </colgroup>
      <thead>
        <tr>
          <th>MODULE</th>
          <th>QUIZ</th>
          <th>FINDING SYNTAX ERROR</th>
          <th>PROGRAM TRACING</th>
          <th>MACHINE PROBLEM</th>
        </tr>
      </thead>
      <tbody id="moduleTableBody"></tbody>
    </table>
    <div class="mod-footer">
      <button class="pag-btn" id="prevBtn" disabled><i class="fa-solid fa-chevron-left"></i></button>
      <button class="pag-btn" id="nextBtn"><i class="fa-solid fa-chevron-right"></i></button>
    </div>
  </div>

</main>

<script>
  const allLearners = [
    {
        "id": 1,
        "name": "test1 acc",
        "email": "test1@gmail.com",
        "classification": "intermediate",
        "lessonsDone": 3,
        "totalLessons": 23,
        "avgScore": 57,
        "quizzesTaken": 3,
        "modules": [
            {
                "name": "Introduction to Java",
                "level": "beginner",
                "quizScore": 7,
                "quizTotal": 10,
                "syntaxError": null,
                "programTracing": null,
                "machineProblem": null
            },
            {
                "name": "Classes and Objects",
                "level": "intermediate",
                "quizScore": 7,
                "quizTotal": 10,
                "syntaxError": null,
                "programTracing": null,
                "machineProblem": null
            },
            {
                "name": "Java Syntax and Comments",
                "level": "beginner",
                "quizScore": 3,
                "quizTotal": 10,
                "syntaxError": null,
                "programTracing": null,
                "machineProblem": null
            }
        ]
    },
    {
        "id": 2,
        "name": "test2 acc",
        "email": "test2@gmail.com",
        "classification": "beginner",
        "lessonsDone": 0,
        "totalLessons": 23,
        "avgScore": 0,
        "quizzesTaken": 0,
        "modules": []
    },
    {
        "id": 3,
        "name": "test3 acc",
        "email": "test3@gmail.com",
        "classification": "intermediate",
        "lessonsDone": 0,
        "totalLessons": 23,
        "avgScore": 0,
        "quizzesTaken": 0,
        "modules": []
    },
    {
        "id": 4,
        "name": "test5 acc",
        "email": "test5@gmail.com",
        "classification": "beginner",
        "lessonsDone": 0,
        "totalLessons": 23,
        "avgScore": 0,
        "quizzesTaken": 0,
        "modules": []
    },
    {
        "id": 5,
        "name": "test6 acc",
        "email": "test6@gmail.com",
        "classification": "beginner",
        "lessonsDone": 0,
        "totalLessons": 23,
        "avgScore": 0,
        "quizzesTaken": 0,
        "modules": []
    },
    {
        "id": 6,
        "name": "Mea Azores",
        "email": "meaaz@gmail.com",
        "classification": "intermediate",
        "lessonsDone": 3,
        "totalLessons": 23,
        "avgScore": 63,
        "quizzesTaken": 3,
        "modules": [
            {
                "name": "Introduction to Java",
                "level": "beginner",
                "quizScore": 5,
                "quizTotal": 10,
                "syntaxError": null,
                "programTracing": null,
                "machineProblem": null
            },
            {
                "name": "Java Syntax and Comments",
                "level": "beginner",
                "quizScore": 7,
                "quizTotal": 10,
                "syntaxError": null,
                "programTracing": null,
                "machineProblem": null
            },
            {
                "name": "Variables and Data Types",
                "level": "beginner",
                "quizScore": 7,
                "quizTotal": 10,
                "syntaxError": null,
                "programTracing": null,
                "machineProblem": null
            }
        ]
    },
    {
        "id": 7,
        "name": "App Test",
        "email": "apptest@gmail.com",
        "classification": "beginner",
        "lessonsDone": 0,
        "totalLessons": 23,
        "avgScore": 0,
        "quizzesTaken": 0,
        "modules": []
    },
    {
        "id": 8,
        "name": "test app",
        "email": "testapp@gmail.com",
        "classification": "advanced",
        "lessonsDone": 2,
        "totalLessons": 23,
        "avgScore": 65,
        "quizzesTaken": 2,
        "modules": [
            {
                "name": "Introduction to Java",
                "level": "beginner",
                "quizScore": 5,
                "quizTotal": 10,
                "syntaxError": null,
                "programTracing": null,
                "machineProblem": null
            },
            {
                "name": "Java Syntax and Comments",
                "level": "beginner",
                "quizScore": 8,
                "quizTotal": 10,
                "syntaxError": null,
                "programTracing": null,
                "machineProblem": null
            }
        ]
    },
    {
        "id": 9,
        "name": "testapp j",
        "email": "testapp1@gmail.com",
        "classification": "advanced",
        "lessonsDone": 4,
        "totalLessons": 23,
        "avgScore": 60,
        "quizzesTaken": 4,
        "modules": [
            {
                "name": "Introduction to Java",
                "level": "beginner",
                "quizScore": 5,
                "quizTotal": 10,
                "syntaxError": null,
                "programTracing": null,
                "machineProblem": null
            },
            {
                "name": "Java Syntax and Comments",
                "level": "beginner",
                "quizScore": 8,
                "quizTotal": 10,
                "syntaxError": null,
                "programTracing": null,
                "machineProblem": null
            },
            {
                "name": "Variables and Data Types",
                "level": "beginner",
                "quizScore": 6,
                "quizTotal": 10,
                "syntaxError": null,
                "programTracing": null,
                "machineProblem": null
            },
            {
                "name": "Type Casting and Operators",
                "level": "beginner",
                "quizScore": 5,
                "quizTotal": 10,
                "syntaxError": null,
                "programTracing": null,
                "machineProblem": null
            }
        ]
    },
    {
        "id": 10,
        "name": "tilay lomoljo",
        "email": "kristelleilomoljo@gmail.com",
        "classification": "intermediate",
        "lessonsDone": 5,
        "totalLessons": 23,
        "avgScore": 73,
        "quizzesTaken": 3,
        "modules": [
            {
                "name": "Introduction to Java",
                "level": "beginner",
                "quizScore": 8,
                "quizTotal": 10,
                "syntaxError": null,
                "programTracing": null,
                "machineProblem": null
            },
            {
                "name": "Java Syntax and Comments",
                "level": "beginner",
                "quizScore": 8,
                "quizTotal": 10,
                "syntaxError": "complete",
                "programTracing": null,
                "machineProblem": null
            },
            {
                "name": "Variables and Data Types",
                "level": "beginner",
                "quizScore": 6,
                "quizTotal": 10,
                "syntaxError": "complete",
                "programTracing": null,
                "machineProblem": null
            },
            {
                "name": "Type Casting and Operators",
                "level": "beginner",
                "quizScore": 0,
                "quizTotal": 10,
                "syntaxError": null,
                "programTracing": "complete",
                "machineProblem": null
            },
            {
                "name": "String Manipulation, Math, and Booleans",
                "level": "beginner",
                "quizScore": 0,
                "quizTotal": 10,
                "syntaxError": null,
                "programTracing": "complete",
                "machineProblem": null
            }
        ]
    },
    {
        "id": 11,
        "name": "Sarah Luzada",
        "email": "luzada.sarah.bshs@gmail.com",
        "classification": "beginner",
        "lessonsDone": 0,
        "totalLessons": 23,
        "avgScore": 0,
        "quizzesTaken": 0,
        "modules": []
    },
    {
        "id": 12,
        "name": "test account",
        "email": "test11@gmail.com",
        "classification": "beginner",
        "lessonsDone": 0,
        "totalLessons": 23,
        "avgScore": 0,
        "quizzesTaken": 0,
        "modules": []
    },
    {
        "id": 13,
        "name": "Rona Joy Danlog",
        "email": "danlogronajoy@gmail.com",
        "classification": "beginner",
        "lessonsDone": 2,
        "totalLessons": 23,
        "avgScore": 60,
        "quizzesTaken": 2,
        "modules": [
            {
                "name": "Introduction to Java",
                "level": "beginner",
                "quizScore": 9,
                "quizTotal": 10,
                "syntaxError": null,
                "programTracing": null,
                "machineProblem": null
            },
            {
                "name": "Java Syntax and Comments",
                "level": "beginner",
                "quizScore": 3,
                "quizTotal": 10,
                "syntaxError": null,
                "programTracing": null,
                "machineProblem": null
            }
        ]
    },
    {
        "id": 14,
        "name": "Rona Joy Danlog",
        "email": "kirmoretti@gmail.com",
        "classification": "beginner",
        "lessonsDone": 3,
        "totalLessons": 23,
        "avgScore": 70,
        "quizzesTaken": 3,
        "modules": [
            {
                "name": "Introduction to Java",
                "level": "beginner",
                "quizScore": 7,
                "quizTotal": 10,
                "syntaxError": null,
                "programTracing": null,
                "machineProblem": null
            },
            {
                "name": "Java Syntax and Comments",
                "level": "beginner",
                "quizScore": 8,
                "quizTotal": 10,
                "syntaxError": null,
                "programTracing": null,
                "machineProblem": null
            },
            {
                "name": "Variables and Data Types",
                "level": "beginner",
                "quizScore": 6,
                "quizTotal": 10,
                "syntaxError": null,
                "programTracing": null,
                "machineProblem": null
            }
        ]
    },
    {
        "id": 15,
        "name": "tilay Lomoljo",
        "email": "loms@gmail.com",
        "classification": "advanced",
        "lessonsDone": 0,
        "totalLessons": 23,
        "avgScore": 0,
        "quizzesTaken": 0,
        "modules": []
    },
    {
        "id": 16,
        "name": "whoopiee nel",
        "email": "whoopieenel@gmail.com",
        "classification": "beginner",
        "lessonsDone": 0,
        "totalLessons": 23,
        "avgScore": 0,
        "quizzesTaken": 0,
        "modules": []
    },
    {
        "id": 17,
        "name": "asd sdfsdf",
        "email": "test99@gmail.com",
        "classification": "beginner",
        "lessonsDone": 6,
        "totalLessons": 23,
        "avgScore": 58,
        "quizzesTaken": 6,
        "modules": [
            {
                "name": "Introduction to Java",
                "level": "beginner",
                "quizScore": 5,
                "quizTotal": 10,
                "syntaxError": null,
                "programTracing": null,
                "machineProblem": null
            },
            {
                "name": "Classes and Objects",
                "level": "intermediate",
                "quizScore": 5,
                "quizTotal": 10,
                "syntaxError": null,
                "programTracing": null,
                "machineProblem": null
            },
            {
                "name": "Java Syntax and Comments",
                "level": "beginner",
                "quizScore": 8,
                "quizTotal": 10,
                "syntaxError": null,
                "programTracing": null,
                "machineProblem": null
            },
            {
                "name": "Variables and Data Types",
                "level": "beginner",
                "quizScore": 6,
                "quizTotal": 10,
                "syntaxError": null,
                "programTracing": null,
                "machineProblem": null
            },
            {
                "name": "Type Casting and Operators",
                "level": "beginner",
                "quizScore": 5,
                "quizTotal": 10,
                "syntaxError": null,
                "programTracing": null,
                "machineProblem": null
            },
            {
                "name": "String Manipulation, Math, and Booleans",
                "level": "beginner",
                "quizScore": 6,
                "quizTotal": 10,
                "syntaxError": null,
                "programTracing": null,
                "machineProblem": null
            }
        ]
    },
    {
        "id": 18,
        "name": "dhgshfsdugyui dsiugh",
        "email": "gfdsfg@gmail.com",
        "classification": "notClassified",
        "lessonsDone": 0,
        "totalLessons": 23,
        "avgScore": 0,
        "quizzesTaken": 0,
        "modules": []
    },
    {
        "id": 19,
        "name": "John Rave",
        "email": "jrave@gmail.com",
        "classification": "beginner",
        "lessonsDone": 1,
        "totalLessons": 23,
        "avgScore": 50,
        "quizzesTaken": 1,
        "modules": [
            {
                "name": "Introduction to Java",
                "level": "beginner",
                "quizScore": 5,
                "quizTotal": 10,
                "syntaxError": null,
                "programTracing": null,
                "machineProblem": null
            }
        ]
    },
    {
        "id": 20,
        "name": "test 100",
        "email": "test100@gmail.com",
        "classification": "beginner",
        "lessonsDone": 2,
        "totalLessons": 23,
        "avgScore": 55,
        "quizzesTaken": 2,
        "modules": [
            {
                "name": "Introduction to Java",
                "level": "beginner",
                "quizScore": 5,
                "quizTotal": 10,
                "syntaxError": null,
                "programTracing": null,
                "machineProblem": null
            },
            {
                "name": "Java Syntax and Comments",
                "level": "beginner",
                "quizScore": 6,
                "quizTotal": 10,
                "syntaxError": null,
                "programTracing": null,
                "machineProblem": null
            }
        ]
    },
    {
        "id": 21,
        "name": "ave feliciano",
        "email": "ave@gmail.com",
        "classification": "notClassified",
        "lessonsDone": 1,
        "totalLessons": 23,
        "avgScore": 20,
        "quizzesTaken": 1,
        "modules": [
            {
                "name": "Introduction to Java",
                "level": "beginner",
                "quizScore": 2,
                "quizTotal": 10,
                "syntaxError": null,
                "programTracing": null,
                "machineProblem": null
            }
        ]
    },
    {
        "id": 22,
        "name": "Juyuy Rawr",
        "email": "juyjuy@gmail.com",
        "classification": "beginner",
        "lessonsDone": 3,
        "totalLessons": 23,
        "avgScore": 73,
        "quizzesTaken": 3,
        "modules": [
            {
                "name": "Introduction to Java",
                "level": "beginner",
                "quizScore": 8,
                "quizTotal": 10,
                "syntaxError": null,
                "programTracing": null,
                "machineProblem": null
            },
            {
                "name": "Java Syntax and Comments",
                "level": "beginner",
                "quizScore": 8,
                "quizTotal": 10,
                "syntaxError": null,
                "programTracing": null,
                "machineProblem": null
            },
            {
                "name": "Variables and Data Types",
                "level": "beginner",
                "quizScore": 6,
                "quizTotal": 10,
                "syntaxError": null,
                "programTracing": null,
                "machineProblem": null
            }
        ]
    },
    {
        "id": 23,
        "name": "Tils Loms",
        "email": "lomoljo@gmail.com",
        "classification": "notClassified",
        "lessonsDone": 0,
        "totalLessons": 23,
        "avgScore": 0,
        "quizzesTaken": 0,
        "modules": []
    },
    {
        "id": 24,
        "name": "meh meee",
        "email": "tin@gmail.com",
        "classification": "notClassified",
        "lessonsDone": 0,
        "totalLessons": 23,
        "avgScore": 0,
        "quizzesTaken": 0,
        "modules": []
    },
    {
        "id": 25,
        "name": "wuwiw hssuia",
        "email": "rawrw@gmail.com",
        "classification": "beginner",
        "lessonsDone": 2,
        "totalLessons": 23,
        "avgScore": 65,
        "quizzesTaken": 2,
        "modules": [
            {
                "name": "Introduction to Java",
                "level": "beginner",
                "quizScore": 5,
                "quizTotal": 10,
                "syntaxError": null,
                "programTracing": null,
                "machineProblem": null
            },
            {
                "name": "Java Syntax and Comments",
                "level": "beginner",
                "quizScore": 8,
                "quizTotal": 10,
                "syntaxError": null,
                "programTracing": null,
                "machineProblem": null
            }
        ]
    },
    {
        "id": 26,
        "name": "Mea@#@1329999999999999999999999999 Azoreaa1@(@(@899999999",
        "email": "mea@gmail.com",
        "classification": "notClassified",
        "lessonsDone": 0,
        "totalLessons": 23,
        "avgScore": 0,
        "quizzesTaken": 0,
        "modules": []
    },
    {
        "id": 27,
        "name": "Era Gannaban",
        "email": "eramarie.gannaban@gmail.com",
        "classification": "beginner",
        "lessonsDone": 0,
        "totalLessons": 23,
        "avgScore": 0,
        "quizzesTaken": 0,
        "modules": []
    },
    {
        "id": 28,
        "name": "Era Gannaban",
        "email": "era@gmail.com",
        "classification": "beginner",
        "lessonsDone": 0,
        "totalLessons": 23,
        "avgScore": 0,
        "quizzesTaken": 0,
        "modules": []
    },
    {
        "id": 29,
        "name": "Qia acc",
        "email": "test101@gmail.com",
        "classification": "notClassified",
        "lessonsDone": 0,
        "totalLessons": 23,
        "avgScore": 0,
        "quizzesTaken": 0,
        "modules": []
    },
    {
        "id": 30,
        "name": "Juan Dela Cruz",
        "email": "delacruz@gmail.com",
        "classification": "beginner",
        "lessonsDone": 2,
        "totalLessons": 23,
        "avgScore": 90,
        "quizzesTaken": 2,
        "modules": [
            {
                "name": "Introduction to Java",
                "level": "beginner",
                "quizScore": 9,
                "quizTotal": 10,
                "syntaxError": null,
                "programTracing": null,
                "machineProblem": null
            },
            {
                "name": "Java Syntax and Comments",
                "level": "beginner",
                "quizScore": 9,
                "quizTotal": 10,
                "syntaxError": null,
                "programTracing": null,
                "machineProblem": null
            }
        ]
    },
    {
        "id": 31,
        "name": "Subi",
        "email": "mahpersonalemel@gmail.com",
        "classification": "beginner",
        "lessonsDone": 4,
        "totalLessons": 23,
        "avgScore": 75,
        "quizzesTaken": 4,
        "modules": [
            {
                "name": "Introduction to Java",
                "level": "beginner",
                "quizScore": 8,
                "quizTotal": 10,
                "syntaxError": null,
                "programTracing": null,
                "machineProblem": null
            },
            {
                "name": "Java Syntax and Comments",
                "level": "beginner",
                "quizScore": 6,
                "quizTotal": 10,
                "syntaxError": "complete",
                "programTracing": null,
                "machineProblem": null
            },
            {
                "name": "Variables and Data Types",
                "level": "beginner",
                "quizScore": 9,
                "quizTotal": 10,
                "syntaxError": "complete",
                "programTracing": null,
                "machineProblem": null
            },
            {
                "name": "Type Casting and Operators",
                "level": "beginner",
                "quizScore": 7,
                "quizTotal": 10,
                "syntaxError": "complete",
                "programTracing": "complete",
                "machineProblem": "complete"
            }
        ]
    },
    {
        "id": 32,
        "name": "Mine rawr",
        "email": "qia123@gmail.com",
        "classification": "notClassified",
        "lessonsDone": 0,
        "totalLessons": 23,
        "avgScore": 0,
        "quizzesTaken": 0,
        "modules": []
    },
    {
        "id": 33,
        "name": "Norine Tiyanak",
        "email": "tiyanak@gmail.com",
        "classification": "notClassified",
        "lessonsDone": 0,
        "totalLessons": 23,
        "avgScore": 0,
        "quizzesTaken": 0,
        "modules": []
    },
    {
        "id": 34,
        "name": "Mea Azores",
        "email": "meacamillea@gmail.com",
        "classification": "beginner",
        "lessonsDone": 4,
        "totalLessons": 23,
        "avgScore": 58,
        "quizzesTaken": 4,
        "modules": [
            {
                "name": "Introduction to Java",
                "level": "beginner",
                "quizScore": 5,
                "quizTotal": 10,
                "syntaxError": null,
                "programTracing": null,
                "machineProblem": null
            },
            {
                "name": "Java Syntax and Comments",
                "level": "beginner",
                "quizScore": 5,
                "quizTotal": 10,
                "syntaxError": null,
                "programTracing": null,
                "machineProblem": null
            },
            {
                "name": "Variables and Data Types",
                "level": "beginner",
                "quizScore": 6,
                "quizTotal": 10,
                "syntaxError": null,
                "programTracing": null,
                "machineProblem": null
            },
            {
                "name": "Type Casting and Operators",
                "level": "beginner",
                "quizScore": 7,
                "quizTotal": 10,
                "syntaxError": null,
                "programTracing": null,
                "machineProblem": null
            }
        ]
    },
    {
        "id": 35,
        "name": "Rawrs",
        "email": "supotipay@gmail.com",
        "classification": "beginner",
        "lessonsDone": 0,
        "totalLessons": 23,
        "avgScore": 0,
        "quizzesTaken": 0,
        "modules": []
    },
    {
        "id": 36,
        "name": "Accou Nt",
        "email": "ntaccou34@gmail.com",
        "classification": "advanced",
        "lessonsDone": 0,
        "totalLessons": 23,
        "avgScore": 0,
        "quizzesTaken": 0,
        "modules": []
    },
    {
        "id": 37,
        "name": "Testing Account",
        "email": "test@gmail.com",
        "classification": "beginner",
        "lessonsDone": 0,
        "totalLessons": 23,
        "avgScore": 0,
        "quizzesTaken": 0,
        "modules": []
    },
    {
        "id": 38,
        "name": "T_T O_o",
        "email": "ghehe815@gmail.com",
        "classification": "beginner",
        "lessonsDone": 4,
        "totalLessons": 23,
        "avgScore": 73,
        "quizzesTaken": 3,
        "modules": [
            {
                "name": "Introduction to Java",
                "level": "beginner",
                "quizScore": 7,
                "quizTotal": 10,
                "syntaxError": null,
                "programTracing": null,
                "machineProblem": null
            },
            {
                "name": "Java Syntax and Comments",
                "level": "beginner",
                "quizScore": 8,
                "quizTotal": 10,
                "syntaxError": "complete",
                "programTracing": null,
                "machineProblem": null
            },
            {
                "name": "Variables and Data Types",
                "level": "beginner",
                "quizScore": 7,
                "quizTotal": 10,
                "syntaxError": "complete",
                "programTracing": null,
                "machineProblem": null
            },
            {
                "name": "Type Casting and Operators",
                "level": "beginner",
                "quizScore": 0,
                "quizTotal": 10,
                "syntaxError": null,
                "programTracing": "complete",
                "machineProblem": "complete"
            }
        ]
    },
    {
        "id": 39,
        "name": "Mea Camille Azores",
        "email": "meacamille09@outlook.com",
        "classification": "beginner",
        "lessonsDone": 2,
        "totalLessons": 23,
        "avgScore": 70,
        "quizzesTaken": 2,
        "modules": [
            {
                "name": "Introduction to Java",
                "level": "beginner",
                "quizScore": 8,
                "quizTotal": 10,
                "syntaxError": null,
                "programTracing": null,
                "machineProblem": null
            },
            {
                "name": "Java Syntax and Comments",
                "level": "beginner",
                "quizScore": 6,
                "quizTotal": 10,
                "syntaxError": "complete",
                "programTracing": null,
                "machineProblem": null
            }
        ]
    },
    {
        "id": 40,
        "name": "Mea Camille Azores",
        "email": "meacamillea@outlook.com",
        "classification": "beginner",
        "lessonsDone": 5,
        "totalLessons": 23,
        "avgScore": 85,
        "quizzesTaken": 4,
        "modules": [
            {
                "name": "Introduction to Java",
                "level": "beginner",
                "quizScore": 8,
                "quizTotal": 10,
                "syntaxError": null,
                "programTracing": null,
                "machineProblem": null
            },
            {
                "name": "Java Syntax and Comments",
                "level": "beginner",
                "quizScore": 6,
                "quizTotal": 10,
                "syntaxError": "complete",
                "programTracing": null,
                "machineProblem": null
            },
            {
                "name": "Variables and Data Types",
                "level": "beginner",
                "quizScore": 10,
                "quizTotal": 10,
                "syntaxError": "complete",
                "programTracing": null,
                "machineProblem": null
            },
            {
                "name": "Type Casting and Operators",
                "level": "beginner",
                "quizScore": 10,
                "quizTotal": 10,
                "syntaxError": "complete",
                "programTracing": "complete",
                "machineProblem": null
            },
            {
                "name": "String Manipulation, Math, and Booleans",
                "level": "beginner",
                "quizScore": 0,
                "quizTotal": 10,
                "syntaxError": null,
                "programTracing": null,
                "machineProblem": "complete"
            }
        ]
    },
    {
        "id": 41,
        "name": "Rona Joy Danlog",
        "email": "ronajoy@gmail.com",
        "classification": "advanced",
        "lessonsDone": 2,
        "totalLessons": 23,
        "avgScore": 65,
        "quizzesTaken": 2,
        "modules": [
            {
                "name": "Introduction to Java",
                "level": "beginner",
                "quizScore": 8,
                "quizTotal": 10,
                "syntaxError": null,
                "programTracing": null,
                "machineProblem": null
            },
            {
                "name": "Type Casting and Operators",
                "level": "beginner",
                "quizScore": 5,
                "quizTotal": 10,
                "syntaxError": "complete",
                "programTracing": "complete",
                "machineProblem": "complete"
            }
        ]
    },
    {
        "id": 42,
        "name": "Mea Camille Azores",
        "email": "meacamille@outlook.com",
        "classification": "beginner",
        "lessonsDone": 4,
        "totalLessons": 23,
        "avgScore": 72,
        "quizzesTaken": 4,
        "modules": [
            {
                "name": "Introduction to Java",
                "level": "beginner",
                "quizScore": 5,
                "quizTotal": 10,
                "syntaxError": null,
                "programTracing": null,
                "machineProblem": null
            },
            {
                "name": "Java Syntax and Comments",
                "level": "beginner",
                "quizScore": 6,
                "quizTotal": 10,
                "syntaxError": "complete",
                "programTracing": null,
                "machineProblem": null
            },
            {
                "name": "Variables and Data Types",
                "level": "beginner",
                "quizScore": 9,
                "quizTotal": 10,
                "syntaxError": "complete",
                "programTracing": null,
                "machineProblem": null
            },
            {
                "name": "Type Casting and Operators",
                "level": "beginner",
                "quizScore": 9,
                "quizTotal": 10,
                "syntaxError": null,
                "programTracing": "complete",
                "machineProblem": "complete"
            }
        ]
    },
    {
        "id": 43,
        "name": "Rona Rawr",
        "email": "ronajoy1@gmail.com",
        "classification": "notClassified",
        "lessonsDone": 0,
        "totalLessons": 23,
        "avgScore": 0,
        "quizzesTaken": 0,
        "modules": []
    },
    {
        "id": 44,
        "name": "WIN",
        "email": "winthat5stargenshinchar@gmail.com",
        "classification": "notClassified",
        "lessonsDone": 0,
        "totalLessons": 23,
        "avgScore": 0,
        "quizzesTaken": 0,
        "modules": []
    },
    {
        "id": 45,
        "name": "Fade",
        "email": "omgreyal999@gmail.com",
        "classification": "notClassified",
        "lessonsDone": 0,
        "totalLessons": 23,
        "avgScore": 0,
        "quizzesTaken": 0,
        "modules": []
    },
    {
        "id": 46,
        "name": "Kristel Lomoljo",
        "email": "plukkristellomoljo@gmail.com",
        "classification": "notClassified",
        "lessonsDone": 0,
        "totalLessons": 23,
        "avgScore": 0,
        "quizzesTaken": 0,
        "modules": []
    },
    {
        "id": 47,
        "name": "Azores Danlog Lomoljo",
        "email": "azdanlo@gmail.com",
        "classification": "notClassified",
        "lessonsDone": 0,
        "totalLessons": 23,
        "avgScore": 0,
        "quizzesTaken": 0,
        "modules": []
    },
    {
        "id": 48,
        "name": "Account Dzuh",
        "email": "nameism728@gmail.com",
        "classification": "notClassified",
        "lessonsDone": 0,
        "totalLessons": 23,
        "avgScore": 0,
        "quizzesTaken": 0,
        "modules": []
    },
    {
        "id": 49,
        "name": "Wiwiwi",
        "email": "wiwiwiaccou987@gmail.com",
        "classification": "notClassified",
        "lessonsDone": 0,
        "totalLessons": 23,
        "avgScore": 0,
        "quizzesTaken": 0,
        "modules": []
    },
    {
        "id": 50,
        "name": "Mea Camille",
        "email": "azoresmeacamille@gmail.com",
        "classification": "beginner",
        "lessonsDone": 0,
        "totalLessons": 23,
        "avgScore": 0,
        "quizzesTaken": 0,
        "modules": []
    },
    {
        "id": 51,
        "name": "Codex Test",
        "email": "codex@gmail.com",
        "classification": "beginner",
        "lessonsDone": 0,
        "totalLessons": 23,
        "avgScore": 0,
        "quizzesTaken": 0,
        "modules": []
    },
    {
        "id": 52,
        "name": "Progress Testing",
        "email": "progresstest@gmail.com",
        "classification": "beginner",
        "lessonsDone": 1,
        "totalLessons": 23,
        "avgScore": 50,
        "quizzesTaken": 1,
        "modules": [
            {
                "name": "Introduction to Java",
                "level": "beginner",
                "quizScore": 5,
                "quizTotal": 10,
                "syntaxError": null,
                "programTracing": null,
                "machineProblem": null
            }
        ]
    },
    {
        "id": 53,
        "name": "test acc",
        "email": "testacc1231@gmail.com",
        "classification": "notClassified",
        "lessonsDone": 1,
        "totalLessons": 23,
        "avgScore": 90,
        "quizzesTaken": 1,
        "modules": [
            {
                "name": "Introduction to Java",
                "level": "beginner",
                "quizScore": 9,
                "quizTotal": 10,
                "syntaxError": null,
                "programTracing": null,
                "machineProblem": null
            }
        ]
    }
];

  // ── Init ─────────────────────────────────────────────────────────────────────
  const params = new URLSearchParams(location.search);
  const learnerId = parseInt(params.get('id')) || allLearners[0]?.id;
  const learner = allLearners.find(l => l.id === learnerId) || allLearners[0];

  if (!learner) {
    document.querySelector('.db-main').innerHTML = '<p style="padding:40px;color:#6b7280;">Learner not found.</p>';
    throw new Error('No learner found');
  }

  // Profile
  document.getElementById('nameEl').textContent   = learner.name;
  document.getElementById('emailEl').textContent  = learner.email;

  const classEl = document.getElementById('classEl');
  const classKey = learner.classification.toLowerCase();
  classEl.textContent = cap(learner.classification === 'notClassified' ? 'Not Classified' : learner.classification);
  classEl.className = `class-pill ${classKey}`;

  // Summary
  document.getElementById('sumLessons').textContent = `${learner.lessonsDone}/${learner.totalLessons}`;
  document.getElementById('sumQuizzes').textContent = `${learner.quizzesTaken}/${learner.totalLessons}`;
  document.getElementById('sumScore').textContent   = learner.avgScore > 0 ? `${learner.avgScore}%` : '—';

  // Modules table
  const PAGE_SIZE = 5;
  let currentPage = 1;
  let activeFilter = 'all';

  function getFiltered() {
    return (learner.modules || []).filter(m =>
      activeFilter === 'all' || m.level === activeFilter
    );
  }

  function renderModules() {
    const filtered = getFiltered();
    const totalPages = Math.max(1, Math.ceil(filtered.length / PAGE_SIZE));
    currentPage = Math.min(currentPage, totalPages);
    const slice = filtered.slice((currentPage - 1) * PAGE_SIZE, currentPage * PAGE_SIZE);

    const tbody = document.getElementById('moduleTableBody');
    if (slice.length === 0) {
      tbody.innerHTML = `<tr><td colspan="5" style="padding:30px;text-align:center;color:#9ca3af;font-size:14px;">No modules found.</td></tr>`;
    } else {
      tbody.innerHTML = slice.map(m => {
        const pct = m.quizTotal ? (m.quizScore / m.quizTotal * 100) : 0;
        const fillClass = pct === 0 ? 'empty' : '';
        return `
          <tr>
            <td>${m.name}</td>
            <td>
              <div class="quiz-cell">
                <div class="bar-track"><div class="bar-fill ${fillClass}" style="width:${pct}%"></div></div>
                <span class="quiz-score">${m.quizScore}/${m.quizTotal}</span>
              </div>
            </td>
            <td>${badgeCell(m.syntaxError)}</td>
            <td>${badgeCell(m.programTracing)}</td>
            <td>${badgeCell(m.machineProblem)}</td>
          </tr>`;
      }).join('');
    }

    document.getElementById('prevBtn').disabled = currentPage <= 1;
    document.getElementById('nextBtn').disabled = currentPage >= totalPages;
  }

  function badgeCell(val) {
    if (val === 'complete')   return `<span class="badge complete">Complete</span>`;
    if (val === 'incomplete') return `<span class="badge incomplete">Incomplete</span>`;
    return `<span class="badge na">--/--</span>`;
  }

  function cap(s) {
    return s.charAt(0).toUpperCase() + s.slice(1);
  }

  document.querySelectorAll('.sort-btn').forEach(btn => {
    btn.addEventListener('click', () => {
      document.querySelectorAll('.sort-btn').forEach(b => b.classList.remove('active'));
      btn.classList.add('active');
      activeFilter = btn.dataset.filter;
      currentPage = 1;
      renderModules();
    });
  });

  document.getElementById('prevBtn').addEventListener('click', () => { currentPage--; renderModules(); });
  document.getElementById('nextBtn').addEventListener('click', () => { currentPage++; renderModules(); });

  renderModules();
</script>
</body>
</html>