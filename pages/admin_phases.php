<?php
// admin_phases.php
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Phase Manager</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php include '../admin_navbar.php'; ?>
<div class="d-flex">
  <?php include '../admin_sidebar.php'; ?>

  <div class="content container">
    <h2>📋 Admin - Manage Phases</h2>
    <div class="row mb-3">
        <div class="col-md-6">
            <label>Project</label>
            <select class="form-select" id="project_select"></select>
        </div>
        <div class="col-md-6 d-flex align-items-end">
            <button class="btn btn-primary w-100" onclick="openPhaseModal()">➕ Add Phase</button>
        </div>
    </div>
    <div id="phaseTable"></div>

    <!-- Modal -->
    <div class="modal" id="phaseModal" tabindex="-1">
      <div class="modal-dialog">
        <div class="modal-content">
          <form id="phaseForm">
            <div class="modal-header">
              <h5 class="modal-title">Phase</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
              <input type="hidden" name="id" id="phase_id">
              <input type="hidden" name="project_id" id="phase_project_id">
              <div class="mb-2">
                <label>Name</label>
                <input type="text" class="form-control" name="name" id="phase_name" required>
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
            modal = new bootstrap.Modal(document.getElementById('phaseModal'));
            loadProjects();

            $('#project_select').on('change', function () {
                loadPhases($(this).val());
            });

            $('#phaseForm').on('submit', function (e) {
                e.preventDefault();
                $.post('ajax/save_phase.php', $(this).serialize(), function () {
                    modal.hide();
                    loadPhases($('#project_select').val());
                });
            });
        });

        function loadProjects() {
            $.get('../ajax/load_projects.php', function (data) {
                $('#project_select').html(data).change();
            });
        }

        function loadPhases(project_id) {
            $.get('../ajax/load_phases_table.php?project_id=' + project_id, function (data) {
                $('#phaseTable').html(data);
            });
        }

        function openPhaseModal(phase = {}) {
            $('#phase_id').val(phase.id || '');
            $('#phase_project_id').val($('#project_select').val());
            $('#phase_name').val(phase.name || '');
            modal.show();
        }

        function editPhase(id) {
            $.getJSON('../ajax/get_phase.php?id=' + id, openPhaseModal);
        }

        function deletePhase(id) {
            if (confirm('Delete this phase?')) {
                $.post('../ajax/delete_phase.php', { id }, function () {
                    loadPhases($('#project_select').val());
                });
            }
        }
    </script>
</body>
</html>
