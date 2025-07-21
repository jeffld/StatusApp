<?php
//load_roles_table.php
require_once '../db.php'; // adjust path as needed

header('Content-Type: text/html; charset=utf-8');

// Fetch roles from database
$stmt = $pdo->prepare("SELECT id, name, description FROM roles ORDER BY id ASC");
$stmt->execute();
$roles = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Begin HTML table
echo '<table class="table table-bordered table-striped table-hover">';
echo '<thead>';
echo '<tr>';
echo '<th>ID</th>';
echo '<th>Role Name</th>';
echo '<th>Description</th>';
echo '<th>Actions</th>';
echo '</tr>';
echo '</thead>';
echo '<tbody>';

if (count($roles) > 0) {
    foreach ($roles as $role) {
        echo '<tr>';
        echo '<td>' . htmlspecialchars($role['id']) . '</td>';
        echo '<td>' . htmlspecialchars($role['name']) . '</td>';
        echo '<td>' . nl2br(htmlspecialchars($role['description'])) . '</td>';
        echo '<td>';
        echo '<button class="btn btn-sm btn-primary edit-role" data-id="' . $role['id'] . '">Edit</button> ';
        echo '<button class="btn btn-sm btn-danger delete-role" data-id="' . $role['id'] . '">Delete</button>';
        echo '</td>';
        echo '</tr>';
    }
} else {
    echo '<tr><td colspan="4">No roles found.</td></tr>';
}

echo '</tbody>';
echo '</table>';
