<!DOCTYPE html>
<html lang="en">
    <?php $this->load->view("load/header_main") ?>
    <head>
        <!-- Summernote WYSIWYG Editor -->
        <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
        <!-- MathJax for MathML support -->
        <script src="https://polyfill.io/v3/polyfill.min.js?features=es6"></script>
        <script id="MathJax-script" async src="https://cdn.jsdelivr.net/npm/mathjax@3/es5/tex-mml-chtml.js"></script>
        <style>
            .upload-area {
                border: 2px dashed #28a745;
                border-radius: 10px;
                padding: 30px;
                text-align: center;
                background-color: #f8fff9;
                cursor: pointer;
                transition: all 0.3s ease;
            }
            .upload-area:hover {
                background-color: #e9f7e9;
                border-color: #218838;
            }
            .upload-area.dragover {
                background-color: #d1f2d1;
                border-color: #1e7e34;
            }
            .file-info {
                margin-top: 15px;
                padding: 10px;
                background-color: #e9f7e9;
                border-radius: 5px;
                display: none;
            }
            .progress {
                height: 25px;
                margin-top: 15px;
                display: none;
            }
            .tab-content {
                padding: 20px 0;
            }
            .nav-tabs .nav-link.active {
                background-color: #fff;
                border-bottom-color: #fff;
            }
            .preview-content {
                max-height: 400px;
                overflow-y: auto;
                padding: 15px;
                border: 1px solid #dee2e6;
                border-radius: 5px;
                background-color: #f8f9fa;
            }
            .note-content img {
                max-width: 100%;
                height: auto;
            }
            .supported-formats {
                background-color: #f8f9fa;
                border-radius: 5px;
                padding: 15px;
                margin-top: 15px;
            }
            .format-badge {
                margin-right: 5px;
                margin-bottom: 5px;
            }
        </style>
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
                                <h1>Course Notes Management</h1>
                                <p class="text-muted">Total Notes: <?php echo isset($note_count) ? $note_count : 0; ?></p>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                                    <li class="breadcrumb-item active">Notes</li>
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
                                <h3 class="card-title"><i class="fas fa-filter"></i> Filter Notes</h3>
                                <div class="card-tools">
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="card-body">
                                <form id="filterForm" method="GET" action="<?php echo base_url('welcome/opennotes'); ?>">
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
                                        <!-- Add this after the Course filter in the filter section -->
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="filterTopic">Topic</label>
                                                <select class="form-control" id="filterTopic" name="topic_id">
                                                    <option value="">All Topics</option>
                                                    <?php if(isset($topics) && !empty($topics)): ?>
                                                        <?php foreach($topics as $topic): ?>
                                                            <option value="<?php echo $topic['id']; ?>" <?php echo isset($filter_topic_id) && $filter_topic_id == $topic['id'] ? 'selected' : ''; ?>>
                                                                <?php echo htmlspecialchars($topic['name']); ?>
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
                                                <a href="<?php echo base_url('welcome/opennotes'); ?>" class="btn btn-secondary">
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
                                <button type="button" class="btn btn-success" data-toggle="modal" data-target="#addNoteModal">
                                    <i class="fas fa-plus-circle"></i> Add New Note
                                </button>
                                <button type="button" class="btn btn-secondary" id="refreshTable">
                                    <i class="fas fa-sync-alt"></i> Refresh Table
                                </button>
                                <a href="<?php echo base_url('courses'); ?>" class="btn btn-info">
                                    <i class="fas fa-book"></i> Manage Courses
                                </a>
                            </div>
                        </div>
                        
                        <!-- Notes Table -->
                        <div class="row">
                            <div class="col-md-12"> 
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title">All Course Notes</h3>
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
                                            <table id="notesTable" class="table table-bordered table-striped">
                                                <thead>
                                                    <?php echo $dbhead ?>
                                                </thead>
                                                <tbody>
                                                    <?php echo isset($dbbody) ? $dbbody : '' ?>
                                                </tbody>
                                            </table>
                                        <?php else: ?>
                                            <div class="alert alert-info text-center">
                                                <h5><i class="icon fas fa-info"></i> No Notes Found!</h5>
                                                <?php if(isset($filter_school_id) || isset($filter_dept_id) || isset($filter_course_id)): ?>
                                                    No notes match your filter criteria. Try changing your filters or 
                                                    <a href="<?php echo base_url('welcome/opennotes'); ?>">clear filters</a>.
                                                <?php else: ?>
                                                    Click "Add New Note" button to add your first course note.
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
        
        <!-- Add Note Modal -->
        <div class="modal fade" id="addNoteModal" tabindex="-1" role="dialog" aria-labelledby="addNoteModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-success">
                        <h5 class="modal-title" id="addNoteModalLabel">
                            <i class="fas fa-sticky-note"></i> Add Course Note
                        </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    
                    <!-- Tabs for different upload methods -->
                    <ul class="nav nav-tabs" id="noteTab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="editor-tab" data-toggle="tab" href="#editor" role="tab">
                                <i class="fas fa-edit"></i> WYSIWYG Editor
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="file-tab" data-toggle="tab" href="#file" role="tab">
                                <i class="fas fa-file-upload"></i> Upload File
                            </a>
                        </li>
                    </ul>
                    
                    <div class="tab-content" id="noteTabContent">
                        <!-- Tab 1: WYSIWYG Editor -->
                        <!-- Tab 1: WYSIWYG Editor -->
<div class="tab-pane fade show active" id="editor" role="tabpanel">
    <form id="addNoteForm">
        <div class="modal-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="addNoteTitle">Note Title *</label>
                        <input type="text" class="form-control" id="addNoteTitle" name="title" required 
                               placeholder="e.g., Introduction to Programming, Chapter 1 Summary">
                        <small class="form-text text-danger" id="addTitleError"></small>
                    </div>
                </div>
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
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="addModalTopic">Topic (Optional)</label>
                        <select class="form-control" id="addModalTopic" name="topic_id">
                            <option value="">-- Select Topic --</option>
                        </select>
                        <small class="form-text text-muted">You can associate this note with a specific topic</small>
                    </div>
                </div>
            </div>
            
            <div class="form-group">
                <label for="addNoteContent">Note Content *</label>
                <textarea class="form-control summernote" id="addNoteContent" name="note_content" rows="10" required></textarea>
                <small class="form-text text-muted">
                    Use the toolbar for rich text formatting, images, tables, and MathML equations
                </small>
                <small class="form-text text-danger" id="addNoteContentError"></small>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-success">
                <i class="fas fa-save"></i> Save Note
            </button>
        </div>
    </form>
</div>
                        
                        <!-- Tab 2: File Upload -->
                        <!-- Tab 2: File Upload -->
<div class="tab-pane fade" id="file" role="tabpanel">
    <form id="addNoteFileForm" enctype="multipart/form-data">
        <div class="modal-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="fileNoteTitle">Note Title *</label>
                        <input type="text" class="form-control" id="fileNoteTitle" name="file_title" required 
                               placeholder="e.g., Uploaded from 'Chapter1.docx'">
                        <small class="form-text text-danger" id="fileTitleError"></small>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="fileModalSchool">School</label>
                        <select class="form-control" id="fileModalSchool" name="file_modal_school_id">
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
                        <label for="fileModalDepartment">Department</label>
                        <select class="form-control" id="fileModalDepartment" name="file_modal_dept_id">
                            <option value="">-- Select Department --</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="fileModalCourse">Course *</label>
                        <select class="form-control" id="fileModalCourse" name="file_course_id" required>
                            <option value="">-- Select Course --</option>
                        </select>
                        <small class="form-text text-danger" id="fileCourseError"></small>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="fileModalTopic">Topic (Optional)</label>
                        <select class="form-control" id="fileModalTopic" name="file_topic_id">
                            <option value="">-- Select Topic --</option>
                        </select>
                        <small class="form-text text-muted">You can associate this note with a specific topic</small>
                    </div>
                </div>
            </div>
            
            <div class="upload-area" id="noteUploadArea">
                <i class="fas fa-cloud-upload-alt fa-3x text-success mb-3"></i>
                <h5>Drag & Drop your file here</h5>
                <p class="text-muted">or click to browse</p>
                <p class="text-muted">Supported formats: TXT, DOC, DOCX</p>
                <input type="file" id="noteFile" name="note_file" accept=".txt,.doc,.docx" >
            </div>
            
            <div class="file-info" id="noteFileInfo">
                <div class="row">
                    <div class="col-md-8">
                        <strong>Selected File:</strong> <span id="noteFileName"></span><br>
                        <strong>File Size:</strong> <span id="noteFileSize"></span><br>
                        <strong>File Type:</strong> <span id="noteFileType"></span>
                    </div>
                    <div class="col-md-4 text-right">
                        <button type="button" class="btn btn-danger btn-sm" id="removeNoteFile">
                            <i class="fas fa-times"></i> Remove
                        </button>
                    </div>
                </div>
            </div>
            
            <div class="progress" id="noteUploadProgress">
                <div class="progress-bar progress-bar-striped progress-bar-animated bg-success" 
                     role="progressbar" style="width: 0%"></div>
            </div>
            
            <div class="alert alert-info mt-3">
                <h6><i class="fas fa-info-circle"></i> File Upload Information:</h6>
                <ul class="mb-0">
                    <li><strong>TXT files:</strong> Will be converted to HTML with paragraph formatting</li>
                    <li><strong>DOC files:</strong> Basic text extraction (requires antiword on server for best results)</li>
                    <li><strong>DOCX files:</strong> Text will be extracted and converted to HTML</li>
                    <li>Complex formatting (tables, images, etc.) may not be preserved perfectly</li>
                    <li>For best results with complex documents, use the WYSIWYG editor</li>
                </ul>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-success" id="uploadNoteFileBtn" disabled>
                <i class="fas fa-upload"></i> Upload & Convert
            </button>
        </div>
    </form>
</div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Edit Note Modal -->
        <!-- Edit Note Modal -->
<div class="modal fade" id="editNoteModal" tabindex="-1" role="dialog" aria-labelledby="editNoteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h5 class="modal-title" id="editNoteModalLabel">
                    <i class="fas fa-edit"></i> Edit Note
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="editNoteForm">
                <div class="modal-body">
                    <input type="hidden" id="editNoteId" name="note_id">
                    <input type="hidden" id="editNoteCourseId" name="course_id">
                    
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="editNoteTitle">Note Title *</label>
                                <input type="text" class="form-control" id="editNoteTitle" name="title" required>
                                <small class="form-text text-danger" id="editTitleError"></small>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="editNoteCourse">Course</label>
                                <input type="text" class="form-control" id="editNoteCourse" readonly>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="editNoteTopic">Topic (Optional)</label>
                                <select class="form-control" id="editNoteTopic" name="topic_id">
                                    <option value="">-- Select Topic --</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="editNoteContent">Note Content *</label>
                        <textarea class="form-control summernote" id="editNoteContent" name="note_content" rows="10" required></textarea>
                        <small class="form-text text-danger" id="editNoteContentError"></small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-warning">
                        <i class="fas fa-sync-alt"></i> Update Note
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
        
        <!-- Preview Note Modal -->
        <!-- Update the preview modal body -->
<div class="modal-body">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 id="previewNoteTitle"></h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <p><strong>Course:</strong> <span id="previewCourse"></span></p>
                        </div>
                        <div class="col-md-4">
                            <p><strong>Topic:</strong> <span id="previewTopic"></span></p>
                        </div>
                        <div class="col-md-4">
                            <p><strong>Type:</strong> <span id="previewType"></span></p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>School:</strong> <span id="previewSchool"></span></p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Department:</strong> <span id="previewDepartment"></span></p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <p><strong>Date Added:</strong> <span id="previewDate"></span></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row mt-3">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">Note Content</h6>
                </div>
                <div class="card-body">
                    <div class="preview-content" id="previewNoteContent">
                        <!-- Note content will be loaded here -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
        
        <!-- Delete Confirmation Modal -->
        <div class="modal fade" id="deleteNoteModal" tabindex="-1" role="dialog" aria-labelledby="deleteNoteModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-danger">
                        <h5 class="modal-title" id="deleteNoteModalLabel">
                            <i class="fas fa-exclamation-triangle"></i> Confirm Delete
                        </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>Are you sure you want to delete this note?</p>
                        <p><strong>Course: <span id="deleteNoteCourse"></span></strong></p>
                        <p class="text-danger">This action cannot be undone!</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-danger" id="confirmDeleteNoteBtn">
                            <i class="fas fa-trash"></i> Delete Note
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
                var noteTable = $('#notesTable').DataTable({
                    "responsive": true,
                    "lengthChange": true,
                    "autoWidth": false,
                    "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"],
                    "pageLength": 10,
                    "order": [[0, 'asc']],
                    "columnDefs": [
                        { "width": "5%", "targets": 0 },
                        { "width": "20%", "targets": 1 },
                        { "width": "15%", "targets": 2 },
                        { "width": "15%", "targets": 3 },
                        { "width": "20%", "targets": 4 },
                        { "width": "10%", "targets": 5 },
                        { "width": "10%", "targets": 6 }
                    ]
                });
                
                // Append buttons to container
                noteTable.buttons().container().appendTo('#notesTable_wrapper .col-md-6:eq(0)');
                
                // Initialize Summernote WYSIWYG Editors
                function initSummernote(selector, height = 400) {
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
                        ]
                    });
                }
                
                // Initialize Summernote editors
                $('#addNoteModal').on('shown.bs.modal', function() {
                    initSummernote('#addNoteContent');
                });
                
                $('#editNoteModal').on('shown.bs.modal', function() {
                    initSummernote('#editNoteContent');
                });
                
                // Hide preloader
                window.addEventListener('load', function() {
                    const preloader = document.getElementById('preloader-overlay');
                    if (preloader) {
                        preloader.style.display = 'none';
                    }
                });
                
                // Function to show alert
                function showAlert(message, type = 'success') {
                    var alertClass = type === 'success' ? 'alert-success' : (type === 'warning' ? 'alert-warning' : 'alert-danger');
                    var icon = type === 'success' ? 'fa-check' : (type === 'warning' ? 'fa-exclamation-triangle' : 'fa-ban');
                    var title = type === 'success' ? 'Success!' : (type === 'warning' ? 'Warning!' : 'Error!');
                    
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
                        url: '<?php echo base_url("welcome/getcoursesfordepartment"); ?>/' + schoolId + '/' + deptId,
                        method: 'GET',
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
                
                // School change handler for Add modal (Editor tab)
                $('#addModalSchool').change(function() {
                    var schoolId = $(this).val();
                    $('#addModalDepartment').empty().append('<option value="">-- Select Department --</option>');
                    $('#addModalCourse').empty().append('<option value="">-- Select Course --</option>');
                    
                    if (schoolId) {
                        loadDepartmentsBySchool(schoolId, $('#addModalDepartment'));
                    }
                });
                
                // Department change handler for Add modal (Editor tab)
                $('#addModalDepartment').change(function() {
                    var schoolId = $('#addModalSchool').val();
                    var deptId = $(this).val();
                    $('#addModalCourse').empty().append('<option value="">-- Select Course --</option>');
                    
                    if (schoolId && deptId) {
                        loadCoursesByFilters(schoolId, deptId, $('#addModalCourse'));
                    }
                });
                
                // School change handler for Add modal (File tab)
                $('#fileModalSchool').change(function() {
                    var schoolId = $(this).val();
                    $('#fileModalDepartment').empty().append('<option value="">-- Select Department --</option>');
                    $('#fileModalCourse').empty().append('<option value="">-- Select Course --</option>');
                    
                    if (schoolId) {
                        loadDepartmentsBySchool(schoolId, $('#fileModalDepartment'));
                    }
                });
                
                // Department change handler for Add modal (File tab)
                $('#fileModalDepartment').change(function() {
                    var schoolId = $('#fileModalSchool').val();
                    var deptId = $(this).val();
                    $('#fileModalCourse').empty().append('<option value="">-- Select Course --</option>');
                    
                    if (schoolId && deptId) {
                        loadCoursesByFilters(schoolId, deptId, $('#fileModalCourse'));
                    }
                });
                
                // File upload handling for Note File tab
                $('#noteUploadArea').click(function() {
                    $('#noteFile').click();
                });
                
                $('#noteFile').change(function(e) {
                    handleNoteFileSelect(e.target.files[0]);
                });
                
                // Drag and drop functionality for Note File tab
                $('#noteUploadArea').on('dragover', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    $(this).addClass('dragover');
                });
                
                $('#noteUploadArea').on('dragleave', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    $(this).removeClass('dragover');
                });
                
                $('#noteUploadArea').on('drop', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    $(this).removeClass('dragover');
                    
                    var files = e.originalEvent.dataTransfer.files;
                    if (files.length > 0) {
                        handleNoteFileSelect(files[0]);
                    }
                });
                
                function handleNoteFileSelect(file) {
                    if (!file) return;
                    
                    // Check file type
                    var validExtensions = ['txt', 'pdf', 'doc', 'docx', 'md', 'html', 'htm'];
                    var fileExtension = file.name.split('.').pop().toLowerCase();
                    
                    if (!validExtensions.includes(fileExtension)) {
                        showAlert('Please upload a supported file format: TXT, PDF, DOC, DOCX, MD, HTML', 'error');
                        return;
                    }
                    
                    // Check file size (10MB max)
                    if (file.size > 10 * 1024 * 1024) {
                        showAlert('File size must be less than 10MB.', 'error');
                        return;
                    }
                    
                    // Display file info
                    $('#noteFileName').text(file.name);
                    $('#noteFileSize').text(formatFileSize(file.size));
                    $('#noteFileType').text(fileExtension.toUpperCase());
                    $('#noteFileInfo').show();
                    
                    // Enable upload button
                    $('#uploadNoteFileBtn').prop('disabled', false);
                }
                
                function formatFileSize(bytes) {
                    if (bytes === 0) return '0 Bytes';
                    var k = 1024;
                    var sizes = ['Bytes', 'KB', 'MB', 'GB'];
                    var i = Math.floor(Math.log(bytes) / Math.log(k));
                    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
                }
                
                // Remove file for Note File tab
                $('#removeNoteFile').click(function() {
                    $('#noteFile').val('');
                    $('#noteFileInfo').hide();
                    $('#uploadNoteFileBtn').prop('disabled', true);
                    $('#noteFileName, #noteFileSize, #noteFileType').text('');
                });
                
                // Add Note Form Submission (Editor tab)
                $('#addNoteForm').submit(function(e) {
                    e.preventDefault();
                    
                    // Clear previous errors
                    $('.form-text.text-danger').text('');
                    
                    // Get form data
                    var courseId = $('#addModalCourse').val();
                    var noteContent = $('#addNoteContent').summernote('code');
                    
                    // Basic validation
                    if (!courseId) {
                        $('#addCourseError').text('Please select a course!');
                        return;
                    }
                    
                    if (!noteContent || noteContent.trim() === '' || noteContent === '<p><br></p>') {
                        $('#addNoteContentError').text('Note content is required!');
                        return;
                    }
                    
                    $.ajax({
                        url: '<?php echo base_url("welcome/addnote_ajax"); ?>',
                        method: 'POST',
                        data: {
                            course_id: courseId,
                            note_content: noteContent
                        },
                        dataType: 'json',
                        beforeSend: function() {
                            $('#addNoteForm button[type="submit"]').html('<i class="fas fa-spinner fa-spin"></i> Saving...').prop('disabled', true);
                        },
                        success: function(response) {
                            if (response.status === 'success' || response.status === 'warning') {
                                showAlert(response.message, response.status);
                                if (response.status === 'success') {
                                    $('#addNoteModal').modal('hide');
                                    $('#addNoteForm')[0].reset();
                                    $('#addNoteContent').summernote('reset');
                                    $('#addModalDepartment, #addModalCourse').empty().append('<option value="">-- Select --</option>');
                                    refreshTable();
                                }
                            } else if (response.status === 'validation_error') {
                                var errors = response.message;
                                if (errors.includes('Course')) {
                                    $('#addCourseError').text('Please select a course');
                                }
                                if (errors.includes('Note Content')) {
                                    $('#addNoteContentError').text('Please enter valid note content');
                                }
                            } else {
                                showAlert(response.message, 'error');
                            }
                        },
                        error: function() {
                            showAlert('An error occurred. Please try again!', 'error');
                        },
                        complete: function() {
                            $('#addNoteForm button[type="submit"]').html('<i class="fas fa-save"></i> Save Note').prop('disabled', false);
                        }
                    });
                });
                
                // Add Note File Form Submission (File tab)
                $('#addNoteFileForm').submit(function(e) {
                    e.preventDefault();
                    
                    // Clear previous errors
                    $('.form-text.text-danger').text('');
                    
                    // Get form data
                    var courseId = $('#fileModalCourse').val();
                    var fileInput = $('#noteFile')[0];
                    
                    // Basic validation
                    if (!courseId) {
                        $('#fileCourseError').text('Please select a course!');
                        return;
                    }
                    
                    if (!fileInput.files[0]) {
                        showAlert('Please select a file to upload', 'error');
                        return;
                    }
                    
                    // Create FormData object
                    var formData = new FormData(this);
                    
                    // Show progress bar
                    $('#noteUploadProgress').show();
                    
                    $.ajax({
                        url: '<?php echo base_url("welcome/addnotefile_ajax"); ?>',
                        method: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        dataType: 'json',
                        xhr: function() {
                            var xhr = new XMLHttpRequest();
                            xhr.upload.addEventListener('progress', function(e) {
                                if (e.lengthComputable) {
                                    var percent = Math.round((e.loaded / e.total) * 100);
                                    $('#noteUploadProgress .progress-bar').css('width', percent + '%');
                                }
                            }, false);
                            return xhr;
                        },
                        beforeSend: function() {
                            $('#uploadNoteFileBtn').html('<i class="fas fa-spinner fa-spin"></i> Uploading...').prop('disabled', true);
                        },
                        success: function(response) {
                            $('#noteUploadProgress').hide();
                            
                            if (response.status === 'success') {
                                showAlert(response.message, 'success');
                                $('#addNoteModal').modal('hide');
                                $('#addNoteFileForm')[0].reset();
                                $('#noteFileInfo').hide();
                                $('#noteFileName, #noteFileSize, #noteFileType').text('');
                                $('#fileModalDepartment, #fileModalCourse').empty().append('<option value="">-- Select --</option>');
                                refreshTable();
                            } else if (response.status === 'validation_error') {
                                var errors = response.message;
                                if (errors.includes('Course')) {
                                    $('#fileCourseError').text('Please select a course');
                                }
                            } else {
                                showAlert(response.message, 'error');
                            }
                        },
                        error: function() {
                            $('#noteUploadProgress').hide();
                            showAlert('Error uploading file. Please try again!', 'error');
                        },
                        complete: function() {
                            $('#uploadNoteFileBtn').html('<i class="fas fa-upload"></i> Upload & Convert').prop('disabled', false);
                        }
                    });
                });
                
                // Edit Note Button Click
                $(document).on('click', '.edit-note', function() {
                    var noteId = $(this).data('id');
                    var courseId = $(this).data('course_id');
                    
                    // Load note details
                    $.ajax({
                        url: '<?php echo base_url("welcome/getnote_ajax"); ?>/' + noteId,
                        method: 'GET',
                        dataType: 'json',
                        beforeSend: function() {
                            $('#editNoteModal').modal('show');
                            $('.modal-body').addClass('loading');
                        },
                        success: function(response) {
                            if (response.status === 'success') {
                                var note = response.note;
                                
                                // Set values
                                $('#editNoteId').val(note.id);
                                $('#editNoteCourseId').val(note.crse_id);
                                $('#editNoteCourse').val(note.course_code + ' - ' + note.course_title);
                                $('#editNoteContent').summernote('code', note.data);
                            }
                        },
                        error: function() {
                            showAlert('Error loading note details!', 'error');
                        },
                        complete: function() {
                            $('.modal-body').removeClass('loading');
                        }
                    });
                });
                
                // Preview Note Button Click
                $(document).on('click', '.preview-note', function() {
                    var noteId = $(this).data('id');
                    
                    // Load note details for preview
                    $.ajax({
                        url: '<?php echo base_url("welcome/previewnote_ajax"); ?>/' + noteId,
                        method: 'GET',
                        dataType: 'json',
                        beforeSend: function() {
                            $('#previewNoteModal').modal('show');
                            $('#previewNoteContent').html('<div class="text-center"><i class="fas fa-spinner fa-spin fa-2x"></i><p>Loading preview...</p></div>');
                        },
                        success: function(response) {
                            if (response.status === 'success') {
                                var note = response.note;
                                
                                // Set course information
                                $('#previewCourse').text(note.course_code + ' - ' + note.course_title);
                                $('#previewSchool').text(note.school_name || 'N/A');
                                $('#previewDepartment').text(note.dept_name || 'N/A');
                                
                                // Set note content
                                $('#previewNoteContent').html(note.data);
                                
                                // Process MathJax after loading content
                                if (window.MathJax) {
                                    MathJax.typesetPromise([document.getElementById('previewNoteContent')]);
                                }
                            } else {
                                $('#previewNoteContent').html('<div class="alert alert-danger">Error loading note preview!</div>');
                            }
                        },
                        error: function() {
                            $('#previewNoteContent').html('<div class="alert alert-danger">Error loading note preview!</div>');
                        }
                    });
                });
                
                // Print Note Button
                $('#printNoteBtn').click(function() {
                    var printContent = document.getElementById('previewNoteContent').innerHTML;
                    var originalContent = document.body.innerHTML;
                    
                    document.body.innerHTML = printContent;
                    window.print();
                    document.body.innerHTML = originalContent;
                    location.reload(); // Reload to restore functionality
                });
                
                // Edit Note Form Submission
                $('#editNoteForm').submit(function(e) {
                    e.preventDefault();
                    
                    // Clear previous errors
                    $('.form-text.text-danger').text('');
                    
                    // Get Summernote content
                    var noteContent = $('#editNoteContent').summernote('code');
                    
                    // Basic validation
                    if (!noteContent || noteContent.trim() === '' || noteContent === '<p><br></p>') {
                        $('#editNoteContentError').text('Note content is required!');
                        return;
                    }
                    
                    $.ajax({
                        url: '<?php echo base_url("welcome/updatenote_ajax"); ?>',
                        method: 'POST',
                        data: {
                            note_id: $('#editNoteId').val(),
                            note_content: noteContent
                        },
                        dataType: 'json',
                        beforeSend: function() {
                            $('#editNoteForm button[type="submit"]').html('<i class="fas fa-spinner fa-spin"></i> Updating...').prop('disabled', true);
                        },
                        success: function(response) {
                            if (response.status === 'success') {
                                showAlert(response.message, 'success');
                                $('#editNoteModal').modal('hide');
                                refreshTable();
                            } else if (response.status === 'validation_error') {
                                var errors = response.message;
                                if (errors.includes('Note Content')) {
                                    $('#editNoteContentError').text('Please enter valid note content');
                                }
                            } else {
                                showAlert(response.message, 'error');
                            }
                        },
                        error: function() {
                            showAlert('An error occurred. Please try again!', 'error');
                        },
                        complete: function() {
                            $('#editNoteForm button[type="submit"]').html('<i class="fas fa-sync-alt"></i> Update Note').prop('disabled', false);
                        }
                    });
                });
                
                // Delete Note Button Click
                $(document).on('click', '.delete-note', function() {
                    var noteId = $(this).data('id');
                    var courseCode = $(this).data('course');
                    
                    $('#deleteNoteCourse').text(courseCode);
                    $('#confirmDeleteNoteBtn').data('id', noteId);
                    $('#deleteNoteModal').modal('show');
                });
                
                // Confirm Delete Button Click
                $('#confirmDeleteNoteBtn').click(function() {
                    var noteId = $(this).data('id');
                    
                    $.ajax({
                        url: '<?php echo base_url("welcome/deletenote_ajax"); ?>',
                        method: 'POST',
                        data: {id: noteId},
                        dataType: 'json',
                        beforeSend: function() {
                            $('#confirmDeleteNoteBtn').html('<i class="fas fa-spinner fa-spin"></i> Deleting...').prop('disabled', true);
                        },
                        success: function(response) {
                            if (response.status === 'success') {
                                showAlert(response.message, 'success');
                                $('#deleteNoteModal').modal('hide');
                                refreshTable();
                            } else {
                                showAlert(response.message, 'error');
                            }
                        },
                        error: function() {
                            showAlert('An error occurred. Please try again!', 'error');
                        },
                        complete: function() {
                            $('#confirmDeleteNoteBtn').html('<i class="fas fa-trash"></i> Delete Note').prop('disabled', false);
                        }
                    });
                });
                
                // Reset forms when modals are hidden
                $('#addNoteModal').on('hidden.bs.modal', function() {
                    $('#addNoteForm, #addNoteFileForm')[0].reset();
                    $('.summernote').summernote('reset');
                    $('.form-text.text-danger').text('');
                    $('#addModalDepartment, #addModalCourse, #fileModalDepartment, #fileModalCourse').empty().append('<option value="">-- Select --</option>');
                    $('#noteFileInfo').hide();
                    $('#uploadNoteFileBtn').prop('disabled', true);
                    
                    // Switch back to Editor tab
                    $('#editor-tab').tab('show');
                });
                
                $('#editNoteModal').on('hidden.bs.modal', function() {
                    $('.form-text.text-danger').text('');
                });
                
                // Auto-hide alerts when clicked
                $(document).on('click', '.alert', function() {
                    $(this).alert('close');
                });
                
                // CSS for loading state
                $('<style>').text('.modal-body.loading { opacity: 0.5; pointer-events: none; }').appendTo('head');
            });

            // Add this function to load topics when a course is selected
function loadTopicsByCourse(courseId, targetSelect, selectedTopicId = '') {
    if (!courseId) {
        targetSelect.empty().append('<option value="">-- Select Topic --</option>');
        return;
    }
    
    $.ajax({
        url: '<?php echo base_url("welcome/gettopicsforcourse_ajax"); ?>/' + courseId,
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

// Add course change handlers for topics
$('#addModalCourse').change(function() {
    var courseId = $(this).val();
    loadTopicsByCourse(courseId, $('#addModalTopic'));
});

$('#fileModalCourse').change(function() {
    var courseId = $(this).val();
    loadTopicsByCourse(courseId, $('#fileModalTopic'));
});

// Update the filter form to load topics when course changes
$('#filterCourse').change(function() {
    var courseId = $(this).val();
    loadTopicsByCourse(courseId, $('#filterTopic'));
});

// Update Edit Note loading
$(document).on('click', '.edit-note', function() {
    var noteId = $(this).data('id');
    
    // Load note details
    $.ajax({
        url: '<?php echo base_url("welcome/getnote_ajax"); ?>/' + noteId,
        method: 'GET',
        dataType: 'json',
        beforeSend: function() {
            $('#editNoteModal').modal('show');
            $('.modal-body').addClass('loading');
        },
        success: function(response) {
            if (response.status === 'success') {
                var note = response.note;
                
                // Set values
                $('#editNoteId').val(note.id);
                $('#editNoteCourseId').val(note.crse_id);
                $('#editNoteTitle').val(note.title);
                $('#editNoteCourse').val(note.course_code + ' - ' + note.course_title);
                $('#editNoteContent').summernote('code', note.data);
                
                // Load topics for this course and select the current one
                loadTopicsByCourse(note.crse_id, $('#editNoteTopic'), note.topic_id);
            }
        },
        error: function() {
            showAlert('Error loading note details!', 'error');
        },
        complete: function() {
            $('.modal-body').removeClass('loading');
        }
    });
});

// Update Preview Note loading
$(document).on('click', '.preview-note', function() {
    var noteId = $(this).data('id');
    
    // Load note details for preview
    $.ajax({
        url: '<?php echo base_url("welcome/previewnote_ajax"); ?>/' + noteId,
        method: 'GET',
        dataType: 'json',
        beforeSend: function() {
            $('#previewNoteModal').modal('show');
            $('#previewNoteContent').html('<div class="text-center"><i class="fas fa-spinner fa-spin fa-2x"></i><p>Loading preview...</p></div>');
        },
        success: function(response) {
            if (response.status === 'success') {
                var note = response.note;
                
                // Set note information
                $('#previewNoteTitle').text(note.title);
                $('#previewCourse').text(note.course_code + ' - ' + note.course_title);
                $('#previewTopic').text(note.topic_name || '<span class="text-muted">N/A</span>');
                $('#previewSchool').text(note.school_name || 'N/A');
                $('#previewDepartment').text(note.dept_name || 'N/A');
                $('#previewDate').text(note.date_added ? new Date(note.date_added).toLocaleString() : 'N/A');
                
                // Set type badge
                var typeText = '';
                switch(note.file_type) {
                    case 'txt': typeText = '<span class="badge bg-secondary">TXT</span>'; break;
                    case 'doc': typeText = '<span class="badge bg-primary">DOC</span>'; break;
                    case 'docx': typeText = '<span class="badge bg-primary">DOCX</span>'; break;
                    case 'pdf': typeText = '<span class="badge bg-danger">PDF</span>'; break;
                    case 'editor': typeText = '<span class="badge bg-success">Editor</span>'; break;
                    default: typeText = '<span class="badge bg-info">' + note.file_type.toUpperCase() + '</span>';
                }
                $('#previewType').html(typeText);
                
                // Set note content
                $('#previewNoteContent').html(note.data);
                
                // Process MathJax after loading content
                if (window.MathJax) {
                    MathJax.typesetPromise([document.getElementById('previewNoteContent')]);
                }
            } else {
                $('#previewNoteContent').html('<div class="alert alert-danger">Error loading note preview!</div>');
            }
        },
        error: function() {
            $('#previewNoteContent').html('<div class="alert alert-danger">Error loading note preview!</div>');
        }
    });
});

// Update Add Note Form Submission
$('#addNoteForm').submit(function(e) {
    e.preventDefault();
    
    // Clear previous errors
    $('.form-text.text-danger').text('');
    
    // Get form data
    var title = $('#addNoteTitle').val();
    var courseId = $('#addModalCourse').val();
    var topicId = $('#addModalTopic').val();
    var noteContent = $('#addNoteContent').summernote('code');
    
    // Basic validation
    if (!title || title.trim() === '') {
        $('#addTitleError').text('Note title is required!');
        return;
    }
    
    if (!courseId) {
        $('#addCourseError').text('Please select a course!');
        return;
    }
    
    if (!noteContent || noteContent.trim() === '' || noteContent === '<p><br></p>') {
        $('#addNoteContentError').text('Note content is required!');
        return;
    }
    
    $.ajax({
        url: '<?php echo base_url("welcome/addnote_ajax"); ?>',
        method: 'POST',
        data: {
            title: title,
            course_id: courseId,
            topic_id: topicId,
            note_content: noteContent
        },
        dataType: 'json',
        beforeSend: function() {
            $('#addNoteForm button[type="submit"]').html('<i class="fas fa-spinner fa-spin"></i> Saving...').prop('disabled', true);
        },
        success: function(response) {
            if (response.status === 'success' || response.status === 'warning') {
                showAlert(response.message, response.status);
                if (response.status === 'success') {
                    $('#addNoteModal').modal('hide');
                    $('#addNoteForm')[0].reset();
                    $('#addNoteContent').summernote('reset');
                    $('#addModalDepartment, #addModalCourse, #addModalTopic').empty().append('<option value="">-- Select --</option>');
                    refreshTable();
                }
            } else if (response.status === 'validation_error') {
                var errors = response.message;
                if (errors.includes('Title')) {
                    $('#addTitleError').text('Please enter a valid title (3-255 characters)');
                }
                if (errors.includes('Course')) {
                    $('#addCourseError').text('Please select a course');
                }
                if (errors.includes('Note Content')) {
                    $('#addNoteContentError').text('Please enter valid note content');
                }
            } else {
                showAlert(response.message, 'error');
            }
        },
        error: function() {
            showAlert('An error occurred. Please try again!', 'error');
        },
        complete: function() {
            $('#addNoteForm button[type="submit"]').html('<i class="fas fa-save"></i> Save Note').prop('disabled', false);
        }
    });
});

// Update Add Note File Form Submission
$('#addNoteFileForm').submit(function(e) {
    e.preventDefault();
    
    // Clear previous errors
    $('.form-text.text-danger').text('');
    
    // Get form data
    var title = $('#fileNoteTitle').val();
    var courseId = $('#fileModalCourse').val();
    var topicId = $('#fileModalTopic').val();
    var fileInput = $('#noteFile')[0];
    
    // Basic validation
    if (!title || title.trim() === '') {
        $('#fileTitleError').text('Note title is required!');
        return;
    }
    
    if (!courseId) {
        $('#fileCourseError').text('Please select a course!');
        return;
    }
    
    if (!fileInput.files[0]) {
        showAlert('Please select a file to upload', 'error');
        return;
    }
    
    // Create FormData object
    var formData = new FormData(this);
    formData.append('file_title', title);
    formData.append('file_course_id', courseId);
    formData.append('file_topic_id', topicId);
    
    // Show progress bar
    $('#noteUploadProgress').show();
    
    $.ajax({
        url: '<?php echo base_url("welcome/addnotefile_ajax"); ?>',
        method: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        dataType: 'json',
        xhr: function() {
            var xhr = new XMLHttpRequest();
            xhr.upload.addEventListener('progress', function(e) {
                if (e.lengthComputable) {
                    var percent = Math.round((e.loaded / e.total) * 100);
                    $('#noteUploadProgress .progress-bar').css('width', percent + '%');
                }
            }, false);
            return xhr;
        },
        beforeSend: function() {
            $('#uploadNoteFileBtn').html('<i class="fas fa-spinner fa-spin"></i> Uploading...').prop('disabled', true);
        },
        success: function(response) {
            $('#noteUploadProgress').hide();
            
            if (response.status === 'success') {
                showAlert(response.message, 'success');
                $('#addNoteModal').modal('hide');
                $('#addNoteFileForm')[0].reset();
                $('#noteFileInfo').hide();
                $('#noteFileName, #noteFileSize, #noteFileType').text('');
                $('#fileModalDepartment, #fileModalCourse, #fileModalTopic').empty().append('<option value="">-- Select --</option>');
                refreshTable();
            } else if (response.status === 'validation_error') {
                var errors = response.message;
                if (errors.includes('Title')) {
                    $('#fileTitleError').text('Please enter a valid title (3-255 characters)');
                }
                if (errors.includes('Course')) {
                    $('#fileCourseError').text('Please select a course');
                }
            } else {
                showAlert(response.message, 'error');
            }
        },
        error: function() {
            $('#noteUploadProgress').hide();
            showAlert('Error uploading file. Please try again!', 'error');
        },
        complete: function() {
            $('#uploadNoteFileBtn').html('<i class="fas fa-upload"></i> Upload & Convert').prop('disabled', false);
        }
    });
});

// Update Edit Note Form Submission
$('#editNoteForm').submit(function(e) {
    e.preventDefault();
    
    // Clear previous errors
    $('.form-text.text-danger').text('');
    
    // Get form data
    var title = $('#editNoteTitle').val();
    var topicId = $('#editNoteTopic').val();
    var noteContent = $('#editNoteContent').summernote('code');
    
    // Basic validation
    if (!title || title.trim() === '') {
        $('#editTitleError').text('Note title is required!');
        return;
    }
    
    if (!noteContent || noteContent.trim() === '' || noteContent === '<p><br></p>') {
        $('#editNoteContentError').text('Note content is required!');
        return;
    }
    
    $.ajax({
        url: '<?php echo base_url("welcome/updatenote_ajax"); ?>',
        method: 'POST',
        data: {
            note_id: $('#editNoteId').val(),
            title: title,
            topic_id: topicId,
            note_content: noteContent
        },
        dataType: 'json',
        beforeSend: function() {
            $('#editNoteForm button[type="submit"]').html('<i class="fas fa-spinner fa-spin"></i> Updating...').prop('disabled', true);
        },
        success: function(response) {
            if (response.status === 'success') {
                showAlert(response.message, 'success');
                $('#editNoteModal').modal('hide');
                refreshTable();
            } else if (response.status === 'validation_error') {
                var errors = response.message;
                if (errors.includes('Title')) {
                    $('#editTitleError').text('Please enter a valid title (3-255 characters)');
                }
                if (errors.includes('Note Content')) {
                    $('#editNoteContentError').text('Please enter valid note content');
                }
            } else {
                showAlert(response.message, 'error');
            }
        },
        error: function() {
            showAlert('An error occurred. Please try again!', 'error');
        },
        complete: function() {
            $('#editNoteForm button[type="submit"]').html('<i class="fas fa-sync-alt"></i> Update Note').prop('disabled', false);
        }
    });
});
        </script>
    </body>
</html>