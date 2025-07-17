<?php
require_once '../db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $taskId = isset($_POST['task_id']) ? (int) $_POST['task_id'] : 0;
    $isDone = isset($_POST['is_done']) ? (int) $_POST['is_done'] : 0;

    if ($taskId > 0) {
        $stmt = $pdo->prepare("UPDATE tasks SET is_done = ? WHERE id = ?");
        $stmt->execute([$isDone, $taskId]);
        echo 'OK';
    } else {
        echo 'Invalid task ID';
    }
} else {
    echo 'Invalid request method';
}
