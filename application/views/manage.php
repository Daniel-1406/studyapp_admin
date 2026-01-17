<!DOCTYPE html>
<html lang="en">
    <?php $this->load->view("load/header_main") ?>
    <body class="hold-transition sidebar-mini">
        <!-- Pre loader -->
        <div id="preloader-overlay">
            <div class="preloader-spinner"></div>
        </div>
    <?php if(isset($feedback)) echo $feedback?>

        <div class="wrapper">
            <??>
            <?php $this->load->view("load/header") ?>
            <!-- /.navbar -->

            <!-- Main Sidebar Container -->
            
            <?php $this->load->view("load/sidelinks") ?>

            <!-- Content Wrapper. Contains page content -->

       
            <div class="content-wrapper">
                <!-- Content Header (Page header) -->
                <section class="content-header">
                    <div class="container-fluid">
                        <div class="row mb-2">
                            <div class="col-sm-6">
                                <h1>Management Area</h1>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                                    <li class="breadcrumb-item active">General Form</li>
                                </ol>
                            </div> 
                        </div>
                    </div><!-- /.container-fluid -->
                </section>

                <!-- Main content -->
                <section class="content"> 
                    <div class="container-fluid">
                    <?php if ($this->session->flashdata('success_message')): ?>
                            <div class="alert alert-success alert-dismissible">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                <h5><i class="icon fas fa-check"></i> Success!</h5>
                                <?php echo $this->session->flashdata('success_message'); // Changed 'success' to 'success_message' ?>
                            </div>
                        <?php endif; ?>

                        <?php if ($this->session->flashdata('error_message')): ?>
                            <div class="alert alert-danger alert-dismissible">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                <h5><i class="icon fas fa-ban"></i> Error!</h5>
                                <?php echo $this->session->flashdata('error_message'); // Changed 'error' to 'error_message' ?>
                            </div>
                        <?php endif; ?>
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Details of Contents present</h3>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body" style="width:100%;">
                                <table id="example1" class="table table-bordered table-striped">
                                    <thead>
                                        <?php echo $dbhead ?>
                                    </thead>
                                    <tbody>
                                        <?php echo $dbbody ?>
                                    </tbody>
                                    <tfoot>
                                        <?php echo $dbhead ?>
                                    </tfoot>
                                </table>
                            </div>
                            <!-- /.card-body -->
                        </div>
                    </div>
                </section>
                <!-- /.content -->
            </div>
            <!-- /.content-wrapper -->
            <?php $this->load->view("load/footer") ?>


        </div>
        <div id="feedback" class="modal">
                             <?php if(isset($feedback)){echo $feedback;}?>
                        </div>
        <!-- ./wrapper -->
          

    </body>

    <script>
        $(function() {
            $("#example1").DataTable({
                "responsive": true, "lengthChange": false, "autoWidth": false,
                "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]

            }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
            $('#example2').DataTable({
                "paging": true,
                "lengthChange": false,
                "searching": false,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                "responsive": true,
            });
        });
		 window.addEventListener('load', function() {
        const preloader = document.getElementById('preloader-overlay');
        if (preloader) {
            preloader.style.display = 'none';
        }
    });


       
    </script>
</html>
