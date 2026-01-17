<!DOCTYPE html>
<html lang="en">
    <?php $this->load->view("load/header_main") ?>
    <body class="hold-transition sidebar-mini">
        <!-- Pre loader -->
        <div id="preloader-overlay">
            <div class="preloader-spinner"></div>
        </div>
        
        <div class="wrapper">
            <?php $this->load->view("load/header") ?>
            <?php $this->load->view("load/sidelinks") ?>
            
            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper">
                <!-- Content Header (Page header) -->
                <section class="content-header">
                    <div class="container-fluid">
                        <div class="row mb-2">
                            <div class="col-sm-6">
                                <h1>Departments Management</h1>
                                <p class="text-muted">Total Departments: <?php echo isset($department_count) ? $department_count : 0; ?></p>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                                    <li class="breadcrumb-item active">Departments</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Main content -->
                <section class="content"> 
                    <div class="container-fluid">
                        <!-- Alert Messages Container -->
                        <div id="alert-container"></div>
                        
                        <!-- Action Buttons -->
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <button type="button" class="btn btn-success" data-toggle="modal" data-target="#addDepartmentModal">
                                    <i class="fas fa-plus-circle"></i> Add New Department
                                </button>
                                <button type="button" class="btn btn-secondary" id="refreshTable">
                                    <i class="fas fa-sync-alt"></i> Refresh Table
                                </button>
                                <a href="<?php echo base_url('welcome/openschools'); ?>" class="btn btn-info">
                                    <i class="fas fa-school"></i> Manage Schools
                                </a>
                            </div>
                        </div>
                        
                        <!-- Departments Table -->
                        <div class="row">
                            <div class="col-md-12"> 
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title">All Departments</h3>
                                        <div class="card-tools">
                                            <div class="input-group input-group-sm" style="width: 150px;">
                                                <input type="text" name="table_search" class="form-control float-right" placeholder="Search">
                                                <div class="input-group-append">
                                                    <button type="submit" class="btn btn-default">
                                                        <i class="fas fa-search"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- /.card-header -->
                                    <div class="card-body">
                                        <?php if(isset($dbhead) && !empty($dbhead)): ?>
                                            <table id="departmentsTable" class="table table-bordered table-striped">
                                                <thead>
                                                    <?php echo $dbhead ?>
                                                </thead>
                                                <tbody>
                                                    <?php echo isset($dbbody) ? $dbbody : '' ?>
                                                </tbody>
                                            </table>
                                        <?php else: ?>
                                            <div class="alert alert-info text-center">
                                                <h5><i class="icon fas fa-info"></i> No Departments Found!</h5>
                                                Click "Add New Department" button to add your first department.
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <!-- /.card-body -->
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
            <!-- /.content-wrapper -->
            <?php $this->load->view("load/footer") ?>
        </div>
        <!-- ./wrapper -->
        
        <!-- Add Department Modal -->
        <div class="modal fade" id="addDepartmentModal" tabindex="-1" role="dialog" aria-labelledby="addDepartmentModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-success">
                        <h5 class="modal-title" id="addDepartmentModalLabel">
                            <i class="fas fa-building"></i> Add New Department
                        </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form id="addDepartmentForm">
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="addDeptName">Department Name *</label>
                                <input type="text" class="form-control" id="addDeptName" name="dept_name" 
                                       placeholder="Enter department name" required>
                                <small class="form-text text-danger" id="addDeptNameError"></small>
                            </div>
                            <div class="form-group">
                                <label for="addSchoolSelect">School *</label>
                                <select class="form-control" id="addSchoolSelect" name="sch_id" required>
                                    <option value="">-- Select School --</option>
                                    <?php if(isset($schools) && !empty($schools)): ?>
                                        <?php foreach($schools as $id => $name): ?>
                                            <option value="<?php echo $id; ?>"><?php echo htmlspecialchars($name); ?></option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                                <small class="form-text text-danger" id="addSchoolError"></small>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-save"></i> Save Department
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Edit Department Modal -->
        <div class="modal fade" id="editDepartmentModal" tabindex="-1" role="dialog" aria-labelledby="editDepartmentModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-primary">
                        <h5 class="modal-title" id="editDepartmentModalLabel">
                            <i class="fas fa-edit"></i> Edit Department
                        </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form id="editDepartmentForm">
                        <div class="modal-body">
                            <input type="hidden" id="editDeptId" name="dept_id">
                            <div class="form-group">
                                <label for="editDeptName">Department Name *</label>
                                <input type="text" class="form-control" id="editDeptName" name="dept_name" 
                                       placeholder="Enter department name" required>
                                <small class="form-text text-danger" id="editDeptNameError"></small>
                            </div>
                            <div class="form-group">
                                <label for="editSchoolSelect">School *</label>
                                <select class="form-control" id="editSchoolSelect" name="sch_id" required>
                                    <option value="">-- Select School --</option>
                                    <?php if(isset($schools) && !empty($schools)): ?>
                                        <?php foreach($schools as $id => $name): ?>
                                            <option value="<?php echo $id; ?>"><?php echo htmlspecialchars($name); ?></option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                                <small class="form-text text-danger" id="editSchoolError"></small>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-sync-alt"></i> Update Department
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Delete Confirmation Modal -->
        <div class="modal fade" id="deleteDepartmentModal" tabindex="-1" role="dialog" aria-labelledby="deleteDepartmentModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-danger">
                        <h5 class="modal-title" id="deleteDepartmentModalLabel">
                            <i class="fas fa-exclamation-triangle"></i> Confirm Delete
                        </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>Are you sure you want to delete this department?</p>
                        <p><strong>Department Name: <span id="deleteDeptName"></span></strong></p>
                        <p class="text-danger">This action cannot be undone!</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-danger" id="confirmDeleteDeptBtn">
                            <i class="fas fa-trash"></i> Delete Department
                        </button>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- JavaScript -->
        <script>
            $(document).ready(function() {
                // Initialize DataTable
                var deptTable = $('#departmentsTable').DataTable({
                    "responsive": true,
                    "lengthChange": true,
                    "autoWidth": false,
                    "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"],
                    "pageLength": 10,
                    "order": [[0, 'asc']]
                });
                
                // Append buttons to container
                deptTable.buttons().container().appendTo('#departmentsTable_wrapper .col-md-6:eq(0)');
                
                // Hide preloader
                window.addEventListener('load', function() {
                    const preloader = document.getElementById('preloader-overlay');
                    if (preloader) {
                        preloader.style.display = 'none';
                    }
                });
                
                // Function to show alert
                function showAlert(message, type = 'success') {
                    var alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
                    var icon = type === 'success' ? 'fa-check' : 'fa-ban';
                    var title = type === 'success' ? 'Success!' : 'Error!';
                    
                    var alertHtml = `
                        <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            <h5><i class="icon fas ${icon}"></i> ${title}</h5>
                            ${message}
                        </div>
                    `;
                    
                    $('#alert-container').html(alertHtml);
                    
                    // Auto-remove alert after 5 seconds
                    setTimeout(function() {
                        $('.alert').alert('close');
                    }, 5000);
                }
                
                // Function to refresh table
                function refreshTable() {
                    location.reload(); // Simple reload for now
                }
                
                // Refresh Table Button
                $('#refreshTable').click(function() {
                    refreshTable();
                });
                
                // Add Department Form Submission
                $('#addDepartmentForm').submit(function(e) {
                    e.preventDefault();
                    
                    var deptName = $('#addDeptName').val().trim();
                    var schoolId = $('#addSchoolSelect').val();
                    
                    // Validation
                    if (deptName === '') {
                        $('#addDeptNameError').text('Department name is required!');
                        return;
                    }
                    
                    if (schoolId === '') {
                        $('#addSchoolError').text('Please select a school!');
                        return;
                    }
                    
                    // Clear errors
                    $('#addDeptNameError').text('');
                    $('#addSchoolError').text('');
                    
                    $.ajax({
                        url: '<?php echo base_url("welcome/adddepartment_ajax"); ?>',
                        method: 'POST',
                        data: $(this).serialize(),
                        dataType: 'json',
                        beforeSend: function() {
                            $('#addDepartmentForm button[type="submit"]').html('<i class="fas fa-spinner fa-spin"></i> Saving...').prop('disabled', true);
                        },
                        success: function(response) {
                            if (response.status === 'success') {
                                showAlert(response.message, 'success');
                                $('#addDepartmentModal').modal('hide');
                                $('#addDepartmentForm')[0].reset();
                                refreshTable();
                            } else if (response.status === 'validation_error') {
                                // Handle validation errors
                                var errors = response.message;
                                if (errors.includes('Department Name')) {
                                    $('#addDeptNameError').text('Please enter a valid department name');
                                }
                                if (errors.includes('School')) {
                                    $('#addSchoolError').text('Please select a school');
                                }
                            } else {
                                showAlert(response.message, 'error');
                            }
                        },
                        error: function() {
                            showAlert('An error occurred. Please try again!', 'error');
                        },
                        complete: function() {
                            $('#addDepartmentForm button[type="submit"]').html('<i class="fas fa-save"></i> Save Department').prop('disabled', false);
                        }
                    });
                });
                
                // Edit Department Button Click
                $(document).on('click', '.edit-department', function() {
                    var deptId = $(this).data('id');
                    var deptName = $(this).data('name');
                    var schId = $(this).data('sch_id');
                    
                    $('#editDeptId').val(deptId);
                    $('#editDeptName').val(deptName);
                    $('#editSchoolSelect').val(schId);
                    $('#editDepartmentModal').modal('show');
                });
                
                // Edit Department Form Submission
                $('#editDepartmentForm').submit(function(e) {
                    e.preventDefault();
                    
                    var deptName = $('#editDeptName').val().trim();
                    var schoolId = $('#editSchoolSelect').val();
                    
                    // Validation
                    if (deptName === '') {
                        $('#editDeptNameError').text('Department name is required!');
                        return;
                    }
                    
                    if (schoolId === '') {
                        $('#editSchoolError').text('Please select a school!');
                        return;
                    }
                    
                    // Clear errors
                    $('#editDeptNameError').text('');
                    $('#editSchoolError').text('');
                    
                    $.ajax({
                        url: '<?php echo base_url("welcome/updatedepartment_ajax"); ?>',
                        method: 'POST',
                        data: $(this).serialize(),
                        dataType: 'json',
                        beforeSend: function() {
                            $('#editDepartmentForm button[type="submit"]').html('<i class="fas fa-spinner fa-spin"></i> Updating...').prop('disabled', true);
                        },
                        success: function(response) {
                            if (response.status === 'success') {
                                showAlert(response.message, 'success');
                                $('#editDepartmentModal').modal('hide');
                                refreshTable();
                            } else if (response.status === 'validation_error') {
                                var errors = response.message;
                                if (errors.includes('Department Name')) {
                                    $('#editDeptNameError').text('Please enter a valid department name');
                                }
                                if (errors.includes('School')) {
                                    $('#editSchoolError').text('Please select a school');
                                }
                            } else {
                                showAlert(response.message, 'error');
                            }
                        },
                        error: function() {
                            showAlert('An error occurred. Please try again!', 'error');
                        },
                        complete: function() {
                            $('#editDepartmentForm button[type="submit"]').html('<i class="fas fa-sync-alt"></i> Update Department').prop('disabled', false);
                        }
                    });
                });
                
                // Delete Department Button Click
                $(document).on('click', '.delete-department', function() {
                    var deptId = $(this).data('id');
                    var deptName = $(this).data('name');
                    
                    $('#deleteDeptName').text(deptName);
                    $('#confirmDeleteDeptBtn').data('id', deptId);
                    $('#deleteDepartmentModal').modal('show');
                });
                
                // Confirm Delete Button Click
                $('#confirmDeleteDeptBtn').click(function() {
                    var deptId = $(this).data('id');
                    
                    $.ajax({
                        url: '<?php echo base_url("welcome/deletedepartment_ajax"); ?>',
                        method: 'POST',
                        data: {id: deptId},
                        dataType: 'json',
                        beforeSend: function() {
                            $('#confirmDeleteDeptBtn').html('<i class="fas fa-spinner fa-spin"></i> Deleting...').prop('disabled', true);
                        },
                        success: function(response) {
                            if (response.status === 'success') {
                                showAlert(response.message, 'success');
                                $('#deleteDepartmentModal').modal('hide');
                                refreshTable();
                            } else {
                                showAlert(response.message, 'error');
                            }
                        },
                        error: function() {
                            showAlert('An error occurred. Please try again!', 'error');
                        },
                        complete: function() {
                            $('#confirmDeleteDeptBtn').html('<i class="fas fa-trash"></i> Delete Department').prop('disabled', false);
                        }
                    });
                });
                
                // Reset forms when modals are hidden
                $('#addDepartmentModal').on('hidden.bs.modal', function() {
                    $('#addDepartmentForm')[0].reset();
                    $('#addDeptNameError').text('');
                    $('#addSchoolError').text('');
                });
                
                $('#editDepartmentModal').on('hidden.bs.modal', function() {
                    $('#editDeptNameError').text('');
                    $('#editSchoolError').text('');
                });
                
                // Auto-hide alerts when clicked
                $(document).on('click', '.alert', function() {
                    $(this).alert('close');
                });
                
                // Load schools for dropdown dynamically (optional)
                function loadSchoolsForDropdown() {
                    $.ajax({
                        url: '<?php echo base_url("welcome/getschoolsfordropdown_ajax"); ?>',
                        method: 'GET',
                        dataType: 'json',
                        success: function(response) {
                            if (response.status === 'success') {
                                var addSelect = $('#addSchoolSelect');
                                var editSelect = $('#editSchoolSelect');
                                
                                addSelect.empty().append('<option value="">-- Select School --</option>');
                                editSelect.empty().append('<option value="">-- Select School --</option>');
                                
                                $.each(response.schools, function(id, name) {
                                    addSelect.append('<option value="' + id + '">' + name + '</option>');
                                    editSelect.append('<option value="' + id + '">' + name + '</option>');
                                });
                            }
                        }
                    });
                }
                
                // Uncomment to load schools dynamically
                // loadSchoolsForDropdown();
            });
        </script>
    </body>
</html>