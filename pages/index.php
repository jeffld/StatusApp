<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>StatusApp Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      overflow-x: hidden;
    }
    .content {
      margin-left: 250px;
      padding: 20px;
    }
  </style>
</head>
<body>
  <?php include '../navbar.php'; ?>

  <div class="d-flex">
    <?php include '../sidebar.php'; ?>

    <div class="content">
      <h1>Welcome to StatusApp</h1>
      <p>This is your project management dashboard for AI Assisted todo lists.</p>
    </div>
  </div>
</body>
</html>
