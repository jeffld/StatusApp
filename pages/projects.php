<?php
require_once '../db.php';

$stmt = $pdo->query("SELECT * FROM projects ORDER BY created_at DESC");
$projects = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Projects</title>
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
    <h2>📁 All Projects</h2>

    <?php if (count($projects) === 0): ?>
      <div class="alert alert-warning">No projects found. Try <a href="../import_json.php">importing a JSON</a>.</div>
    <?php else: ?>
      <table class="table table-bordered table-hover">
        <thead class="table-light">
          <tr>
            <th>Name</th>
            <th>Description</th>
            <th style="width: 150px;">Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($projects as $project): ?>
            <tr>
              <td><?= htmlspecialchars($project['name']) ?></td>
              <td><?= htmlspecialchars($project['description']) ?></td>
				<td class="d-flex gap-2">
			  <a href="view_tasks.php?project_id=<?= $project['id'] ?>" class="btn btn-sm btn-primary">
				View Tasks
			  </a>
			  <a href="export_json.php?project_id=<?= $project['id'] ?>" class="btn btn-sm btn-success" target="_blank">
				Export JSON
			  </a>
			    <a href="next_task_prompt.php?project_id=<?= $project['id'] ?>" class="btn btn-sm btn-warning">Work on Next Task</a>
				<a href="import_tasks.php?project_id=<?= $project['id'] ?>" class="btn btn-secondary">Import Tasks</a>

			</td>
						</tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    <?php endif; ?>
  </div>
</div>
</body>
</html>
