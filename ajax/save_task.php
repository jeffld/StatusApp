<?php
require '../db.php';
$id = $_POST['id'] ?? null;
$phase_id = $_POST['phase_id'];
$step_number = $_POST['step_number'];
$description = $_POST['description'];
$is_done = isset($_POST['is_done']) ? 1 : 0;
$is_committed = isset($_POST['is_committed']) ? 1 : 0;

if ($id) {
    $stmt = $pdo->prepare("UPDATE tasks SET phase_id=?, step_number=?, description=?, is_done=?, is_committed=? WHERE id=?");
    $stmt->execute([$phase_id, $step_number, $description, $is_done, $is_committed, $id]);
} else {
    $stmt = $pdo->prepare("INSERT INTO tasks (phase_id, step_number, description, is_done, is_committed) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$phase_id, $step_number, $description, $is_done, $is_committed]);
}
?>
