<?php
require '../db.php';

$username = 'jdarlng'; // Use $_SERVER['PHP_AUTH_USER'] in production

$id = $_POST['id'] ?? null;
$phase_id = $_POST['phase_id'];
$step_number = $_POST['step_number'];
$description = $_POST['description'];
$is_done = isset($_POST['is_done']) ? 1 : 0;
$is_committed = isset($_POST['is_committed']) ? 1 : 0;

if ($id) {
    // FETCH OLD VALUES
    $stmt = $pdo->prepare("SELECT * FROM tasks WHERE id = ?");
    $stmt->execute([$id]);
    $before = $stmt->fetch(PDO::FETCH_ASSOC);

    // UPDATE TASK
    $stmt = $pdo->prepare("UPDATE tasks SET phase_id=?, step_number=?, description=?, is_done=?, is_committed=? WHERE id=?");
    $stmt->execute([$phase_id, $step_number, $description, $is_done, $is_committed, $id]);

    // AUDIT TRAIL FOR UPDATE
    $stmt = $pdo->prepare("
        INSERT INTO task_audit_log (
            task_id, action, username,
            old_step_number, new_step_number,
            old_description, new_description,
            old_is_done, new_is_done,
            old_is_committed, new_is_committed
        ) VALUES (?, 'update', ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");
    $stmt->execute([
        $id,
        $username,
        $before['step_number'], $step_number,
        $before['description'], $description,
        $before['is_done'], $is_done,
        $before['is_committed'], $is_committed
    ]);
} else {
    // INSERT TASK
    $stmt = $pdo->prepare("INSERT INTO tasks (phase_id, step_number, description, is_done, is_committed) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$phase_id, $step_number, $description, $is_done, $is_committed]);
    $newTaskId = $pdo->lastInsertId();

    // AUDIT TRAIL FOR CREATE
    $stmt = $pdo->prepare("
        INSERT INTO task_audit_log (
            task_id, action, username,
            new_step_number, new_description,
            new_is_done, new_is_committed
        ) VALUES (?, 'create', ?, ?, ?, ?, ?)
    ");
    $stmt->execute([
        $newTaskId,
        $username,
        $step_number,
        $description,
        $is_done,
        $is_committed
    ]);
}
?>
