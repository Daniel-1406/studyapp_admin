<!DOCTYPE html>
<html lang="en">
    <?php $this->load->view("load/header_main") ?>
    <head>
        <!-- Summernote WYSIWYG Editor -->
        <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
        <!-- MathJax for MathML support -->
        <script src="https://polyfill.io/v3/polyfill.min.js?features=es6"></script>
        <script id="MathJax-script" async src="https://cdn.jsdelivr.net/npm/mathjax@3/es5/tex-mml-chtml.js"></script>
    </head>
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
                                <h1>Objective Questions Management</h1>
                                <p class="text-muted">Total Questions: <?php echo isset($question_count) ? $question_count : 0; ?></p>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                                    <li class="breadcrumb-item active">Questions</li>
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
                                <h3 class="card-title"><i class="fas fa-filter"></i> Filter Questions</h3>
                                <div class="card-tools">
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="card-body">
                                <form id="filterForm" method="GET" action="<?php echo base_url('welcome/openquestions'); ?>">
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
                                            <div class="form-group">
                                                <label for="filterTopic">Topic</label>
                                                <select class="form-control" id="filterTopic" name="topic_id">
                                                    <option value="">All Topics</option>
                                                    <?php if(isset($topics) && !empty($topics)): ?>
                                                        <?php foreach($topics as $id => $name): ?>
                                                            <option value="<?php echo $id; ?>" <?php echo isset($filter_topic_id) && $filter_topic_id == $id ? 'selected' : ''; ?>>
                                                                <?php echo htmlspecialchars($name); ?>
                                                            </option>
                                                        <?php endforeach; ?>
                                                    <?php endif; ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12 text-right">
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fas fa-filter"></i> Apply Filter
                                            </button>
                                            <a href="<?php echo base_url('welcome/openquestions'); ?>" class="btn btn-secondary">
                                                <i class="fas fa-times"></i> Clear
                                            </a>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        
                        <!-- Action Buttons -->
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <button type="button" class="btn btn-success" data-toggle="modal" data-target="#addQuestionModal">
                                    <i class="fas fa-plus-circle"></i> Add New Question
                                </button>
                                <button type="button" class="btn btn-secondary" id="refreshTable">
                                    <i class="fas fa-sync-alt"></i> Refresh Table
                                </button>
                                <a href="<?php echo base_url('topics'); ?>" class="btn btn-info">
                                    <i class="fas fa-list-alt"></i> Manage Topics
                                </a>
                            </div>
                        </div>
                        
                        <!-- Questions Table -->
                        <div class="row">
                            <div class="col-md-12"> 
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title">All Questions</h3>
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
                                            <table id="questionsTable" class="table table-bordered table-striped">
                                                <thead>
                                                    <?php echo $dbhead ?>
                                                </thead>
                                                <tbody>
                                                    <?php echo isset($dbbody) ? $dbbody : '' ?>
                                                </tbody>
                                            </table>
                                        <?php else: ?>
                                            <div class="alert alert-info text-center">
                                                <h5><i class="icon fas fa-info"></i> No Questions Found!</h5>
                                                <?php if(isset($filter_school_id) || isset($filter_dept_id) || isset($filter_course_id) || isset($filter_topic_id)): ?>
                                                    No questions match your filter criteria. Try changing your filters or 
                                                    <a href="<?php echo base_url('welcome/openquestions'); ?>">clear filters</a>.
                                                <?php else: ?>
                                                    Click "Add New Question" button to add your first question.
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
        
        <!-- Add Question Modal -->
        <div class="modal fade" id="addQuestionModal" tabindex="-1" role="dialog" aria-labelledby="addQuestionModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-success">
                        <h5 class="modal-title" id="addQuestionModalLabel">
                            <i class="fas fa-question-circle"></i> Add New Objective Question
                        </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form id="addQuestionForm">
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-6">
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
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="addModalDepartment">Department</label>
                                        <select class="form-control" id="addModalDepartment" name="modal_dept_id">
                                            <option value="">-- Select Department --</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="addModalCourse">Course *</label>
                                        <select class="form-control" id="addModalCourse" name="course_id" required>
                                            <option value="">-- Select Course --</option>
                                        </select>
                                        <small class="form-text text-danger" id="addCourseError"></small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="addModalTopic">Topic *</label>
                                        <select class="form-control" id="addModalTopic" name="topic_id" required>
                                            <option value="">-- Select Topic --</option>
                                        </select>
                                        <small class="form-text text-danger" id="addTopicError"></small>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="addInstruction">Instruction (Optional)</label>
                                <textarea class="form-control summernote" id="addInstruction" name="instruction" rows="2"></textarea>
                                <small class="form-text text-muted">Use this for special instructions or hints</small>
                            </div>
                            
                            <div class="form-group">
                                <label for="addQuestion">Question *</label>
                                <textarea class="form-control summernote" id="addQuestion" name="qst" rows="3" required></textarea>
                                <small class="form-text text-muted">Use the toolbar for formatting, images, and MathML equations</small>
                                <small class="form-text text-danger" id="addQuestionError"></small>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="addOptionA">Option A *</label>
                                        <textarea class="form-control summernote-option" id="addOptionA" name="option_a" rows="2" required></textarea>
                                        <small class="form-text text-danger" id="addOptionAError"></small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="addOptionB">Option B *</label>
                                        <textarea class="form-control summernote-option" id="addOptionB" name="option_b" rows="2" required></textarea>
                                        <small class="form-text text-danger" id="addOptionBError"></small>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="addOptionC">Option C *</label>
                                        <textarea class="form-control summernote-option" id="addOptionC" name="option_c" rows="2" required></textarea>
                                        <small class="form-text text-danger" id="addOptionCError"></small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="addOptionD">Option D *</label>
                                        <textarea class="form-control summernote-option" id="addOptionD" name="option_d" rows="2" required></textarea>
                                        <small class="form-text text-danger" id="addOptionDError"></small>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="addCorrectAnswer">Correct Answer *</label>
                                <select class="form-control" id="addCorrectAnswer" name="ans" required>
                                    <option value="">-- Select Correct Answer --</option>
                                    <?php if(isset($answer_options) && !empty($answer_options)): ?>
                                        <?php foreach($answer_options as $value => $label): ?>
                                            <option value="<?php echo $value; ?>"><?php echo htmlspecialchars($label); ?></option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                                <small class="form-text text-danger" id="addAnswerError"></small>
                            </div>
                            
                            <div class="form-group">
                                <label for="addExplanation">Explanation (Optional)</label>
                                <textarea class="form-control summernote" id="addExplanation" name="explanation" rows="3"></textarea>
                                <small class="form-text text-muted">Use this to explain why the answer is correct</small>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-save"></i> Save Question
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Edit Question Modal -->
        <div class="modal fade" id="editQuestionModal" tabindex="-1" role="dialog" aria-labelledby="editQuestionModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-primary">
                        <h5 class="modal-title" id="editQuestionModalLabel">
                            <i class="fas fa-edit"></i> Edit Question
                        </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form id="editQuestionForm">
                        <div class="modal-body">
                            <input type="hidden" id="editQuestionId" name="question_id">
                            <div class="row">
                                <div class="col-md-6">
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
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="editModalDepartment">Department</label>
                                        <select class="form-control" id="editModalDepartment" name="modal_dept_id">
                                            <option value="">-- Select Department --</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="editModalCourse">Course *</label>
                                        <select class="form-control" id="editModalCourse" name="course_id" required>
                                            <option value="">-- Select Course --</option>
                                        </select>
                                        <small class="form-text text-danger" id="editCourseError"></small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="editModalTopic">Topic *</label>
                                        <select class="form-control" id="editModalTopic" name="topic_id" required>
                                            <option value="">-- Select Topic --</option>
                                        </select>
                                        <small class="form-text text-danger" id="editTopicError"></small>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="editInstruction">Instruction (Optional)</label>
                                <textarea class="form-control summernote" id="editInstruction" name="instruction" rows="2"></textarea>
                            </div>
                            
                            <div class="form-group">
                                <label for="editQuestion">Question *</label>
                                <textarea class="form-control summernote" id="editQuestion" name="qst" rows="3" required></textarea>
                                <small class="form-text text-danger" id="editQuestionError"></small>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="editOptionA">Option A *</label>
                                        <textarea class="form-control summernote-option" id="editOptionA" name="option_a" rows="2" required></textarea>
                                        <small class="form-text text-danger" id="editOptionAError"></small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="editOptionB">Option B *</label>
                                        <textarea class="form-control summernote-option" id="editOptionB" name="option_b" rows="2" required></textarea>
                                        <small class="form-text text-danger" id="editOptionBError"></small>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="editOptionC">Option C *</label>
                                        <textarea class="form-control summernote-option" id="editOptionC" name="option_c" rows="2" required></textarea>
                                        <small class="form-text text-danger" id="editOptionCError"></small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="editOptionD">Option D *</label>
                                        <textarea class="form-control summernote-option" id="editOptionD" name="option_d" rows="2" required></textarea>
                                        <small class="form-text text-danger" id="editOptionDError"></small>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="editCorrectAnswer">Correct Answer *</label>
                                <select class="form-control" id="editCorrectAnswer" name="ans" required>
                                    <option value="">-- Select Correct Answer --</option>
                                    <?php if(isset($answer_options) && !empty($answer_options)): ?>
                                        <?php foreach($answer_options as $value => $label): ?>
                                            <option value="<?php echo $value; ?>"><?php echo htmlspecialchars($label); ?></option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                                <small class="form-text text-danger" id="editAnswerError"></small>
                            </div>
                            
                            <div class="form-group">
                                <label for="editExplanation">Explanation (Optional)</label>
                                <textarea class="form-control summernote" id="editExplanation" name="explanation" rows="3"></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-sync-alt"></i> Update Question
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Preview Question Modal -->
        <div class="modal fade" id="previewQuestionModal" tabindex="-1" role="dialog" aria-labelledby="previewQuestionModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-info">
                        <h5 class="modal-title" id="previewQuestionModalLabel">
                            <i class="fas fa-eye"></i> Question Preview
                        </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" id="previewContent">
                        <!-- Preview content will be loaded here -->
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Delete Confirmation Modal -->
        <div class="modal fade" id="deleteQuestionModal" tabindex="-1" role="dialog" aria-labelledby="deleteQuestionModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-danger">
                        <h5 class="modal-title" id="deleteQuestionModalLabel">
                            <i class="fas fa-exclamation-triangle"></i> Confirm Delete
                        </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>Are you sure you want to delete this question?</p>
                        <p><strong>Question Preview: <span id="deleteQuestionPreview"></span></strong></p>
                        <p class="text-danger">This action cannot be undone!</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-danger" id="confirmDeleteQuestionBtn">
                            <i class="fas fa-trash"></i> Delete Question
                        </button>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- JavaScript -->
        <!-- Summernote WYSIWYG Editor -->
        <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
        
        <script>
            $(document).ready(function() {
                // Initialize DataTable
                var questionTable = $('#questionsTable').DataTable({
                    "responsive": true,
                    "lengthChange": true,
                    "autoWidth": false,
                    "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"],
                    "pageLength": 10,
                    "order": [[0, 'asc']],
                    "columnDefs": [
                        { "width": "5%", "targets": 0 },
                        { "width": "35%", "targets": 1 },
                        { "width": "15%", "targets": 2 },
                        { "width": "10%", "targets": 3 },
                        { "width": "10%", "targets": 4 },
                        { "width": "10%", "targets": 5 },
                        { "width": "10%", "targets": 6 }
                    ]
                });
                
                // Append buttons to container
                questionTable.buttons().container().appendTo('#questionsTable_wrapper .col-md-6:eq(0)');
                
                // Initialize Summernote WYSIWYG Editors
                function initSummernote(selector, height = 150) {
                    $(selector).summernote({
                        height: height,
                        toolbar: [
                            ['style', ['style']],
                            ['font', ['bold', 'italic', 'underline', 'clear']],
                            ['fontname', ['fontname']],
                            ['fontsize', ['fontsize']],
                            ['color', ['color']],
                            ['para', ['ul', 'ol', 'paragraph']],
                            ['height', ['height']],
                            ['table', ['table']],
                            ['insert', ['link', 'picture', 'video', 'hr']],
                            ['math', ['math']],
                            ['view', ['fullscreen', 'codeview', 'help']]
                        ],
                        // Math plugin configuration
                        math: {
                            htmlTags: ['script', 'noscript', 'style', 'textarea', 'pre', 'code'],
                            MathML: true
                        }
                    });
                }
                
                // Initialize main editors
                initSummernote('.summernote', 200);
                initSummernote('.summernote-option', 120);
                
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
                                    var courseText = course.code + ' - ' + course.title;
                                    targetSelect.append('<option value="' + course.id + '" ' + selected + '>' + courseText + '</option>');
                                });
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
                
                // Function to load topics based on course
                function loadTopicsByCourse(courseId, targetSelect, selectedTopicId = '') {
                    if (!courseId) {
                        targetSelect.empty().append('<option value="">-- Select Topic --</option>');
                        return;
                    }
                    
                    $.ajax({
                        url: '<?php echo base_url("welcome/gettopicsbycourse_ajax"); ?>/' + courseId,
                        method: 'GET',
                        dataType: 'json',
                        beforeSend: function() {
                            targetSelect.prop('disabled', true);
                        },
                        success: function(response) {
                            if (response.status === 'success') {
                                targetSelect.empty().append('<option value="">-- Select Topic --</option>');
                                
                                $.each(response.topics, function(index, topic) {
                                    var selected = (topic.id == selectedTopicId) ? 'selected' : '';
                                    targetSelect.append('<option value="' + topic.id + '" ' + selected + '>' + topic.name + '</option>');
                                });
                            }
                        },
                        error: function() {
                            showAlert('Error loading topics!', 'error');
                        },
                        complete: function() {
                            targetSelect.prop('disabled', false);
                        }
                    });
                }
                
                // School change handler for Add modal
                $('#addModalSchool').change(function() {
                    var schoolId = $(this).val();
                    $('#addModalDepartment').empty().append('<option value="">-- Select Department --</option>');
                    $('#addModalCourse').empty().append('<option value="">-- Select Course --</option>');
                    $('#addModalTopic').empty().append('<option value="">-- Select Topic --</option>');
                    
                    if (schoolId) {
                        loadDepartmentsBySchool(schoolId, $('#addModalDepartment'));
                    }
                });
                
                // Department change handler for Add modal
                $('#addModalDepartment').change(function() {
                    var schoolId = $('#addModalSchool').val();
                    var deptId = $(this).val();
                    $('#addModalCourse').empty().append('<option value="">-- Select Course --</option>');
                    $('#addModalTopic').empty().append('<option value="">-- Select Topic --</option>');
                    
                    if (schoolId && deptId) {
                        loadCoursesByFilters(schoolId, deptId, $('#addModalCourse'));
                    }
                });
                
                // Course change handler for Add modal
                $('#addModalCourse').change(function() {
                    var courseId = $(this).val();
                    $('#addModalTopic').empty().append('<option value="">-- Select Topic --</option>');
                    
                    if (courseId) {
                        loadTopicsByCourse(courseId, $('#addModalTopic'));
                    }
                });
                
                // School change handler for Edit modal
                $('#editModalSchool').change(function() {
                    var schoolId = $(this).val();
                    $('#editModalDepartment').empty().append('<option value="">-- Select Department --</option>');
                    $('#editModalCourse').empty().append('<option value="">-- Select Course --</option>');
                    $('#editModalTopic').empty().append('<option value="">-- Select Topic --</option>');
                    
                    if (schoolId) {
                        loadDepartmentsBySchool(schoolId, $('#editModalDepartment'));
                    }
                });
                
                // Department change handler for Edit modal
                $('#editModalDepartment').change(function() {
                    var schoolId = $('#editModalSchool').val();
                    var deptId = $(this).val();
                    $('#editModalCourse').empty().append('<option value="">-- Select Course --</option>');
                    $('#editModalTopic').empty().append('<option value="">-- Select Topic --</option>');
                    
                    if (schoolId && deptId) {
                        loadCoursesByFilters(schoolId, deptId, $('#editModalCourse'));
                    }
                });
                
                // Course change handler for Edit modal
                $('#editModalCourse').change(function() {
                    var courseId = $(this).val();
                    $('#editModalTopic').empty().append('<option value="">-- Select Topic --</option>');
                    
                    if (courseId) {
                        loadTopicsByCourse(courseId, $('#editModalTopic'));
                    }
                });
                
                // Add Question Form Submission
                $('#addQuestionForm').submit(function(e) {
                    e.preventDefault();
                    
                    // Clear previous errors
                    $('.form-text.text-danger').text('');
                    
                    // Get form data
                    var courseId = $('#addModalCourse').val();
                    var topicId = $('#addModalTopic').val();
                    var answer = $('#addCorrectAnswer').val();
                    
                    // Get Summernote content
                    var questionContent = $('#addQuestion').summernote('code');
                    var optionAContent = $('#addOptionA').summernote('code');
                    var optionBContent = $('#addOptionB').summernote('code');
                    var optionCContent = $('#addOptionC').summernote('code');
                    var optionDContent = $('#addOptionD').summernote('code');
                    var instructionContent = $('#addInstruction').summernote('code');
                    var explanationContent = $('#addExplanation').summernote('code');
                    
                    // Basic validation
                    if (!courseId) {
                        $('#addCourseError').text('Please select a course!');
                        return;
                    }
                    
                    if (!topicId) {
                        $('#addTopicError').text('Please select a topic!');
                        return;
                    }
                    
                    if (!questionContent || questionContent.trim() === '' || questionContent === '<p><br></p>') {
                        $('#addQuestionError').text('Question content is required!');
                        return;
                    }
                    
                    if (!optionAContent || optionAContent.trim() === '' || optionAContent === '<p><br></p>') {
                        $('#addOptionAError').text('Option A is required!');
                        return;
                    }
                    
                    if (!optionBContent || optionBContent.trim() === '' || optionBContent === '<p><br></p>') {
                        $('#addOptionBError').text('Option B is required!');
                        return;
                    }
                    
                    if (!optionCContent || optionCContent.trim() === '' || optionCContent === '<p><br></p>') {
                        $('#addOptionCError').text('Option C is required!');
                        return;
                    }
                    
                    if (!optionDContent || optionDContent.trim() === '' || optionDContent === '<p><br></p>') {
                        $('#addOptionDError').text('Option D is required!');
                        return;
                    }
                    
                    if (!answer) {
                        $('#addAnswerError').text('Please select the correct answer!');
                        return;
                    }
                    
                    // Prepare form data
                    var formData = new FormData();
                    formData.append('course_id', courseId);
                    formData.append('topic_id', topicId);
                    formData.append('qst', questionContent);
                    formData.append('option_a', optionAContent);
                    formData.append('option_b', optionBContent);
                    formData.append('option_c', optionCContent);
                    formData.append('option_d', optionDContent);
                    formData.append('ans', answer);
                    formData.append('instruction', instructionContent);
                    formData.append('explanation', explanationContent);
                    
                    $.ajax({
                        url: '<?php echo base_url("welcome/addquestion_ajax"); ?>',
                        method: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        dataType: 'json',
                        beforeSend: function() {
                            $('#addQuestionForm button[type="submit"]').html('<i class="fas fa-spinner fa-spin"></i> Saving...').prop('disabled', true);
                        },
                        success: function(response) {
                            if (response.status === 'success') {
                                showAlert(response.message, 'success');
                                $('#addQuestionModal').modal('hide');
                                $('#addQuestionForm')[0].reset();
                                // Reset Summernote editors
                                $('.summernote').summernote('reset');
                                $('.summernote-option').summernote('reset');
                                // Reset dropdowns
                                $('#addModalDepartment, #addModalCourse, #addModalTopic').empty().append('<option value="">-- Select --</option>');
                                refreshTable();
                            } else if (response.status === 'validation_error') {
                                var errors = response.message;
                                if (errors.includes('Question')) {
                                    $('#addQuestionError').text('Please enter a valid question');
                                }
                                if (errors.includes('Course')) {
                                    $('#addCourseError').text('Please select a course');
                                }
                                if (errors.includes('Topic')) {
                                    $('#addTopicError').text('Please select a topic');
                                }
                                if (errors.includes('Option A')) {
                                    $('#addOptionAError').text('Option A is required');
                                }
                                if (errors.includes('Option B')) {
                                    $('#addOptionBError').text('Option B is required');
                                }
                                if (errors.includes('Option C')) {
                                    $('#addOptionCError').text('Option C is required');
                                }
                                if (errors.includes('Option D')) {
                                    $('#addOptionDError').text('Option D is required');
                                }
                                if (errors.includes('Correct Answer')) {
                                    $('#addAnswerError').text('Please select the correct answer');
                                }
                            } else {
                                showAlert(response.message, 'error');
                            }
                        },
                        error: function() {
                            showAlert('An error occurred. Please try again!', 'error');
                        },
                        complete: function() {
                            $('#addQuestionForm button[type="submit"]').html('<i class="fas fa-save"></i> Save Question').prop('disabled', false);
                        }
                    });
                });
                
                // Edit Question Button Click
                $(document).on('click', '.edit-question', function() {
                    var questionId = $(this).data('id');
                    
                    // Load question details
                    $.ajax({
                        url: '<?php echo base_url("welcome/getquestion_ajax"); ?>/' + questionId,
                        method: 'GET',
                        dataType: 'json',
                        beforeSend: function() {
                            $('#editQuestionModal').modal('show');
                            $('.modal-body').addClass('loading');
                        },
                        success: function(response) {
                            if (response.status === 'success') {
                                var question = response.question;
                                
                                // Set basic values
                                $('#editQuestionId').val(question.id);
                                $('#editQuestion').summernote('code', question.qst);
                                $('#editOptionA').summernote('code', question.option_a);
                                $('#editOptionB').summernote('code', question.option_b);
                                $('#editOptionC').summernote('code', question.option_c);
                                $('#editOptionD').summernote('code', question.option_d);
                                $('#editCorrectAnswer').val(question.ans);
                                $('#editInstruction').summernote('code', question.instruction || '');
                                $('#editExplanation').summernote('code', question.explanation || '');
                                
                                // Set school, department, course, and topic
                                if (question.sch_id) {
                                    $('#editModalSchool').val(question.sch_id);
                                    loadDepartmentsBySchool(question.sch_id, $('#editModalDepartment'), question.dept_id);
                                    
                                    // After departments load, load courses and topics
                                    setTimeout(function() {
                                        if (question.dept_id) {
                                            loadCoursesByFilters(question.sch_id, question.dept_id, $('#editModalCourse'), question.crse_id);
                                            
                                            setTimeout(function() {
                                                if (question.crse_id) {
                                                    loadTopicsByCourse(question.crse_id, $('#editModalTopic'), question.topic_id);
                                                }
                                            }, 500);
                                        }
                                    }, 500);
                                }
                            }
                        },
                        error: function() {
                            showAlert('Error loading question details!', 'error');
                        },
                        complete: function() {
                            $('.modal-body').removeClass('loading');
                        }
                    });
                });
                
                // Preview Question Button Click
                $(document).on('click', '.preview-question', function() {
                    var questionId = $(this).data('id');
                    
                    // Load question details for preview
                    $.ajax({
                        url: '<?php echo base_url("welcome/previewquestion_ajax"); ?>/' + questionId,
                        method: 'GET',
                        dataType: 'json',
                        beforeSend: function() {
                            $('#previewQuestionModal').modal('show');
                            $('#previewContent').html('<div class="text-center"><i class="fas fa-spinner fa-spin fa-2x"></i><p>Loading preview...</p></div>');
                        },
                        success: function(response) {
                            if (response.status === 'success') {
                                var question = response.question;
                                
                                // Create preview HTML
                                var previewHtml = `
                                    <div class="question-preview">
                                        <div class="card">
                                            <div class="card-header bg-light">
                                                <h6 class="mb-0">Question Preview</h6>
                                                <small class="text-muted">Course: ${question.course_code} - ${question.course_title}</small><br>
                                                <small class="text-muted">Topic: ${question.topic_name || 'N/A'}</small>
                                            </div>
                                            <div class="card-body">
                                                ${question.instruction ? `<div class="alert alert-info"><strong>Instruction:</strong><br>${question.instruction}</div>` : ''}
                                                
                                                <div class="question-content mb-4">
                                                    <strong>Question:</strong>
                                                    <div class="border p-3 bg-white rounded">${question.qst}</div>
                                                </div>
                                                
                                                <div class="options mb-4">
                                                    <strong>Options:</strong>
                                                    <div class="list-group">
                                                        <div class="list-group-item ${question.ans === 'a' ? 'list-group-item-success' : ''}">
                                                            <strong>A:</strong> ${question.option_a}
                                                        </div>
                                                        <div class="list-group-item ${question.ans === 'b' ? 'list-group-item-success' : ''}">
                                                            <strong>B:</strong> ${question.option_b}
                                                        </div>
                                                        <div class="list-group-item ${question.ans === 'c' ? 'list-group-item-success' : ''}">
                                                            <strong>C:</strong> ${question.option_c}
                                                        </div>
                                                        <div class="list-group-item ${question.ans === 'd' ? 'list-group-item-success' : ''}">
                                                            <strong>D:</strong> ${question.option_d}
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                ${question.explanation ? `
                                                <div class="explanation mt-4">
                                                    <strong>Explanation:</strong>
                                                    <div class="border p-3 bg-light rounded">${question.explanation}</div>
                                                </div>
                                                ` : ''}
                                                
                                                <div class="answer-info mt-3">
                                                    <span class="badge bg-primary">Correct Answer: ${question.ans.toUpperCase()}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                `;
                                
                                $('#previewContent').html(previewHtml);
                                
                                // Process MathJax after loading content
                                if (window.MathJax) {
                                    MathJax.typesetPromise([document.getElementById('previewContent')]);
                                }
                            } else {
                                $('#previewContent').html('<div class="alert alert-danger">Error loading question preview!</div>');
                            }
                        },
                        error: function() {
                            $('#previewContent').html('<div class="alert alert-danger">Error loading question preview!</div>');
                        }
                    });
                });
                
                // Edit Question Form Submission
                $('#editQuestionForm').submit(function(e) {
                    e.preventDefault();
                    
                    // Clear previous errors
                    $('.form-text.text-danger').text('');
                    
                    // Get form data
                    var courseId = $('#editModalCourse').val();
                    var topicId = $('#editModalTopic').val();
                    var answer = $('#editCorrectAnswer').val();
                    
                    // Get Summernote content
                    var questionContent = $('#editQuestion').summernote('code');
                    var optionAContent = $('#editOptionA').summernote('code');
                    var optionBContent = $('#editOptionB').summernote('code');
                    var optionCContent = $('#editOptionC').summernote('code');
                    var optionDContent = $('#editOptionD').summernote('code');
                    var instructionContent = $('#editInstruction').summernote('code');
                    var explanationContent = $('#editExplanation').summernote('code');
                    
                    // Basic validation
                    if (!courseId) {
                        $('#editCourseError').text('Please select a course!');
                        return;
                    }
                    
                    if (!topicId) {
                        $('#editTopicError').text('Please select a topic!');
                        return;
                    }
                    
                    if (!questionContent || questionContent.trim() === '' || questionContent === '<p><br></p>') {
                        $('#editQuestionError').text('Question content is required!');
                        return;
                    }
                    
                    if (!optionAContent || optionAContent.trim() === '' || optionAContent === '<p><br></p>') {
                        $('#editOptionAError').text('Option A is required!');
                        return;
                    }
                    
                    if (!optionBContent || optionBContent.trim() === '' || optionBContent === '<p><br></p>') {
                        $('#editOptionBError').text('Option B is required!');
                        return;
                    }
                    
                    if (!optionCContent || optionCContent.trim() === '' || optionCContent === '<p><br></p>') {
                        $('#editOptionCError').text('Option C is required!');
                        return;
                    }
                    
                    if (!optionDContent || optionDContent.trim() === '' || optionDContent === '<p><br></p>') {
                        $('#editOptionDError').text('Option D is required!');
                        return;
                    }
                    
                    if (!answer) {
                        $('#editAnswerError').text('Please select the correct answer!');
                        return;
                    }
                    
                    // Prepare form data
                    var formData = new FormData();
                    formData.append('question_id', $('#editQuestionId').val());
                    formData.append('course_id', courseId);
                    formData.append('topic_id', topicId);
                    formData.append('qst', questionContent);
                    formData.append('option_a', optionAContent);
                    formData.append('option_b', optionBContent);
                    formData.append('option_c', optionCContent);
                    formData.append('option_d', optionDContent);
                    formData.append('ans', answer);
                    formData.append('instruction', instructionContent);
                    formData.append('explanation', explanationContent);
                    
                    $.ajax({
                        url: '<?php echo base_url("welcome/updatequestion_ajax"); ?>',
                        method: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        dataType: 'json',
                        beforeSend: function() {
                            $('#editQuestionForm button[type="submit"]').html('<i class="fas fa-spinner fa-spin"></i> Updating...').prop('disabled', true);
                        },
                        success: function(response) {
                            if (response.status === 'success') {
                                showAlert(response.message, 'success');
                                $('#editQuestionModal').modal('hide');
                                refreshTable();
                            } else if (response.status === 'validation_error') {
                                var errors = response.message;
                                if (errors.includes('Question')) {
                                    $('#editQuestionError').text('Please enter a valid question');
                                }
                                if (errors.includes('Course')) {
                                    $('#editCourseError').text('Please select a course');
                                }
                                if (errors.includes('Topic')) {
                                    $('#editTopicError').text('Please select a topic');
                                }
                                if (errors.includes('Option A')) {
                                    $('#editOptionAError').text('Option A is required');
                                }
                                if (errors.includes('Option B')) {
                                    $('#editOptionBError').text('Option B is required');
                                }
                                if (errors.includes('Option C')) {
                                    $('#editOptionCError').text('Option C is required');
                                }
                                if (errors.includes('Option D')) {
                                    $('#editOptionDError').text('Option D is required');
                                }
                                if (errors.includes('Correct Answer')) {
                                    $('#editAnswerError').text('Please select the correct answer');
                                }
                            } else {
                                showAlert(response.message, 'error');
                            }
                        },
                        error: function() {
                            showAlert('An error occurred. Please try again!', 'error');
                        },
                        complete: function() {
                            $('#editQuestionForm button[type="submit"]').html('<i class="fas fa-sync-alt"></i> Update Question').prop('disabled', false);
                        }
                    });
                });
                
                // Delete Question Button Click
                $(document).on('click', '.delete-question', function() {
                    var questionId = $(this).data('id');
                    var questionPreview = $(this).data('preview');
                    
                    $('#deleteQuestionPreview').text(questionPreview);
                    $('#confirmDeleteQuestionBtn').data('id', questionId);
                    $('#deleteQuestionModal').modal('show');
                });
                
                // Confirm Delete Button Click
                $('#confirmDeleteQuestionBtn').click(function() {
                    var questionId = $(this).data('id');
                    
                    $.ajax({
                        url: '<?php echo base_url("welcome/deletequestion_ajax"); ?>',
                        method: 'POST',
                        data: {id: questionId},
                        dataType: 'json',
                        beforeSend: function() {
                            $('#confirmDeleteQuestionBtn').html('<i class="fas fa-spinner fa-spin"></i> Deleting...').prop('disabled', true);
                        },
                        success: function(response) {
                            if (response.status === 'success') {
                                showAlert(response.message, 'success');
                                $('#deleteQuestionModal').modal('hide');
                                refreshTable();
                            } else {
                                showAlert(response.message, 'error');
                            }
                        },
                        error: function() {
                            showAlert('An error occurred. Please try again!', 'error');
                        },
                        complete: function() {
                            $('#confirmDeleteQuestionBtn').html('<i class="fas fa-trash"></i> Delete Question').prop('disabled', false);
                        }
                    });
                });
                
                // Reset forms when modals are hidden
                $('#addQuestionModal').on('hidden.bs.modal', function() {
                    $('#addQuestionForm')[0].reset();
                    $('.summernote').summernote('reset');
                    $('.summernote-option').summernote('reset');
                    $('#addModalDepartment, #addModalCourse, #addModalTopic').empty().append('<option value="">-- Select --</option>');
                    $('.form-text.text-danger').text('');
                });
                
                $('#editQuestionModal').on('hidden.bs.modal', function() {
                    $('.form-text.text-danger').text('');
                });
                
                // Auto-hide alerts when clicked
                $(document).on('click', '.alert', function() {
                    $(this).alert('close');
                });
                
                // CSS for loading state
                $('<style>').text('.modal-body.loading { opacity: 0.5; pointer-events: none; }').appendTo('head');
                
                // Initialize MathJax for MathML support in Summernote
                window.mathPlugin = {
                    math: function (context) {
                        var ui = $.summernote.ui;
                        var button = ui.button({
                            contents: '<i class="fa fa-square-root-alt"/> Math',
                            tooltip: 'Insert Math Equation',
                            click: function () {
                                context.invoke('editor.saveRange');
                                var mathml = prompt('Enter MathML equation (or LaTeX):', '\\frac{1}{2}');
                                if (mathml) {
                                    context.invoke('editor.restoreRange');
                                    context.invoke('editor.pasteHTML', '<math>' + mathml + '</math>');
                                }
                            }
                        });
                        return button.render();
                    }
                };
                
                // Add Math button to Summernote toolbar
                $.extend($.summernote.plugins, {
                    'math': window.mathPlugin
                });
            });
        </script>
    </body>
</html>