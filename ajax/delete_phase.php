<?php
require '../db.php';
$id = intval($_POST['id']);
$pdo->prepare("DELETE FROM phases WHERE id = ?")->execute([$id]);
?>