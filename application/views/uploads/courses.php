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
                                <h1>Courses Management</h1>
                                <p class="text-muted">Total Courses: <?php echo isset($course_count) ? $course_count : 0; ?></p>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                                    <li class="breadcrumb-item active">Courses</li>
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
                                <button type="button" class="btn btn-success" data-toggle="modal" data-target="#addCourseModal">
                                    <i class="fas fa-plus-circle"></i> Add New Course
                                </button>
                                <button type="button" class="btn btn-secondary" id="refreshTable">
                                    <i class="fas fa-sync-alt"></i> Refresh Table
                                </button>
                                <a href="<?php echo base_url('schools'); ?>" class="btn btn-info">
                                    <i class="fas fa-school"></i> Manage Schools
                                </a>
                                <a href="<?php echo base_url('departments'); ?>" class="btn btn-info">
                                    <i class="fas fa-building"></i> Manage Departments
                                </a>
                            </div>
                        </div>
                        
                        <!-- Courses Table -->
                        <div class="row">
                            <div class="col-md-12"> 
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title">All Courses</h3>
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
                                            <table id="coursesTable" class="table table-bordered table-striped">
                                                <thead>
                                                    <?php echo $dbhead ?>
                                                </thead>
                                                <tbody>
                                                    <?php echo isset($dbbody) ? $dbbody : '' ?>
                                                </tbody>
                                            </table>
                                        <?php else: ?>
                                            <div class="alert alert-info text-center">
                                                <h5><i class="icon fas fa-info"></i> No Courses Found!</h5>
                                                Click "Add New Course" button to add your first course.
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
        
        <!-- Add Course Modal -->
        <div class="modal fade" id="addCourseModal" tabindex="-1" role="dialog" aria-labelledby="addCourseModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-success">
                        <h5 class="modal-title" id="addCourseModalLabel">
                            <i class="fas fa-book"></i> Add New Course
                        </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form id="addCourseForm">
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="addCourseCode">Course Code *</label>
                                        <input type="text" class="form-control" id="addCourseCode" name="course_code" 
                                               placeholder="e.g., CSC101" required>
                                        <small class="form-text text-danger" id="addCourseCodeError"></small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="addSemester">Semester *</label>
                                        <select class="form-control" id="addSemester" name="semester" required>
                                            <option value="">-- Select Semester --</option>
                                            <?php if(isset($semesters) && !empty($semesters)): ?>
                                                <?php foreach($semesters as $value => $label): ?>
                                                    <option value="<?php echo $value; ?>"><?php echo htmlspecialchars($label); ?></option>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </select>
                                        <small class="form-text text-danger" id="addSemesterError"></small>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="addCourseTitle">Course Title *</label>
                                <input type="text" class="form-control" id="addCourseTitle" name="course_title" 
                                       placeholder="Enter course title" required>
                                <small class="form-text text-danger" id="addCourseTitleError"></small>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
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
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="addDepartmentSelect">Department *</label>
                                        <select class="form-control" id="addDepartmentSelect" name="dept_id" required>
                                            <option value="">-- Select Department --</option>
                                            <?php if(isset($departments) && !empty($departments)): ?>
                                                <?php foreach($departments as $id => $name): ?>
                                                    <option value="<?php echo $id; ?>" data-school=""><?php echo htmlspecialchars($name); ?></option>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </select>
                                        <small class="form-text text-danger" id="addDepartmentError"></small>
                                        <small class="form-text text-muted" id="departmentHint">
                                            Please select a school first to see departments
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-save"></i> Save Course
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Edit Course Modal -->
        <div class="modal fade" id="editCourseModal" tabindex="-1" role="dialog" aria-labelledby="editCourseModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-primary">
                        <h5 class="modal-title" id="editCourseModalLabel">
                            <i class="fas fa-edit"></i> Edit Course
                        </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form id="editCourseForm">
                        <div class="modal-body">
                            <input type="hidden" id="editCourseId" name="course_id">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="editCourseCode">Course Code *</label>
                                        <input type="text" class="form-control" id="editCourseCode" name="course_code" 
                                               placeholder="e.g., CSC101" required>
                                        <small class="form-text text-danger" id="editCourseCodeError"></small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="editSemester">Semester *</label>
                                        <select class="form-control" id="editSemester" name="semester" required>
                                            <option value="">-- Select Semester --</option>
                                            <?php if(isset($semesters) && !empty($semesters)): ?>
                                                <?php foreach($semesters as $value => $label): ?>
                                                    <option value="<?php echo $value; ?>"><?php echo htmlspecialchars($label); ?></option>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </select>
                                        <small class="form-text text-danger" id="editSemesterError"></small>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="editCourseTitle">Course Title *</label>
                                <input type="text" class="form-control" id="editCourseTitle" name="course_title" 
                                       placeholder="Enter course title" required>
                                <small class="form-text text-danger" id="editCourseTitleError"></small>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
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
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="editDepartmentSelect">Department *</label>
                                        <select class="form-control" id="editDepartmentSelect" name="dept_id" required>
                                            <option value="">-- Select Department --</option>
                                            <?php if(isset($departments) && !empty($departments)): ?>
                                                <?php foreach($departments as $id => $name): ?>
                                                    <option value="<?php echo $id; ?>" data-school=""><?php echo htmlspecialchars($name); ?></option>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </select>
                                        <small class="form-text text-danger" id="editDepartmentError"></small>
                                        <small class="form-text text-muted" id="editDepartmentHint">
                                            Departments will update based on selected school
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-sync-alt"></i> Update Course
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Delete Confirmation Modal -->
        <div class="modal fade" id="deleteCourseModal" tabindex="-1" role="dialog" aria-labelledby="deleteCourseModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-danger">
                        <h5 class="modal-title" id="deleteCourseModalLabel">
                            <i class="fas fa-exclamation-triangle"></i> Confirm Delete
                        </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>Are you sure you want to delete this course?</p>
                        <p><strong>Course: <span id="deleteCourseTitle"></span></strong></p>
                        <p class="text-danger">This action cannot be undone!</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-danger" id="confirmDeleteCourseBtn">
                            <i class="fas fa-trash"></i> Delete Course
                        </button>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- JavaScript -->
        <script>
            $(document).ready(function() {
                // Initialize DataTable
                var courseTable = $('#coursesTable').DataTable({
                    "responsive": true,
                    "lengthChange": true,
                    "autoWidth": false,
                    "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"],
                    "pageLength": 10,
                    "order": [[0, 'asc']],
                    "columnDefs": [
                        { "width": "5%", "targets": 0 },
                        { "width": "10%", "targets": 1 },
                        { "width": "25%", "targets": 2 },
                        { "width": "10%", "targets": 3 },
                        { "width": "15%", "targets": 4 },
                        { "width": "15%", "targets": 5 },
                        { "width": "10%", "targets": 6 },
                        { "width": "10%", "targets": 7 }
                    ]
                });
                
                // Append buttons to container
                courseTable.buttons().container().appendTo('#coursesTable_wrapper .col-md-6:eq(0)');
                
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
                    location.reload();
                }
                
                // Refresh Table Button
                $('#refreshTable').click(function() {
                    refreshTable();
                });
               // Function to load departments based on selected school
function loadDepartmentsBySchool(schoolId, targetSelect, selectedDeptId = '') {
    if (!schoolId) {
        targetSelect.empty().append('<option value="">-- Select Department --</option>');
        return;
    }
    
    $.ajax({
        url: '<?php echo base_url("welcome/getdepartmentsbyschool_ajax"); ?>/' + schoolId,
        method: 'GET',
        dataType: 'json',
        beforeSend: function() {
            targetSelect.prop('disabled', true).empty().append('<option value="">Loading departments...</option>');
        },
        success: function(response) {
            console.log('Departments response:', response); // Debug log
            targetSelect.empty();
            
            if (response.status === 'success') {
                if (response.departments && response.departments.length > 0) {
                    targetSelect.append('<option value="">-- Select Department --</option>');
                    
                    $.each(response.departments, function(index, dept) {
                        var selected = (dept.id == selectedDeptId) ? 'selected' : '';
                        targetSelect.append('<option value="' + dept.id + '" ' + selected + '>' + dept.name + '</option>');
                    });
                } else {
                    targetSelect.append('<option value="">No departments found for this school</option>');
                }
            } else {
                targetSelect.append('<option value="">Error loading departments</option>');
                console.error('Error response:', response);
            }
        },
        error: function(xhr, status, error) {
            console.error('AJAX Error:', status, error);
            console.error('Response:', xhr.responseText);
            targetSelect.empty().append('<option value="">Error loading departments</option>');
            showAlert('Error loading departments. Please try again.', 'error');
        },
        complete: function() {
            targetSelect.prop('disabled', false);
        }
    });
} 
                // School change handler for Add modal
                $('#addSchoolSelect').change(function() {
                    var schoolId = $(this).val();
                    $('#departmentHint').text('Loading departments...');
                    loadDepartmentsBySchool(schoolId, $('#addDepartmentSelect'));
                    $('#departmentHint').text('');
                });
                
                // School change handler for Edit modal
                $('#editSchoolSelect').change(function() {
                    var schoolId = $(this).val();
                    $('#editDepartmentHint').text('Loading departments...');
                    loadDepartmentsBySchool(schoolId, $('#editDepartmentSelect'));
                    $('#editDepartmentHint').text('');
                });
                
                // Add Course Form Submission
                $('#addCourseForm').submit(function(e) {
                    e.preventDefault();
                    
                    // Clear previous errors
                    $('.form-text.text-danger').text('');
                    
                    // Get form data
                    var formData = $(this).serializeArray();
                    var hasError = false;
                    
                    // Basic validation
                    if ($('#addCourseCode').val().trim() === '') {
                        $('#addCourseCodeError').text('Course code is required!');
                        hasError = true;
                    }
                    
                    if ($('#addCourseTitle').val().trim() === '') {
                        $('#addCourseTitleError').text('Course title is required!');
                        hasError = true;
                    }
                    
                    if ($('#addSemester').val() === '') {
                        $('#addSemesterError').text('Please select a semester!');
                        hasError = true;
                    }
                    
                    if ($('#addSchoolSelect').val() === '') {
                        $('#addSchoolError').text('Please select a school!');
                        hasError = true;
                    }
                    
                    if ($('#addDepartmentSelect').val() === '') {
                        $('#addDepartmentError').text('Please select a department!');
                        hasError = true;
                    }
                    
                    if (hasError) return;
                    
                    $.ajax({
                        url: '<?php echo base_url("welcome/addcourse_ajax"); ?>',
                        method: 'POST',
                        data: $(this).serialize(),
                        dataType: 'json',
                        beforeSend: function() {
                            $('#addCourseForm button[type="submit"]').html('<i class="fas fa-spinner fa-spin"></i> Saving...').prop('disabled', true);
                        },
                        success: function(response) {
                            if (response.status === 'success') {
                                showAlert(response.message, 'success');
                                $('#addCourseModal').modal('hide');
                                $('#addCourseForm')[0].reset();
                                $('#addDepartmentSelect').empty().append('<option value="">-- Select Department --</option>');
                                refreshTable();
                            } else if (response.status === 'validation_error') {
                                // Handle validation errors
                                var errors = response.message;
                                if (errors.includes('Course Code')) {
                                    $('#addCourseCodeError').text('Please enter a valid course code');
                                }
                                if (errors.includes('Course Title')) {
                                    $('#addCourseTitleError').text('Please enter a valid course title');
                                }
                                if (errors.includes('Semester')) {
                                    $('#addSemesterError').text('Please select a valid semester (1-8)');
                                }
                                if (errors.includes('School')) {
                                    $('#addSchoolError').text('Please select a school');
                                }
                                if (errors.includes('Department')) {
                                    $('#addDepartmentError').text('Please select a department');
                                }
                            } else {
                                showAlert(response.message, 'error');
                            }
                        },
                        error: function() {
                            showAlert('An error occurred. Please try again!', 'error');
                        },
                        complete: function() {
                            $('#addCourseForm button[type="submit"]').html('<i class="fas fa-save"></i> Save Course').prop('disabled', false);
                        }
                    });
                });
                
                // Edit Course Button Click
                $(document).on('click', '.edit-course', function() {
                    var courseId = $(this).data('id');
                    var courseCode = $(this).data('code');
                    var courseTitle = $(this).data('title');
                    var semester = $(this).data('semester');
                    var schId = $(this).data('sch_id');
                    var deptId = $(this).data('dept_id');
                    
                    // Set form values
                    $('#editCourseId').val(courseId);
                    $('#editCourseCode').val(courseCode);
                    $('#editCourseTitle').val(courseTitle);
                    $('#editSemester').val(semester);
                    $('#editSchoolSelect').val(schId);
                    
                    // Load departments for the selected school
                    if (schId) {
                        $('#editDepartmentHint').text('Loading departments...');
                        loadDepartmentsBySchool(schId, $('#editDepartmentSelect'), deptId);
                    }
                    
                    $('#editCourseModal').modal('show');
                });
                
                // Edit Course Form Submission
                $('#editCourseForm').submit(function(e) {
                    e.preventDefault();
                    
                    // Clear previous errors
                    $('.form-text.text-danger').text('');
                    
                    // Get form data
                    var hasError = false;
                    
                    // Basic validation
                    if ($('#editCourseCode').val().trim() === '') {
                        $('#editCourseCodeError').text('Course code is required!');
                        hasError = true;
                    }
                    
                    if ($('#editCourseTitle').val().trim() === '') {
                        $('#editCourseTitleError').text('Course title is required!');
                        hasError = true;
                    }
                    
                    if ($('#editSemester').val() === '') {
                        $('#editSemesterError').text('Please select a semester!');
                        hasError = true;
                    }
                    
                    if ($('#editSchoolSelect').val() === '') {
                        $('#editSchoolError').text('Please select a school!');
                        hasError = true;
                    }
                    
                    if ($('#editDepartmentSelect').val() === '') {
                        $('#editDepartmentError').text('Please select a department!');
                        hasError = true;
                    }
                    
                    if (hasError) return;
                    
                    $.ajax({
                        url: '<?php echo base_url("welcome/updatecourse_ajax"); ?>',
                        method: 'POST',
                        data: $(this).serialize(),
                        dataType: 'json',
                        beforeSend: function() {
                            $('#editCourseForm button[type="submit"]').html('<i class="fas fa-spinner fa-spin"></i> Updating...').prop('disabled', true);
                        },
                        success: function(response) {
                            if (response.status === 'success') {
                                showAlert(response.message, 'success');
                                $('#editCourseModal').modal('hide');
                                refreshTable();
                            } else if (response.status === 'validation_error') {
                                var errors = response.message;
                                if (errors.includes('Course Code')) {
                                    $('#editCourseCodeError').text('Please enter a valid course code');
                                }
                                if (errors.includes('Course Title')) {
                                    $('#editCourseTitleError').text('Please enter a valid course title');
                                }
                                if (errors.includes('Semester')) {
                                    $('#editSemesterError').text('Please select a valid semester (1-8)');
                                }
                                if (errors.includes('School')) {
                                    $('#editSchoolError').text('Please select a school');
                                }
                                if (errors.includes('Department')) {
                                    $('#editDepartmentError').text('Please select a department');
                                }
                            } else {
                                showAlert(response.message, 'error');
                            }
                        },
                        error: function() {
                            showAlert('An error occurred. Please try again!', 'error');
                        },
                        complete: function() {
                            $('#editCourseForm button[type="submit"]').html('<i class="fas fa-sync-alt"></i> Update Course').prop('disabled', false);
                        }
                    });
                });
                
                // Delete Course Button Click
                $(document).on('click', '.delete-course', function() {
                    var courseId = $(this).data('id');
                    var courseTitle = $(this).data('title');
                    
                    $('#deleteCourseTitle').text(courseTitle);
                    $('#confirmDeleteCourseBtn').data('id', courseId);
                    $('#deleteCourseModal').modal('show');
                });
                
                // Confirm Delete Button Click
                $('#confirmDeleteCourseBtn').click(function() {
                    var courseId = $(this).data('id');
                    
                    $.ajax({
                        url: '<?php echo base_url("welcome/deletecourse_ajax"); ?>',
                        method: 'POST',
                        data: {id: courseId},
                        dataType: 'json',
                        beforeSend: function() {
                            $('#confirmDeleteCourseBtn').html('<i class="fas fa-spinner fa-spin"></i> Deleting...').prop('disabled', true);
                        },
                        success: function(response) {
                            if (response.status === 'success') {
                                showAlert(response.message, 'success');
                                $('#deleteCourseModal').modal('hide');
                                refreshTable();
                            } else {
                                showAlert(response.message, 'error');
                            }
                        },
                        error: function() {
                            showAlert('An error occurred. Please try again!', 'error');
                        },
                        complete: function() {
                            $('#confirmDeleteCourseBtn').html('<i class="fas fa-trash"></i> Delete Course').prop('disabled', false);
                        }
                    });
                });
                
                // Reset forms when modals are hidden
                $('#addCourseModal').on('hidden.bs.modal', function() {
                    $('#addCourseForm')[0].reset();
                    $('#addDepartmentSelect').empty().append('<option value="">-- Select Department --</option>');
                    $('.form-text.text-danger').text('');
                });
                
                $('#editCourseModal').on('hidden.bs.modal', function() {
                    $('.form-text.text-danger').text('');
                });
                
                // Auto-hide alerts when clicked
                $(document).on('click', '.alert', function() {
                    $(this).alert('close');
                });
            });
        </script>
    </body>
</html>