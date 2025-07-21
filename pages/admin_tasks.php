<?php
// admin_tasks.php
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Task Manager</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php include '../admin_navbar.php'; ?>
<div class="d-flex">
  <?php include '../admin_sidebar.php'; ?>

  <div class="content container">
    <h2>🛠️ Admin - Manage Tasks</h2>
    <div class="row mb-3">
        <div class="col-md-4">
            <label>Project</label>
            <select class="form-select" id="project_select"></select>
        </div>
        <div class="col-md-4">
            <label>Phase</label>
            <select class="form-select" id="phase_select"></select>
        </div>
        <div class="col-md-4 d-flex align-items-end">
            <button class="btn btn-primary w-100" onclick="openTaskModal()">➕ Add Task</button>
        </div>
    </div>
    <div id="taskTable"></div>

    <!-- Task Modal -->
    <div class="modal" id="taskModal" tabindex="-1">
      <div class="modal-dialog">
        <div class="modal-content">
          <form id="taskForm">
            <div class="modal-header">
              <h5 class="modal-title">Task</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
              <input type="hidden" name="id" id="task_id">
              <input type="hidden" name="phase_id" id="task_phase_id">
              <div class="mb-2">
                <label>Step Number</label>
                <input type="text" class="form-control" name="step_number" id="task_step_number" required>
              </div>
              <div class="mb-2">
                <label>Description</label>
                <textarea class="form-control" name="description" id="task_description" required></textarea>
              </div>
              <div class="form-check mb-2">
                <input class="form-check-input" type="checkbox" name="is_done" id="task_done">
                <label class="form-check-label">Done</label>
              </div>
              <div class="form-check mb-2">
                <input class="form-check-input" type="checkbox" name="is_committed" id="task_committed">
                <label class="form-check-label">Committed</label>
              </div>
            </div>
            <div class="modal-footer">
              <button type="submit" class="btn btn-success">💾 Save</button>
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            </div>
          </form>
        </div>
      </div>
    </div>
</div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        let modal;

        $(document).ready(function () {
            modal = new bootstrap.Modal(document.getElementById('taskModal'));
            loadProjects();

            $('#project_select').on('change', function () {
                loadPhases($(this).val());
            });

            $('#phase_select').on('change', function () {
                loadTasks($(this).val());
            });

            $('#taskForm').on('submit', function (e) {
                e.preventDefault();
                $.post('../ajax/save_task.php', $(this).serialize(), function () {
                    modal.hide();
                    loadTasks($('#phase_select').val());
                });
            });
        });

        function loadProjects() {
            $.get('../ajax/load_projects.php', function (data) {
                $('#project_select').html(data).change();
            });
        }

        function loadPhases(project_id) {
            $.get('../ajax/load_phases.php?project_id=' + project_id, function (data) {
                $('#phase_select').html(data).change();
            });
        }

        function loadTasks(phase_id) {
            $.get('../ajax/load_tasks.php?phase_id=' + phase_id, function (data) {
                $('#taskTable').html(data);
            });
        }

        function openTaskModal(task = {}) {
            $('#task_id').val(task.id || '');
            $('#task_phase_id').val($('#phase_select').val());
            $('#task_step_number').val(task.step_number || '');
            $('#task_description').val(task.description || '');
            $('#task_done').prop('checked', !!task.is_done);
            $('#task_committed').prop('checked', !!task.is_committed);
            modal.show();
        }

        function editTask(id) {
            $.getJSON('../ajax/get_task.php?id=' + id, openTaskModal);
        }

        function deleteTask(id) {
            if (confirm('Delete this task?')) {
                $.post('../ajax/delete_task.php', { id }, function () {
                    loadTasks($('#phase_select').val());
                });
            }
        }
    </script>
</body>
</html>
