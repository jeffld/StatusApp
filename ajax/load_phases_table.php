<?php
require '../db.php';
$project_id = intval($_GET['project_id']);
$stmt = $pdo->prepare("SELECT * FROM phases WHERE project_id = ? ORDER BY name");
$stmt->execute([$project_id]);
echo "<table class='table table-bordered'><thead><tr><th>Name</th><th>Actions</th></tr></thead><tbody>";
while ($row = $stmt->fetch()) {
    echo "<tr>";
    echo "<td>{$row['name']}</td>";
    echo "<td>
        <button class='btn btn-sm btn-primary' onclick='editPhase({$row['id']})'>✏️</button>
        <button class='btn btn-sm btn-danger' onclick='deletePhase({$row['id']})'>🗑️</button>
    </td>";
    echo "</tr>";
}
echo "</tbody></table>";
?>