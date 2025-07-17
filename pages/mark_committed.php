<?php
require_once '../db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $task_id = (int) ($_POST['task_id'] ?? 0);

    $stmt = $pdo->prepare("UPDATE tasks SET is_committed = 1 WHERE id = ?");
    $stmt->execute([$task_id]);

    // Get project ID to redirect back
    $stmt = $pdo->prepare("SELECT pr.id AS project_id
        FROM tasks t
        JOIN phases p ON t.phase_id = p.id
        JOIN projects pr ON p.project_id = pr.id
        WHERE t.id = ?");
    $stmt->execute([$task_id]);
    $row = $stmt->fetch();

    header("Location: view_tasks.php?project_id=" . $row['project_id']);
    exit;
}
