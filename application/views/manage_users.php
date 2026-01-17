<!DOCTYPE html>
<html lang="en">
    <style>
        body {font-family: Arial, Helvetica, sans-serif;}


@-webkit-keyframes animatetop {
  from {top:-300px; opacity:0} 
  to {top:0; opacity:1}
}

@keyframes animatetop {
  from {top:-300px; opacity:0}
  to {top:0; opacity:1}
}

/* The Close Button */
.close {
  color: white;
  float: right;
  font-size: 28px;
  font-weight: bold;
}

.close:hover,
.close:focus {
  color: #000;
  text-decoration: none;
  cursor: pointer;
}

.modal-header {
  padding: 2px 16px;
  background-color: #5cb85c;
  color: white;
}

.modal-body {padding: 2px 16px;}

.modal-footer {
  padding: 2px 16px;
  background-color: #5cb85c;
  color: white;
}
    </style>
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
                                    <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                                    <li class="breadcrumb-item active">Users</li>
                                </ol>
                            </div> 
                        </div>
                    </div><!-- /.container-fluid -->
                </section>

                <section class="content">
    <div class="container-fluid">
        <?php if ($this->session->flashdata('success_message')): ?>
            <div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <h5><i class="icon fas fa-check"></i> Success!</h5>
                <?php echo $this->session->flashdata('success_message'); ?>
            </div>
        <?php endif; ?>
        <?php if ($this->session->flashdata('error_message')): ?>
            <div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <h5><i class="icon fas fa-ban"></i> Error!</h5>
                <?php echo $this->session->flashdata('error_message'); ?>
            </div>
        <?php endif; ?>

        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Details of Registered Users</h3>
                <div class="card-tools">
                    <!-- <a href="<?php echo site_url('users/createuser'); ?>" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i> Add New User
                    </a> -->
                </div>
            </div>
            <div class="card-body">
                <table id="example1" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>N/A</th>
                            <th>ID</th>
                            <th>Full Name</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Telephone</th>
                            <th>Created At</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $x=1; if (!empty($users)): ?>
                            <?php foreach ($users as $user): ?>
                                <tr>
                                    <td><?php echo $x?></td>
                                    <td><?php echo htmlspecialchars($user->id); ?></td>
                                    <td><?php echo htmlspecialchars($user->fullname); ?></td>
                                    <td><?php echo htmlspecialchars($user->username); ?></td>
                                    <td><?php echo htmlspecialchars($user->email); ?></td>
                                    <td><?php echo htmlspecialchars($user->telephone); ?></td>
                                    <td><?php echo date('l, F j, Y g:iA', strtotime($user->created_at)); ?></td>
                                    <td>
                                    <span class="badge <?php
                                        switch ($user->status) {
                                            case 'verified':
                                                echo 'badge-success';
                                                break;
                                            case 'blocked':
                                                echo 'badge-danger';
                                                break;
                                            case 'registered':
                                                echo 'badge-secondary';
                                                break;
                                            default:
                                                echo 'badge-info'; // A default badge color for any other status
                                                break;
                                        }
                                    ?>">
                                        <?php echo htmlspecialchars(ucfirst($user->status)); ?>
                                    </span>
                                    </td>
                                    <td>
                                        <div class="btn-group-vertical">
                                            <a class="btn btn-info btn-sm mb-1" href='<?php echo site_url("users/editthisuser/" . $user->id); ?>'><i class='fas fa-pencil-alt'></i> Edit User</a>
                                            <a class="btn btn-primary btn-sm mb-1" href='<?php echo site_url("users/viewcart/" . $user->id); ?>'><i class='fas fa-shopping-cart'></i> View Cart</a>
                                            <a class="btn btn-warning btn-sm mb-1" href='<?php echo site_url("users/viewuserorders/" . $user->id); ?>'><i class='fas fa-receipt'></i> Previous Orders</a>
                                            <a class="btn btn-danger btn-sm mb-1" href='<?php echo site_url("users/deletethisuser/" . $user->id); ?>' onclick="return confirm('Are you sure you want to delete this user? This will mark them as deleted and affect their associated data.');"><i class='fas fa-trash'></i> Delete User</a>
                                        </div>
                                    </td>
                                </tr>
                            <?php $x++; endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="8" class="text-center">No users found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th>N/A</th>
                            <th>ID</th>
                            <th>Full Name</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Telephone</th>
                            <th>Created At</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
            </div>
        </div></section>
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
