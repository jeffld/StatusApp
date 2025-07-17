<?php
require_once '../db.php';

$project_id = isset($_GET['project_id']) ? (int) $_GET['project_id'] : 0;

// Get project
$stmt = $pdo->prepare("SELECT * FROM projects WHERE id = ?");
$stmt->execute([$project_id]);
$project = $stmt->fetch();

if (!$project) {
    die("❌ Project not found.");
}

// Get next task (ordered by phase and step number)
$stmt = $pdo->prepare("
    SELECT p.name AS phase_name, t.step_number, t.description
    FROM phases p
    JOIN tasks t ON p.id = t.phase_id
    WHERE p.project_id = ? AND t.is_done = 0
    ORDER BY p.id, t.step_number ASC
    LIMIT 1
");
$stmt->execute([$project_id]);
$task = $stmt->fetch();

$prompt = '';

if ($task) {
    $prompt = <<<EOT
I'm working on a project called "{$project['name']}".
The next task is:

Phase: {$task['phase_name']}
Step: {$task['step_number']}
Task: {$task['description']}

Please help me complete this task. What are the key considerations? What should I watch out for? What are the best practices?
EOT;
} else {
    $prompt = "🎉 All tasks for this project are complete!";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Next Task - <?= htmlspecialchars($project['name']) ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    .content { margin-left: 250px; padding: 20px; }
    textarea { font-family: monospace; font-size: 1rem; }
  </style>
</head>
<body>
<?php include '../navbar.php'; ?>
<div class="d-flex">
  <?php include '../sidebar.php'; ?>

  <div class="content container">
    <h2>🚀 Prompt for Next Task in <em><?= htmlspecialchars($project['name']) ?></em></h2>

    <div class="mb-3">
      <label for="promptText" class="form-label">Copy this prompt and paste it into ChatGPT:</label>
      <textarea id="promptText" class="form-control" rows="10"><?= htmlspecialchars($prompt) ?></textarea>
    </div>
    <button onclick="copyPrompt()" class="btn btn-primary">📋 Copy to Clipboard</button>
    <a href="view_tasks.php?project_id=<?= $project_id ?>" class="btn btn-secondary">← Back to Tasks</a>
  </div>
</div>

<script>
function copyPrompt() {
  const textarea = document.getElementById('promptText');
  textarea.select();
  document.execCommand('copy');
  alert('Prompt copied to clipboard!');
}
</script>
</body>
</html>
