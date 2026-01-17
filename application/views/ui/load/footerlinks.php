
    <!-- Scroll Top Button -->
    <a class="btn-scroll-top"><i class="biolife-icon icon-left-arrow"></i></a>
    <?php 
    $this->load->view("ui/scripts/get-details");
    ?>
   <script>
function addToCartHandler(productId) {
    console.log("addToCartHandler called for Product ID:", productId);

    // Get the quantity from the input field
    // Select the input element specifically for this product page
    var qtyInput = $('input[name="qty_' + productId + '"]'); // Using the name attribute
    // OR if you added the class 'product-qty-input' as suggested:
    // var qtyInput = $('.qty-input .product-qty-input'); 

    var qty = parseInt(qtyInput.val()); // Get the value and convert to integer

    // Basic validation: ensure quantity is a positive number
    if (isNaN(qty) || qty < 1) {
        qty = 1;
        // alert("Please enter a valid quantity.");
        // return; // Stop the function if quantity is invalid
    }

    $.ajax({
        url: "<?php echo site_url("home/addtocart")?>",
        type: "POST",
        dataType: "json",
        data: {
            productId: productId,
            qty: qty // Pass the dynamic quantity here
        },
        success: function(response) {
            console.log("AJAX success response:", response);
            alert(response.message);
            // Optionally, update mini-cart or other UI elements here
        },
        error: function(xhr, status, error) {
            console.error("AJAX Error:", xhr.status, xhr.responseText, status, error);

            if (xhr.status === 302 || xhr.status === 0 || xhr.responseText.includes('login')) {
                console.log("Redirect detected or login required. Redirecting to login page...");
                window.location.href = "<?php echo site_url("home/login")?>";
            } else {
                alert("An error occurred. Please try again.");
            }
        }
    });
}</script>


    <script src="https://js.paystack.co/v2/inline.js"></script>
    <script src="<?php echo base_url(); ?>ui_assets/js/jquery-3.4.1.min.js"></script>
    <script src="<?php echo base_url(); ?>ui_assets/js/bootstrap.min.js"></script>
    <script src="<?php echo base_url(); ?>ui_assets/js/jquery.countdown.min.js"></script>
    <script src="<?php echo base_url(); ?>ui_assets/js/jquery.nice-select.min.js"></script>
    <script src="<?php echo base_url(); ?>ui_assets/js/jquery.nicescroll.min.js"></script>
    <script src="<?php echo base_url(); ?>ui_assets/js/slick.min.js"></script>
    <script src="<?php echo base_url(); ?>ui_assets/js/biolife.framework.js"></script>
    <script src="<?php echo base_url(); ?>ui_assets/js/functions.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
