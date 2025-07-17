<?php
require '../db.php';
$id = $_POST['id'] ?? null;
$project_id = $_POST['project_id'];
$name = $_POST['name'];

if ($id) {
    $stmt = $pdo->prepare("UPDATE phases SET project_id=?, name=? WHERE id=?");
    $stmt->execute([$project_id, $name, $id]);
} else {
    $stmt = $pdo->prepare("INSERT INTO phases (project_id, name) VALUES (?, ?)");
    $stmt->execute([$project_id, $name]);
}
?>