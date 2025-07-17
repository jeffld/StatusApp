<?php
require '../db.php';
$project_id = intval($_GET['project_id']);
$stmt = $pdo->prepare("SELECT * FROM phases WHERE project_id = ? ORDER BY name");
$stmt->execute([$project_id]);
while ($row = $stmt->fetch()) {
    echo "<option value='{$row['id']}'>{$row['name']}</option>";
}
?>
