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
                                <h1>News & Updates Management</h1>
                                <p class="text-muted">Total Items: <?php echo isset($news_count) ? $news_count : 0; ?></p>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                                    <li class="breadcrumb-item active">News & Updates</li>
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
                                <button type="button" class="btn btn-success" data-toggle="modal" data-target="#addNewsModal">
                                    <i class="fas fa-plus-circle"></i> Add New Item
                                </button>
                                <button type="button" class="btn btn-secondary" id="refreshTable">
                                    <i class="fas fa-sync-alt"></i> Refresh Table
                                </button>
                                
                                <!-- Filter Buttons -->
                                <div class="btn-group ml-2">
                                    <button type="button" class="btn btn-outline-info dropdown-toggle" data-toggle="dropdown">
                                        Filter by Type
                                    </button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item <?php echo (!isset($filter_type)) ? 'active' : ''; ?>" 
                                           href="<?php echo base_url('welcome/opennews'); ?>">All Types</a>
                                        <?php foreach($news_types as $key => $type): ?>
                                            <a class="dropdown-item <?php echo ($filter_type == $key) ? 'active' : ''; ?>" 
                                               href="<?php echo base_url('welcome/opennews?type=' . $key); ?>">
                                               <?php echo $type; ?>
                                            </a>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                                
                                <div class="btn-group ml-2">
                                    <button type="button" class="btn btn-outline-info dropdown-toggle" data-toggle="dropdown">
                                        Filter by Status
                                    </button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item <?php echo (!isset($filter_status)) ? 'active' : ''; ?>" 
                                           href="<?php echo base_url('welcome/opennews'); ?>">All Statuses</a>
                                        <?php foreach($news_statuses as $key => $status): ?>
                                            <a class="dropdown-item <?php echo ($filter_status == $key) ? 'active' : ''; ?>" 
                                               href="<?php echo base_url('welcome/opennews?status=' . $key); ?>">
                                               <?php echo $status; ?>
                                            </a>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- News Table -->
                        <div class="row">
                            <div class="col-md-12"> 
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title">All News & Updates</h3>
                                        <div class="card-tools">
                                            <div class="input-group input-group-sm" style="width: 150px;">
                                                <input type="text" name="table_search" class="form-control float-right" placeholder="Search" id="newsSearch">
                                                <div class="input-group-append">
                                                    <button type="button" class="btn btn-default" id="searchButton">
                                                        <i class="fas fa-search"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- /.card-header -->
                                    <div class="card-body">
                                        <?php if(isset($dbhead) && !empty($dbhead)): ?>
                                            <table id="newsTable" class="table table-bordered table-striped">
                                                <thead>
                                                    <?php echo $dbhead ?>
                                                </thead>
                                                <tbody>
                                                    <?php echo isset($dbbody) ? $dbbody : '' ?>
                                                </tbody>
                                            </table>
                                        <?php else: ?>
                                            <div class="alert alert-info text-center">
                                                <h5><i class="icon fas fa-info"></i> No News/Updates Found!</h5>
                                                Click "Add New Item" button to add your first news item or update.
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
        
        <!-- Add News Modal -->
        <div class="modal fade" id="addNewsModal" tabindex="-1" role="dialog" aria-labelledby="addNewsModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-success">
                        <h5 class="modal-title" id="addNewsModalLabel">
                            <i class="fas fa-newspaper"></i> Add News/Update
                        </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form id="addNewsForm" enctype="multipart/form-data">
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <label for="addTitle">Title *</label>
                                        <input type="text" class="form-control" id="addTitle" name="title" 
                                               placeholder="Enter title" required>
                                        <small class="form-text text-danger" id="addTitleError"></small>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="addContent">Content *</label>
                                        <textarea class="form-control" id="addContent" name="content" rows="15" 
                                                  placeholder="Enter content here..." required></textarea>
                                        <small class="form-text text-danger" id="addContentError"></small>
                                    </div>
                                </div>
                                
                                <div class="col-md-4">
                                    <!-- Featured Image Upload -->
                                    <div class="form-group">
                                        <label for="addFeaturedImage">Featured Image</label>
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" id="addFeaturedImage" name="featured_image" accept="image/*">
                                            <label class="custom-file-label" for="addFeaturedImage">Choose file</label>
                                        </div>
                                        <small class="form-text text-muted">Max size: 2MB. Formats: JPG, PNG, GIF, WebP</small>
                                        <div id="imagePreview" class="mt-2" style="display: none;">
                                            <img id="previewImage" src="#" alt="Preview" class="img-fluid rounded" style="max-height: 200px;">
                                        </div>
                                    </div>
                                    
                                    <!-- Type Selection -->
                                    <div class="form-group">
                                        <label for="addType">Type *</label>
                                        <select class="form-control" id="addType" name="type" required>
                                            <option value="">-- Select Type --</option>
                                            <?php if(isset($news_types) && !empty($news_types)): ?>
                                                <?php foreach($news_types as $value => $label): ?>
                                                    <option value="<?php echo $value; ?>"><?php echo htmlspecialchars($label); ?></option>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </select>
                                        <small class="form-text text-danger" id="addTypeError"></small>
                                    </div>
                                    
                                    <!-- Status Selection -->
                                    <div class="form-group">
                                        <label for="addStatus">Status *</label>
                                        <select class="form-control" id="addStatus" name="status" required>
                                            <option value="">-- Select Status --</option>
                                            <?php if(isset($news_statuses) && !empty($news_statuses)): ?>
                                                <?php foreach($news_statuses as $value => $label): ?>
                                                    <option value="<?php echo $value; ?>"><?php echo htmlspecialchars($label); ?></option>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </select>
                                        <small class="form-text text-danger" id="addStatusError"></small>
                                    </div>
                                    
                                    <!-- Featured Checkbox -->
                                    <div class="form-group">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="addFeatured" name="is_featured" value="1">
                                            <label class="custom-control-label" for="addFeatured">Mark as Featured</label>
                                        </div>
                                        <small class="form-text text-muted">Featured items appear prominently</small>
                                    </div>
                                    
                                    <!-- Dates -->
                                    <div class="form-group">
                                        <label for="addPublishDate">Publish Date</label>
                                        <input type="datetime-local" class="form-control" id="addPublishDate" name="publish_date">
                                        <small class="form-text text-muted">Leave empty to publish immediately</small>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="addExpiryDate">Expiry Date</label>
                                        <input type="datetime-local" class="form-control" id="addExpiryDate" name="expiry_date">
                                        <small class="form-text text-muted">Leave empty for no expiry</small>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Excerpt and Meta Info -->
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="addExcerpt">Excerpt (Summary)</label>
                                        <textarea class="form-control" id="addExcerpt" name="excerpt" rows="3" 
                                                  placeholder="Brief summary (optional)"></textarea>
                                        <small class="form-text text-muted">Maximum 500 characters</small>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="addMetaKeywords">Meta Keywords</label>
                                        <input type="text" class="form-control" id="addMetaKeywords" name="meta_keywords" 
                                               placeholder="keyword1, keyword2, keyword3">
                                        <small class="form-text text-muted">Separate keywords with commas</small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="addMetaDescription">Meta Description</label>
                                        <textarea class="form-control" id="addMetaDescription" name="meta_description" rows="2" 
                                                  placeholder="Brief description for search engines"></textarea>
                                        <small class="form-text text-muted">Maximum 160 characters</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-save"></i> Save Item
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Edit News Modal -->
        <div class="modal fade" id="editNewsModal" tabindex="-1" role="dialog" aria-labelledby="editNewsModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-primary">
                        <h5 class="modal-title" id="editNewsModalLabel">
                            <i class="fas fa-edit"></i> Edit News/Update
                        </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form id="editNewsForm" enctype="multipart/form-data">
                        <div class="modal-body">
                            <input type="hidden" id="editNewsId" name="news_id">
                            <input type="hidden" id="editCurrentImage" name="current_image">
                            
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <label for="editTitle">Title *</label>
                                        <input type="text" class="form-control" id="editTitle" name="title" 
                                               placeholder="Enter title" required>
                                        <small class="form-text text-danger" id="editTitleError"></small>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="editContent">Content *</label>
                                        <textarea class="form-control" id="editContent" name="content" rows="15" 
                                                  placeholder="Enter content here..." required></textarea>
                                        <small class="form-text text-danger" id="editContentError"></small>
                                    </div>
                                </div>
                                
                                <div class="col-md-4">
                                    <!-- Current Featured Image -->
                                    <div class="form-group" id="currentImageContainer">
                                        <label>Current Featured Image</label>
                                        <div id="currentImagePreview" class="border p-2 text-center">
                                            <p class="text-muted">No image</p>
                                        </div>
                                        <button type="button" class="btn btn-sm btn-danger mt-2" id="removeImageBtn" style="display: none;">
                                            <i class="fas fa-trash"></i> Remove Image
                                        </button>
                                    </div>
                                    
                                    <!-- New Featured Image Upload -->
                                    <div class="form-group">
                                        <label for="editFeaturedImage">Change Featured Image</label>
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" id="editFeaturedImage" name="featured_image" accept="image/*">
                                            <label class="custom-file-label" for="editFeaturedImage">Choose new file</label>
                                        </div>
                                        <small class="form-text text-muted">Max size: 2MB. Formats: JPG, PNG, GIF, WebP</small>
                                        <div id="editImagePreview" class="mt-2" style="display: none;">
                                            <img id="editPreviewImage" src="#" alt="Preview" class="img-fluid rounded" style="max-height: 200px;">
                                        </div>
                                    </div>
                                    
                                    <!-- Type Selection -->
                                    <div class="form-group">
                                        <label for="editType">Type *</label>
                                        <select class="form-control" id="editType" name="type" required>
                                            <option value="">-- Select Type --</option>
                                            <?php if(isset($news_types) && !empty($news_types)): ?>
                                                <?php foreach($news_types as $value => $label): ?>
                                                    <option value="<?php echo $value; ?>"><?php echo htmlspecialchars($label); ?></option>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </select>
                                        <small class="form-text text-danger" id="editTypeError"></small>
                                    </div>
                                    
                                    <!-- Status Selection -->
                                    <div class="form-group">
                                        <label for="editStatus">Status *</label>
                                        <select class="form-control" id="editStatus" name="status" required>
                                            <option value="">-- Select Status --</option>
                                            <?php if(isset($news_statuses) && !empty($news_statuses)): ?>
                                                <?php foreach($news_statuses as $value => $label): ?>
                                                    <option value="<?php echo $value; ?>"><?php echo htmlspecialchars($label); ?></option>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </select>
                                        <small class="form-text text-danger" id="editStatusError"></small>
                                    </div>
                                    
                                    <!-- Featured Checkbox -->
                                    <div class="form-group">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="editFeatured" name="is_featured" value="1">
                                            <label class="custom-control-label" for="editFeatured">Mark as Featured</label>
                                        </div>
                                        <small class="form-text text-muted">Featured items appear prominently</small>
                                    </div>
                                    
                                    <!-- Dates -->
                                    <div class="form-group">
                                        <label for="editPublishDate">Publish Date</label>
                                        <input type="datetime-local" class="form-control" id="editPublishDate" name="publish_date">
                                        <small class="form-text text-muted">Leave empty to publish immediately</small>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="editExpiryDate">Expiry Date</label>
                                        <input type="datetime-local" class="form-control" id="editExpiryDate" name="expiry_date">
                                        <small class="form-text text-muted">Leave empty for no expiry</small>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Excerpt and Meta Info -->
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="editExcerpt">Excerpt (Summary)</label>
                                        <textarea class="form-control" id="editExcerpt" name="excerpt" rows="3" 
                                                  placeholder="Brief summary (optional)"></textarea>
                                        <small class="form-text text-muted">Maximum 500 characters</small>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="editMetaKeywords">Meta Keywords</label>
                                        <input type="text" class="form-control" id="editMetaKeywords" name="meta_keywords" 
                                               placeholder="keyword1, keyword2, keyword3">
                                        <small class="form-text text-muted">Separate keywords with commas</small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="editMetaDescription">Meta Description</label>
                                        <textarea class="form-control" id="editMetaDescription" name="meta_description" rows="2" 
                                                  placeholder="Brief description for search engines"></textarea>
                                        <small class="form-text text-muted">Maximum 160 characters</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-sync-alt"></i> Update Item
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Preview News Modal -->
        <div class="modal fade" id="previewNewsModal" tabindex="-1" role="dialog" aria-labelledby="previewNewsModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-info">
                        <h5 class="modal-title" id="previewNewsModalLabel">
                            <i class="fas fa-eye"></i> Preview News/Update
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
        <div class="modal fade" id="deleteNewsModal" tabindex="-1" role="dialog" aria-labelledby="deleteNewsModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-danger">
                        <h5 class="modal-title" id="deleteNewsModalLabel">
                            <i class="fas fa-exclamation-triangle"></i> Confirm Delete
                        </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>Are you sure you want to delete this news item/update?</p>
                        <p><strong>Title: <span id="deleteNewsTitle"></span></strong></p>
                        <p class="text-danger">This action cannot be undone!</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-danger" id="confirmDeleteNewsBtn">
                            <i class="fas fa-trash"></i> Delete Item
                        </button>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- JavaScript -->
        <script>
            $(document).ready(function() {
                // Initialize DataTable
                var newsTable = $('#newsTable').DataTable({
                    "responsive": true,
                    "lengthChange": true,
                    "autoWidth": false,
                    "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"],
                    "pageLength": 10,
                    "order": [[0, 'asc']],
                    "columnDefs": [
                        { "width": "5%", "targets": 0 },
                        { "width": "20%", "targets": 1 },
                        { "width": "10%", "targets": 2 },
                        { "width": "10%", "targets": 3 },
                        { "width": "15%", "targets": 4 },
                        { "width": "10%", "targets": 5 },
                        { "width": "10%", "targets": 6 },
                        { "width": "20%", "targets": 7, "orderable": false }
                    ]
                });
                
                // Append buttons to container
                newsTable.buttons().container().appendTo('#newsTable_wrapper .col-md-6:eq(0)');
                
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
                
                // File input preview
                $('#addFeaturedImage').change(function() {
                    readURL(this, '#previewImage', '#imagePreview');
                });
                
                $('#editFeaturedImage').change(function() {
                    readURL(this, '#editPreviewImage', '#editImagePreview');
                });
                
                function readURL(input, imageSelector, previewSelector) {
                    if (input.files && input.files[0]) {
                        var reader = new FileReader();
                        
                        reader.onload = function(e) {
                            $(imageSelector).attr('src', e.target.result);
                            $(previewSelector).show();
                        }
                        
                        reader.readAsDataURL(input.files[0]);
                    }
                }
                
                // Custom file input label
                $('.custom-file-input').on('change', function() {
                    var fileName = $(this).val().split('\\').pop();
                    $(this).next('.custom-file-label').addClass("selected").html(fileName);
                });
                
                // Initialize CKEditor for content (if you have it)
                <?php if (file_exists(APPPATH . 'libraries/Ckeditor.php')): ?>
                    CKEDITOR.replace('addContent', {
                        height: 300,
                        toolbar: [
                            { name: 'document', items: ['Source', '-', 'Save', 'NewPage', 'Preview', 'Print', '-', 'Templates'] },
                            { name: 'clipboard', items: ['Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo'] },
                            { name: 'editing', items: ['Find', 'Replace', '-', 'SelectAll'] },
                            '/',
                            { name: 'basicstyles', items: ['Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript', '-', 'CopyFormatting', 'RemoveFormat'] },
                            { name: 'paragraph', items: ['NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote', 'CreateDiv', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock', '-', 'BidiLtr', 'BidiRtl'] },
                            { name: 'links', items: ['Link', 'Unlink', 'Anchor'] },
                            { name: 'insert', items: ['Image', 'Flash', 'Table', 'HorizontalRule', 'Smiley', 'SpecialChar', 'PageBreak', 'Iframe'] },
                            '/',
                            { name: 'styles', items: ['Styles', 'Format', 'Font', 'FontSize'] },
                            { name: 'colors', items: ['TextColor', 'BGColor'] },
                            { name: 'tools', items: ['Maximize', 'ShowBlocks'] }
                        ]
                    });
                    
                    CKEDITOR.replace('editContent', {
                        height: 300,
                        toolbar: [
                            { name: 'document', items: ['Source', '-', 'Save', 'NewPage', 'Preview', 'Print', '-', 'Templates'] },
                            { name: 'clipboard', items: ['Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo'] },
                            { name: 'editing', items: ['Find', 'Replace', '-', 'SelectAll'] },
                            '/',
                            { name: 'basicstyles', items: ['Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript', '-', 'CopyFormatting', 'RemoveFormat'] },
                            { name: 'paragraph', items: ['NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote', 'CreateDiv', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock', '-', 'BidiLtr', 'BidiRtl'] },
                            { name: 'links', items: ['Link', 'Unlink', 'Anchor'] },
                            { name: 'insert', items: ['Image', 'Flash', 'Table', 'HorizontalRule', 'Smiley', 'SpecialChar', 'PageBreak', 'Iframe'] },
                            '/',
                            { name: 'styles', items: ['Styles', 'Format', 'Font', 'FontSize'] },
                            { name: 'colors', items: ['TextColor', 'BGColor'] },
                            { name: 'tools', items: ['Maximize', 'ShowBlocks'] }
                        ]
                    });
                <?php endif; ?>
                
                // Add News Form Submission
                $('#addNewsForm').submit(function(e) {
                    e.preventDefault();
                    
                    // Clear previous errors
                    $('.form-text.text-danger').text('');
                    
                    // Get form data
                    var formData = new FormData(this);
                    var hasError = false;
                    
                    // Basic validation
                    if ($('#addTitle').val().trim() === '') {
                        $('#addTitleError').text('Title is required!');
                        hasError = true;
                    }
                    
                    // For CKEditor, get content differently
                    var content = '';
                    <?php if (file_exists(APPPATH . 'libraries/Ckeditor.php')): ?>
                        content = CKEDITOR.instances.addContent.getData();
                    <?php else: ?>
                        content = $('#addContent').val();
                    <?php endif; ?>
                    
                    if (content.trim() === '') {
                        $('#addContentError').text('Content is required!');
                        hasError = true;
                    }
                    
                    if ($('#addType').val() === '') {
                        $('#addTypeError').text('Please select a type!');
                        hasError = true;
                    }
                    
                    if ($('#addStatus').val() === '') {
                        $('#addStatusError').text('Please select a status!');
                        hasError = true;
                    }
                    
                    if (hasError) return;
                    
                    // Add CKEditor content to formData
                    formData.append('content', content);
                    
                    $.ajax({
                        url: '<?php echo base_url("welcome/addnews_ajax"); ?>',
                        method: 'POST',
                        data: formData,
                        contentType: false,
                        processData: false,
                        dataType: 'json',
                        beforeSend: function() {
                            $('#addNewsForm button[type="submit"]').html('<i class="fas fa-spinner fa-spin"></i> Saving...').prop('disabled', true);
                        },
                        success: function(response) {
                            if (response.status === 'success') {
                                showAlert(response.message, 'success');
                                $('#addNewsModal').modal('hide');
                                $('#addNewsForm')[0].reset();
                                $('#imagePreview').hide();
                                $('.custom-file-label').html('Choose file');
                                refreshTable();
                            } else if (response.status === 'validation_error') {
                                var errors = response.message;
                                if (errors.includes('Title')) {
                                    $('#addTitleError').text('Title is required (5-255 characters)');
                                }
                                if (errors.includes('Content')) {
                                    $('#addContentError').text('Content is required (minimum 10 characters)');
                                }
                                if (errors.includes('Type')) {
                                    $('#addTypeError').text('Please select a valid type');
                                }
                                if (errors.includes('Status')) {
                                    $('#addStatusError').text('Please select a valid status');
                                }
                            } else {
                                showAlert(response.message, 'error');
                            }
                        },
                        error: function(xhr, status, error) {
                            showAlert('An error occurred. Please try again!', 'error');
                            console.log(xhr.responseText);
                        },
                        complete: function() {
                            $('#addNewsForm button[type="submit"]').html('<i class="fas fa-save"></i> Save Item').prop('disabled', false);
                        }
                    });
                });
                
                // Edit News Button Click
                $(document).on('click', '.edit-news', function() {
                    var newsId = $(this).data('id');
                    
                    $.ajax({
                        url: '<?php echo base_url("welcome/getnews_ajax"); ?>/' + newsId,
                        method: 'GET',
                        dataType: 'json',
                        beforeSend: function() {
                            // Show loading state
                            $('#editNewsModal').modal('show');
                            $('#editNewsForm').find('input, textarea, select').val('');
                        },
                        success: function(response) {
                            if (response.status === 'success') {
                                var news = response.news;
                                
                                // Set form values
                                $('#editNewsId').val(news.id);
                                $('#editTitle').val(news.title);
                                $('#editCurrentImage').val(news.featured_image);
                                
                                // Set CKEditor or textarea content
                                <?php if (file_exists(APPPATH . 'libraries/Ckeditor.php')): ?>
                                    CKEDITOR.instances.editContent.setData(news.content);
                                <?php else: ?>
                                    $('#editContent').val(news.content);
                                <?php endif; ?>
                                
                                $('#editExcerpt').val(news.excerpt);
                                $('#editType').val(news.type);
                                $('#editStatus').val(news.status);
                                $('#editMetaKeywords').val(news.meta_keywords);
                                $('#editMetaDescription').val(news.meta_description);
                                $('#editFeatured').prop('checked', news.is_featured == 1);
                                
                                // Format dates for datetime-local input
                                if (news.publish_date) {
                                    var publishDate = new Date(news.publish_date);
                                    $('#editPublishDate').val(publishDate.toISOString().slice(0, 16));
                                }
                                
                                if (news.expiry_date) {
                                    var expiryDate = new Date(news.expiry_date);
                                    $('#editExpiryDate').val(expiryDate.toISOString().slice(0, 16));
                                }
                                
                                // Show current image
                                if (news.featured_image) {
                                    $('#currentImagePreview').html('<img src="<?php echo base_url("uploads/news/"); ?>' + news.featured_image + '" class="img-fluid rounded" style="max-height: 200px;">');
                                    $('#removeImageBtn').show();
                                } else {
                                    $('#currentImagePreview').html('<p class="text-muted">No image</p>');
                                    $('#removeImageBtn').hide();
                                }
                                
                            } else {
                                showAlert(response.message, 'error');
                                $('#editNewsModal').modal('hide');
                            }
                        },
                        error: function() {
                            showAlert('Error loading news item!', 'error');
                            $('#editNewsModal').modal('hide');
                        }
                    });
                });
                
                // Remove Image Button
                $('#removeImageBtn').click(function() {
                    var newsId = $('#editNewsId').val();
                    
                    if (confirm('Are you sure you want to remove the featured image?')) {
                        $.ajax({
                            url: '<?php echo base_url("welcome/removefeaturedimage_ajax"); ?>',
                            method: 'POST',
                            data: {news_id: newsId},
                            dataType: 'json',
                            success: function(response) {
                                if (response.status === 'success') {
                                    $('#currentImagePreview').html('<p class="text-muted">No image</p>');
                                    $('#removeImageBtn').hide();
                                    $('#editCurrentImage').val('');
                                    showAlert('Image removed successfully!', 'success');
                                } else {
                                    showAlert(response.message, 'error');
                                }
                            }
                        });
                    }
                });
                
                // Edit News Form Submission
                $('#editNewsForm').submit(function(e) {
                    e.preventDefault();
                    
                    // Clear previous errors
                    $('.form-text.text-danger').text('');
                    
                    // Get form data
                    var formData = new FormData(this);
                    var hasError = false;
                    
                    // Basic validation
                    if ($('#editTitle').val().trim() === '') {
                        $('#editTitleError').text('Title is required!');
                        hasError = true;
                    }
                    
                    // For CKEditor, get content differently
                    var content = '';
                    <?php if (file_exists(APPPATH . 'libraries/Ckeditor.php')): ?>
                        content = CKEDITOR.instances.editContent.getData();
                    <?php else: ?>
                        content = $('#editContent').val();
                    <?php endif; ?>
                    
                    if (content.trim() === '') {
                        $('#editContentError').text('Content is required!');
                        hasError = true;
                    }
                    
                    if ($('#editType').val() === '') {
                        $('#editTypeError').text('Please select a type!');
                        hasError = true;
                    }
                    
                    if ($('#editStatus').val() === '') {
                        $('#editStatusError').text('Please select a status!');
                        hasError = true;
                    }
                    
                    if (hasError) return;
                    
                    // Add CKEditor content to formData
                    formData.append('content', content);
                    
                    $.ajax({
                        url: '<?php echo base_url("welcome/updatenews_ajax"); ?>',
                        method: 'POST',
                        data: formData,
                        contentType: false,
                        processData: false,
                        dataType: 'json',
                        beforeSend: function() {
                            $('#editNewsForm button[type="submit"]').html('<i class="fas fa-spinner fa-spin"></i> Updating...').prop('disabled', true);
                        },
                        success: function(response) {
                            if (response.status === 'success') {
                                showAlert(response.message, 'success');
                                $('#editNewsModal').modal('hide');
                                $('#editImagePreview').hide();
                                $('.custom-file-label').html('Choose file');
                                refreshTable();
                            } else if (response.status === 'validation_error') {
                                var errors = response.message;
                                if (errors.includes('Title')) {
                                    $('#editTitleError').text('Title is required (5-255 characters)');
                                }
                                if (errors.includes('Content')) {
                                    $('#editContentError').text('Content is required (minimum 10 characters)');
                                }
                                if (errors.includes('Type')) {
                                    $('#editTypeError').text('Please select a valid type');
                                }
                                if (errors.includes('Status')) {
                                    $('#editStatusError').text('Please select a valid status');
                                }
                            } else {
                                showAlert(response.message, 'error');
                            }
                        },
                        error: function() {
                            showAlert('An error occurred. Please try again!', 'error');
                        },
                        complete: function() {
                            $('#editNewsForm button[type="submit"]').html('<i class="fas fa-sync-alt"></i> Update Item').prop('disabled', false);
                        }
                    });
                });
                
                // Preview News Button Click
                $(document).on('click', '.preview-news', function() {
                    var newsId = $(this).data('id');
                    
                    $.ajax({
                        url: '<?php echo base_url("welcome/previewnews_ajax"); ?>/' + newsId,
                        method: 'GET',
                        dataType: 'json',
                        beforeSend: function() {
                            $('#previewContent').html('<div class="text-center"><i class="fas fa-spinner fa-spin fa-2x"></i><p>Loading...</p></div>');
                            $('#previewNewsModal').modal('show');
                        },
                        success: function(response) {
                            if (response.status === 'success') {
                                var news = response.news;
                                
                                var previewHtml = `
                                    <div class="news-preview">
                                        <h2 class="mb-3">${news.title}</h2>
                                        
                                        <div class="row mb-4">
                                            <div class="col-md-6">
                                                <span class="badge badge-info">${news.type}</span>
                                                <span class="badge ${news.status === 'published' ? 'badge-success' : 'badge-secondary'} ml-2">${news.status}</span>
                                                ${news.is_featured == 1 ? '<span class="badge badge-warning ml-2"><i class="fas fa-star"></i> Featured</span>' : ''}
                                            </div>
                                            <div class="col-md-6 text-right text-muted">
                                                ${news.publish_date ? 'Publish: ' + new Date(news.publish_date).toLocaleDateString() : ''}
                                                ${news.views ? ' | Views: ' + news.views : ''}
                                            </div>
                                        </div>
                                        
                                        ${news.featured_image ? `
                                            <div class="text-center mb-4">
                                                <img src="<?php echo base_url('uploads/news/'); ?>${news.featured_image}" class="img-fluid rounded" style="max-height: 400px;">
                                            </div>
                                        ` : ''}
                                        
                                        ${news.excerpt ? `
                                            <div class="alert alert-info">
                                                <h5><i class="fas fa-quote-left"></i> Excerpt</h5>
                                                <p>${news.excerpt}</p>
                                            </div>
                                        ` : ''}
                                        
                                        <div class="content-preview border p-3 rounded">
                                            ${news.content}
                                        </div>
                                        
                                        ${news.meta_keywords || news.meta_description ? `
                                            <div class="mt-4 p-3 bg-light rounded">
                                                ${news.meta_keywords ? `<p><strong>Meta Keywords:</strong> ${news.meta_keywords}</p>` : ''}
                                                ${news.meta_description ? `<p><strong>Meta Description:</strong> ${news.meta_description}</p>` : ''}
                                            </div>
                                        ` : ''}
                                    </div>
                                `;
                                
                                $('#previewContent').html(previewHtml);
                            } else {
                                $('#previewContent').html('<div class="alert alert-danger">Error loading preview!</div>');
                            }
                        },
                        error: function() {
                            $('#previewContent').html('<div class="alert alert-danger">Error loading preview!</div>');
                        }
                    });
                });
                
                // Delete News Button Click
                $(document).on('click', '.delete-news', function() {
                    var newsId = $(this).data('id');
                    var newsTitle = $(this).data('title');
                    
                    $('#deleteNewsTitle').text(newsTitle);
                    $('#confirmDeleteNewsBtn').data('id', newsId);
                    $('#deleteNewsModal').modal('show');
                });
                
                // Confirm Delete Button Click
                $('#confirmDeleteNewsBtn').click(function() {
                    var newsId = $(this).data('id');
                    
                    $.ajax({
                        url: '<?php echo base_url("welcome/deletenews_ajax"); ?>',
                        method: 'POST',
                        data: {id: newsId},
                        dataType: 'json',
                        beforeSend: function() {
                            $('#confirmDeleteNewsBtn').html('<i class="fas fa-spinner fa-spin"></i> Deleting...').prop('disabled', true);
                        },
                        success: function(response) {
                            if (response.status === 'success') {
                                showAlert(response.message, 'success');
                                $('#deleteNewsModal').modal('hide');
                                refreshTable();
                            } else {
                                showAlert(response.message, 'error');
                            }
                        },
                        error: function() {
                            showAlert('An error occurred. Please try again!', 'error');
                        },
                        complete: function() {
                            $('#confirmDeleteNewsBtn').html('<i class="fas fa-trash"></i> Delete Item').prop('disabled', false);
                        }
                    });
                });
                
                // Reset forms when modals are hidden
                $('#addNewsModal').on('hidden.bs.modal', function() {
                    $('#addNewsForm')[0].reset();
                    $('#imagePreview').hide();
                    $('.custom-file-label').html('Choose file');
                    $('.form-text.text-danger').text('');
                    
                    // Reset CKEditor if exists
                    <?php if (file_exists(APPPATH . 'libraries/Ckeditor.php')): ?>
                        if (CKEDITOR.instances.addContent) {
                            CKEDITOR.instances.addContent.setData('');
                        }
                    <?php endif; ?>
                });
                
                $('#editNewsModal').on('hidden.bs.modal', function() {
                    $('#editNewsForm')[0].reset();
                    $('#editImagePreview').hide();
                    $('#currentImagePreview').html('<p class="text-muted">No image</p>');
                    $('#removeImageBtn').hide();
                    $('.custom-file-label').html('Choose file');
                    $('.form-text.text-danger').text('');
                    
                    // Reset CKEditor if exists
                    <?php if (file_exists(APPPATH . 'libraries/Ckeditor.php')): ?>
                        if (CKEDITOR.instances.editContent) {
                            CKEDITOR.instances.editContent.setData('');
                        }
                    <?php endif; ?>
                });
                
                // Search functionality
                $('#searchButton').click(function() {
                    var searchTerm = $('#newsSearch').val();
                    newsTable.search(searchTerm).draw();
                });
                
                $('#newsSearch').on('keyup', function(e) {
                    if (e.key === 'Enter') {
                        var searchTerm = $(this).val();
                        newsTable.search(searchTerm).draw();
                    }
                });
                
                // Auto-hide alerts when clicked
                $(document).on('click', '.alert', function() {
                    $(this).alert('close');
                });
            });
        </script>
    </body>
</html>