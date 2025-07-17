<?php
require '../db.php';
$phase_id = intval($_GET['phase_id']);
$stmt = $pdo->prepare("SELECT * FROM tasks WHERE phase_id = ? ORDER BY step_number");
$stmt->execute([$phase_id]);
echo "<table class='table table-bordered'><thead><tr><th>Step</th><th>Description</th><th>Done</th><th>Committed</th><th>Actions</th></tr></thead><tbody>";
while ($row = $stmt->fetch()) {
    echo "<tr>";
    echo "<td>{$row['step_number']}</td>";
    echo "<td>{$row['description']}</td>";
    echo "<td>" . ($row['is_done'] ? '✅' : '❌') . "</td>";
    echo "<td>" . ($row['is_committed'] ? '✅' : '❌') . "</td>";
    echo "<td>
        <button class='btn btn-sm btn-primary' onclick='editTask({$row['id']})'>✏️</button>
        <button class='btn btn-sm btn-danger' onclick='deleteTask({$row['id']})'>🗑️</button>
    </td>";
    echo "</tr>";
}
echo "</tbody></table>";
?>
