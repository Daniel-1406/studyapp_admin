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
                                <h1>Schools Management</h1>
                                <p class="text-muted">Total Schools: <?php echo isset($school_count) ? $school_count : 0; ?></p>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                                    <li class="breadcrumb-item active">Schools</li>
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
                                <button type="button" class="btn btn-success" data-toggle="modal" data-target="#addSchoolModal">
                                    <i class="fas fa-plus-circle"></i> Add New School
                                </button>
                                <button type="button" class="btn btn-secondary" id="refreshTable">
                                    <i class="fas fa-sync-alt"></i> Refresh Table
                                </button>
                            </div>
                        </div>
                        
                        <!-- Schools Table -->
                        <div class="row">
                            <div class="col-md-12"> 
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title">All Schools</h3>
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
                                            <table id="schoolsTable" class="table table-bordered table-striped">
                                                <thead>
                                                    <?php echo $dbhead ?>
                                                </thead>
                                                <tbody>
                                                    <?php echo isset($dbbody) ? $dbbody : '' ?>
                                                </tbody>
                                            </table>
                                        <?php else: ?>
                                            <div class="alert alert-info text-center">
                                                <h5><i class="icon fas fa-info"></i> No Schools Found!</h5>
                                                Click "Add New School" button to add your first school.
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
        
        <!-- Add School Modal -->
        <div class="modal fade" id="addSchoolModal" tabindex="-1" role="dialog" aria-labelledby="addSchoolModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-success">
                        <h5 class="modal-title" id="addSchoolModalLabel">
                            <i class="fas fa-school"></i> Add New School
                        </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form id="addSchoolForm">
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="addSchoolName">School Name *</label>
                                <input type="text" class="form-control" id="addSchoolName" name="sch_name" 
                                       placeholder="Enter school name" required>
                                <small class="form-text text-danger" id="addSchoolError"></small>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-save"></i> Save School
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Edit School Modal -->
        <div class="modal fade" id="editSchoolModal" tabindex="-1" role="dialog" aria-labelledby="editSchoolModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-primary">
                        <h5 class="modal-title" id="editSchoolModalLabel">
                            <i class="fas fa-edit"></i> Edit School
                        </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form id="editSchoolForm">
                        <div class="modal-body">
                            <input type="hidden" id="editSchoolId" name="sch_id">
                            <div class="form-group">
                                <label for="editSchoolName">School Name *</label>
                                <input type="text" class="form-control" id="editSchoolName" name="sch_name" 
                                       placeholder="Enter school name" required>
                                <small class="form-text text-danger" id="editSchoolError"></small>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-sync-alt"></i> Update School
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Delete Confirmation Modal -->
        <div class="modal fade" id="deleteSchoolModal" tabindex="-1" role="dialog" aria-labelledby="deleteSchoolModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-danger">
                        <h5 class="modal-title" id="deleteSchoolModalLabel">
                            <i class="fas fa-exclamation-triangle"></i> Confirm Delete
                        </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>Are you sure you want to delete this school?</p>
                        <p><strong>School Name: <span id="deleteSchoolName"></span></strong></p>
                        <p class="text-danger">This action cannot be undone!</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-danger" id="confirmDeleteBtn">
                            <i class="fas fa-trash"></i> Delete School
                        </button>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- JavaScript -->
        <script>
            $(document).ready(function() {
                // Initialize DataTable
                var schoolTable = $('#schoolsTable').DataTable({
                    "responsive": true,
                    "lengthChange": true,
                    "autoWidth": false,
                    "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"],
                    "pageLength": 10
                });
                
                // Append buttons to container
                schoolTable.buttons().container().appendTo('#schoolsTable_wrapper .col-md-6:eq(0)');
                
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
                    $.ajax({
                        url: '<?php echo base_url("welcome/openschools"); ?>',
                        method: 'GET',
                        success: function(response) {
                            // This would reload the whole page
                            // For better UX, we can use AJAX to reload just the table
                            location.reload();
                        }
                    });
                }
                
                // Refresh Table Button
                $('#refreshTable').click(function() {
                    refreshTable();
                });
                
                // Add School Form Submission
                $('#addSchoolForm').submit(function(e) {
                    e.preventDefault();
                    
                    var schoolName = $('#addSchoolName').val().trim();
                    
                    if (schoolName === '') {
                        $('#addSchoolError').text('School name is required!');
                        return;
                    }
                    
                    $('#addSchoolError').text('');
                    
                    $.ajax({
                        url: '<?php echo base_url("welcome/addschool_ajax"); ?>',
                        method: 'POST',
                        data: $(this).serialize(),
                        dataType: 'json',
                        beforeSend: function() {
                            $('#addSchoolForm button[type="submit"]').html('<i class="fas fa-spinner fa-spin"></i> Saving...').prop('disabled', true);
                        },
                        success: function(response) {
                            if (response.status === 'success') {
                                showAlert(response.message, 'success');
                                $('#addSchoolModal').modal('hide');
                                $('#addSchoolForm')[0].reset();
                                refreshTable();
                            } else if (response.status === 'validation_error') {
                                $('#addSchoolError').text(response.message);
                            } else {
                                showAlert(response.message, 'error');
                            }
                        },
                        error: function() {
                            showAlert('An error occurred. Please try again!', 'error');
                        },
                        complete: function() {
                            $('#addSchoolForm button[type="submit"]').html('<i class="fas fa-save"></i> Save School').prop('disabled', false);
                        }
                    });
                });
                
                // Edit School Button Click
                $(document).on('click', '.edit-school', function() {
                    var schoolId = $(this).data('id');
                    var schoolName = $(this).data('name');
                    
                    $('#editSchoolId').val(schoolId);
                    $('#editSchoolName').val(schoolName);
                    $('#editSchoolModal').modal('show');
                });
                
                // Edit School Form Submission
                $('#editSchoolForm').submit(function(e) {
                    e.preventDefault();
                    
                    var schoolName = $('#editSchoolName').val().trim();
                    
                    if (schoolName === '') {
                        $('#editSchoolError').text('School name is required!');
                        return;
                    }
                    
                    $('#editSchoolError').text('');
                    
                    $.ajax({
                        url: '<?php echo base_url("welcome/updateschool_ajax"); ?>',
                        method: 'POST',
                        data: $(this).serialize(),
                        dataType: 'json',
                        beforeSend: function() {
                            $('#editSchoolForm button[type="submit"]').html('<i class="fas fa-spinner fa-spin"></i> Updating...').prop('disabled', true);
                        },
                        success: function(response) {
                            if (response.status === 'success') {
                                showAlert(response.message, 'success');
                                $('#editSchoolModal').modal('hide');
                                refreshTable();
                            } else if (response.status === 'validation_error') {
                                $('#editSchoolError').text(response.message);
                            } else {
                                showAlert(response.message, 'error');
                            }
                        },
                        error: function() {
                            showAlert('An error occurred. Please try again!', 'error');
                        },
                        complete: function() {
                            $('#editSchoolForm button[type="submit"]').html('<i class="fas fa-sync-alt"></i> Update School').prop('disabled', false);
                        }
                    });
                });
                
                // Delete School Button Click
                $(document).on('click', '.delete-school', function() {
                    var schoolId = $(this).data('id');
                    var schoolName = $(this).data('name');
                    
                    $('#deleteSchoolName').text(schoolName);
                    $('#confirmDeleteBtn').data('id', schoolId);
                    $('#deleteSchoolModal').modal('show');
                });
                
                // Confirm Delete Button Click
                $('#confirmDeleteBtn').click(function() {
                    var schoolId = $(this).data('id');
                    
                    $.ajax({
                        url: '<?php echo base_url("welcome/deleteschool_ajax"); ?>',
                        method: 'POST',
                        data: {id: schoolId},
                        dataType: 'json',
                        beforeSend: function() {
                            $('#confirmDeleteBtn').html('<i class="fas fa-spinner fa-spin"></i> Deleting...').prop('disabled', true);
                        },
                        success: function(response) {
                            if (response.status === 'success') {
                                showAlert(response.message, 'success');
                                $('#deleteSchoolModal').modal('hide');
                                refreshTable();
                            } else {
                                showAlert(response.message, 'error');
                            }
                        },
                        error: function() {
                            showAlert('An error occurred. Please try again!', 'error');
                        },
                        complete: function() {
                            $('#confirmDeleteBtn').html('<i class="fas fa-trash"></i> Delete School').prop('disabled', false);
                        }
                    });
                });
                
                // Reset forms when modals are hidden
                $('#addSchoolModal').on('hidden.bs.modal', function() {
                    $('#addSchoolForm')[0].reset();
                    $('#addSchoolError').text('');
                });
                
                $('#editSchoolModal').on('hidden.bs.modal', function() {
                    $('#editSchoolError').text('');
                });
                
                // Auto-hide alerts when clicked
                $(document).on('click', '.alert', function() {
                    $(this).alert('close');
                });
            });
        </script>
    </body>
</html>