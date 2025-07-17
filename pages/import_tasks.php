<?php
require_once '../db.php';
$message = '';

$project_id = isset($_GET['project_id']) ? (int) $_GET['project_id'] : ($_POST['project_id'] ?? null);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $json_input = trim($_POST['json_input']);
	
    $data = json_decode($json_input, true);

    if (!$data || !is_array($data)) {
        $message = "❌ Invalid JSON input. Please check your formatting.";
    } else {
        $imported = 0;

        foreach ($data as $entry) {
            $phase_name = trim($entry['phase']);
            $step = $entry['task']['step'];
            $description = trim($entry['task']['description']);
            $is_done = $entry['task']['is_done'] ? 1 : 0;

            // Check or create phase
            $stmt = $pdo->prepare("SELECT id FROM phases WHERE project_id = ? AND name = ?");
            $stmt->execute([$project_id, $phase_name]);
            $phase = $stmt->fetch();

            if (!$phase) {
                $insert_phase = $pdo->prepare("INSERT INTO phases (project_id, name) VALUES (?, ?)");
                $insert_phase->execute([$project_id, $phase_name]);
                $phase_id = $pdo->lastInsertId();
            } else {
                $phase_id = $phase['id'];
            }

            // Insert task
            $insert_task = $pdo->prepare("INSERT INTO tasks (phase_id, step_number, description, is_done) VALUES (?, ?, ?, ?)");
            $insert_task->execute([$phase_id, $step, $description, $is_done]);

            $imported++;
        }

        $message = "✅ Successfully imported {$imported} task(s).";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Import Tasks</title>
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
    <h2>📥 Import Tasks to Existing Project</h2>

    <?php if ($message): ?>
      <div class="alert alert-info"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <form method="POST">
      <div class="mb-3">
        <label for="project_id" class="form-label">Project ID:</label>
        <input type="number" name="project_id" id="project_id" class="form-control" required>
      </div>

      <div class="mb-3">
        <label for="json_input" class="form-label">Paste Task JSON:</label>
        <textarea name="json_input" id="json_input" rows="10" class="form-control" placeholder='[
  {
    "phase": "Setup & Foundation",
    "task": {
      "step": "1.7",
      "description": "Switch to XRPL Mainnet endpoint for production",
      "is_done": false
    }
  }
]'></textarea>
      </div>

      <button type="submit" class="btn btn-primary">🚀 Import Tasks</button>
    </form>
  </div>
</div>
</body>
</html>	
	
	
