<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>StatusApp Help</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    .content {
      margin-left: 250px;
      padding: 20px;
    }
    pre {
      background: #f8f9fa;
      padding: 15px;
      border-radius: 5px;
    }
  </style>
</head>
<body>
<?php include '../navbar.php'; ?>
<div class="d-flex">
  <?php include '../sidebar.php'; ?>
  <div class="content container">
    <h2>📖 Help: How to Create and Import a Todo List</h2>

    <p>Each time you start a new project, give this prompt to ChatGPT:</p>

    <h5>🧠 Prompt Template:</h5>
    <pre><code>
I am building a software project. Please return a todo list in the following JSON format.
Include project name, optional description, and a breakdown of tasks grouped by phase.
Assign appropriate roles to each task using role IDs (e.g., [1, 3]).
Return only the JSON with no explanation text.
    </code></pre>

    <h5>📄 Expected JSON Structure:</h5>
    <pre><code>{
  "project": "XRPBot App",
  "description": "Self-hosted XRP trading bot with dashboard.",
  "tasks": [
    {
      "phase": "Setup & Foundation",
      "roles": [1],
      "task": {
        "step": "1.1",
        "description": "Install PHP 8.3 on Windows with IIS",
        "is_done": false
      }
    },
    {
      "phase": "Setup & Foundation",
      "roles": [1],
      "task": {
        "step": "1.2",
        "description": "Enable gmp extension in php.ini",
        "is_done": false
      }
    },
    {
      "phase": "Database Setup",
      "roles": [2],
      "task": {
        "step": "2.1",
        "description": "Install and configure MySQL",
        "is_done": false
      }
    },
    {
      "phase": "Database Setup",
      "roles": [2, 3],
      "task": {
        "step": "2.2",
        "description": "Create tables: trade_log, price_log, offer_status",
        "is_done": false
      }
    }
  ]
}
    </code></pre>

    <h5>💡 Tips:</h5>
    <ul>
      <li>Make sure the JSON is valid (use <a href="https://jsonlint.com/" target="_blank">JSONLint</a> to verify).</li>
      <li>Use either the paste field or upload a file via <a href="import_json.php">Import Todo List</a>.</li>
      <li>Each task should include a step (e.g., "1.1"), a description, and one or more role IDs in the <code>roles</code> array.</li>
      <li>Ask your administrator for a list of available role IDs and their meanings (e.g., Developer = 1, DBA = 2, QA = 3).</li>
    </ul>
  </div>
</div>
</body>
</html>
