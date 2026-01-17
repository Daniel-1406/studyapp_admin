<!DOCTYPE html>
<html class="no-js" lang="en">

<head>
<?php 
    $this->load->view("ui/load/headlinks");
    ?>
</head>
<body class="biolife-body">
 
    <!-- Preloader -->
    <div id="biof-loading">
        <div class="biof-loading-center">
            <div class="biof-loading-center-absolute">
                <div class="dot dot-one"></div>
                <div class="dot dot-two"></div>
                <div class="dot dot-three"></div>
            </div>
        </div>
    </div>

    <!-- HEADER -->
    <header id="header" class="header-area style-01 layout-03">
    <?php 
    $this->load->view("ui/load/head");
    ?>
    </header>

    <!--Hero Section-->
    <div class="hero-section hero-background" style="background-image: url('<?php echo base_url('images/login.jpg')?>');">
        <h1 class="page-title">Sign In</h1>
    </div>

    <!--Navigation section-->
    <div class="container">
        <nav class="biolife-nav">
            <ul>
                <li class="nav-item"><a href="index-2.html" class="permal-link">Home</a></li>
                <li class="nav-item"><span class="current-page">Sign In</span></li>
            </ul>
        </nav>
    </div>

    <div class="page-contain shopping-cart">

    <div id="main-content" class="main-content">
    <div class="container">

        <div class="row">
<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12" id="signin-section">
    <div class="signin-container">
        <?php
        echo form_open("home/signinuser"); // This correctly points to the signinuser method

        // Display validation errors
        // Consider using form_error() for specific fields as shown below for better UX
        // echo '<div style="text-align:center; color:red;"><b>' . validation_errors() . '</b></div>';

        // Display login feedback (e.g., wrong email/password)
        if (isset($loginfeedback)) {
            echo '<p style="text-align:center;"><b style="color:red;">' . htmlspecialchars($loginfeedback) . '</b></p>';
        }
        if (isset($registration_feedback)) {
            echo '<p style="text-align:center;"><b style="color:green; font-weight:bold;">' . htmlspecialchars($registration_feedback) . '</b></p>';
        }
        if (isset($feedback)) {
            echo '<p style="text-align:center;"><b style="color:green; font-weight:bold;">' . htmlspecialchars($feedback) . '</b></p>';
        }

        ?>
        <p class="form-row">
            <label for="fid-email">Email Address:<span class="requite">*</span></label>
            <?php
            // Changed input name to 'login-email' and type to 'email'
            print form_input("login-email", set_value("login-email", ""), ' class="txt-input" id="fid-email" type="email" required');
            echo form_error('login-email', '<div style="color:red; font-size: 0.9em; margin-top: 5px;">', '</div>'); // Display error for this field
            ?>
        </p>
        <p class="form-row">
            <label for="fid-pass">Password:<span class="requite">*</span></label>
            <?php
            print form_password("login-password", set_value("login-password", ""), ' class="txt-input" id="fid-pass" required');
            echo form_error('login-password', '<div style="color:red; font-size: 0.9em; margin-top: 5px;">', '</div>'); // Display error for this field
            ?>
        </p>
        <p class="form-row wrap-btn">
            <button class="btn btn-submit btn-bold" type="submit">sign in</button>
            <a href="<?= site_url('home/forgotPassword'); ?>" class="link-to-help">Forgot your password</a>
        </p>
        <?php echo form_close(); ?>
    </div>
</div>

           
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12" id="register-prompt-section">
                <div class="register-in-container">
                    <div class="intro">
                        <h4 class="box-title">New Customer?</h4>
                        <p class="sub-title">Create an account with us and you’ll be able to:</p>
                        <ul class="lis">
                            <li>Save items to your cart</li>
                            <li>Track new orders</li>
                            <li>Access your order history</li>
                            <li>Check out faster</li>
                            <li>Save shipping address</li>
                        </ul>
                        <a href="./createaccount" class="btn btn-bold" id="create-account-btn">Create an account</a>
                    </div>
                </div>
            </div>


        </div> </div> </div>

  
    <!-- FOOTER -->
    <footer id="footer" class="footer layout-03">
        <?php $this->load->view("ui/load/footer");?>
    </footer>

    <!--Footer For Mobile-->
    <div class="mobile-footer">
    <?php $this->load->view("ui/load/mobile_footer");?>
    </div>

    <div class="mobile-block-global">
    <?php $this->load->view("ui/load/mobile_footer_global");?>

    </div>
    <?php 
    $this->load->view("ui/load/quick_view_popup");
    ?>

    <?php 
    $this->load->view("ui/load/footerlinks");
    ?>



</body>

</html>