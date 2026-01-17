<!DOCTYPE html>
<html lang="en">
    <?php $this->load->view("load/header_main") ?>
    <head>
        <!-- Dropzone CSS (optional) -->
        <link href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/dropzone.min.css" rel="stylesheet">
        <style>
            .upload-area {
                border: 2px dashed #007bff;
                border-radius: 10px;
                padding: 40px;
                text-align: center;
                background-color: #f8f9fa;
                cursor: pointer;
                transition: all 0.3s ease;
            }
            .upload-area:hover {
                background-color: #e9ecef;
                border-color: #0056b3;
            }
            .upload-area.dragover {
                background-color: #d1ecf1;
                border-color: #0c5460;
            }
            .file-info {
                margin-top: 15px;
                padding: 10px;
                background-color: #e9ecef;
                border-radius: 5px;
                display: none;
            }
            .progress {
                height: 25px;
                margin-top: 15px;
                display: none;
            }
            .validation-results {
                max-height: 300px;
                overflow-y: auto;
                display: none;
            }
            .sample-table {
                font-size: 0.9rem;
            }
            .sample-table th {
                background-color: #343a40;
                color: white;
            }
            .step-indicator {
                margin-bottom: 30px;
            }
            .step {
                display: inline-block;
                width: 30px;
                height: 30px;
                line-height: 30px;
                text-align: center;
                border-radius: 50%;
                background-color: #6c757d;
                color: white;
                margin-right: 10px;
            }
            .step.active {
                background-color: #007bff;
            }
            .step.completed {
                background-color: #28a745;
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
                                <h1>Batch Questions Upload</h1>
                                <p class="text-muted">Upload multiple questions at once using CSV or Excel files</p>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                                    <li class="breadcrumb-item"><a href="<?php echo base_url('questions'); ?>">Questions</a></li>
                                    <li class="breadcrumb-item active">Batch Upload</li>
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
                        
                        <!-- Step Indicator -->
                        <div class="step-indicator text-center">
                            <span class="step active" id="step1">1</span>
                            <span>Select Course & Topic</span>
                            <span class="mx-3">→</span>
                            <span class="step" id="step2">2</span>
                            <span>Upload File</span>
                            <span class="mx-3">→</span>
                            <span class="step" id="step3">3</span>
                            <span>Review & Submit</span>
                        </div>
                        
                        <!-- Main Form -->
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card card-primary">
                                    <div class="card-header">
                                        <h3 class="card-title">
                                            <i class="fas fa-upload"></i> Batch Upload Questions
                                        </h3>
                                    </div>
                                    <form id="batchUploadForm" enctype="multipart/form-data">
                                        <div class="card-body">
                                            <!-- Step 1: Course & Topic Selection -->
                                            <div id="step1-content">
                                                <h5><i class="fas fa-book"></i> Step 1: Select Course and Topic</h5>
                                                <p class="text-muted">Select the course and topic where these questions will be added.</p>
                                                
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="uploadSchool">School *</label>
                                                            <select class="form-control" id="uploadSchool" name="school_id" required>
                                                                <option value="">-- Select School --</option>
                                                                <?php if(isset($schools) && !empty($schools)): ?>
                                                                    <?php foreach($schools as $id => $name): ?>
                                                                        <option value="<?php echo $id; ?>"><?php echo htmlspecialchars($name); ?></option>
                                                                    <?php endforeach; ?>
                                                                <?php endif; ?>
                                                            </select>
                                                            <small class="form-text text-danger" id="schoolError"></small>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="uploadDepartment">Department *</label>
                                                            <select class="form-control" id="uploadDepartment" name="dept_id" required disabled>
                                                                <option value="">-- Select Department --</option>
                                                            </select>
                                                            <small class="form-text text-danger" id="departmentError"></small>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="uploadCourse">Course *</label>
                                                            <select class="form-control" id="uploadCourse" name="course_id" required disabled>
                                                                <option value="">-- Select Course --</option>
                                                            </select>
                                                            <small class="form-text text-danger" id="courseError"></small>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="uploadTopic">Topic *</label>
                                                            <select class="form-control" id="uploadTopic" name="topic_id" required disabled>
                                                                <option value="">-- Select Topic --</option>
                                                            </select>
                                                            <small class="form-text text-danger" id="topicError"></small>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Template Files</label>
                                                            <div>
                                                                <a href="<?php echo base_url('welcome/downloadtemplate/csv'); ?>" class="btn btn-outline-success btn-sm">
                                                                    <i class="fas fa-file-csv"></i> Download CSV Template
                                                                </a>
                                                                <a href="<?php echo base_url('welcome/downloadtemplate/excel'); ?>" class="btn btn-outline-primary btn-sm">
                                                                    <i class="fas fa-file-excel"></i> Download Excel Template
                                                                </a>
                                                            </div>
                                                            <small class="form-text text-muted">Download template files to see the required format</small>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="text-right">
                                                    <button type="button" class="btn btn-primary" id="nextToStep2">
                                                        Next <i class="fas fa-arrow-right"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            
                                            <!-- Step 2: File Upload -->
                                            <div id="step2-content" style="display: none;">
                                                <h5><i class="fas fa-cloud-upload-alt"></i> Step 2: Upload Questions File</h5>
                                                <p class="text-muted">Upload your CSV or Excel file containing questions.</p>
                                                
                                               <!-- In the upload section, update the file input and supported formats -->
                                                <div class="upload-area" id="uploadArea">
                                                    <i class="fas fa-cloud-upload-alt fa-3x text-primary mb-3"></i>
                                                    <h5>Drag & Drop your file here</h5>
                                                    <p class="text-muted">or click to browse</p>
                                                    <p class="text-muted">Supported formats: Text (TXT), Word (DOC, DOCX)</p>
                                                    <p class="text-muted">Max file size: 5MB</p>
                                                    <p class="text-muted"><strong>Format:</strong> Each question per line, fields separated by double commas (,,), lines ending with pipe (|)</p>
                                                </div>
                                                
                                               <!-- Update the file input to only accept the specified formats -->
                                                <input type="file" id="questionsFile" name="questions_file" accept=".txt,.doc,.docx" style="position: absolute; left: -9999px;">

                                                
<!-- Update the format example section -->
<div class="row mt-4">
    <div class="col-md-12">
        <h6><i class="fas fa-table"></i> Required File Format:</h6>
        
        <div class="alert alert-info">
            <h6><i class="fas fa-info-circle"></i> Format Specification:</h6>
            <p>Each question must be on a single line with the following format:</p>
            <code>Question text,,Option A,,Option B,,Option C,,Option D,,Correct Answer,,Instruction,,Explanation|</code>
            <p class="mt-2"><strong>Note:</strong> Use double commas (,,) as separators and pipe (|) at the end of each line.</p>
        </div>
        
        <div class="table-responsive">
            <table class="table table-bordered sample-table">
                <thead>
                    <tr>
                        <th>Format Example</th>
                        <th>Explanation</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><code>What is 2 + 2?,,3,,4,,5,,6,,B,,Choose the correct answer.,,2 + 2 equals 4, which is option B.|</code></td>
                        <td>Complete question with all fields</td>
                    </tr>
                    <tr>
                        <td><code>Capital of France?,,London,,Berlin,,Paris,,Madrid,,C,,,,Paris is capital.|</code></td>
                        <td>Question with empty instruction field (notice double commas)</td>
                    </tr>
                    <tr>
                        <td><code>Chemical symbol for water?,,H,,HO,,H2O,,OH,,C,,,,H2O is the chemical formula for water.|</code></td>
                        <td>Another example with explanation</td>
                    </tr>
                </tbody>
            </table>
        </div>
        
        <div class="alert alert-warning">
            <h6><i class="fas fa-exclamation-triangle"></i> Important Notes:</h6>
            <ol>
                <li>All fields except Instruction and Explanation are required</li>
                <li>Correct Answer must be A, B, C, or D (case insensitive)</li>
                <li>Use exactly <strong>double commas (,,)</strong> to separate fields</li>
                <li>End each line with a <strong>pipe character (|)</strong></li>
                <li>Each question must be on its own line</li>
            </ol>
        </div>
        
        <div class="text-center mt-3">
            <a href="<?php echo base_url('welcome/downloadtexttemplate'); ?>" class="btn btn-outline-primary">
                <i class="fas fa-download"></i> Download Text Template
            </a>
            <a href="<?php echo base_url('welcome/downloadwordtemplate'); ?>" class="btn btn-outline-info">
                <i class="fas fa-file-word"></i> Download Word Template
            </a>
        </div>
    </div>
</div>

                                                <div class="file-info" id="fileInfo">
                                                    <div class="row">
                                                        <div class="col-md-8">
                                                            <strong>Selected File:</strong> <span id="fileName"></span><br>
                                                            <strong>File Size:</strong> <span id="fileSize"></span><br>
                                                            <strong>File Type:</strong> <span id="fileType"></span>
                                                        </div>
                                                        <div class="col-md-4 text-right">
                                                            <button type="button" class="btn btn-danger btn-sm" id="removeFile">
                                                                <i class="fas fa-times"></i> Remove
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="progress" id="uploadProgress">
                                                    <div class="progress-bar progress-bar-striped progress-bar-animated" 
                                                         role="progressbar" style="width: 0%"></div>
                                                </div>
                                                
                                                <div class="row mt-4">
                                                    <div class="col-md-12">
                                                        <h6><i class="fas fa-table"></i> Required File Format:</h6>
                                                        <div class="table-responsive">
                                                            <table class="table table-bordered sample-table">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Question</th>
                                                                        <th>Option A</th>
                                                                        <th>Option B</th>
                                                                        <th>Option C</th>
                                                                        <th>Option D</th>
                                                                        <th>Correct Answer</th>
                                                                        <th>Instruction (Optional)</th>
                                                                        <th>Explanation (Optional)</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <tr>
                                                                        <td>What is 2 + 2?</td>
                                                                        <td>3</td>
                                                                        <td>4</td>
                                                                        <td>5</td>
                                                                        <td>6</td>
                                                                        <td>B</td>
                                                                        <td>Choose the correct answer.</td>
                                                                        <td>2 + 2 equals 4, which is option B.</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>What is the capital of France?</td>
                                                                        <td>London</td>
                                                                        <td>Berlin</td>
                                                                        <td>Paris</td>
                                                                        <td>Madrid</td>
                                                                        <td>C</td>
                                                                        <td></td>
                                                                        <td>Paris is the capital of France.</td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                        <small class="text-muted">
                                                            <strong>Note:</strong> Correct Answer must be A, B, C, or D (case insensitive).
                                                            All fields except Instruction and Explanation are required.
                                                        </small>
                                                    </div>
                                                </div>
                                                
                                                <div class="text-right mt-3">
                                                    <button type="button" class="btn btn-secondary" id="backToStep1">
                                                        <i class="fas fa-arrow-left"></i> Back
                                                    </button>
                                                    <button type="button" class="btn btn-primary" id="validateFile" disabled>
                                                        Validate File <i class="fas fa-check"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            
                                            <!-- Step 3: Review & Submit -->
                                            <div id="step3-content" style="display: none;">
                                                <h5><i class="fas fa-check-circle"></i> Step 3: Review & Submit</h5>
                                                
                                                <div id="validationResults">
                                                    <div class="text-center" id="validationLoading">
                                                        <i class="fas fa-spinner fa-spin fa-2x"></i>
                                                        <p>Validating file contents...</p>
                                                    </div>
                                                    
                                                    <div id="validationSuccess" style="display: none;">
                                                        <div class="alert alert-success">
                                                            <h5><i class="fas fa-check"></i> File Validation Successful!</h5>
                                                            <p>File is ready for upload. Review the details below:</p>
                                                        </div>
                                                        
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="card">
                                                                    <div class="card-header">
                                                                        <h6 class="mb-0">Upload Details</h6>
                                                                    </div>
                                                                    <div class="card-body">
                                                                        <p><strong>School:</strong> <span id="reviewSchool"></span></p>
                                                                        <p><strong>Department:</strong> <span id="reviewDepartment"></span></p>
                                                                        <p><strong>Course:</strong> <span id="reviewCourse"></span></p>
                                                                        <p><strong>Topic:</strong> <span id="reviewTopic"></span></p>
                                                                        <p><strong>File:</strong> <span id="reviewFile"></span></p>
                                                                        <p><strong>Total Questions:</strong> <span id="reviewTotalQuestions" class="badge bg-primary"></span></p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="card">
                                                                    <div class="card-header">
                                                                        <h6 class="mb-0">Validation Summary</h6>
                                                                    </div>
                                                                    <div class="card-body">
                                                                        <p><strong>Valid Rows:</strong> <span id="validRows" class="badge bg-success"></span></p>
                                                                        <p><strong>Invalid Rows:</strong> <span id="invalidRows" class="badge bg-danger"></span></p>
                                                                        <div id="errorDetails" style="display: none;">
                                                                            <hr>
                                                                            <h6>Validation Errors:</h6>
                                                                            <div class="validation-results" id="errorList"></div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                    <div id="validationErrors" style="display: none;">
                                                        <div class="alert alert-danger">
                                                            <h5><i class="fas fa-exclamation-triangle"></i> File Validation Failed!</h5>
                                                            <p>Please fix the errors below before uploading:</p>
                                                        </div>
                                                        <div class="validation-results" id="errorDetailsList"></div>
                                                    </div>
                                                </div>
                                                
                                                <div class="text-right mt-3">
                                                    <button type="button" class="btn btn-secondary" id="backToStep2">
                                                        <i class="fas fa-arrow-left"></i> Back
                                                    </button>
                                                    <button type="submit" class="btn btn-success" id="submitUpload" disabled>
                                                        <i class="fas fa-upload"></i> Upload Questions
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
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
        
        <!-- JavaScript -->
        <script>
            $(document).ready(function() {
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
                    
                    // Scroll to top to show alert
                    $('html, body').animate({ scrollTop: 0 }, 'fast');
                    
                    // Auto-remove alert after 5 seconds
                    setTimeout(function() {
                        $('.alert').alert('close');
                    }, 5000);
                }
                
                // Step navigation
                var currentStep = 1;
                
                function goToStep(step) {
                    // Hide all steps
                    $('#step1-content, #step2-content, #step3-content').hide();
                    
                    // Update step indicators
                    $('#step1, #step2, #step3').removeClass('active completed');
                    
                    // Show current step
                    if (step === 1) {
                        $('#step1-content').show();
                        $('#step1').addClass('active');
                    } else if (step === 2) {
                        $('#step2-content').show();
                        $('#step1').addClass('completed');
                        $('#step2').addClass('active');
                    } else if (step === 3) {
                        $('#step3-content').show();
                        $('#step1, #step2').addClass('completed');
                        $('#step3').addClass('active');
                        validateFileContents();
                    }
                    
                    currentStep = step;
                }
                
                // Step 1 to Step 2
                $('#nextToStep2').click(function() {
                    // Validate step 1
                    var school = $('#uploadSchool').val();
                    var department = $('#uploadDepartment').val();
                    var course = $('#uploadCourse').val();
                    var topic = $('#uploadTopic').val();
                    
                    var hasError = false;
                    
                    // Clear previous errors
                    $('.form-text.text-danger').text('');
                    
                    if (!school) {
                        $('#schoolError').text('Please select a school');
                        hasError = true;
                    }
                    
                    if (!department) {
                        $('#departmentError').text('Please select a department');
                        hasError = true;
                    }
                    
                    if (!course) {
                        $('#courseError').text('Please select a course');
                        hasError = true;
                    }
                    
                    if (!topic) {
                        $('#topicError').text('Please select a topic');
                        hasError = true;
                    }
                    
                    if (hasError) {
                        return;
                    }
                    
                    goToStep(2);
                });
                
                // Step 2 to Step 1
                $('#backToStep1').click(function() {
                    goToStep(1);
                });
                
                // Step 3 to Step 2
                $('#backToStep2').click(function() {
                    goToStep(2);
                });
                
                // FIXED: File upload handling
                $('#uploadArea').click(function(e) {
                    e.preventDefault();
                    $('#questionsFile').trigger('click');
                });
                
                $('#questionsFile').on('change', function(e) {
                    if (this.files && this.files.length > 0) {
                        handleFileSelect(this.files[0]);
                    }
                });
                
                // Drag and drop functionality
                $('#uploadArea').on('dragover', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    $(this).addClass('dragover');
                });
                
                $('#uploadArea').on('dragleave', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    $(this).removeClass('dragover');
                });
                
                $('#uploadArea').on('drop', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    $(this).removeClass('dragover');
                    
                    var files = e.originalEvent.dataTransfer.files;
                    if (files.length > 0) {
                        // Set the file to the input element
                        var dataTransfer = new DataTransfer();
                        dataTransfer.items.add(files[0]);
                        document.getElementById('questionsFile').files = dataTransfer.files;
                        
                        handleFileSelect(files[0]);
                    }
                });
                
                function handleFileSelect(file) {
    if (!file) {
        showAlert('No file selected.', 'error');
        return;
    }
    
    console.log('File selected:', file.name);
    
    // Check file extension - ONLY TXT, DOC, DOCX
    var fileExtension = file.name.split('.').pop().toLowerCase();
    var allowedExtensions = ['txt', 'doc', 'docx'];
    
    // Check if file extension is allowed
    if (!allowedExtensions.includes(fileExtension)) {
        showAlert('Please upload a supported file format: Text (TXT) or Word (DOC, DOCX) files only.', 'error');
        $('#questionsFile').val('');
        return;
    }
    
    // Check file size (5MB max)
    if (file.size > 5 * 1024 * 1024) {
        showAlert('File size must be less than 5MB.', 'error');
        $('#questionsFile').val('');
        return;
    }
    
    // Display file info
    $('#fileName').text(file.name);
    $('#fileSize').text(formatFileSize(file.size));
    $('#fileType').text(getFileTypeName(fileExtension));
    $('#fileInfo').show();
    
    // Enable validate button
    $('#validateFile').prop('disabled', false);
}

function getFileTypeName(extension) {
    var types = {
        'txt': 'Text Document',
        'doc': 'Word Document',
        'docx': 'Word Document'
    };
    return types[extension] || extension.toUpperCase();
}

                function getFileTypeName(extension) {
                    var types = {
                        'csv': 'CSV Document',
                        'xls': 'Excel Spreadsheet',
                        'xlsx': 'Excel Spreadsheet',
                        'txt': 'Text Document',
                        'doc': 'Word Document',
                        'docx': 'Word Document'
                    };
                    return types[extension] || extension.toUpperCase();
                }
                
                function formatFileSize(bytes) {
                    if (bytes === 0) return '0 Bytes';
                    var k = 1024;
                    var sizes = ['Bytes', 'KB', 'MB', 'GB'];
                    var i = Math.floor(Math.log(bytes) / Math.log(k));
                    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
                }
                
                // Remove file
                $('#removeFile').click(function() {
                    $('#questionsFile').val('');
                    $('#fileInfo').hide();
                    $('#validateFile').prop('disabled', true);
                    $('#fileName, #fileSize, #fileType').text('');
                });
                
                // Load departments based on school
                $('#uploadSchool').change(function() {
                    var schoolId = $(this).val();
                    
                    if (!schoolId) {
                        $('#uploadDepartment').prop('disabled', true).empty().append('<option value="">-- Select Department --</option>');
                        $('#uploadCourse').prop('disabled', true).empty().append('<option value="">-- Select Course --</option>');
                        $('#uploadTopic').prop('disabled', true).empty().append('<option value="">-- Select Topic --</option>');
                        return;
                    }
                    
                    $('#uploadDepartment').prop('disabled', true);
                    $('#uploadCourse').prop('disabled', true);
                    $('#uploadTopic').prop('disabled', true);
                    
                    $.ajax({
                        url: '<?php echo base_url("welcome/getdepartmentsforschool"); ?>/' + schoolId,
                        method: 'GET',
                        dataType: 'json',
                        success: function(response) {
                            if (response.status === 'success') {
                                var $deptSelect = $('#uploadDepartment');
                                $deptSelect.empty().append('<option value="">-- Select Department --</option>');
                                
                                $.each(response.departments, function(index, dept) {
                                    $deptSelect.append('<option value="' + dept.id + '">' + dept.name + '</option>');
                                });
                                
                                $deptSelect.prop('disabled', false);
                            }
                        }
                    });
                });
                
                // Load courses based on department
                $('#uploadDepartment').change(function() {
                    var schoolId = $('#uploadSchool').val();
                    var deptId = $(this).val();
                    
                    if (!schoolId || !deptId) {
                        $('#uploadCourse').prop('disabled', true).empty().append('<option value="">-- Select Course --</option>');
                        $('#uploadTopic').prop('disabled', true).empty().append('<option value="">-- Select Topic --</option>');
                        return;
                    }
                    
                    $('#uploadCourse').prop('disabled', true);
                    $('#uploadTopic').prop('disabled', true);
                    
                    $.ajax({
                        url: '<?php echo base_url("welcome/getcoursesfordepartment"); ?>/' + schoolId + '/' + deptId,
                        method: 'GET',
                        dataType: 'json',
                        success: function(response) {
                            if (response.status === 'success') {
                                var $courseSelect = $('#uploadCourse');
                                $courseSelect.empty().append('<option value="">-- Select Course --</option>');
                                
                                $.each(response.courses, function(index, course) {
                                    $courseSelect.append('<option value="' + course.id + '">' + course.code + ' - ' + course.title + '</option>');
                                });
                                
                                $courseSelect.prop('disabled', false);
                            }
                        }
                    });
                });
                
                // Load topics based on course
                $('#uploadCourse').change(function() {
                    var courseId = $(this).val();
                    
                    if (!courseId) {
                        $('#uploadTopic').prop('disabled', true).empty().append('<option value="">-- Select Topic --</option>');
                        return;
                    }
                    
                    $('#uploadTopic').prop('disabled', true);
                    
                    $.ajax({
                        url: '<?php echo base_url("welcome/gettopicsforcourse"); ?>/' + courseId,
                        method: 'GET',
                        dataType: 'json',
                        success: function(response) {
                            if (response.status === 'success') {
                                var $topicSelect = $('#uploadTopic');
                                $topicSelect.empty().append('<option value="">-- Select Topic --</option>');
                                
                                $.each(response.topics, function(index, topic) {
                                    $topicSelect.append('<option value="' + topic.id + '">' + topic.name + '</option>');
                                });
                                
                                $topicSelect.prop('disabled', false);
                            }
                        }
                    });
                });
                
                // Validate file button
                $('#validateFile').click(function() {
                    var fileInput = $('#questionsFile')[0];
                    
                    if (!fileInput.files || fileInput.files.length === 0) {
                        showAlert('Please select a file to upload.', 'error');
                        return;
                    }
                    
                    goToStep(3);
                });
                
                // Validate file contents
                function validateFileContents() {
                    var fileInput = $('#questionsFile')[0];
                    
                    if (!fileInput.files || !fileInput.files[0]) {
                        showAlert('No file selected.', 'error');
                        goToStep(2);
                        return;
                    }
                    
                    // Show loading
                    $('#validationLoading').show();
                    $('#validationSuccess, #validationErrors').hide();
                    
                    // Create FormData object
                    var formData = new FormData();
                    formData.append('course_id', $('#uploadCourse').val());
                    formData.append('topic_id', $('#uploadTopic').val());
                    formData.append('questions_file', fileInput.files[0]);
                    
                    // Set review details
                    $('#reviewSchool').text($('#uploadSchool option:selected').text());
                    $('#reviewDepartment').text($('#uploadDepartment option:selected').text());
                    $('#reviewCourse').text($('#uploadCourse option:selected').text());
                    $('#reviewTopic').text($('#uploadTopic option:selected').text());
                    $('#reviewFile').text(fileInput.files[0].name);
                    
                    console.log('Sending file for validation:', fileInput.files[0].name);
                    
                    // Send validation request
                    $.ajax({
                        url: '<?php echo base_url("welcome/processbatchupload"); ?>',
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
                                    $('#uploadProgress .progress-bar').css('width', percent + '%');
                                }
                            }, false);
                            return xhr;
                        },
                        beforeSend: function() {
                            $('#uploadProgress').show();
                        },
                        success: function(response) {
                            console.log('Validation response:', response);
                            $('#uploadProgress').hide();
                            $('#validationLoading').hide();
                            
                            if (response.status === 'validation_error') {
                                // Show validation errors
                                showValidationErrors(response.validation_result);
                            } else if (response.status === 'success') {
                                // Show success and upload summary
                                showValidationSuccess(response);
                            } else {
                                // Show error
                                showAlert(response.message, 'error');
                                goToStep(2);
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error('AJAX Error:', status, error);
                            console.error('Response:', xhr.responseText);
                            $('#uploadProgress').hide();
                            $('#validationLoading').hide();
                            showAlert('Error validating file. Please try again. ' + error, 'error');
                            goToStep(2);
                        }
                    });
                }
                
                function showValidationErrors(validationResult) {
                    $('#validationSuccess').hide();
                    $('#validationErrors').show();
                    
                    var $errorList = $('#errorDetailsList');
                    $errorList.empty();
                    
                    $('#reviewTotalQuestions').text(validationResult.total_rows);
                    $('#validRows').text(validationResult.valid_rows);
                    $('#invalidRows').text(validationResult.invalid_rows);
                    
                    if (validationResult.errors && Object.keys(validationResult.errors).length > 0) {
                        var errorHtml = '<div class="alert alert-warning"><strong>Found ' + validationResult.invalid_rows + ' error(s):</strong></div>';
                        
                        $.each(validationResult.errors, function(rowNum, errors) {
                            errorHtml += '<div class="card mb-2">';
                            errorHtml += '<div class="card-header bg-warning">';
                            errorHtml += '<strong>Row ' + rowNum + ':</strong>';
                            errorHtml += '</div>';
                            errorHtml += '<div class="card-body">';
                            errorHtml += '<ul>';
                            $.each(errors, function(i, error) {
                                errorHtml += '<li>' + error + '</li>';
                            });
                            errorHtml += '</ul>';
                            errorHtml += '</div>';
                            errorHtml += '</div>';
                        });
                        
                        $errorList.html(errorHtml);
                    }
                    
                    $('#submitUpload').prop('disabled', true);
                }
                
                function showValidationSuccess(response) {
                    $('#validationErrors').hide();
                    $('#validationSuccess').show();
                    
                    var totalQuestions = (response.validation_result && response.validation_result.total_rows) 
                        ? response.validation_result.total_rows 
                        : (response.success_count + response.failed_count);
                    
                    var validRows = (response.validation_result && response.validation_result.valid_rows)
                        ? response.validation_result.valid_rows
                        : response.success_count;
                    
                    var invalidRows = (response.validation_result && response.validation_result.invalid_rows)
                        ? response.validation_result.invalid_rows
                        : response.failed_count;
                    
                    $('#reviewTotalQuestions').text(totalQuestions);
                    $('#validRows').text(validRows);
                    $('#invalidRows').text(invalidRows);
                    
                    if (invalidRows > 0 && response.validation_result && response.validation_result.errors) {
                        $('#errorDetails').show();
                        var $errorList = $('#errorList');
                        $errorList.empty();
                        
                        var errorHtml = '<div class="alert alert-warning"><strong>' + invalidRows + ' record(s) have errors:</strong></div>';
                        
                        $.each(response.validation_result.errors, function(rowNum, errors) {
                            errorHtml += '<div class="card mb-2">';
                            errorHtml += '<div class="card-header bg-warning">';
                            errorHtml += '<strong>Row ' + rowNum + ':</strong>';
                            errorHtml += '</div>';
                            errorHtml += '<div class="card-body">';
                            errorHtml += '<ul>';
                            $.each(errors, function(i, error) {
                                errorHtml += '<li>' + error + '</li>';
                            });
                            errorHtml += '</ul>';
                            errorHtml += '</div>';
                            errorHtml += '</div>';
                        });
                        
                        $errorList.html(errorHtml);
                    } else {
                        $('#errorDetails').hide();
                    }
                    
                    // Enable submit only if there are no errors
                    $('#submitUpload').prop('disabled', invalidRows > 0);
                }
                
                // Form submission
                // Form submission
$('#batchUploadForm').submit(function(e) {
    e.preventDefault();
    
    var fileInput = $('#questionsFile')[0];
    
    if (!fileInput.files || !fileInput.files[0]) {
        showAlert('No file selected.', 'error');
        return;
    }
    
    // Disable submit button
    $('#submitUpload').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Uploading...');
    
    // Create FormData object
    var formData = new FormData();
    formData.append('course_id', $('#uploadCourse').val());
    formData.append('topic_id', $('#uploadTopic').val());
    formData.append('questions_file', fileInput.files[0]);
    formData.append('confirm_upload', 'true'); // Flag for actual upload
    
    // Show progress bar
    $('#uploadProgress').show();
    
    $.ajax({
        url: '<?php echo base_url("welcome/processbatchupload"); ?>',
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
                    $('#uploadProgress .progress-bar').css('width', percent + '%');
                }
            }, false);
            return xhr;
        },
        success: function(response) {
            $('#uploadProgress').hide();
            
            console.log('Upload response:', response);
            
            if (response.status === 'success') {
                var successCount = response.upload_result ? response.upload_result.success_count : 0;
                var failedCount = response.upload_result ? response.upload_result.failed_count : 0;
                
                var message = 'Successfully uploaded ' + successCount + ' questions.';
                if (failedCount > 0) {
                    message += ' ' + failedCount + ' questions failed to upload.';
                }
                
                showAlert(message, 'success');
                
                // Reset form and redirect
                setTimeout(function() {
                    window.location.href = '<?php echo base_url("questions"); ?>';
                }, 3000);
                
            } else if (response.status === 'validation_error') {
                // Show validation errors
                showValidationErrors(response.validation_result);
                $('#submitUpload').prop('disabled', true).html('<i class="fas fa-upload"></i> Upload Questions');
                
            } else {
                showAlert(response.message, 'error');
                $('#submitUpload').prop('disabled', false).html('<i class="fas fa-upload"></i> Upload Questions');
            }
        },
        error: function(xhr, status, error) {
    console.error('Upload error:', status, error);
    console.error('Response text:', xhr.responseText);
    
    $('#uploadProgress').hide();
    
    // Try to parse error response
    var errorMessage = 'Error uploading file. Please try again.';
    try {
        var response = JSON.parse(xhr.responseText);
        if (response.message) {
            errorMessage = response.message;
        }
    } catch (e) {
        // If not JSON, show raw response
        if (xhr.responseText) {
            errorMessage = xhr.responseText.substring(0, 200) + '...';
        }
    }
    
    showAlert(errorMessage, 'error');
    $('#submitUpload').prop('disabled', false).html('<i class="fas fa-upload"></i> Upload Questions');
}
    });
});
            });
        </script>
    </body>
</html>