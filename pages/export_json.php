<?php
require_once '../db.php';

$project_id = isset($_GET['project_id']) ? (int) $_GET['project_id'] : 0;

// Get project info
$stmt = $pdo->prepare("SELECT * FROM projects WHERE id = ?");
$stmt->execute([$project_id]);
$project = $stmt->fetch();

if (!$project) {
    http_response_code(404);
    die(json_encode(["error" => "Project not found."]));
}

// Get phases and tasks
$stmt = $pdo->prepare("
    SELECT p.id AS phase_id, p.name AS phase_name, t.id AS task_id, t.step_number, t.description, t.is_done
    FROM phases p
    JOIN tasks t ON p.id = t.phase_id
    WHERE p.project_id = ?
    ORDER BY p.id, t.step_number
");
$stmt->execute([$project_id]);
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Structure data
$phases = [];

foreach ($rows as $row) {
    $phase_id = $row['phase_id'];

    if (!isset($phases[$phase_id])) {
        $phases[$phase_id] = [
            'name' => $row['phase_name'],
            'tasks' => []
        ];
    }

    $phases[$phase_id]['tasks'][] = [
        'step' => $row['step_number'],
        'description' => $row['description'],
        'is_done' => (bool) $row['is_done']
    ];
}

$output = [
    'project' => $project['name'],
    'description' => $project['description'],
    'phases' => array_values($phases) // flatten index
];

// Output JSON
header('Content-Type: application/json');
header('Content-Disposition: attachment; filename="project_' . $project_id . '_export.json"');
echo json_encode($output, JSON_PRETTY_PRINT);
exit;
