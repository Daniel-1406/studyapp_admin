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
    <body class="hold-transition sidebar-mini" onload="fedbackmessage()" id="body">
        <div class="wrapper">
            <??>
            <?php $this->load->view("load/header") ?>
    
            <?php $this->load->view("load/sidelinks") ?>

            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper" style="background-color:white;">
                <!-- Content Header (Page header) -->
                <section class="content-header" style="background-color:white;">
                    <div class="container-fluid">
                        <div class="row mb-2">
                            <div class="col-sm-6">
                                <h1><b>Feedback</b></h1>
                            </div>
                        </div>
                    </div><!-- /.container-fluid -->
                </section>

                <!-- Main content -->
                <section class="content">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-12">
                                <!-- general form elements -->
                                <div class="card card-primary">
                                    <div class="card-header">
                                        <h3 class="card-title">Feedback</h3>
                                    </div>



                                    <form>
                                        <div class="card-body">

                                            <?php
                                                echo  $feedback ;
                                            ;
                                            ?> 


                                        </div>
                                        <!-- /.card-body -->

                                         <div class="card-footer ">
                                            <a href="<?php echo base_url(); ?>welcome/opendashboard"><button type="button" class="btn btn btn-danger "><i class="fa fa-back"></i>Back</button></a>
                                        </div>
                                    </form>
                                </div>
                                <!-- /.row -->
                            </div><!-- /.container-fluid -->
                            </section>









                            <!-- /.content -->
                        </div>
                        <!-- /.content-wrapper -->
                        <?php $this->load->view("load/footer") ?>

                    </div>
                    <!-- <div id="feedback" class="modal">
                            
                        </div> -->
                    
                
                                        
                    <script>
                    // Get the modal
                    var modal = document.getElementById("feedback");

                    // Get the button that opens the modal
                    var btn = document.getElementById("body");

                    // Get the <span> element that closes the modal
                    var span = document.getElementsByClassName("close")[0];

                    // When the user clicks the button, open the modal 
                    btn.onload = function() {
                    modal.style.display = "block";
                    }

                    // When the user clicks on <span> (x), close the modal
                    span.onclick = function() {
                    modal.style.display = "none";
                    }

                    // When the user clicks anywhere outside of the modal, close it
                    window.onclick = function(event) {
                    if (event.target == modal) {
                        modal.style.display = "none";
                    }
                    }
                    </script>

                    </body>
                    </html>
