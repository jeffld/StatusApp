<?php
require '../db.php';
$id = intval($_POST['id']);
$pdo->prepare("DELETE FROM tasks WHERE id = ?")->execute([$id]);
?>
