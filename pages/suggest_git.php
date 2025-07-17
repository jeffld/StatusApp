<?php
require_once '../db.php';

$task_id = isset($_GET['task_id']) ? (int) $_GET['task_id'] : 0;

$stmt = $pdo->prepare("
  SELECT t.*, p.name AS phase_name, pr.name AS project_name, pr.id AS project_id
  FROM tasks t
  JOIN phases p ON t.phase_id = p.id
  JOIN projects pr ON p.project_id = pr.id
  WHERE t.id = ?
");

$stmt->execute([$task_id]);
$task = $stmt->fetch();

if (!$task) {
  die("Task not found.");
}

$commitMessage = "{$task['step_number']} - {$task['description']}";
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Suggested Git Commit</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    .content { margin-left: 250px; padding: 20px; }
    code { font-family: monospace; font-size: 0.95rem; }
  </style>
</head>
<body>
<?php include '../navbar.php'; ?>
<div class="d-flex">
  <?php include '../sidebar.php'; ?>
  <div class="content container">
    <h2>💾 Git Commit Suggestion</h2>
    <p><strong>Project:</strong> <?= htmlspecialchars($task['project_name']) ?></p>
    <p><strong>Phase:</strong> <?= htmlspecialchars($task['phase_name']) ?></p>
    <p><strong>Task:</strong> <?= htmlspecialchars($task['description']) ?></p>

    <h5 class="mt-4">💡 Suggested Git Commands</h5>
    <pre><code>git add .  
git commit -m "<?= htmlspecialchars($commitMessage) ?>"</code></pre>

    <form method="POST" action="mark_committed.php">
      <input type="hidden" name="task_id" value="<?= $task_id ?>">
      <button class="btn btn-success mt-3">✅ Mark as Committed</button>
      <a href="view_tasks.php?project_id=<?= $task['project_id'] ?>" class="btn btn-secondary mt-3">← Back</a>
    </form>
  </div>
</div>
</body>
</html>
