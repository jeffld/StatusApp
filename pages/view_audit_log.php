<?php
require_once '../db.php';

$stmt = $pdo->query("
SELECT 
  log.*, 
  t.step_number AS current_step,
  CASE 
    WHEN t.id IS NULL THEN 'Deleted' 
    ELSE 'Active' 
  END AS task_status
FROM task_audit_log log
LEFT JOIN tasks t ON log.task_id = t.id
ORDER BY log.timestamp DESC
");

$logs = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Audit Log</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php include '../navbar.php'; ?>
<div class="container mt-4">
  <h2>📜 Task Audit Log</h2>
  <table class="table table-bordered table-sm table-striped align-middle">
    <thead class="table-light">
      <tr>
        <th>#</th>
        <th>Task ID</th>
        <th>Step</th>
        <th>User</th>
        <th>Action</th>
        <th>Timestamp</th>
		 <th>Task Status</th>
        <th>Before → After</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($logs as $log): ?>
        <tr>
          <td><?= $log['id'] ?></td>
          <td><?= $log['task_id'] ?></td>
          <td><?= htmlspecialchars($log['old_step_number'] ?? $log['new_step_number']) ?></td>
          <td><?= htmlspecialchars($log['username']) ?></td>
          <td><?= $log['action'] ?></td>
          <td><?= $log['timestamp'] ?></td>
		  <td><?= $log['task_status'] ?></td>
          <td>
            <div><strong>Desc:</strong><br><small><?= nl2br(htmlspecialchars($log['old_description'])) ?></small>
            <br>→<br>
            <small><?= nl2br(htmlspecialchars($log['new_description'])) ?></small></div>
            <div><strong>Done:</strong> <?= $log['old_is_done'] ?> → <?= $log['new_is_done'] ?></div>
            <div><strong>Committed:</strong> <?= $log['old_is_committed'] ?> → <?= $log['new_is_committed'] ?></div>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>
</body>
</html>
