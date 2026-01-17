<!DOCTYPE html>
<html lang="en">
<?php?>
    <?php $this->load->view("load/header_main") ?>
    <body class="hold-transition sidebar-mini">
        <!-- Pre loader -->
        <div id="preloader-overlay">
            <div class="preloader-spinner"></div>
        </div>
        <div class="wrapper">
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
                                <h1><b>Hi <?php echo strtoupper($this->session->userdata("admin_pass")) ?>!</b></h1>
                            </div>
                        </div>
                    </div>
                </section>
                <section class="content"> 
                    <div class="container-fluid">
                        <div class="row">
                          
                            <div class="col-lg-3 col-6">
                                <div class="small-box bg-success">
                                <div class="inner">
                                        <h3>₦
                                            <?php 
                                                    echo isset($revenue) ? $revenue : 0.00; 
                                            ?>
                                        </h3>
                                        <p>Questions</p>
                                    </div>
                                    <div class="icon">
                                    <i class="nav-icon fas fa-money-bill"></i>
                                    </div>
                                    <a href="javascript:void(0)" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                                </div>
                            </div>
                            
                            <div class="col-lg-3 col-6">
                                <div class="small-box bg-warning">
                                    <div class="inner">
                                        <h3>
                                        <?php 
                                                // Check if the variable is set and not empty, otherwise default to 0.
                                                echo isset($new_orders_count) ? $new_orders_count : 0; 
                                        ?>
                                        </h3>

                                        <p>Schools</p>
                                    </div>
                                    <div class="icon">
                                    <i class="nav-icon fas fas fa-shopping-basket"></i>
                                    </div>
                                    <a href="javascript:void(0)" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                                </div>
                            </div>
                            <!-- <div class="col-lg-3 col-6">
                                <div class="small-box bg-success">
                                    <div class="inner">
                                        <h3>
                                        <?php 
                                                // Check if the variable is set and not empty, otherwise default to 0.
                                                echo isset($new_users_count) ? $new_users_count : 0; 
                                        ?>
                                        </h3>

                                        <p>New Users</p>
                                    </div>
                                    <div class="icon">
                                    <i class="nav-icon fas fas fa-user-plus"></i>
                                    </div>
                                    <a href="javascript:void(0)" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                                </div>
                            </div> -->
                            <div class="col-lg-3 col-6">
                                <div class="small-box bg-danger">
                                    <div class="inner">
                                        <h3>
                                        <?php 
                                            echo isset($unsuccess_count) ? $unsuccess_count : 0; 
                                        ?>
                                        </h3>

                                        <p>Departments</p>
                                    </div>
                                    <div class="icon">
                                    <i class="nav-icon fas fa-history"></i>
                                    </div>
                                    <a href="javascript:void(0)" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                                </div>
                            </div>
                            <div class="col-lg-3 col-6">
                                <div class="small-box bg-secondary">
                                    <div class="inner">
                                        <h3>
                                        <?php 
                                            echo isset($successfulorders) ? $successfulorders : 0; 
                                        ?>
                                        </h3>

                                        <p>Notes</p>
                                    </div>
                                    <div class="icon">
                                    <i class="nav-icon fas fa-check-circle"></i>
                                    </div>
                                    <a href="javascript:void(0)" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                                </div>
                            </div>
                            <div class="col-lg-3 col-6">
                                <div class="small-box bg-light">
                                    <div class="inner">
                                        <h3>
                                        <?php 
                                            echo isset($deliveredorders) ? $deliveredorders : 0; 
                                        ?>
                                        </h3>

                                        <p>Topics</p>
                                    </div>
                                    <div class="icon">
                                    <i class="nav-icon fas fa-truck"></i>
                                    </div>
                                    <a href="javascript:void(0)" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                                </div>
                            </div>
                            <div class="col-lg-3 col-6">
                                <div class="small-box bg-primary">
                                    <div class="inner">
                                        <h3>
                                        <?php 
                                            echo isset($verifiedUsers) ? $verifiedUsers : 0; 
                                        ?>
                                        </h3>

                                        <p>Users</p>
                                    </div>
                                    <div class="icon">
                                    <i class="nav-icon fas fa-users"></i>
                                    </div>
                                    <a href="javascript:void(0)" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                                </div>
                            </div>
                            <div class="col-lg-3 col-6">
                                <div class="small-box bg-dark">
                                    <div class="inner">
                                        <h3>
                                        <?php 
                                            echo isset($countActiveProducts) ? $countActiveProducts : 0; 
                                        ?>
                                        </h3>

                                        <p>Products</p>
                                    </div>
                                    <div class="icon">
                                    <i class="nav-icon fas fa-tags"></i>
                                    </div>
                                    <a href="javascript:void(0)" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                                </div>
                            </div>
                            <div class="col-lg-3 col-6">
                                <div class="small-box bg-pink">
                                <div class="inner">
                                        <h3>
                                            <?php 
                                                // Check if the variable is set and not empty, otherwise default to 0.
                                                echo isset($visitors_count) ? $visitors_count : 0; 
                                            ?>
                                        </h3>
                                        <p>Visits</p>
                                    </div>
                                    <div class="icon">
                                    <i class="nav-icon fas fa-eye"></i>
                                    </div>
                                    <a href="javascript:void(0)" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                                </div>
                            </div>
                            

                           
                    </div>
                </section>
                <!-- /.content -->
            </div>
            <!-- /.content-wrapper -->
            <?php $this->load->view("load/footer") ?>


        </div>
        <!-- ./wrapper -->
        <script>
		 window.addEventListener('load', function() {
        const preloader = document.getElementById('preloader-overlay');
        if (preloader) {
            preloader.style.display = 'none';
        }
    });

		</script>
    </body>
</html>
