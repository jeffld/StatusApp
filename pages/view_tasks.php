<?php
require_once '../db.php';

$project_id = isset($_GET['project_id']) ? (int) $_GET['project_id'] : 0;

// Fetch project info
$projectStmt = $pdo->prepare("SELECT * FROM projects WHERE id = ?");
$projectStmt->execute([$project_id]);
$project = $projectStmt->fetch();

if (!$project) {
    die("❌ Project not found.");
}

// Fetch phases + tasks
$phasesStmt = $pdo->prepare("
    SELECT phases.id AS phase_id, phases.name AS phase_name, tasks.*
    FROM phases
    JOIN tasks ON phases.id = tasks.phase_id
    WHERE phases.project_id = ?
    ORDER BY phases.id, tasks.step_number
");
$phasesStmt->execute([$project_id]);
$rows = $phasesStmt->fetchAll();

$phases = [];
foreach ($rows as $row) {
    $phases[$row['phase_id']]['name'] = $row['phase_name'];
    $phases[$row['phase_id']]['tasks'][] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>View Tasks - <?= htmlspecialchars($project['name']) ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    .content {
      margin-left: 250px;
      padding: 20px;
    }
    .task-done {
      text-decoration: line-through;
      color: #888;
    }
  </style>
</head>
<body>
<?php include '../navbar.php'; ?>

<div class="d-flex">
<?php include '../sidebar.php'; ?>

  <div class="content container">
    <h2>📋 Tasks for Project: <?= htmlspecialchars($project['name']) ?></h2>

    <?php foreach ($phases as $phase): ?>
      <h4 class="mt-4"><?= htmlspecialchars($phase['name']) ?></h4>
      <table class="table table-bordered">
        <thead>
          <tr>
            <th style="width: 50px;">Done</th>
            <th>Step</th>
            <th>Description</th>
			<th style="width: 130px;">Git Status</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($phase['tasks'] as $task): ?>
            <tr>
              <td class="text-center">
                <input type="checkbox" class="form-check-input toggle-task"
                       data-task-id="<?= $task['id'] ?>"
                       <?= $task['is_done'] ? 'checked' : '' ?>>
              </td>
              <td><?= htmlspecialchars($task['step_number']) ?></td>
              <td class="<?= $task['is_done'] ? 'task-done' : '' ?>">
                <?= htmlspecialchars($task['description']) ?>
              </td>
						  <td>
			  <?php if ($task['is_committed']): ?>
				<span class="badge bg-success">Committed</span>
			  <?php else: ?>
				<a href="suggest_git.php?task_id=<?= $task['id'] ?>" class="btn btn-sm btn-outline-secondary">
				  Suggest Commit
				</a>
			  <?php endif; ?>
			</td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    <?php endforeach; ?>
  </div>
</div>

<script>
document.querySelectorAll('.toggle-task').forEach(function (checkbox) {
    checkbox.addEventListener('change', function () {
        const taskId = this.dataset.taskId;
        const isDone = this.checked ? 1 : 0;

        fetch('../ajax/update_task_status.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: `task_id=${taskId}&is_done=${isDone}`
        })
        .then(response => response.text())
        .then(data => {
            if (data !== 'OK') alert("Error: " + data);
            else location.reload(); // Refresh to show strikethrough
        });
    });
});
</script>

</body>
</html>
