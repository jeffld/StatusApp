<?php
// import_json.php
require_once '../db.php'; // assume db connection in db.php

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $jsonData = '';

    // Option 1: Pasted JSON
    if (!empty($_POST['json_text'])) {
        $jsonData = $_POST['json_text'];
    }

    // Option 2: Uploaded JSON file
    elseif (!empty($_FILES['json_file']['tmp_name'])) {
        $jsonData = file_get_contents($_FILES['json_file']['tmp_name']);
    }

    if ($jsonData) {
        $data = json_decode($jsonData, true);

        if ($data && isset($data['project'], $data['phases'])) {
            $projectName = $data['project'];
            $projectDesc = $data['description'] ?? '';

            // Insert into projects
            $stmt = $pdo->prepare("INSERT INTO projects (name, description) VALUES (?, ?)");
            $stmt->execute([$projectName, $projectDesc]);
            $projectId = $pdo->lastInsertId();

            foreach ($data['phases'] as $phase) {
                $phaseName = $phase['name'];
                $stmt = $pdo->prepare("INSERT INTO phases (project_id, name) VALUES (?, ?)");
                $stmt->execute([$projectId, $phaseName]);
                $phaseId = $pdo->lastInsertId();

                foreach ($phase['tasks'] as $task) {
                    $step = $task['step'] ?? null;
                    $desc = $task['description'] ?? '';

                    $stmt = $pdo->prepare("INSERT INTO tasks (phase_id, step_number, description) VALUES (?, ?, ?)");
                    $stmt->execute([$phaseId, $step, $desc]);
                }
            }

            $message = "✅ Project imported successfully!";
        } else {
            $message = "❌ Invalid JSON format.";
        }
    } else {
        $message = "❌ No JSON data provided.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Import Todo List</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    .content {
      margin-left: 250px;
      padding: 20px;
    }
  </style>
</head>
<body>
<?php include '../navbar.php'; ?>
<div class="d-flex">
  <?php include '../sidebar.php'; ?>
  <div class="content container">
    <h2>📥 Import Todo List JSON</h2>

    <?php if ($message): ?>
      <div class="alert alert-info"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data" class="mb-4">
      <div class="mb-3">
        <label for="json_text" class="form-label">Paste JSON Here:</label>
        <textarea name="json_text" class="form-control" rows="10"></textarea>
      </div>
      <div class="mb-3">
        <label for="json_file" class="form-label">Or Upload JSON File:</label>
        <input type="file" name="json_file" class="form-control">
      </div>
      <button type="submit" class="btn btn-primary">Import</button>
    </form>
  </div>
</div>
</body>
</html>
