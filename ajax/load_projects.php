<?php
require '../db.php';
$res = $pdo->query("SELECT * FROM projects ORDER BY name");
while ($row = $res->fetch()) {
    echo "<option value='{$row['id']}'>{$row['name']}</option>";
}
?>
