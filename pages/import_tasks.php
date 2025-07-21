<?php
//import_tasks.php
require_once '../db.php';
$message = '';

//$project_id = isset($_GET['project_id']) ? (int) $_GET['project_id'] : ($_POST['project_id'] ?? null);
$project_id = $_GET['project_id'] ?? null;
$project_name = $_GET['project_name'] ?? 'Unknown Project';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $json_input = trim($_POST['json_input']);
	
    $data = json_decode($json_input, true);
	
	
//	echo '<pre>';
//	print_r($data);
//	echo '</pre>';


    if (!$data || !is_array($data)) {
        $message = "❌ Invalid JSON input. Please check your formatting.";
    } else {
        $imported = 0;

foreach ($data as $projectData) {
    if (!isset($projectData['phases']) || !is_array($projectData['phases'])) {
        $message .= "❌ Skipping project: no phases found.<br>";
        continue;
    }

    foreach ($projectData['phases'] as $phaseData) {
        if (!isset($phaseData['name'])) {
            $message .= "❌ Skipping phase: missing name.<br>";
            continue;
        }

        $phase_name = trim($phaseData['name']);

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

        // Assign roles
        if (isset($phaseData['roles']) && is_array($phaseData['roles'])) {
            foreach ($phaseData['roles'] as $role_id) {
                $check_role = $pdo->prepare("SELECT 1 FROM phase_roles WHERE phase_id = ? AND role_id = ?");
                $check_role->execute([$phase_id, $role_id]);
                if (!$check_role->fetch()) {
                    $assign_role = $pdo->prepare("INSERT INTO phase_roles (phase_id, role_id) VALUES (?, ?)");
                    $assign_role->execute([$phase_id, $role_id]);
                }
            }
        } else {
            $message .= "⚠️ No roles for phase '{$phase_name}'.<br>";
        }

        // Import tasks
        if (isset($phaseData['tasks']) && is_array($phaseData['tasks'])) {
            foreach ($phaseData['tasks'] as $task) {
                if (!isset($task['step']) || !isset($task['description']) || !isset($task['is_done'])) {
                    $message .= "❌ Incomplete task data in phase '{$phase_name}'.<br>";
                    continue;
                }

                $step = $task['step'];
                $description = trim($task['description']);
                $is_done = $task['is_done'] ? 1 : 0;

                $insert_task = $pdo->prepare("INSERT INTO tasks (phase_id, step_number, description, is_done) VALUES (?, ?, ?, ?)");
                $insert_task->execute([$phase_id, $step, $description, $is_done]);

                $imported++;
            }
        } else {
            $message .= "⚠️ No tasks for phase '{$phase_name}'.<br>";
        }
    }
}



        //$message = "✅ Successfully imported {$imported} task(s).";
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



    <?php 
	echo "<h5 class='text-muted'>📁 Project: <strong>" . htmlspecialchars($project_name) . "</strong></h5>";
	
	if ($message): ?>
      <div class="alert alert-info"><?= $message?></div>
    <?php endif; ?>

    <form method="POST">

      <div class="mb-3">
        <label for="json_input" class="form-label">Paste Task JSON:</label>
       <textarea name="json_input" id="json_input" rows="10" class="form-control" placeholder='[
  {
    "phase": "Setup & Foundation",
    "roles": [1, 3],
    "task": {
      "step": "1.7",
      "description": "Switch to XRPL Mainnet endpoint for production",
      "is_done": false
    }
  },
  {
    "phase": "Launch Preparation",
    "roles": [2],
    "task": {
      "step": "2.1",
      "description": "Announce launch date to stakeholders",
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
	
	
