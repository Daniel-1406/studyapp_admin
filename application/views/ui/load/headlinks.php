<?php 

  // --- Start of unique visitor tracking logic ---
  if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$filePath = FCPATH . 'images\visitors.txt'; 


// Get the user's IP address
function get_ip_address() {
if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
    return $_SERVER['HTTP_CLIENT_IP'];
} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    return $_SERVER['HTTP_X_FORWARDED_FOR'];
} else {
    return $_SERVER['REMOTE_ADDR'];
}
}

$ipAddress = get_ip_address();

// Check if the user's session has already been counted
if (!isset($_SESSION['counted_visitor'])) {
// Read existing unique IPs from the file
$visitors = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

// Check if the current IP is already in the list
if (!in_array($ipAddress, $visitors)) {
    // If not, add it and mark the session
    file_put_contents($filePath, $ipAddress . PHP_EOL, FILE_APPEND);
    $_SESSION['counted_visitor'] = true;
}
}


?>
<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="keywords" content="glamour collections, onlinestore, ecommerce">
    <meta name="description" content="<?php echo isset($information) ? htmlspecialchars($information) : ''; ?>">
    <title><?php echo isset($name) ? htmlspecialchars($name).' | ' : ''; ?><?php echo isset($thispage) ? htmlspecialchars($thispage) : ''; ?></title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css?family=Cairo:400,600,700&amp;display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Poppins:600&amp;display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Playfair+Display:400i,700i" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Ubuntu&amp;display=swap" rel="stylesheet">
    <link rel="shortcut icon" type="image/x-icon" href="<?php print base_url()?>images/<?php echo $logo ?>" />
    <link rel="stylesheet" href="<?php echo base_url(); ?>ui_assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>ui_assets/css/animate.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>ui_assets/css/font-awesome.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>ui_assets/css/nice-select.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>ui_assets/css/slick.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>ui_assets/css/style.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>ui_assets/css/main-color04.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<style>
    /* Basic styling for error messages */
    .error-message {
        color: red;
        font-size: 0.9em;
        margin-top: 5px;
    }
    .alert {
        padding: 15px;
        margin-bottom: 20px;
        border: 1px solid transparent;
        border-radius: 4px;
    }
    .alert-success {
        color: #3c763d;
        background-color: #dff0d8;
        border-color: #d6e9c6;
    }
    .alert-danger {
        color: #a94442;
        background-color: #f2dede;
        border-color: #ebccd1;
    }
    /* Basic styling for the floating WhatsApp icon */
.whatsapp-float {
    position: fixed;
    bottom: 130px; /* Adjust as needed */
    right: 20px;  /* Adjust as needed */
    z-index: 1000; /* Ensures it's on top of other content */
    display: block;
    width: 60px; /* Adjust size as needed */
    height: 60px;
    border-radius: 50%;
    overflow: hidden;
    box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
    transition: transform 0.3s ease-in-out;
}

.whatsapp-icon {
    width: 100%;
    height: auto;
    display: block;
}

.whatsapp-float:hover {
    transform: scale(1.1);
}

/* New styling for the pop-up text (tooltip) */
.whatsapp-text {
    visibility: hidden;
    width: 120px;
    background-color: #333;
    color: #fff;
    text-align: center;
    border-radius: 6px;
    padding: 5px 0;
    
    /* Position the tooltip */
    position: absolute;
    z-index: 1;
    bottom: 50%; /* Position it vertically centered with the icon */
    left: 110%; /* Place it to the right of the icon */
    margin-left: 10px; /* Add some space between the icon and the text */

    opacity: 0;
    transition: opacity 0.3s;
}

/* Show the pop-up text on hover */
.whatsapp-float:hover .whatsapp-text {
    visibility: visible;
    opacity: 1;
}
.biolife-banner__promotion2 .media{
    background-image: url(<?php print base_url()?>images/<?php echo "woman7.png" ?>);
}
.newsletter-block_popup-layout{
    background-image: url(<?php print base_url()?>images/<?php echo "no_bg1.png" ?>);
    
}
</style>

<?php 
    $this->load->view("ui/load/color");
    ?>
    