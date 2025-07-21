<?php
// admin_roles.php
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Role Manager</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php include '../admin_navbar.php'; ?>
<div class="d-flex">
  <?php include '../admin_sidebar.php'; ?>

  <div class="content container">
    <h2>👥 Admin - Manage Roles</h2>
    <div class="row mb-3">
        <div class="col-md-6">
            <label>Project</label>
            <select class="form-select" id="project_select"></select>
        </div>
        <div class="col-md-6 d-flex align-items-end">
            <button class="btn btn-primary w-100" onclick="openRoleModal()">➕ Add Role</button>
        </div>
    </div>
    <div id="roleTable"></div>

    <!-- Modal -->
    <div class="modal" id="roleModal" tabindex="-1">
      <div class="modal-dialog">
        <div class="modal-content">
          <form id="roleForm">
            <div class="modal-header">
              <h5 class="modal-title">Role</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
              <input type="hidden" name="id" id="role_id">
              <input type="hidden" name="project_id" id="role_project_id">
              <div class="mb-2">
                <label>Role Name</label>
                <input type="text" class="form-control" name="name" id="role_name" required>
              </div>
              <div class="mb-2">
                <label>Description</label>
                <textarea class="form-control" name="description" id="role_description"></textarea>
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
        modal = new bootstrap.Modal(document.getElementById('roleModal'));
        loadProjects();

        $('#project_select').on('change', function () {
            loadRoles($(this).val());
        });

        $('#roleForm').on('submit', function (e) {
            e.preventDefault();
            $.post('ajax/save_role.php', $(this).serialize(), function () {
                modal.hide();
                loadRoles($('#project_select').val());
            });
        });
    });

    function loadProjects() {
        $.get('../ajax/load_projects.php', function (data) {
            $('#project_select').html(data).change();
        });
    }

    function loadRoles(project_id) {
        $.get('../ajax/load_roles_table.php?project_id=' + project_id, function (data) {
            $('#roleTable').html(data);
        });
    }

    function openRoleModal(role = {}) {
        $('#role_id').val(role.id || '');
        $('#role_project_id').val($('#project_select').val());
        $('#role_name').val(role.name || '');
        $('#role_description').val(role.description || '');
        modal.show();
    }

    function editRole(id) {
        $.getJSON('../ajax/get_role.php?id=' + id, openRoleModal);
    }

    function deleteRole(id) {
        if (confirm('Delete this role?')) {
            $.post('../ajax/delete_role.php', { id }, function () {
                loadRoles($('#project_select').val());
            });
        }
    }
  </script>
</div>
</body>
</html>
