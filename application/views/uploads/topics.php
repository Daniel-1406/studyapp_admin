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
                                <h1>Topics Management</h1>
                                <p class="text-muted">Total Topics: <?php echo isset($topic_count) ? $topic_count : 0; ?></p>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                                    <li class="breadcrumb-item active">Topics</li>
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
                        
                        <!-- Filter Section -->
                        <div class="card card-info">
                            <div class="card-header">
                                <h3 class="card-title"><i class="fas fa-filter"></i> Filter Topics</h3>
                                <div class="card-tools">
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="card-body">
                                <form id="filterForm" method="GET" action="<?php echo base_url('welcome/opentopics'); ?>">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="filterSchool">School</label>
                                                <select class="form-control" id="filterSchool" name="school_id">
                                                    <option value="">All Schools</option>
                                                    <?php if(isset($schools) && !empty($schools)): ?>
                                                        <?php foreach($schools as $id => $name): ?>
                                                            <option value="<?php echo $id; ?>" <?php echo isset($filter_school_id) && $filter_school_id == $id ? 'selected' : ''; ?>>
                                                                <?php echo htmlspecialchars($name); ?>
                                                            </option>
                                                        <?php endforeach; ?>
                                                    <?php endif; ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="filterDepartment">Department</label>
                                                <select class="form-control" id="filterDepartment" name="dept_id">
                                                    <option value="">All Departments</option>
                                                    <?php if(isset($departments) && !empty($departments)): ?>
                                                        <?php foreach($departments as $id => $name): ?>
                                                            <option value="<?php echo $id; ?>" <?php echo isset($filter_dept_id) && $filter_dept_id == $id ? 'selected' : ''; ?>>
                                                                <?php echo htmlspecialchars($name); ?>
                                                            </option>
                                                        <?php endforeach; ?>
                                                    <?php endif; ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="filterCourse">Course</label>
                                                <select class="form-control" id="filterCourse" name="course_id">
                                                    <option value="">All Courses</option>
                                                    <?php if(isset($courses) && !empty($courses)): ?>
                                                        <?php foreach($courses as $id => $name): ?>
                                                            <option value="<?php echo $id; ?>" <?php echo isset($filter_course_id) && $filter_course_id == $id ? 'selected' : ''; ?>>
                                                                <?php echo htmlspecialchars($name); ?>
                                                            </option>
                                                        <?php endforeach; ?>
                                                    <?php endif; ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group" style="margin-top: 32px;">
                                                <button type="submit" class="btn btn-primary">
                                                    <i class="fas fa-filter"></i> Apply Filter
                                                </button>
                                                <a href="<?php echo base_url('welcome/opentopics'); ?>" class="btn btn-secondary">
                                                    <i class="fas fa-times"></i> Clear
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        
                        <!-- Action Buttons -->
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <button type="button" class="btn btn-success" data-toggle="modal" data-target="#addTopicModal">
                                    <i class="fas fa-plus-circle"></i> Add New Topic
                                </button>
                                <button type="button" class="btn btn-secondary" id="refreshTable">
                                    <i class="fas fa-sync-alt"></i> Refresh Table
                                </button>
                                <a href="<?php echo base_url('courses'); ?>" class="btn btn-info">
                                    <i class="fas fa-book"></i> Manage Courses
                                </a>
                            </div>
                        </div>
                        
                        <!-- Topics Table -->
                        <div class="row">
                            <div class="col-md-12"> 
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title">All Topics</h3>
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
                                            <table id="topicsTable" class="table table-bordered table-striped">
                                                <thead>
                                                    <?php echo $dbhead ?>
                                                </thead>
                                                <tbody>
                                                    <?php echo isset($dbbody) ? $dbbody : '' ?>
                                                </tbody>
                                            </table>
                                        <?php else: ?>
                                            <div class="alert alert-info text-center">
                                                <h5><i class="icon fas fa-info"></i> No Topics Found!</h5>
                                                <?php if(isset($filter_school_id) || isset($filter_dept_id) || isset($filter_course_id)): ?>
                                                    No topics match your filter criteria. Try changing your filters or 
                                                    <a href="<?php echo base_url('welcome/opentopics'); ?>">clear filters</a>.
                                                <?php else: ?>
                                                    Click "Add New Topic" button to add your first topic.
                                                <?php endif; ?>
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
        
        <!-- Add Topic Modal -->
        <div class="modal fade" id="addTopicModal" tabindex="-1" role="dialog" aria-labelledby="addTopicModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-success">
                        <h5 class="modal-title" id="addTopicModalLabel">
                            <i class="fas fa-list-alt"></i> Add New Topic
                        </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form id="addTopicForm">
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="addTopicName">Topic Name *</label>
                                <input type="text" class="form-control" id="addTopicName" name="topic_name" 
                                       placeholder="Enter topic name" required>
                                <small class="form-text text-danger" id="addTopicNameError"></small>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="addModalSchool">School</label>
                                        <select class="form-control" id="addModalSchool" name="modal_school_id">
                                            <option value="">-- Select School --</option>
                                            <?php if(isset($schools) && !empty($schools)): ?>
                                                <?php foreach($schools as $id => $name): ?>
                                                    <option value="<?php echo $id; ?>"><?php echo htmlspecialchars($name); ?></option>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="addModalDepartment">Department</label>
                                        <select class="form-control" id="addModalDepartment" name="modal_dept_id">
                                            <option value="">-- Select Department --</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="addModalCourse">Course *</label>
                                        <select class="form-control" id="addModalCourse" name="course_id" required>
                                            <option value="">-- Select Course --</option>
                                        </select>
                                        <small class="form-text text-danger" id="addCourseError"></small>
                                        <small class="form-text text-muted" id="courseHint">
                                            Select school and department to see courses
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-save"></i> Save Topic
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Edit Topic Modal -->
        <div class="modal fade" id="editTopicModal" tabindex="-1" role="dialog" aria-labelledby="editTopicModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-primary">
                        <h5 class="modal-title" id="editTopicModalLabel">
                            <i class="fas fa-edit"></i> Edit Topic
                        </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form id="editTopicForm">
                        <div class="modal-body">
                            <input type="hidden" id="editTopicId" name="topic_id">
                            <div class="form-group">
                                <label for="editTopicName">Topic Name *</label>
                                <input type="text" class="form-control" id="editTopicName" name="topic_name" 
                                       placeholder="Enter topic name" required>
                                <small class="form-text text-danger" id="editTopicNameError"></small>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="editModalSchool">School</label>
                                        <select class="form-control" id="editModalSchool" name="modal_school_id">
                                            <option value="">-- Select School --</option>
                                            <?php if(isset($schools) && !empty($schools)): ?>
                                                <?php foreach($schools as $id => $name): ?>
                                                    <option value="<?php echo $id; ?>"><?php echo htmlspecialchars($name); ?></option>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="editModalDepartment">Department</label>
                                        <select class="form-control" id="editModalDepartment" name="modal_dept_id">
                                            <option value="">-- Select Department --</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="editModalCourse">Course *</label>
                                        <select class="form-control" id="editModalCourse" name="course_id" required>
                                            <option value="">-- Select Course --</option>
                                        </select>
                                        <small class="form-text text-danger" id="editCourseError"></small>
                                        <small class="form-text text-muted" id="editCourseHint">
                                            Select school and department to see courses
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-sync-alt"></i> Update Topic
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Delete Confirmation Modal -->
        <div class="modal fade" id="deleteTopicModal" tabindex="-1" role="dialog" aria-labelledby="deleteTopicModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-danger">
                        <h5 class="modal-title" id="deleteTopicModalLabel">
                            <i class="fas fa-exclamation-triangle"></i> Confirm Delete
                        </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>Are you sure you want to delete this topic?</p>
                        <p><strong>Topic: <span id="deleteTopicName"></span></strong></p>
                        <p class="text-danger">This action cannot be undone!</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-danger" id="confirmDeleteTopicBtn">
                            <i class="fas fa-trash"></i> Delete Topic
                        </button>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- JavaScript -->
        <script>
            $(document).ready(function() {
                // Initialize DataTable
                var topicTable = $('#topicsTable').DataTable({
                    "responsive": true,
                    "lengthChange": true,
                    "autoWidth": false,
                    "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"],
                    "pageLength": 10,
                    "order": [[0, 'asc']],
                    "columnDefs": [
                        { "width": "5%", "targets": 0 },
                        { "width": "20%", "targets": 1 },
                        { "width": "25%", "targets": 2 },
                        { "width": "15%", "targets": 3 },
                        { "width": "15%", "targets": 4 },
                        { "width": "10%", "targets": 5 },
                        { "width": "10%", "targets": 6 }
                    ]
                });
                
                // Append buttons to container
                topicTable.buttons().container().appendTo('#topicsTable_wrapper .col-md-6:eq(0)');
                
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
                            targetSelect.prop('disabled', true);
                        },
                        success: function(response) {
                            if (response.status === 'success') {
                                targetSelect.empty().append('<option value="">-- Select Department --</option>');
                                
                                $.each(response.departments, function(index, dept) {
                                    var selected = (dept.id == selectedDeptId) ? 'selected' : '';
                                    targetSelect.append('<option value="' + dept.id + '" ' + selected + '>' + dept.name + '</option>');
                                });
                            }
                        },
                        error: function() {
                            showAlert('Error loading departments!', 'error');
                        },
                        complete: function() {
                            targetSelect.prop('disabled', false);
                        }
                    });
                }
                
                // Function to load courses based on school and department
                function loadCoursesByFilters(schoolId, deptId, targetSelect, selectedCourseId = '') {
                    if (!schoolId || !deptId) {
                        targetSelect.empty().append('<option value="">-- Select Course --</option>');
                        return;
                    }
                    
                    $.ajax({
                        url: '<?php echo base_url("welcome/getcoursesbyfilters_ajax"); ?>',
                        method: 'GET',
                        data: {
                            school_id: schoolId,
                            dept_id: deptId
                        },
                        dataType: 'json',
                        beforeSend: function() {
                            targetSelect.prop('disabled', true);
                        },
                        success: function(response) {
                            if (response.status === 'success') {
                                targetSelect.empty().append('<option value="">-- Select Course --</option>');
                                
                                $.each(response.courses, function(index, course) {
                                    var selected = (course.id == selectedCourseId) ? 'selected' : '';
                                    var courseText = course.code + ' - ' + course.title + ' (' + course.school_name + ' - ' + course.dept_name + ')';
                                    targetSelect.append('<option value="' + course.id + '" ' + selected + '>' + courseText + '</option>');
                                });
                                
                                if (response.courses.length === 0) {
                                    targetSelect.append('<option value="" disabled>No courses found for this school/department</option>');
                                }
                            }
                        },
                        error: function() {
                            showAlert('Error loading courses!', 'error');
                        },
                        complete: function() {
                            targetSelect.prop('disabled', false);
                        }
                    });
                }
                
                // School change handler for filter form
                $('#filterSchool').change(function() {
                    var schoolId = $(this).val();
                    $('#filterDepartment').val('');
                    $('#filterCourse').val('');
                });
                
                // Department change handler for filter form
                $('#filterDepartment').change(function() {
                    $('#filterCourse').val('');
                });
                
                // School change handler for Add modal
                $('#addModalSchool').change(function() {
                    var schoolId = $(this).val();
                    $('#addModalDepartment').empty().append('<option value="">-- Select Department --</option>');
                    $('#addModalCourse').empty().append('<option value="">-- Select Course --</option>');
                    
                    if (schoolId) {
                        loadDepartmentsBySchool(schoolId, $('#addModalDepartment'));
                    }
                });
                
                // Department change handler for Add modal
                $('#addModalDepartment').change(function() {
                    var schoolId = $('#addModalSchool').val();
                    var deptId = $(this).val();
                    $('#addModalCourse').empty().append('<option value="">-- Select Course --</option>');
                    
                    if (schoolId && deptId) {
                        $('#courseHint').text('Loading courses...');
                        loadCoursesByFilters(schoolId, deptId, $('#addModalCourse'));
                        $('#courseHint').text('');
                    }
                });
                
                // School change handler for Edit modal
                $('#editModalSchool').change(function() {
                    var schoolId = $(this).val();
                    $('#editModalDepartment').empty().append('<option value="">-- Select Department --</option>');
                    $('#editModalCourse').empty().append('<option value="">-- Select Course --</option>');
                    
                    if (schoolId) {
                        loadDepartmentsBySchool(schoolId, $('#editModalDepartment'));
                    }
                });
                
                // Department change handler for Edit modal
                $('#editModalDepartment').change(function() {
                    var schoolId = $('#editModalSchool').val();
                    var deptId = $(this).val();
                    $('#editModalCourse').empty().append('<option value="">-- Select Course --</option>');
                    
                    if (schoolId && deptId) {
                        $('#editCourseHint').text('Loading courses...');
                        loadCoursesByFilters(schoolId, deptId, $('#editModalCourse'));
                        $('#editCourseHint').text('');
                    }
                });
                
                // Add Topic Form Submission
                $('#addTopicForm').submit(function(e) {
                    e.preventDefault();
                    
                    // Clear previous errors
                    $('.form-text.text-danger').text('');
                    
                    // Get form data
                    var topicName = $('#addTopicName').val().trim();
                    var courseId = $('#addModalCourse').val();
                    
                    // Basic validation
                    if (topicName === '') {
                        $('#addTopicNameError').text('Topic name is required!');
                        return;
                    }
                    
                    if (courseId === '') {
                        $('#addCourseError').text('Please select a course!');
                        return;
                    }
                    
                    $.ajax({
                        url: '<?php echo base_url("welcome/addtopic_ajax"); ?>',
                        method: 'POST',
                        data: {
                            topic_name: topicName,
                            course_id: courseId
                        },
                        dataType: 'json',
                        beforeSend: function() {
                            $('#addTopicForm button[type="submit"]').html('<i class="fas fa-spinner fa-spin"></i> Saving...').prop('disabled', true);
                        },
                        success: function(response) {
                            if (response.status === 'success') {
                                showAlert(response.message, 'success');
                                $('#addTopicModal').modal('hide');
                                $('#addTopicForm')[0].reset();
                                $('#addModalDepartment').empty().append('<option value="">-- Select Department --</option>');
                                $('#addModalCourse').empty().append('<option value="">-- Select Course --</option>');
                                refreshTable();
                            } else if (response.status === 'validation_error') {
                                var errors = response.message;
                                if (errors.includes('Topic Name')) {
                                    $('#addTopicNameError').text('Please enter a valid topic name');
                                }
                                if (errors.includes('Course')) {
                                    $('#addCourseError').text('Please select a course');
                                }
                            } else {
                                showAlert(response.message, 'error');
                            }
                        },
                        error: function() {
                            showAlert('An error occurred. Please try again!', 'error');
                        },
                        complete: function() {
                            $('#addTopicForm button[type="submit"]').html('<i class="fas fa-save"></i> Save Topic').prop('disabled', false);
                        }
                    });
                });
                
                // Edit Topic Button Click
                $(document).on('click', '.edit-topic', function() {
                    var topicId = $(this).data('id');
                    var topicName = $(this).data('name');
                    var courseId = $(this).data('crse_id');
                    
                    // Set basic values
                    $('#editTopicId').val(topicId);
                    $('#editTopicName').val(topicName);
                    
                    // Load topic details to get school and department
                    $.ajax({
                        url: '<?php echo base_url("welcome/gettopic_ajax"); ?>/' + topicId,
                        method: 'GET',
                        dataType: 'json',
                        beforeSend: function() {
                            $('#editTopicModal').modal('show');
                            $('.modal-body').addClass('loading');
                        },
                        success: function(response) {
                            if (response.status === 'success') {
                                var topic = response.topic;
                                
                                // Set school and department
                                if (topic.sch_id) {
                                    $('#editModalSchool').val(topic.sch_id);
                                    loadDepartmentsBySchool(topic.sch_id, $('#editModalDepartment'), topic.dept_id);
                                    
                                    // After departments load, load courses
                                    setTimeout(function() {
                                        loadCoursesByFilters(topic.sch_id, topic.dept_id, $('#editModalCourse'), topic.crse_id);
                                    }, 500);
                                }
                            }
                        },
                        error: function() {
                            showAlert('Error loading topic details!', 'error');
                        },
                        complete: function() {
                            $('.modal-body').removeClass('loading');
                        }
                    });
                });
                
                // Edit Topic Form Submission
                $('#editTopicForm').submit(function(e) {
                    e.preventDefault();
                    
                    // Clear previous errors
                    $('.form-text.text-danger').text('');
                    
                    // Get form data
                    var topicName = $('#editTopicName').val().trim();
                    var courseId = $('#editModalCourse').val();
                    
                    // Basic validation
                    if (topicName === '') {
                        $('#editTopicNameError').text('Topic name is required!');
                        return;
                    }
                    
                    if (courseId === '') {
                        $('#editCourseError').text('Please select a course!');
                        return;
                    }
                    
                    $.ajax({
                        url: '<?php echo base_url("welcome/updatetopic_ajax"); ?>',
                        method: 'POST',
                        data: {
                            topic_id: $('#editTopicId').val(),
                            topic_name: topicName,
                            course_id: courseId
                        },
                        dataType: 'json',
                        beforeSend: function() {
                            $('#editTopicForm button[type="submit"]').html('<i class="fas fa-spinner fa-spin"></i> Updating...').prop('disabled', true);
                        },
                        success: function(response) {
                            if (response.status === 'success') {
                                showAlert(response.message, 'success');
                                $('#editTopicModal').modal('hide');
                                refreshTable();
                            } else if (response.status === 'validation_error') {
                                var errors = response.message;
                                if (errors.includes('Topic Name')) {
                                    $('#editTopicNameError').text('Please enter a valid topic name');
                                }
                                if (errors.includes('Course')) {
                                    $('#editCourseError').text('Please select a course');
                                }
                            } else {
                                showAlert(response.message, 'error');
                            }
                        },
                        error: function() {
                            showAlert('An error occurred. Please try again!', 'error');
                        },
                        complete: function() {
                            $('#editTopicForm button[type="submit"]').html('<i class="fas fa-sync-alt"></i> Update Topic').prop('disabled', false);
                        }
                    });
                });
                
                // Delete Topic Button Click
                $(document).on('click', '.delete-topic', function() {
                    var topicId = $(this).data('id');
                    var topicName = $(this).data('name');
                    
                    $('#deleteTopicName').text(topicName);
                    $('#confirmDeleteTopicBtn').data('id', topicId);
                    $('#deleteTopicModal').modal('show');
                });
                
                // Confirm Delete Button Click
                $('#confirmDeleteTopicBtn').click(function() {
                    var topicId = $(this).data('id');
                    
                    $.ajax({
                        url: '<?php echo base_url("welcome/deletetopic_ajax"); ?>',
                        method: 'POST',
                        data: {id: topicId},
                        dataType: 'json',
                        beforeSend: function() {
                            $('#confirmDeleteTopicBtn').html('<i class="fas fa-spinner fa-spin"></i> Deleting...').prop('disabled', true);
                        },
                        success: function(response) {
                            if (response.status === 'success') {
                                showAlert(response.message, 'success');
                                $('#deleteTopicModal').modal('hide');
                                refreshTable();
                            } else {
                                showAlert(response.message, 'error');
                            }
                        },
                        error: function() {
                            showAlert('An error occurred. Please try again!', 'error');
                        },
                        complete: function() {
                            $('#confirmDeleteTopicBtn').html('<i class="fas fa-trash"></i> Delete Topic').prop('disabled', false);
                        }
                    });
                });
                
                // Reset forms when modals are hidden
                $('#addTopicModal').on('hidden.bs.modal', function() {
                    $('#addTopicForm')[0].reset();
                    $('#addModalDepartment').empty().append('<option value="">-- Select Department --</option>');
                    $('#addModalCourse').empty().append('<option value="">-- Select Course --</option>');
                    $('.form-text.text-danger').text('');
                    $('#courseHint').text('Select school and department to see courses');
                });
                
                $('#editTopicModal').on('hidden.bs.modal', function() {
                    $('.form-text.text-danger').text('');
                    $('#editCourseHint').text('Select school and department to see courses');
                });
                
                // Auto-hide alerts when clicked
                $(document).on('click', '.alert', function() {
                    $(this).alert('close');
                });
                
                // CSS for loading state
                $('<style>').text('.modal-body.loading { opacity: 0.5; pointer-events: none; }').appendTo('head');
            });
        </script>
    </body>
</html>