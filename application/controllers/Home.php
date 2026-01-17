<?php

defined('BASEPATH') OR exit('No direct script access allowed');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP; // Make sure SMTP is also imported


class Home extends CI_Controller {
    private $app_password;
    private $paystack_secret_key;
    private $paystack_public_key;
    // When going live, switch to:
    // private $paystack_secret_key = 'sk_live_YOUR_ACTUAL_LIVE_SECRET_KEY';
    // private $paystack_public_key = 'pk_live_YOUR_ACTUAL_LIVE_PUBLIC_KEY';
    // ...
    
    protected $common_data = [];

    public function __construct() {
        parent::__construct();
        $this->load->library('session');
        $this->load->library('pagination'); // Load the pagination library
        $this->load->helper('url'); // For base_url() and site_url()
        $this->load->model('ordermodel');

         // Prepare the common data block and store it in a class property
         $data = $this->storeinfo->getstoreinfo();
         $data["menu"] = $this->menumodel->getmainmenufordisplay();
         $data["faqs"] = $this->carouselmodel->getfaqs();
         $data["carousels"] = $this->carouselmodel->getcarousels();
         $data["categoryWithProductNum"] = $this->categorymodel->getmaincategorywithproductnum();
         $data["allCategories"] = $this->categorymodel->getAllCategories();
         $data["newStock"] = $this->productmodel->getNewStock();
         $data["getTabbedProducts"] = $this->productmodel->getTabbedProductsHTML();
         $data["minicart_items"] = $this->usermodel->fetchThisUserMiniCart();
 
         // Calculate minicart totals here
         $total_qty = 0;
         $sub_total = 0.00;
         if ($data["minicart_items"]) {
             foreach ($data["minicart_items"] as $item) {
                 $item_qty = (int) $item->qty;
                 $item_price = (float) $item->pr_sell_price;
                 $total_qty += $item_qty;
                 $sub_total += ($item_qty * $item_price);
             }
         }
         $data['minicart_total_qty'] = $total_qty;
         $data['minicart_sub_total'] = $sub_total;
 
         // Store the data in a protected class property
         $this->common_data = $data;
         $this->loadPaystackKeys();
         $this->loadAppPassword();

    }

    private function clean_price($price) {
        if (is_numeric($price)) {
            return (float)$price;
        }
        return (float)str_replace(',', '', $price);
    }
    private function loadPaystackKeys() {
            $this->paystack_public_key = getenv('PAYSTACK_PUBLIC_KEY');
            $this->paystack_secret_key = getenv('PAYSTACK_SECRET_KEY');
           
    }

    private function loadAppPassword() {
            // $this->app_password = $this->secrets->getAppPassword();
            $this->app_password = getenv('GMAIL_APP_PASSWORD');
            
    }

   
    public function index() {


        

        $data = $this->common_data;
        $data['thispage'] = 'Your Exclusive Skincare and Fashion Store in Nigeria';
        $mainCategoriesForDisplay = $this->categorymodel->getRandomMainCategories(3); // Assuming this function is in categorymodel

        $displayCategoryBlocks = array();
        foreach ($mainCategoriesForDisplay as $category) {
            $productsForCategory = $this->productmodel->getRandomProductsByCategory($category->id, 3); // Assuming this function is in productmodel
            if (!empty($productsForCategory)) {
                $displayCategoryBlocks[] = (object) [ // Cast to object for consistent access in view
                    'category_info' => $category,
                    'products'      => $productsForCategory
                ];
            }
        }
        $data['displayCategoryBlocks'] = $displayCategoryBlocks;
        $this->load->view('ui/index', $data);

    }

    
    // public function login() {
    //     $data = $this->storeinfo->getstoreinfo();
    //     $data["menu"] = $this->menumodel->getmainmenufordisplay();
    //    // unset($data['loginfeedback']); // Optional: if you want to clear feedback on page load


    //     $this->load->view('ui/login', $data);
    // }
	
	
    public function deletethiscartproduct() {
        if ($this->session->userdata("user") == "")
        redirect("home/registeruser");
        
        if(!$this->usermodel->deleteThisCartProduct($this->uri->segment(3))){
            $data["feedback"] = "Product Deleted From Cart";
            $this->cart();
        }else{
            $data["feedback"] = "Unable To Delete Product From Cart";
            $this->cart();
        }
    }



    public function login() {
        $data = $this->storeinfo->getstoreinfo();
        $data["menu"] = $this->menumodel->getmainmenufordisplay();
        $data['thispage'] = 'Sign In';

       // unset($data['loginfeedback']); // Optional: if you want to clear feedback on page load


        $this->load->view('ui/login', $data);
    }

    
    public function createAccount() {
        // Load necessary libraries
        $this->load->library('form_validation');
        $this->load->helper('form');
        $this->load->helper('string'); // Helper for random_string()

        // Set validation rules for the new account fields
        $this->form_validation->set_rules("full_name", "Full Name", "required|trim|min_length[3]|max_length[100]");
        $this->form_validation->set_rules("username", "Username", "required|trim|min_length[5]|max_length[50]|is_unique[users.username]|alpha_numeric",['is_unique[users.username]'=>'This username has been used already, try another username']);
        $this->form_validation->set_rules("email", "Email Address", "required|trim|valid_email|is_unique[users.email]",['is_unique[users.email]'=>'This email has been used already, try another valid email']);
        $this->form_validation->set_rules("password", "Password", "required|trim|min_length[8]");
        $this->form_validation->set_rules("confirm_password", "Confirm Password", "required|trim|matches[password]");
        $this->form_validation->set_rules("telephone", "Telephone", "required|trim|numeric");
        $this->form_validation->set_rules("address", "Address", "required|trim|max_length[255]"); // Validation for the new address field
        $this->form_validation->set_rules("shipping_region_id", "Shipping Region", "required|trim"); // Validation for the new address field

        if ($this->form_validation->run() == FALSE) {

            // Validation failed, reload the registration form with errors
            $data = $this->storeinfo->getstoreinfo();
            $data["menu"] = $this->menumodel->getmainmenufordisplay();
            $data['thispage'] = 'Create Account';
            $data['shipping_regions'] = $this->categorymodel->get_shipping_regions_alphabetical();
            $this->load->view("ui/register", $data);
        } else {
            // Validation passed, prepare data for model
            $verification_token = random_string('alnum', 32); // Generate a secure, random token

            $user_data = array(
                'full_name' => $this->input->post('full_name'),
                'username'  => $this->input->post('username'),
                'email'     => $this->input->post('email'),
                'password'  => $this->input->post('password'),
                'telephone' => $this->input->post('telephone'),
                'address'   => $this->input->post('address'),
                'shipping_region_id'   => $this->input->post('shipping_region_id'),
                'verification_token' => $verification_token // Pass the token to the model
            );

            if ($this->usermodel->registerUser($user_data)) {
                // Account created successfully in the database. Now send the verification email.
                $registration_status_message = "Thanks for creating your account! We've sent a verification email to your inbox. If you don't see it, please check your spam folder.";
                $registration_status_success = true;

                // --- PHPMailer for Verification Email ---

                require_once APPPATH . 'third_party/phpmailer/src/Exception.php';
                require_once APPPATH . 'third_party/phpmailer/src/PHPMailer.php';
                require_once APPPATH . 'third_party/phpmailer/src/SMTP.php';
            
                $mail = new PHPMailer(true);

                try {
                    $store_info = $this->storeinfo->getstoreinfo();
                    $mail->SMTPDebug = 0;
                    $mail->isSMTP();
                    $mail->Host       = 'smtp.gmail.com';
                    $mail->SMTPAuth   = true;
                    $mail->Username   = $store_info['email'];
                    $mail->Password   = $this->app_password;
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
                    $mail->Port       = 465;

                    $mail->setFrom($store_info["email"], $store_info["name"]);
                    $mail->addAddress($user_data["email"], $user_data["full_name"]); // Send to the new user
                    $mail->addReplyTo($store_info["email"], $store_info["name"]);

                    $verification_link = site_url("home/verifyAccount/{$verification_token}");

                    $mail->isHTML(true);
                    $mail->Subject = "Verify Your Account on " . $store_info["name"];
                    $mail->Body = "
    <h3>Welcome, " . htmlspecialchars($user_data['full_name']) . "!</h3>
    <p>Thank you for registering on our website. Please click the button below to verify your email address and activate your account:</p>
    <table role='presentation' border='0' cellpadding='0' cellspacing='0'>
      <tr>
        <td align='center'>
          <a href='{$verification_link}' style='display: inline-block; padding: 10px 20px; background-color: #007BFF; color: #ffffff; text-decoration: none; border-radius: 5px; font-weight: bold;'>Verify Your Account</a>
        </td>
      </tr>
    </table>
    <p>If you did not register for an account, you can safely ignore this email.</p>
    <p>Regards,<br>{$store_info['name']} Team</p>
";
                    if ($mail->send()) {
                        log_message('info', 'Verification email sent to new user: ' . $user_data['email']);
                    } else {
                        log_message('error', 'Verification email failed to send: ' . $mail->ErrorInfo);
                        $registration_status_message .= " However, we were unable to send you a verification email. Please contact support.";
                        $registration_status_success = false;
                    }

                } catch (Exception $e) {
                    log_message('error', "Verification email error: {$mail->ErrorInfo}");
                    $registration_status_message .= " However, an error occurred while sending the verification email. Please contact support.";
                    $registration_status_success = false;
                }

                // Load the login view with the feedback message
                $data = $this->storeinfo->getstoreinfo();
                $data["menu"] = $this->menumodel->getmainmenufordisplay();
                $data["registration_feedback"] = $registration_status_message;
                $data["registration_feedback_success"] = $registration_status_success;
                $data['thispage'] = 'Sign In';
                $data['shipping_regions'] = $this->categorymodel->get_shipping_regions_alphabetical();

                $this->load->view("ui/login", $data);

            } else {
                // Account creation failed (e.g., database error)
                $data = $this->storeinfo->getstoreinfo();
                $data["menu"] = $this->menumodel->getmainmenufordisplay();
                $data['shipping_regions'] = $this->categorymodel->get_shipping_regions_alphabetical();
                $data["registration_feedback"] = "Account creation failed. Please try again.";
                $data["registration_feedback_success"] = false;
                $data['thispage'] = 'Create Account';
                
                $this->load->view("ui/register", $data);
            }
        }
    }

    public function verifyAccount($token) {
        $data = $this->storeinfo->getstoreinfo();
        $data["menu"] = $this->menumodel->getmainmenufordisplay();
        $data["carousels"] = $this->carouselmodel->getcarousels();
        $data["categoryWithProductNum"] = $this->categorymodel->getmaincategorywithproductnum();
        $data["allCategories"] = $this->categorymodel->getAllCategories();
        $data["newStock"] = $this->productmodel->getNewStock();
        $data["getTabbedProducts"] = $this->productmodel->getTabbedProductsHTML();

        $data["minicart_items"] = $this->usermodel->fetchThisUserMiniCart(); // Your minicart items

        // Calculate minicart totals here
        $total_qty = 0;
        $sub_total = 0.00; // Initialize as float
        if ($data["minicart_items"]) { // Checks if 'minicart_items' exists and is not empty/false
            foreach ($data["minicart_items"] as $item) { // Loops through each item in the array/object
                // Ensure qty and price are treated as numbers for calculation
                $item_qty = (int) $item->qty; // Casts the item's quantity to an integer
                $item_price = (float) $item->pr_sell_price; // Casts the item's selling price to a float
        
                $total_qty += $item_qty; // Accumulates the total quantity
                $sub_total += ($item_qty * $item_price); // Accumulates the sub-total
            }
        }
        $data['minicart_total_qty'] = $total_qty;
        $data['minicart_sub_total'] = $sub_total;


        // --- NEW IMPLEMENTATION FOR CATEGORY-BASED PRODUCT DISPLAY ---

        // 1. Get random main categories (e.g., max 3 as discussed)
        $mainCategoriesForDisplay = $this->categorymodel->getRandomMainCategories(3); // Assuming this function is in categorymodel

        // 2. Prepare data structure for the view
        $displayCategoryBlocks = array();
        foreach ($mainCategoriesForDisplay as $category) {
            // Get random products for each category (e.g., max 3 products per category)
            $productsForCategory = $this->productmodel->getRandomProductsByCategory($category->id, 3); // Assuming this function is in productmodel

            // Only add the category block if it actually has products to display
            if (!empty($productsForCategory)) {
                $displayCategoryBlocks[] = (object) [ // Cast to object for consistent access in view
                    'category_info' => $category,
                    'products'      => $productsForCategory
                ];
            }
        }

        // Add the prepared data to the $data array that's passed to the view
        $data['displayCategoryBlocks'] = $displayCategoryBlocks;
        // --- END NEW IMPLEMENTATION ---
       
        // Find the user with this verification token
        $user = $this->usermodel->getUserByVerificationToken($token);

        if ($user) {
            // User found, now update their status
            if ($this->usermodel->verifyUser($user->id)) {
                // Verification successful
                $data["verification_feedback"] = "Your account has been successfully verified! You can now log in.";
                $data["verification_feedback_success"] = true;
            } else {
                // Error updating user status
                $data["verification_feedback"] = "An error occurred during verification. Please contact support.";
                $data["verification_feedback_success"] = false;
            }
        } else {
            // Invalid or expired token
            $data["verification_feedback"] = "Invalid verification link. The link may have expired or been used already.";
            $data["verification_feedback_success"] = false;
        }

        // Load a generic verification feedback view
        $this->load->view("ui/verification_feedback", $data); // Create this new view file
    }


     public function signinuser() {
        // Load form validation and helper libraries
        $this->load->library('form_validation');
        $this->load->helper('form');

        // Set validation rules
        $this->form_validation->set_rules("login-email", "Email Address", "required|trim|valid_email");
        $this->form_validation->set_rules("login-password", "Password", "required|trim|min_length[8]");

        if ($this->form_validation->run() == FALSE) {
            // Validation failed, reload login form with errors
            $data = $this->storeinfo->getstoreinfo();
            $data["menu"] = $this->menumodel->getmainmenufordisplay();
            $data['thispage'] = 'Sign In';
            $this->load->view("ui/login", $data);
        } else {
            // Validation passed, attempt login
            $login_result = $this->usermodel->userlogin();
            
            if ($login_result === TRUE) {
                // Login successful, redirect
                redirect('home/dashboard');
            } elseif ($login_result === 'unverified') {
                // User's account is not verified
                $data = $this->storeinfo->getstoreinfo();
                $data["menu"] = $this->menumodel->getmainmenufordisplay();
                $data["loginfeedback"] = "Your account has not been verified. Please check your email for the verification link.";
                $this->load->view("ui/login", $data);
            } elseif($login_result === 'blocked'){
                // User's account is not verified
                $data = $this->storeinfo->getstoreinfo();
                $data["menu"] = $this->menumodel->getmainmenufordisplay();
                $data["loginfeedback"] = "Your account has not been blocked. Please contact the admin to authorize this account.";
                $this->load->view("ui/login", $data);
            }else {
                // Login failed (invalid email/password)
                $data = $this->storeinfo->getstoreinfo();
                $data["menu"] = $this->menumodel->getmainmenufordisplay();
                $data["loginfeedback"] = "Invalid email or password.";
                $data['thispage'] = 'Sign In';
                $this->load->view("ui/login", $data);
            }
        }
    }
    
    // New method for handling the Forgot Password process
    public function forgotPassword() {
        $data = $this->storeinfo->getstoreinfo();
        $data["menu"] = $this->menumodel->getmainmenufordisplay();
        $data["carousels"] = $this->carouselmodel->getcarousels();
        $data["categoryWithProductNum"] = $this->categorymodel->getmaincategorywithproductnum();
        $data["allCategories"] = $this->categorymodel->getAllCategories();
        $data["newStock"] = $this->productmodel->getNewStock();
        $data["getTabbedProducts"] = $this->productmodel->getTabbedProductsHTML();

        $data["minicart_items"] = $this->usermodel->fetchThisUserMiniCart(); // Your minicart items

        // Calculate minicart totals here
        $total_qty = 0;
        $sub_total = 0.00; // Initialize as float
        if ($data["minicart_items"]) { // Checks if 'minicart_items' exists and is not empty/false
            foreach ($data["minicart_items"] as $item) { // Loops through each item in the array/object
                // Ensure qty and price are treated as numbers for calculation
                $item_qty = (int) $item->qty; // Casts the item's quantity to an integer
                $item_price = (float) $item->pr_sell_price; // Casts the item's selling price to a float
        
                $total_qty += $item_qty; // Accumulates the total quantity
                $sub_total += ($item_qty * $item_price); // Accumulates the sub-total
            }
        }
        $data['minicart_total_qty'] = $total_qty;
        $data['minicart_sub_total'] = $sub_total;


        // --- NEW IMPLEMENTATION FOR CATEGORY-BASED PRODUCT DISPLAY ---

        // 1. Get random main categories (e.g., max 3 as discussed)
        $mainCategoriesForDisplay = $this->categorymodel->getRandomMainCategories(3); // Assuming this function is in categorymodel

        // 2. Prepare data structure for the view
        $displayCategoryBlocks = array();
        foreach ($mainCategoriesForDisplay as $category) {
            // Get random products for each category (e.g., max 3 products per category)
            $productsForCategory = $this->productmodel->getRandomProductsByCategory($category->id, 3); // Assuming this function is in productmodel

            // Only add the category block if it actually has products to display
            if (!empty($productsForCategory)) {
                $displayCategoryBlocks[] = (object) [ // Cast to object for consistent access in view
                    'category_info' => $category,
                    'products'      => $productsForCategory
                ];
            }
        }

        // Add the prepared data to the $data array that's passed to the view
        $data['displayCategoryBlocks'] = $displayCategoryBlocks;
        // --- END NEW IMPLEMENTATION ---
       
        // You would load a view with a form to enter the email
        $this->load->view("ui/forgot_password", $data);
    }

    public function sendPasswordResetLink() {
        $data = $this->storeinfo->getstoreinfo();
        $data["menu"] = $this->menumodel->getmainmenufordisplay();
        $data["carousels"] = $this->carouselmodel->getcarousels();
        $data["categoryWithProductNum"] = $this->categorymodel->getmaincategorywithproductnum();
        $data["allCategories"] = $this->categorymodel->getAllCategories();
        $data["newStock"] = $this->productmodel->getNewStock();
        $data["getTabbedProducts"] = $this->productmodel->getTabbedProductsHTML();

        $data["minicart_items"] = $this->usermodel->fetchThisUserMiniCart(); // Your minicart items

        // Calculate minicart totals here
        $total_qty = 0;
        $sub_total = 0.00; // Initialize as float
        if ($data["minicart_items"]) { // Checks if 'minicart_items' exists and is not empty/false
            foreach ($data["minicart_items"] as $item) { // Loops through each item in the array/object
                // Ensure qty and price are treated as numbers for calculation
                $item_qty = (int) $item->qty; // Casts the item's quantity to an integer
                $item_price = (float) $item->pr_sell_price; // Casts the item's selling price to a float
        
                $total_qty += $item_qty; // Accumulates the total quantity
                $sub_total += ($item_qty * $item_price); // Accumulates the sub-total
            }
        }
        $data['minicart_total_qty'] = $total_qty;
        $data['minicart_sub_total'] = $sub_total;


        // --- NEW IMPLEMENTATION FOR CATEGORY-BASED PRODUCT DISPLAY ---

        // 1. Get random main categories (e.g., max 3 as discussed)
        $mainCategoriesForDisplay = $this->categorymodel->getRandomMainCategories(3); // Assuming this function is in categorymodel

        // 2. Prepare data structure for the view
        $displayCategoryBlocks = array();
        foreach ($mainCategoriesForDisplay as $category) {
            // Get random products for each category (e.g., max 3 products per category)
            $productsForCategory = $this->productmodel->getRandomProductsByCategory($category->id, 3); // Assuming this function is in productmodel

            // Only add the category block if it actually has products to display
            if (!empty($productsForCategory)) {
                $displayCategoryBlocks[] = (object) [ // Cast to object for consistent access in view
                    'category_info' => $category,
                    'products'      => $productsForCategory
                ];
            }
        }

        // Add the prepared data to the $data array that's passed to the view
        $data['displayCategoryBlocks'] = $displayCategoryBlocks;
        // --- END NEW IMPLEMENTATION ---
       
        $this->load->library('form_validation');
        $this->form_validation->set_rules('email', 'Email Address', 'required|trim|valid_email');

        if ($this->form_validation->run() == FALSE) {
            $data["feedback"] = "Please enter a valid email address.";
            $this->load->view("ui/forgot_password", $data);
        } else {
            $email = $this->input->post('email');
            
            // Check if the email exists
            $user = $this->usermodel->getUserByEmail($email);

            if ($user) {
                // Generate a password reset token
                $reset_token = bin2hex(random_bytes(32)); // A secure, random token

                // Save the token and an expiration time in the database
                $this->usermodel->savePasswordResetToken($user->id, $reset_token);

                // Send the email
                require_once APPPATH . 'third_party/phpmailer/src/Exception.php';
                require_once APPPATH . 'third_party/phpmailer/src/PHPMailer.php';
                require_once APPPATH . 'third_party/phpmailer/src/SMTP.php';
            
                $mail = new PHPMailer(true);
                try {
                    $store_info = $this->storeinfo->getstoreinfo();

                    $mail->isSMTP();
                    $mail->Host       = 'smtp.gmail.com';
                    $mail->SMTPAuth   = true;
                    $mail->Username   = $store_info['email'];
                    $mail->Password   = $this->app_password;
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
                    $mail->Port       = 465;

                    $mail->setFrom($store_info['email'], $store_info['name']);
                    $mail->addAddress($email);

                    $reset_link = site_url("home/resetPassword/{$reset_token}");

                    $mail->isHTML(true);
                    $mail->Subject = "Password Reset Request for " . $store_info['name'];
                    $mail->Body    = "
                        <h3>Password Reset</h3>
                        <p>We received a request to reset your password. Click the button below to proceed:</p>
                        <table role='presentation' border='0' cellpadding='0' cellspacing='0'>
                          <tr>
                            <td align='center'>
                              <a href='{$reset_link}' style='display: inline-block; padding: 10px 20px; background-color: #dc3545; color: #ffffff; text-decoration: none; border-radius: 5px; font-weight: bold;'>Reset My Password</a>
                            </td>
                          </tr>
                        </table>
                        <p>If you did not request a password reset, you can safely ignore this email.</p>
                        <p>This link will expire in 1 hour.</p>
                        <p>Regards,<br>{$store_info['name']} Team</p>
                    ";
                    $mail->AltBody = "To reset your password, please visit this URL: {$reset_link}";
                    $mail->send();

                    $data["feedback_success"] = true;
                    $data["feedback"] = "A password reset link has been sent to your email address.";

                } catch (Exception $e) {
                    log_message('error', "Password reset email failed: {$mail->ErrorInfo}");
                    $data["feedback_success"] = false;
                    $data["feedback"] = "An error occurred while sending the email. Please try again later.";
                }

            } else {
                $data["feedback_success"] = false;
                $data["feedback"] = "No account found with that email address.";
            }
            $data['thispage'] = 'Forgot Password';

            $this->load->view("ui/forgot_password", $data);
        }
    }
    
    // New method for displaying and processing the password reset form
    public function resetPassword($token = null) {
        $data = $this->storeinfo->getstoreinfo();
        $data["menu"] = $this->menumodel->getmainmenufordisplay();
        $data["carousels"] = $this->carouselmodel->getcarousels();
        $data["categoryWithProductNum"] = $this->categorymodel->getmaincategorywithproductnum();
        $data["allCategories"] = $this->categorymodel->getAllCategories();
        $data["newStock"] = $this->productmodel->getNewStock();
        $data["getTabbedProducts"] = $this->productmodel->getTabbedProductsHTML();

        $data["minicart_items"] = $this->usermodel->fetchThisUserMiniCart(); // Your minicart items

        // Calculate minicart totals here
        $total_qty = 0;
        $sub_total = 0.00; // Initialize as float
        if ($data["minicart_items"]) { // Checks if 'minicart_items' exists and is not empty/false
            foreach ($data["minicart_items"] as $item) { // Loops through each item in the array/object
                // Ensure qty and price are treated as numbers for calculation
                $item_qty = (int) $item->qty; // Casts the item's quantity to an integer
                $item_price = (float) $item->pr_sell_price; // Casts the item's selling price to a float
        
                $total_qty += $item_qty; // Accumulates the total quantity
                $sub_total += ($item_qty * $item_price); // Accumulates the sub-total
            }
        }
        $data['minicart_total_qty'] = $total_qty;
        $data['minicart_sub_total'] = $sub_total;


        // --- NEW IMPLEMENTATION FOR CATEGORY-BASED PRODUCT DISPLAY ---

        // 1. Get random main categories (e.g., max 3 as discussed)
        $mainCategoriesForDisplay = $this->categorymodel->getRandomMainCategories(3); // Assuming this function is in categorymodel

        // 2. Prepare data structure for the view
        $displayCategoryBlocks = array();
        foreach ($mainCategoriesForDisplay as $category) {
            // Get random products for each category (e.g., max 3 products per category)
            $productsForCategory = $this->productmodel->getRandomProductsByCategory($category->id, 3); // Assuming this function is in productmodel

            // Only add the category block if it actually has products to display
            if (!empty($productsForCategory)) {
                $displayCategoryBlocks[] = (object) [ // Cast to object for consistent access in view
                    'category_info' => $category,
                    'products'      => $productsForCategory
                ];
            }
        }

        // Add the prepared data to the $data array that's passed to the view
        $data['displayCategoryBlocks'] = $displayCategoryBlocks;
        // --- END NEW IMPLEMENTATION ---
       
        if (is_null($token) || !$this->usermodel->checkPasswordResetToken($token)) {
            // Invalid or expired token
            $data["feedback"] = "Invalid or expired password reset link.";
            $data["feedback_success"] = false;
            $this->load->view("ui/password_reset", $data); // Create this new view
        } else {
            // Valid token, show the password reset form
            if ($this->input->server('REQUEST_METHOD') === 'POST') {
                $this->load->library('form_validation');
                $this->form_validation->set_rules("new_password", "New Password", "required|trim|min_length[8]");
                $this->form_validation->set_rules("confirm_new_password", "Confirm New Password", "required|trim|matches[new_password]");

                if ($this->form_validation->run() == FALSE) {
                    $data["feedback"] = "Password validation failed. Passwords must match and be at least 8 characters.";
                    $data["feedback_success"] = false;
                    $data['token'] = $token;
                    $this->load->view("ui/password_reset", $data);
                } else {
                    $new_password = $this->input->post('new_password');
                    if ($this->usermodel->updateUserPassword($token, $new_password)) {
                        $data["feedback"] = "Your password has been updated successfully. You can now log in.";
                        $data["feedback_success"] = true;
                        $this->load->view("ui/login", $data); // Redirect to login page
                    } else {
                        $data["feedback"] = "An error occurred while updating your password. Please try again.";
                        $data["feedback_success"] = false;
                        $data['token'] = $token;
                        $this->load->view("ui/password_reset", $data);
                    }
                }
            } else {
                // Initial load of the form with a valid token
                $data['token'] = $token;
                $this->load->view("ui/password_reset", $data);
            }
        }
    }

    public function addtocart() {
        if ($this->session->userdata("user") == "") {
            redirect("home/login");
        }

        $response = array(); // Initialize an array for the JSON response

        $cartFeedback = $this->usermodel->addToCart();

        if (!$cartFeedback) {
            $response['status'] = 'error';
            $response['message'] = "Error Implementing Cart";
        } else {
            $response['status'] = 'success';
            $response['message'] = "Product Added to Cart";
        }
        
        // Set the content type header to application/json
        $this->output
             ->set_content_type('application/json')
             ->set_output(json_encode($response));
    }




    
    public function shop() {
        $data = $this->storeinfo->getstoreinfo();
        $data["menu"] = $this->menumodel->getmainmenufordisplay();
        $data["carousels"] = $this->carouselmodel->getcarousels();
        $data["categoryWithProductNum"] = $this->categorymodel->getmaincategorywithproductnum();
        $data["allCategories"] = $this->categorymodel->getAllCategories();
        $data["newStock"] = $this->productmodel->getNewStock();
        $data["getTabbedProducts"] = $this->productmodel->getTabbedProductsHTML();

        $data["minicart_items"] = $this->usermodel->fetchThisUserMiniCart(); // Your minicart items

        // Calculate minicart totals here
        $total_qty = 0;
        $sub_total = 0.00; // Initialize as float
        if ($data["minicart_items"]) {
            foreach ($data["minicart_items"] as $item) {
                // Ensure qty and price are treated as numbers for calculation
                $item_qty = (int) $item->qty;
                $item_price = (float) $item->pr_sell_price;

                $total_qty += $item_qty;
                $sub_total += ($item_qty * $item_price);
            }
        }
        $data['minicart_total_qty'] = $total_qty;
        $data['minicart_sub_total'] = $sub_total;


        // Pagination Configuration
        $config['base_url'] = base_url() . 'home/shop'; // Base URL for your pagination links
        // IMPORTANT: Make sure this base_url matches your actual route for this page.
        // If the URL to this page is "yourdomain.com/home/productslist1", then change it to:
        // $config['base_url'] = base_url() . 'home/productslist1';
        // If you are using a custom route for this, like '$route['all-products'] = 'home/productslist1';'
        // Then your base_url should probably be 'base_url() . 'all-products';'


        $config['total_rows'] = $this->productmodel->countAllProducts(); // Total records
        $config['per_page'] = 4; // Number of items per page (you can adjust this)
        $config['uri_segment'] = 3; // The segment of the URI containing the page number
                                    // If your URL structure means the page number is in a different segment, adjust this.
                                    // E.g., if URL is example.com/controller/method/PAGE_NUM, PAGE_NUM is segment 3.

        // Styling for pagination links
        $config['full_tag_open'] = '<ul class="pagination-contain">';
        $config['full_tag_close'] = '</ul>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="active"><span class="current-page">';
        $config['cur_tag_close'] = '</a></li>';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';
        $config['prev_tag_open'] = '<li>';
        $config['prev_tag_close'] = '</li>';
        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';
        $config['attributes'] = array('class' => 'page-link'); // Add a class to all links

        $this->pagination->initialize($config); // Initialize pagination

        // Get current page offset
        // FIX: Explicitly cast to integer to prevent ctype_digit() error with null/empty string
        $page = (int) $this->uri->segment($config['uri_segment']);


        // Fetch products for the current page
        $data['products'] = $this->productmodel->getAllProductsPaginated($config['per_page'], $page);

        // Generate pagination links
        $data['pagination_links'] = $this->pagination->create_links();

        // Optional: Fetch all categories once if you need to map category IDs to names in the view
        // This is highly recommended if product->main_category is an ID.
        $allCategories = $this->categorymodel->getAllCategories(); // Ensure this function exists in categorymodel
        // Create an associative array (map) for easy lookup: category_id => category_name
        $category_map = [];
        foreach ($allCategories as $cat) {
            $category_map[$cat->id] = $cat->cat_name; // Assuming 'id' and 'cat_name' fields
        }
        $data['category_map'] = $category_map;
        $data['thispage'] = 'Shop';
        $this->load->view('ui/products-list', $data);
    }


    public function results() {
        // Load store information - essential site-wide data
        $data = $this->storeinfo->getstoreinfo();
    
        // Ensure common data is always available for the view
        // Provide default values if data might not be set in the database
        $data['name'] = $data['name'] ?? 'Default Store Name';
        $data['information'] = $data['information'] ?? 'Default store info...';
        $data['welcome_text'] = $data['welcome_text'] ?? 'Default welcome text...';
        $data['welcome_quote'] = $data['welcome_quote'] ?? 'Default welcome quote...';
        $data['core_values'] = $data['core_values'] ?? '{}'; // Ensure it's an empty JSON object if no data
        $data['logo'] = $data['logo'] ?? 'default_logo.png'; // Ensure a default logo path
    
    
        $data["menu"] = $this->menumodel->getmainmenufordisplay();
        $data["carousels"] = $this->carouselmodel->getcarousels();
        $data["categoryWithProductNum"] = $this->categorymodel->getmaincategorywithproductnum();
        $data["allCategories"] = $this->categorymodel->getAllCategories();
        $data["newStock"] = $this->productmodel->getNewStock();
        $data["getTabbedProducts"] = $this->productmodel->getTabbedProductsHTML();
    
        $data["minicart_items"] = $this->usermodel->fetchThisUserMiniCart(); // Your minicart items
    
        $total_qty = 0;
        $sub_total = 0.00; // Initialize as float
        if ($data["minicart_items"]) {
            foreach ($data["minicart_items"] as $item) {
                $item_qty = (int) $item->qty;
                $item_price = (float) $item->pr_sell_price;
    
                $total_qty += $item_qty;
                $sub_total += ($item_qty * $item_price);
            }
        }
        $data['minicart_total_qty'] = $total_qty;
        $data['minicart_sub_total'] = $sub_total;
    
    
        // --- FIX FOR TRIM() ERROR ---
        // Get the search query from GET request, defaulting to an empty string if 'q' is not set.
        $search_query = trim($this->input->get('q', TRUE) ?? ''); // Corrected line
        $data['search_query'] = $search_query; // Pass to view to pre-fill search box
    
        // Optional: Fetch all categories and tags once for mapping in the view
        $allMainCategories = $this->categorymodel->getAllCategories(); // Assuming Main categories are 'allCategories'
        $main_category_map = [];
        foreach ($allMainCategories as $cat) {
            $main_category_map[$cat->id] = $cat->cat_name;
        }
        $data['category_map'] = $main_category_map;
    
        $allTagCategories = $this->categorymodel->getAllTagCategories();
        $tag_category_map = [];
        foreach ($allTagCategories as $tag) {
            $tag_category_map[$tag->id] = $tag->cat_name;
        }
        $data['tag_category_map'] = $tag_category_map;
    
    
        // Pagination Configuration for Search Results
        $config = array();
        $config['base_url'] = base_url() . 'home/results';
        // Add the search query back to the pagination links for persistence
        if (!empty($search_query)) {
            $config['suffix'] = '?q=' . urlencode($search_query);
            $config['first_url'] = $config['base_url'] . '?q=' . urlencode($search_query);
        } else {
            // Important: If no search query, ensure suffix/first_url don't have '?q='
            // This makes sure pagination works correctly for non-search result views that might use this controller
            $config['suffix'] = '';
            $config['first_url'] = '';
        }
    
        $config['total_rows'] = $this->productmodel->countSearchResults($search_query);
        $config['per_page'] = 4;
        $config['uri_segment'] = 3; // e.g., search/results/12?q=apple
    
        // Pagination styling - ensure `create_links()` matches your HTML structure
        $config['full_tag_open'] = '<ul class="pagination-contain">';
        $config['full_tag_close'] = '</ul>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="active"><span class="current-page">';
        $config['cur_tag_close'] = '</span></li>'; // Corrected: changed </a> to </span>
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';
        $config['prev_tag_open'] = '<li>';
        $config['prev_tag_close'] = '</li>';
        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';
        $config['attributes'] = array('class' => 'page-link'); // Add a class to all links
    
    
        $this->pagination->initialize($config);
    
        // Get current page offset
        // The URI segment contains the offset, not the page number
        $page_offset = (int) $this->uri->segment($config['uri_segment']);
    
        // Fetch products matching the search query for the current page
        // Pass $config['per_page'] for limit and $page_offset for offset
        $data['products'] = $this->productmodel->searchProductsPaginated($search_query, $config['per_page'], $page_offset);
    
        // Generate pagination links
        $data['pagination_links'] = $this->pagination->create_links();
    
        // Load the product list view
        $data['thispage'] = $search_query;

        $this->load->view('ui/products-list', $data);
    }

    
    public function cart() {
        if ($this->session->userdata("user") == "")
        redirect("home/login");

        $username = $this->session->userdata("user");
        $user_id = $this->usermodel->getUserIdFromUsername($username);
        
        

        $data = $this->storeinfo->getstoreinfo();
        $data["cart_items"]= $this->usermodel->fetchThisUserCart();
        $data["menu"] = $this->menumodel->getmainmenufordisplay();

        $data["minicart_items"] = $this->usermodel->fetchThisUserMiniCart(); // Your minicart items

        // Calculate minicart totals here
        $total_qty = 0;
        $sub_total = 0.00; // Initialize as float
        if ($data["minicart_items"]) {
            foreach ($data["minicart_items"] as $item) {
                // Ensure qty and price are treated as numbers for calculation
                $item_qty = (int) $item->qty;
                $item_price = (float) $item->pr_sell_price;

                $total_qty += $item_qty;
                $sub_total += ($item_qty * $item_price);
            }
        }

        if ($user_id) {
            // 3. Fetch all orders and their products for the user
            $data["orders"] = $this->ordermodel->get_orders_by_user_id($user_id);
        } else {
            $data["orders"] = []; // Set to an empty array if no user ID is found
        }
        $data['minicart_total_qty'] = $total_qty;
        $data['minicart_sub_total'] = $sub_total;

        $data['thispage'] = 'My Cart';
        $this->load->view('ui/shopping-cart', $data);
    }

    
    public function dashboard() {
        if ($this->session->userdata("user") == "")
        redirect("home/login");

        $username = $this->session->userdata("user");
        $user_id = $this->usermodel->getUserIdFromUsername($username);
        
        

        $data = $this->storeinfo->getstoreinfo();
        $data['user_fullname'] = $this->usermodel->getUserFullnameFromUsername($username);

        $data["cart_items"]= $this->usermodel->fetchThisUserCart();
        $data["menu"] = $this->menumodel->getmainmenufordisplay();

        $data["minicart_items"] = $this->usermodel->fetchThisUserMiniCart(); // Your minicart items

        // Calculate minicart totals here
        $total_qty = 0;
        $sub_total = 0.00; // Initialize as float
        if ($data["minicart_items"]) {
            foreach ($data["minicart_items"] as $item) {
                // Ensure qty and price are treated as numbers for calculation
                $item_qty = (int) $item->qty;
                $item_price = (float) $item->pr_sell_price;

                $total_qty += $item_qty;
                $sub_total += ($item_qty * $item_price);
            }
        }

        $pending_orders_count = 0;
        $successful_orders_count = 0;
        $delivered_orders_count = 0;
    
        if ($user_id) {
            // Fetch all orders and their products for the user
            $orders = $this->ordermodel->get_orders_by_user_id($user_id);
            
            // Loop through orders to count pending and successful ones
            foreach ($orders as $order) {
                if ($order->status === 'successful') {
                    $successful_orders_count++;
                } elseif($order->status === 'delivered'){
                    $delivered_orders_count++;
                }else {
                    $pending_orders_count++;
                }
            }
    
            $data["orders"] = $orders; // Pass the full order list to the view
        } else {
            $data["orders"] = [];
        }
    
        // Pass the calculated counts to the view
        $data["pending_orders_count"] = $pending_orders_count;
        $data["successful_orders_count"] = $successful_orders_count;
        $data["delivered_orders_count"] = $delivered_orders_count;
        
        $data['minicart_total_qty'] = $total_qty;
        $data['minicart_sub_total'] = $sub_total;


        $data['thispage'] = 'My Account';

        $this->load->view('ui/dashboard', $data);
    }

    
    public function checkout() {
        // Redirect if user is not logged in
        if ($this->session->userdata("user") == "") {
            $this->session->set_flashdata('error', 'Please log in to proceed to checkout.');
            redirect("home/login");
        }
         // --- Fetch User Details to pre-fill form ---
         $username = $this->session->userdata("user");
         $user_details = $this->usermodel->getUserDetailsByUsername($username);
        

        $data = $this->storeinfo->getstoreinfo();
        $data["minicart_items"] = $this->usermodel->fetchThisUserMiniCart(); // Your minicart items (which will power the order summary)

        // Calculate minicart totals here
        $total_qty = 0;
        $sub_total = 0.00; // Initialize as float
        $shipping_cost = 0.00; // Example static value - you might fetch this from a configuration or calculate based on address
        $user_shipping_id = $user_details->shipping_region_id; // Variable to hold the user's default region ID
        if ($data["minicart_items"]) {
            foreach ($data["minicart_items"] as $item) {
                $item_qty = (int) $item->qty;
                $item_price = $this->clean_price($item->pr_sell_price); // Use helper for cleaning price

                $total_qty += $item_qty;
                $sub_total += ($item_qty * $item_price);
            }
        }

        $data['minicart_total_qty'] = $total_qty;
        $data['minicart_sub_total'] = $sub_total;

        $shipping_regions = $this->categorymodel->get_shipping_regions_alphabetical();
        $data['shipping_regions'] = $shipping_regions; // Pass this array to the view for the radio buttons
        if (!empty($shipping_regions)) {
    
            $region_found = false;
            
            // A. Check if the user has a saved region ID
            if ($user_shipping_id !== null) {
                foreach ($shipping_regions as $region) {
                    if ($region['id'] == $user_shipping_id) {
                        // Found a match! Use this price and set the flag.
                        $shipping_cost = (float)$region['shipping_price'];
                        $region_found = true;
                        break;
                    }
                }
            }
            
            // B. Fallback: If no saved ID or the saved ID is invalid/deleted, use the price of the first region in the list.
            if (!$region_found) {
                $shipping_cost = (float)$shipping_regions[0]['shipping_price'];
                // Update the pre-selected ID to match the fallback default
                $user_shipping_id = $shipping_regions[0]['id']; 
            }
        }

        // Define shipping and tax for the order summary (these would typically come from config/DB)
        // You could fetch this from storeinfo or another model:
        // $shipping_cost = $this->storeinfo->get_shipping_cost_for_region($user_details->state);

        $grand_total = $sub_total + $shipping_cost;

        $data['shipping_cost'] = $shipping_cost;
        $data['grand_total'] = $grand_total;

        $data["menu"] = $this->menumodel->getmainmenufordisplay();

        $data['shipping_regions'] = $this->categorymodel->get_shipping_regions_alphabetical();

        // Initialize variables for the form fields
        $data['user_fullname'] = '';
        $data['user_email'] = '';
        $data['user_phone'] = '';
        $data['user_address'] = '';
        $data['shipping_region_id'] = '';
        $data['user_state'] = ''; // Added for potential state-based shipping or display
        $data['user_city'] = ''; // Added for potential city-based shipping or display

        if ($user_details) {
            $data['user_fullname'] = $user_details->fullname;
            $data['user_email'] = $user_details->email;
            // Assuming these columns exist in your 'users' table.
            // Adjust 'phone', 'address', 'state', 'city' to your actual column names.
            $data['user_phone'] = $user_details->telephone ?? ''; // Use null coalescing for safety if column might not exist
            $data['user_address'] = $user_details->address ?? '';
            $data['shipping_region_id'] =  $user_shipping_id;
        }
       
        $data['thispage'] = 'Checkout';
        $this->load->view('ui/checkout', $data);
    }



    public function order_confirmation() {
        // Redirect if user is not logged in
        if ($this->session->userdata("user") == "") {
            redirect("home/login");
        }

        $data = $this->storeinfo->getstoreinfo();
        $data["minicart_items"] = $this->usermodel->fetchThisUserMiniCart(); // Your minicart items (which will power the order summary)

        // Calculate minicart totals here
        $total_qty = 0;
        $sub_total = 0.00; // Initialize as float
        if ($data["minicart_items"]) {
            foreach ($data["minicart_items"] as $item) {
                // Ensure qty and price are treated as numbers for calculation
                $item_qty = (int) $item->qty;
                $item_price = (float) $item->pr_sell_price;

                $total_qty += $item_qty;
                $sub_total += ($item_qty * $item_price);
            }
        }
        $data['minicart_total_qty'] = $total_qty;
        $data['minicart_sub_total'] = $sub_total;

        // Define shipping and tax for the order summary (these would typically come from config/DB)
        $shipping_cost = 20.00; // Example static value
        $tax_rate = 0.00;     // Example static value (0% tax)
        $tax_amount = $sub_total * $tax_rate;
        $grand_total = $sub_total + $shipping_cost + $tax_amount;

        $data['shipping_cost'] = $shipping_cost;
        $data['tax_amount'] = $tax_amount;
        $data['grand_total'] = $grand_total;

        $data["menu"] = $this->menumodel->getmainmenufordisplay();

        // --- Fetch User Details to pre-fill form ---
        $username = $this->session->userdata("user");
        $user_details = $this->usermodel->getUserDetailsByUsername($username);

        // Initialize variables to avoid 'undefined variable' notices if user_details is false
        $data['user_fullname'] = '';
        $data['user_email'] = '';
        $data['user_phone'] = ''; // Assuming you might have a phone column
        $data['user_address'] = ''; // Assuming you might have an address column

        if ($user_details) {
            $data['user_fullname'] = $user_details->fullname;
            $data['user_email'] = $user_details->email;
            // Uncomment and use if you have these columns in your 'user' table
            // $data['user_phone'] = $user_details->phone ?? '';
            // $data['user_address'] = $user_details->address ?? '';
        }

        $this->load->view('ui/order_confirmation', $data);
    }

    private function sendEmail($to_email, $to_name, $subject, $body) {
        // You can get store info and other mailer settings here or pass them as arguments
        $store_info = $this->storeinfo->getstoreinfo();

        require_once APPPATH . 'third_party/phpmailer/src/Exception.php';
        require_once APPPATH . 'third_party/phpmailer/src/PHPMailer.php';
        require_once APPPATH . 'third_party/phpmailer/src/SMTP.php';

        $mail = new PHPMailer(true);
        try {
            //Server settings
            $mail->SMTPDebug = 0; // Set to 2 for debugging
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = $store_info['email'];
            $mail->Password   = $this->app_password; // Use your App Password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port       = 465;

            //Recipients
            $mail->setFrom($store_info['email'], $store_info['name']);
            $mail->addAddress($to_email, $to_name);
            $mail->addReplyTo($store_info['email'], $store_info['name']);

            //Content
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body    = $body;
            $mail->AltBody = strip_tags($body); // For non-HTML mail clients

            $mail->send();
            return true;
        } catch (Exception $e) {
            log_message('error', "Email sending failed for '{$to_email}': " . $mail->ErrorInfo);
            return false;
        }
    }



    public function placeOrder() {
        // Redirect if user is not logged in
        if ($this->session->userdata("user") == "") {
            $this->session->set_flashdata('error', 'Please log in to complete your order.');
            redirect("home/login");
        }

        // Load necessary libraries
        $this->load->library('form_validation');

        // Set validation rules for shipping details
        $this->form_validation->set_rules('full_name', 'Full Name', 'required|trim|max_length[255]');
        $this->form_validation->set_rules('phone', 'Phone Number', 'required|trim|numeric|min_length[10]|max_length[15]');
        $this->form_validation->set_rules('address', 'Shipping Address', 'required|trim|max_length[500]');
        $this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email|max_length[255]');
        $this->form_validation->set_rules('payment_method', 'Payment Method', 'required|in_list[paystack,bank_deposit,whatsapp]');
        $this->form_validation->set_rules('shipping_region_id', 'Shipping Region', 'required');
        $this->form_validation->set_rules('notes', 'Additional Notes', 'trim|max_length[200]');
        // Optional: Add rules for state and city if you collect them in the form
        // $this->form_validation->set_rules('state', 'State', 'required|trim|max_length[100]');
        // $this->form_validation->set_rules('city', 'City', 'required|trim|max_length[100]');

        if ($this->form_validation->run() == FALSE) {
            // If validation fails, reload the checkout page with errors
            $this->checkout(); // This will re-populate the form with old input and show errors
            return;
        }

        // Get shipping details from POST
        $shipping_full_name = $this->input->post('full_name', TRUE);
        $shipping_phone = $this->input->post('phone', TRUE);
        $shipping_address = $this->input->post('address', TRUE);
        $shipping_email = $this->input->post('email', TRUE);
        $payment_method = $this->input->post('payment_method', TRUE);
        $shipping_region_id = $this->input->post('shipping_region_id', TRUE);
        $notes = $this->input->post('notes', TRUE);

        // Fetch cart items to get accurate totals
        $minicart_items = $this->usermodel->fetchThisUserMiniCart();
        if (empty($minicart_items)) {
            $this->session->set_flashdata('error', 'Your cart is empty. Please add items before placing an order.');
            redirect('home/shop'); // Or redirect back to cart page
            return;
        }

        $sub_total = 0.00;
        foreach ($minicart_items as $item) {
            $item_qty = (int) $item->qty;
            $item_price = $this->clean_price($item->pr_sell_price);
            $sub_total += ($item_qty * $item_price);
        }
        $ship_price = $this->categorymodel->get_shipping_price_by_id($shipping_region_id);
        if($ship_price == false){
            $shipping_cost = 0.00;
        }else{
            $shipping_cost = $ship_price["shipping_price"];

        }


        $grand_total = $sub_total + $shipping_cost;

        $user_id = $this->session->userdata('user_id'); // Assuming you store user_id in session
        if (!$user_id) { // Fallback if only 'user' (username) is stored
            $user_obj = $this->usermodel->getUserDetailsByUsername($this->session->userdata("user"));
            $user_id = $user_obj->id;
        }
      

        // Prepare order data for insertion
        $order_data = array(
            'user_id' => $user_id,
            'shipping_full_name' => $shipping_full_name,
            'shipping_phone' => $shipping_phone,
            'shipping_address' => $shipping_address,
            'shipping_email' => $shipping_email,
            'shipping_region	' => $ship_price['location_name'],
            'shipping_price	' => $shipping_cost,
            'status' => 'pending', // Initial status
            'method' => $payment_method,
            'add_notes' => $notes,
            'subtotal' => $sub_total,
            'total_cost' => $grand_total,
            'order_date' => date('Y-m-d H:i:s')
        );

        // --- Handle different payment methods ---
        switch ($payment_method) {
            case 'paystack':
                // For Paystack, we don't insert the order immediately.
                // We'll insert it AFTER successful payment verification.
                // Instead, we'll redirect to Paystack.
                $this->processPaystack($order_data, $minicart_items);
                break;

            case 'bank_deposit':
                // Insert order into database
                $order_id = $this->ordermodel->insert_order($order_data, $minicart_items); // Assuming insert_order handles products_ordered too
                if ($order_id) {
                    // Redirect to a specific page for bank deposit details and upload form
                    $this->session->set_flashdata('success', 'Just one more step! Please make your deposit to the account shown to confirm your order.');
                    redirect('home/bankDepositInstructions/' . $order_id);
                } else {
                    $this->session->set_flashdata('error', 'There was an issue placing your order. Please try again.');
                    redirect('home/checkout');
                }
                break;

            case 'whatsapp':
                // Insert order into database
                $order_id = $this->ordermodel->insert_order($order_data, $minicart_items); // Assuming insert_order handles products_ordered too
                if ($order_id) {
                    // Generate WhatsApp message and redirect
                    $this->redirectToWhatsApp($order_id, $order_data, $minicart_items);
                } else {
                    $this->session->set_flashdata('error', 'There was an issue placing your order. Please try again.');
                    redirect('home/checkout');
                }
                break;

            default:
                $this->session->set_flashdata('error', 'Invalid payment method selected.');
                redirect('home/checkout');
                break;
        }
    }

    // --- Paystack Integration ---
    private function processPaystack($order_data, $minicart_items) {
    
        // Generate a reference
        $reference = 'ORD-' . time() . '-' . md5(uniqid(mt_rand(), true)); // More robust unique ID

        // Prepare data for Paystack initialization
        $paystack_data = array(
            'email' => $order_data['shipping_email'],
            'amount' => $order_data['total_cost'] * 100, // Amount in kobo
            'reference' => $reference,
            'currency' => 'NGN', // Assuming Naira
            'callback_url' => site_url('home/paystackVerifyTransaction/' . $reference), // Callback URL after payment
            'metadata' => [
                'custom_fields' => [
                    'full_name' => $order_data['shipping_full_name'],
                    'phone' => $order_data['shipping_phone'],
                    'address' => $order_data['shipping_address']
                ],
                // You can include the order data and minicart items here to retrieve them later
                'order_details' => $order_data,
                'cart_items' => $minicart_items
            ]
        );

        // Store order_data and minicart_items in session or temporary storage
        // This is crucial because Paystack callback will not have POST data.
        $this->session->set_userdata('temp_order_data_' . $reference, $order_data);
        $this->session->set_userdata('temp_minicart_items_' . $reference, $minicart_items);
       
        // Redirect to Paystack's standard payment page (or use inline for JS)
        // For simplicity, we'll redirect to a view that has the Paystack inline script.
        $data = $this->common_data;
        $data['paystack_public_key'] = $this->paystack_public_key;
        $data['paystack_email'] = $order_data['shipping_email'];
        $data['paystack_amount'] = $order_data['total_cost'] * 100;
        $data['paystack_ref'] = $reference;
        $data['callback_url'] = site_url('home/paystackVerifyTransaction/' . $reference);
        $data['grand_total'] = $order_data['total_cost']; // For display on the payment page

        $this->load->view('ui/paystack_initiate', $data); // New view for Paystack initiation
    }

    public function paystackVerifyTransaction2($reference) {
        //$paystack_secret_key = 'YOUR_PAYSTACK_SECRET_KEY'; // Replace with your actual Paystack Secret Key

        // Retrieve the temporary order data from session
        $order_data = $this->session->userdata('temp_order_data_' . $reference);
        $minicart_items = $this->session->userdata('temp_minicart_items_' . $reference);

        if (!$order_data || !$minicart_items) {
            $this->session->set_flashdata('error', 'Payment verification failed: Order data missing. Please contact support.');
            redirect('home/checkout'); // Or an error page
            return;
        }

        $curl = curl_init();

// Define the path to your cacert.pem file
// Use FCPATH (Front Controller Path) to get the absolute path to your CodeIgniter root
$ca_cert_path = FCPATH . 'application/third_party/certs/cacert.pem'; // Adjust path if you put it elsewhere

// Check if the file exists before attempting to use it
if (!file_exists($ca_cert_path)) {
    // Log an error or handle the case where the cert file is missing
    log_message('error', 'Paystack CA certificate file not found at: ' . $ca_cert_path);
    $this->session->set_flashdata('error', 'Server configuration error: CA certificate missing. Please contact support.');
    redirect('home/checkout');
    return; // Stop execution
}

curl_setopt_array($curl, array(
    CURLOPT_URL => "https://api.paystack.co/transaction/verify/" . rawurlencode($reference),
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => array(
        "accept: application/json",
        "authorization: Bearer " . $this->paystack_secret_key,
        "cache-control: no-cache"
    ),
    // --- ADD THESE TWO LINES ---
    CURLOPT_SSL_VERIFYPEER => true, // Ensure peer verification is enabled (default true, but good to be explicit)
    CURLOPT_CAINFO => $ca_cert_path // Specify the path to your CA certificate bundle
    // --- END ADDITIONS ---
));

$response = curl_exec($curl);
$err = curl_error($curl);
$errno = curl_errno($curl); // Get the error number for more specific logging
curl_close($curl);
$result = json_decode($response);



if ($err) {
     log_message('error', 'Paystack verification cURL error (' . $errno . '): ' . $err); // Log the error for debugging
     $this->session->set_flashdata('error', 'Payment verification failed: ' . $err);
     redirect('home/checkout');
     $result = json_decode($response);
    

} else {
    $result = json_decode($response);
    
            if ($result->status && $result->data->status == 'success') {
                // Payment was successful!
                // Update order status and insert order details
                $order_data['status'] = 'successful'; // Or 'paid'
                $order_data['payment_reference'] = $reference; // Store Paystack reference

                // Clear the cart
                $user_id = $order_data['user_id'];
                $this->usermodel->clearUserCart($user_id); // Implement this method in Usermodel

                $order_id = $this->ordermodel->insert_order($order_data, $minicart_items);
                if ($order_id) {
                    $order = $this->ordermodel->get_order_by_id($order_id);
                    $store_info = $this->storeinfo->getstoreinfo();

                    if ($order) {
                        // Prepare data to pass to the email template view
                        $email_data['order'] = $order;
                        $email_data['store_info'] = $store_info;
                        
                        // Get the HTML content from the email template view
                        $email_body = $this->load->view('ui/load/order_confirmation_email', $email_data, TRUE);
                        
                        $email_subject = "Your Order Confirmation - #" . $order->id;
                        
                        // Send the email using the prepared body
                        $this->sendEmail($order->shipping_email, $order->shipping_full_name, $email_subject, $email_body);
                    }


                    // Clear temporary session data
                    $this->session->unset_userdata('temp_order_data_' . $reference);
                    $this->session->unset_userdata('temp_minicart_items_' . $reference);

                    $this->session->set_flashdata('success', 'Payment successful! Your order #' . $order_id . ' has been placed.');
                    redirect('home/orderConfirmation/' . $order_id); // Redirect to order confirmation page
                } else {
                    $this->session->set_flashdata('error', 'Payment successful, but there was an issue recording your order. Please contact support with reference: ' . $reference);
                    redirect('home/checkout', $result);

                }
            } else {

                // Payment failed or was not successful
                $message = $result->data->gateway_response ?? 'Payment was not successful. Please try again or choose another method.';
                $this->session->set_flashdata('error', 'Payment failed: ' . $message);
                
                redirect('home/checkout', $result);

            }
        }
    }

    public function paystackVerifyTransaction($reference) {
        //$paystack_secret_key = 'YOUR_PAYSTACK_SECRET_KEY'; // Replace with your actual Paystack Secret Key

        // Retrieve the temporary order data from session
        $order_data = $this->session->userdata('temp_order_data_' . $reference);
        $minicart_items = $this->session->userdata('temp_minicart_items_' . $reference);

        if (!$order_data || !$minicart_items) {
            $this->session->set_flashdata('error', 'Payment verification failed: Order data missing. Please contact support.');
            redirect('home/checkout'); // Or an error page
            return;
        }

        $curl = curl_init();

$ca_cert_path = FCPATH . 'application/third_party/certs/cacert.pem'; // Adjust path if you put it elsewhere

if (!file_exists($ca_cert_path)) {
    log_message('error', 'Paystack CA certificate file not found at: ' . $ca_cert_path);
    $this->session->set_flashdata('error', 'Server configuration error: CA certificate missing. Please contact support.');
    redirect('home/checkout');
    return; // Stop execution
}

curl_setopt_array($curl, array(
    CURLOPT_URL => "https://api.paystack.co/transaction/verify/" . rawurlencode($reference),
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => array(
        "accept: application/json",
        "authorization: Bearer " . $this->paystack_secret_key,
        "cache-control: no-cache"
    ),
    // --- ADD THESE TWO LINES ---
    CURLOPT_SSL_VERIFYPEER => true, // Ensure peer verification is enabled (default true, but good to be explicit)
    CURLOPT_CAINFO => $ca_cert_path // Specify the path to your CA certificate bundle
));

$response = curl_exec($curl);
$err = curl_error($curl);
$errno = curl_errno($curl);
curl_close($curl);


if ($err) {
    log_message('error', 'Paystack verification cURL error (' . $errno . '): ' . $err);
    $this->session->set_flashdata('error', 'Payment verification failed: ' . $err);
    redirect('home/checkout');
    return;
}

$result = json_decode($response);

log_message('info', 'Paystack response for reference ' . $reference . ': ' . print_r($result, true));

if (!isset($result->data->status)) {
    $this->session->set_flashdata('error', 'Payment verification failed: Invalid response from Paystack.');
    redirect('home/checkout');
    return;
}


// Now, correctly check for a successful transaction using only the 'data->status' property
if ($result->data->status == 'success') {
    // Payment was successful!
    // ... (Your existing code for a successful payment) ...
    
    $order_data['status'] = 'successful';
    $order_data['payment_reference'] = $reference;

    $user_id = $order_data['user_id'];
    $this->usermodel->clearUserCart($user_id);

    $order_id = $this->ordermodel->insert_order($order_data, $minicart_items);

    if ($order_id) {
        $order = $this->ordermodel->get_order_by_id($order_id);
        $store_info = $this->storeinfo->getstoreinfo();

        // if ($order) {
        //     $email_data['order'] = $order;
        //     $email_data['store_info'] = $store_info;
        //     $email_body = $this->load->view('ui/load/order_confirmation_email', $email_data, TRUE);
        //     $email_subject = "Your Order Confirmation - #" . $order->id;
        //     $this->sendEmail($order->shipping_email, $order->shipping_full_name, $email_subject, $email_body);
        // }
        
        $this->session->unset_userdata('temp_order_data_' . $reference);
        $this->session->unset_userdata('temp_minicart_items_' . $reference);

        $this->session->set_flashdata('success', 'Payment successful! Your order #' . $order_id . ' has been placed.');
        redirect('home/orderConfirmation/' . $order_id);
    } else {
        $this->session->set_flashdata('error', 'Payment successful, but there was an issue recording your order. Please contact support with reference: ' . $reference);
        redirect('home/checkout');
    }
} else {
    // Payment failed or was not successful
    // Use the gateway_response for a more specific message
    $message = $result->data->gateway_response ?? 'Payment was not successful. Please try again or choose another method.';
    $this->session->set_flashdata('error', 'Payment failed: ' . $message);
    redirect('home/checkout');
}
}

    // --- Bank Deposit Integration ---
    public function bankDepositInstructions($order_id) {
        if ($this->session->userdata("user") == "") {
            $this->session->set_flashdata('error', 'Please log in.');
            redirect("home/login");
        }

        $order = $this->ordermodel->get_order_by_id($order_id); // Fetch order details
        if (!$order || $order->user_id != $this->session->userdata('user_id')) { // Ensure user owns the order
            $this->session->set_flashdata('error', 'Invalid order or unauthorized access.');
            redirect('home/dashboard'); // Or suitable redirect
        }

        // Fetch bank details from Storeinfo or config
        $store_info = $this->storeinfo->getstoreinfo();

        $acct_details_decoded =  json_decode($store_info['acct_details']);
        $data = $this->common_data;

        $data['bank_name'] = $acct_details_decoded->acct_bank ?? 'Bank Name'; // Assuming columns in storeinfo
        $data['account_name'] = $acct_details_decoded->acct_name ?? 'Account Name';
        $data['account_number'] = $acct_details_decoded->acct_num ?? 'Account Number';

        $data['order'] = $order;
        $data['name'] = "Bank Deposit"; // Title for the view
        $data["menu"] = $this->menumodel->getmainmenufordisplay(); // Assuming this typo is intended
        $data['thispage'] = 'Make Bank Deposit';
        $this->load->view('ui/bank_deposit_instructions', $data); // New view for instructions
    }

    // In your home controller
public function uploadPaymentProof() {
    if ($this->session->userdata("user") == "") {
        $this->session->set_flashdata('error', 'Please log in to upload proof.');
        redirect("home/login");
        return;
    }

    $order_id = $this->input->post('order_id', TRUE);
    $order = $this->ordermodel->get_order_by_id($order_id);

    if (!$order || $order->user_id != $this->session->userdata('user_id')) {
        $this->session->set_flashdata('error', 'Invalid order or unauthorized access for proof upload.');
        redirect('home/dashboard');
        return;
    }

    // Get shipping details from hidden fields
    $shipping_full_name = $this->input->post('shipping_full_name', TRUE);
    $shipping_phone = $this->input->post('shipping_phone', TRUE);
    $shipping_address = $this->input->post('shipping_address', TRUE);
    $shipping_email = $this->input->post('shipping_email', TRUE);

    // Configure and handle file upload
    $config['upload_path'] = './images/payment_proofs/';
    $config['allowed_types'] = 'gif|jpg|png|jpeg|pdf';
    $config['max_size'] = 2048; // 2MB
    $config['encrypt_name'] = TRUE;

    $this->load->library('upload', $config);

    if (!is_dir($config['upload_path'])) {
        mkdir($config['upload_path'], 0777, TRUE);
    }

    if (!$this->upload->do_upload('payment_proof')) {
        $error = $this->upload->display_errors();
        $this->session->set_flashdata('error', 'File upload failed: ' . strip_tags($error));
        redirect('home/bankDepositInstructions/' . $order_id);
    } else {
        $upload_data = $this->upload->data();
        $file_name = $upload_data['file_name'];

        // Update order in database with all details and new status
        $update_data = [
            'payment_proof_path' => $file_name,
            'status' => 'processing',
            'shipping_full_name' => $shipping_full_name,
            'shipping_phone' => $shipping_phone,
            'shipping_address' => $shipping_address,
            'shipping_email' => $shipping_email,
        ];
        
        $this->ordermodel->update_order_status($order_id, $update_data);
        
        $user_id = $this->usermodel->getUserIdFromUsername($this->session->userdata("user"));
        $this->usermodel->clearUserCart($user_id); // Clear cart after order is placed

        // Fetch the updated order to get all details for emails
        $updated_order = $this->ordermodel->get_order_by_id($order_id);
        
        // Send email notifications
        if($this->sendOrderConfirmationEmails($updated_order)){
            $this->session->set_flashdata('success', 'Payment proof uploaded successfully. Your order is now awaiting verification.');
            redirect('home/orderConfirmation/' . $order_id);
        }else{
            $this->session->set_flashdata('success/mail not sent', 'Mail Not Sent/Payment proof uploaded successfully. Your order is now awaiting verification.');
            redirect('home/orderConfirmation/' . $order_id);
        }

       
    }
}

// In your home controller
private function sendOrderConfirmationEmails($order) {
    // Load your PHPMailer library and configurations
    require_once APPPATH . 'third_party/phpmailer/src/Exception.php';
    require_once APPPATH . 'third_party/phpmailer/src/PHPMailer.php';
    require_once APPPATH . 'third_party/phpmailer/src/SMTP.php';
    
    $mail = new PHPMailer(true);
    $store_info = $this->storeinfo->getstoreinfo();

    try {
        // Server settings
        $mail->SMTPDebug = 0;
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = $store_info['email'];
        $mail->Password   = $this->app_password;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port       = 465;

        $order->store_name = $store_info["name"];
        $order->logo = $store_info["logo"];
        // Create email for the customer
        $mail->setFrom($store_info["email"], $store_info["name"]);
        $mail->addAddress($order->shipping_email, $order->shipping_full_name);
        $mail->Subject = 'Order ID #' . $order->id . ' Confirmation & Payment Proof Received';
        $mail->isHTML(true);

        if($order->method == 'paystack'){
            $customer_email_body = $this->load->view('ui/load/customer_order_confirmation_paystack', ['order' => $order], TRUE);

        }else{
            $customer_email_body = $this->load->view('ui/load/customer_order_confirmation', ['order' => $order], TRUE);

        }

        log_message('debug', 'Customer email body content: ' . $customer_email_body);

        $mail->Body = $customer_email_body;
        $mail->send();

        // Clear addresses for the next email
        $mail->clearAddresses();

        // Create email for the admin
        $mail->setFrom($store_info["email"], $store_info["name"]);
        $mail->addAddress($store_info["email"],$store_info["name"]); // Send to admin
        $mail->Subject = $store_info["name"].': New Order Placed from ' . $order->shipping_full_name;
        $admin_email_body = $this->load->view('ui/load/admin_order_notification', ['order' => $order], TRUE);
        log_message('debug', 'Customer email body content: ' . $admin_email_body);

        $mail->Body = $admin_email_body;
         // Attach the payment proof
         if($order->method == 'bank_deposit'){
            $proof_path = FCPATH . 'images/payment_proofs/' . $order->payment_proof_path;
            if (file_exists($proof_path)) {
                $mail->addAttachment($proof_path, 'Payment_Proof_' . $order->id . '.' . pathinfo($proof_path, PATHINFO_EXTENSION));
            }

         }
        
        
        $mail->send();

        log_message('info', 'Order confirmation and admin notification emails sent for order ID: ' . $order->id);
        return true;
    } catch (Exception $e) {
        log_message('error', 'Email sending failed for order ID: ' . $order->id . '. Mailer Error: ' . $e->getMessage());
        return false;
    }
}


    // --- WhatsApp Integration ---
    private function redirectToWhatsApp($order_id, $order_data, $minicart_items) {
        $whatsapp_number = '2347043635134'; // Replace with your WhatsApp number (e.g., 2348012345678 for +234 80 123 456 78)

        $message = "Hello! I'd like to place an order from your website.\n\n";
        $message .= "Order ID: *" . $order_id . "*\n\n";
        $message .= "*Shipping Details:*\n";
        $message .= "Name: " . $order_data['shipping_full_name'] . "\n";
        $message .= "Phone: " . $order_data['shipping_phone'] . "\n";
        $message .= "Address: " . $order_data['shipping_address'] . "\n";
        $message .= "Email: " . $order_data['shipping_email'] . "\n\n";
        $message .= "*Order Items:*\n";

        foreach ($minicart_items as $item) {
            $item_subtotal = $this->clean_price($item->pr_sell_price) * (int)$item->qty;
            $message .= " - " . (int)$item->qty . "x " . $item->pr_name . " (₦" . number_format($item_subtotal, 2) . ")\n";
        }

        $message .= "\n*Total Order Cost: ₦" . number_format($order_data['total_cost'], 2) . "*\n";
        $message .= "Payment Method: " . ucfirst(str_replace('_', ' ', $order_data['method'])) . "\n\n";
        $message .= "Please confirm my order and provide payment instructions (if needed).";

        $whatsapp_url = "https://wa.me/" . $whatsapp_number . "?text=" . urlencode($message);

        // Redirect after clearing cart
        $user_id = $order_data['user_id'];
        $this->usermodel->clearUserCart($user_id); // Clear cart after order is placed

        redirect($whatsapp_url); // Redirect directly to WhatsApp
    }

    // --- Order Confirmation Page (common for all methods) ---
    public function orderConfirmation($order_id) {
        if ($this->session->userdata("user") == "") {
            $this->session->set_flashdata('error', 'Please log in to view order confirmation.');
            redirect("home/login");
        }

        $order = $this->ordermodel->get_order_by_id($order_id);
        if (!$order || $order->user_id != $this->session->userdata('user_id')) {
            $this->session->set_flashdata('error', 'Invalid order or unauthorized access.');
            redirect('home/myOrders'); // Or a suitable page
        }
        $data = $this->common_data;
        $data['order'] = $order;
        $data['name'] = "Order Confirmation";
        $data["menu"] = $this->menumodel->getmainmenufordisplay(); // Assuming this is correct
        $data = array_merge($data, $this->storeinfo->getstoreinfo()); // Get store info for header/footer
        $data['thispage'] = 'Confirm Order';

        if($this->sendOrderConfirmationEmails($order)){
            $this->session->set_flashdata('success', 'A Mail Has Been Sent To You Regarding Your Order, Thank You!.');
            $this->load->view('ui/order_confirmation', $data); // New view for confirmation

        }else{
            $this->session->set_flashdata('success/mail not sent', 'Mail Not Sent');
            $this->load->view('ui/order_confirmation', $data); // New view for confirmation
        }

    }

    // You need an order_confirmation method and view
    public function whatsapp_proceed($order_id) {
        $data = $this->storeinfo->getstoreinfo();
        $data["menu"] = $this->menumodel->getmainmenufordisplay();

        $order_details = $this->usermodel->getOrderDetails($order_id); // You'll need this model method
        $order_items = $this->usermodel->getOrderItems($order_id); // You'll need this model method

        if (!$order_details || !$this->session->flashdata('success')) {
            // If order not found or no success message (direct access without placing order)
            $this->session->set_flashdata('error', 'Order not found or invalid access.');
            redirect('home/cart');
           // redirect('home/whatsapp_proceed'); //this works

        }

        $data['order_details'] = $order_details;
        $data['order_items'] = $order_items;
        $data['whatsapp_url'] = $this->session->flashdata('whatsapp_url'); // Retrieve the URL
        $this->load->view('ui/whatsapp_proceed', $data);
    }

    public function placeorder1(){
        // Redirect if user is not logged in
        if ($this->session->userdata("user") == "") {
            redirect("home/login");
        }
    
        $data = $this->storeinfo->getstoreinfo();
        // $data["cart_items"] = $this->usermodel->fetchThisUserCart(); // This might be for a full cart page, not needed for *this* summary if minicart_items is the source.
        $data["minicart_items"] = $this->usermodel->fetchThisUserMiniCart(); // Your minicart items (which will power the order summary)
    
        // Calculate minicart totals here
        $total_qty = 0;
        $sub_total = 0.00; // Initialize as float
        
        if ($data["minicart_items"]) {
            foreach ($data["minicart_items"] as $item) {
                // Ensure qty is an integer
                $item_qty = (int) $item->qty;
        
                // --- CRITICAL FIX HERE ---
                // 1. Remove commas from the price string first.
                $clean_price_string = str_replace(',', '', $item->pr_sell_price);
                // 2. Then, cast the cleaned string to float.
                $item_price = (float) $clean_price_string;
        
                // Now perform calculations with the accurate float values
                $total_qty += $item_qty;
                $sub_total += ($item_qty * $item_price);
            }
        }
        
        $data['minicart_total_qty'] = $total_qty;
        $data['minicart_sub_total'] = $sub_total; // This $sub_total will now hold the correct float value
        
    
        // Define shipping and tax for the order summary (these would typically come from config/DB)
        $shipping_cost = 0.00; // Example static value
        $grand_total = $sub_total + $shipping_cost;
    
        $data['shipping_cost'] = $shipping_cost;
        $data['tax_amount'] = $tax_amount;
        $data['grand_total'] = $grand_total;
    
    
        $data["menu"] = $this->menumodel->getmainmenufordisplay();
    
        $this->load->view('ui/placeorder', $data); // Assuming 'ui/checkout' is the main view that includes the minicart
    }

    public function product1($product_id = NULL) {
        if ($product_id === NULL || !is_numeric($product_id)) {
            $this->loaddefaultpage();
            return;
        }
    
        $data = $this->storeinfo->getstoreinfo();
        // $data["cart_items"] = $this->usermodel->fetchThisUserCart(); // This might be for a full cart page, not needed for *this* summary if minicart_items is the source.
        $data["minicart_items"] = $this->usermodel->fetchThisUserMiniCart(); // Your minicart items (which will power the order summary)
    
        // Calculate minicart totals here
        $total_qty = 0;
        $sub_total = 0.00; // Initialize as float
        
        if ($data["minicart_items"]) {
            foreach ($data["minicart_items"] as $item) {
                // Ensure qty is an integer
                $item_qty = (int) $item->qty;
        
                // --- CRITICAL FIX HERE ---
                // 1. Remove commas from the price string first.
                $clean_price_string = str_replace(',', '', $item->pr_sell_price);
                // 2. Then, cast the cleaned string to float.
                $item_price = (float) $clean_price_string;
        
                // Now perform calculations with the accurate float values
                $total_qty += $item_qty;
                $sub_total += ($item_qty * $item_price);
            }
        }
        
        $data['minicart_total_qty'] = $total_qty;
        $data['minicart_sub_total'] = $sub_total; // This $sub_total will now hold the correct float value
        
        $data["cart_items"] = $this->usermodel->fetchThisUserCart();
        $data["menu"] = $this->menumodel->getmainmenufordisplay();
    
        $product = $this->productmodel->fetchSingleProduct($product_id);
    
        if ($product) {
            $data['product'] = $product;
            $data['product']->category_name = $this->productmodel->getCategoryName($product->main_category);
            $data['product']->tag_names = $this->productmodel->getTagNames($product->tag_category);
    
            $data['related_products'] = $this->productmodel->getRelatedProducts($product_id, $product->main_category);
            $user_id = $this->session->userdata("user_id"); // Assuming you store user_id in session
            $existing_cart_item = $this->productmodel->getCartItemQuantity($product_id, $user_id);
    
            if ($existing_cart_item) {
                $data['initial_quantity'] = $existing_cart_item->qty;
            } else {
                $data['initial_quantity'] = 1; // Default to 1 if not in cart
            }
            // --- NEW CODE END ---
    
            $this->load->view('ui/single-product', $data);
        } else {
            $this->loaddefaultpage();
        }
    }

    public function product($pr_url) {
        // If the URL is empty, load the default page
        if (empty($pr_url)) {
            $this->loaddefaultpage();
            return;
        }
    
        // Fetch the product from the model using the URL slug
        $product = $this->productmodel->fetchSingleProduct($pr_url);
    
        // If a product is found, proceed to load the view with its data
        if ($product) {
            $data = $this->storeinfo->getstoreinfo();

            // $data["cart_items"] = $this->usermodel->fetchThisUserCart(); // This might be for a full cart page, not needed for *this* summary if minicart_items is the source.
        $data["minicart_items"] = $this->usermodel->fetchThisUserMiniCart(); // Your minicart items (which will power the order summary)
    
        // Calculate minicart totals here
        $total_qty = 0;
        $sub_total = 0.00; // Initialize as float
        
        if ($data["minicart_items"]) {
            foreach ($data["minicart_items"] as $item) {
                // Ensure qty is an integer
                $item_qty = (int) $item->qty;
        
                // --- CRITICAL FIX HERE ---
                // 1. Remove commas from the price string first.
                $clean_price_string = str_replace(',', '', $item->pr_sell_price);
                // 2. Then, cast the cleaned string to float.
                $item_price = (float) $clean_price_string;
        
                // Now perform calculations with the accurate float values
                $total_qty += $item_qty;
                $sub_total += ($item_qty * $item_price);
            }
        }
        
        $data['minicart_total_qty'] = $total_qty;
        $data['minicart_sub_total'] = $sub_total; // This $sub_total will now hold the correct float value
        
            $data["cart_items"] = $this->usermodel->fetchThisUserCart();
            $data["menu"] = $this->menumodel->getmainmenufordisplay();
            
            // Pass the fetched product data to the view
            $data['product'] = $product;
            
            // Fetch related data using the product's ID
            $data['product']->category_name = $this->productmodel->getCategoryName($product->main_category);
            $data['product']->tag_names = $this->productmodel->getTagNames($product->tag_category);
            
            $data['related_products'] = $this->productmodel->getRelatedProducts($product->id, $product->main_category);
            
            // Determine the initial quantity for the cart
            $user_id = $this->session->userdata("user_id"); // Assuming you store user_id in session
            $existing_cart_item = $this->productmodel->getCartItemQuantity($product->id, $user_id);
            
            if ($existing_cart_item) {
                $data['initial_quantity'] = $existing_cart_item->qty;
            } else {
                $data['initial_quantity'] = 1; // Default to 1 if not in cart
            }
            $data['thispage'] = $product->pr_name;

            $this->load->view('ui/single-product', $data);
        } else {
            // If no product is found, load the default page
            $this->loaddefaultpage();
        }
    }
    

   
   
    
    public function about() {
        $data = $this->storeinfo->getstoreinfo();
        $data["menu"] = $this->menumodel->getmainmenufordisplay();
        $data["carousels"] = $this->carouselmodel->getcarousels();
        $data["categoryWithProductNum"] = $this->categorymodel->getmaincategorywithproductnum();
        $data["allCategories"] = $this->categorymodel->getAllCategories();
        $data["newStock"] = $this->productmodel->getNewStock();
        $data["getTabbedProducts"] = $this->productmodel->getTabbedProductsHTML();

        $data["minicart_items"] = $this->usermodel->fetchThisUserMiniCart(); // Your minicart items

        // Calculate minicart totals here
        $total_qty = 0;
        $sub_total = 0.00; // Initialize as float
        if ($data["minicart_items"]) {
            foreach ($data["minicart_items"] as $item) {
                // Ensure qty and price are treated as numbers for calculation
                $item_qty = (int) $item->qty;
                $item_price = (float) $item->pr_sell_price;

                $total_qty += $item_qty;
                $sub_total += ($item_qty * $item_price);
            }
        }
        $data['minicart_total_qty'] = $total_qty;
        $data['minicart_sub_total'] = $sub_total;


        // --- NEW IMPLEMENTATION FOR CATEGORY-BASED PRODUCT DISPLAY ---

        // 1. Get random main categories (e.g., max 3 as discussed)
        $mainCategoriesForDisplay = $this->categorymodel->getRandomMainCategories(3); // Assuming this function is in categorymodel

        // 2. Prepare data structure for the view
        $displayCategoryBlocks = array();
        foreach ($mainCategoriesForDisplay as $category) {
            // Get random products for each category (e.g., max 3 products per category)
            $productsForCategory = $this->productmodel->getRandomProductsByCategory($category->id, 3); // Assuming this function is in productmodel

            // Only add the category block if it actually has products to display
            if (!empty($productsForCategory)) {
                $displayCategoryBlocks[] = (object) [ // Cast to object for consistent access in view
                    'category_info' => $category,
                    'products'      => $productsForCategory
                ];
            }
        }

        // Add the prepared data to the $data array that's passed to the view
        $data['displayCategoryBlocks'] = $displayCategoryBlocks;
        
        $data['thispage'] = 'About Us';

        $this->load->view('ui/about-us', $data);
    }
    public function contact() {
        $data = $this->storeinfo->getstoreinfo();
        $data["menu"] = $this->menumodel->getmainmenufordisplay();
        $data["carousels"] = $this->carouselmodel->getcarousels();
        $data["categoryWithProductNum"] = $this->categorymodel->getmaincategorywithproductnum();
        $data["allCategories"] = $this->categorymodel->getAllCategories();
        $data["newStock"] = $this->productmodel->getNewStock();
        $data["getTabbedProducts"] = $this->productmodel->getTabbedProductsHTML();

        $data["minicart_items"] = $this->usermodel->fetchThisUserMiniCart(); // Your minicart items

        // Calculate minicart totals here
        $total_qty = 0;
        $sub_total = 0.00; // Initialize as float
        if ($data["minicart_items"]) {
            foreach ($data["minicart_items"] as $item) {
                // Ensure qty and price are treated as numbers for calculation
                $item_qty = (int) $item->qty;
                $item_price = (float) $item->pr_sell_price;

                $total_qty += $item_qty;
                $sub_total += ($item_qty * $item_price);
            }
        }
        $data['minicart_total_qty'] = $total_qty;
        $data['minicart_sub_total'] = $sub_total;


        // --- NEW IMPLEMENTATION FOR CATEGORY-BASED PRODUCT DISPLAY ---

        // 1. Get random main categories (e.g., max 3 as discussed)
        $mainCategoriesForDisplay = $this->categorymodel->getRandomMainCategories(3); // Assuming this function is in categorymodel

        // 2. Prepare data structure for the view
        $displayCategoryBlocks = array();
        foreach ($mainCategoriesForDisplay as $category) {
            // Get random products for each category (e.g., max 3 products per category)
            $productsForCategory = $this->productmodel->getRandomProductsByCategory($category->id, 3); // Assuming this function is in productmodel

            // Only add the category block if it actually has products to display
            if (!empty($productsForCategory)) {
                $displayCategoryBlocks[] = (object) [ // Cast to object for consistent access in view
                    'category_info' => $category,
                    'products'      => $productsForCategory
                ];
            }
        }

        // Add the prepared data to the $data array that's passed to the view
        $data['displayCategoryBlocks'] = $displayCategoryBlocks;
       

        $data['thispage'] = 'Contact Us';
        $this->load->view('ui/contact', $data);
    }

    
    
  
    public function getStoreDetailsApi() { 
        // Renamed the function to reflect its API purpose
        $storeInfo = $this->storeinfo->getStoreInfo();

        // Set the content type header to application/json
        $this->output->set_content_type('application/json');

        // Output the data as JSON
        echo json_encode($storeInfo);
    }
    public function menu() {
        // Get the entire menu hierarchy
        $this->output->set_content_type('application/json');

        $menu_data = $this->Menu_Model->build_menu_hierarchy('Main');

        // Output as JSON
        echo json_encode($menu_data);
    }

    public function loaddefaultpage() {
        $data = $this->storeinfo->getstoreinfo();
        $data["menu"] = $this->menumodel->getmainmenufordisplay();
        $data["carousels"] = $this->carouselmodel->getcarousels();
        $data["categoryWithProductNum"] = $this->categorymodel->getmaincategorywithproductnum();
        $data["allCategories"] = $this->categorymodel->getAllCategories();
        $data["newStock"] = $this->productmodel->getNewStock();
        $data["getTabbedProducts"] = $this->productmodel->getTabbedProductsHTML();

        $data["minicart_items"] = $this->usermodel->fetchThisUserMiniCart(); // Your minicart items

        // Calculate minicart totals here
        $total_qty = 0;
        $sub_total = 0.00; // Initialize as float
        if ($data["minicart_items"]) {
            foreach ($data["minicart_items"] as $item) {
                // Ensure qty and price are treated as numbers for calculation
                $item_qty = (int) $item->qty;
                $item_price = (float) $item->pr_sell_price;

                $total_qty += $item_qty;
                $sub_total += ($item_qty * $item_price);
            }
        }
        $data['minicart_total_qty'] = $total_qty;
        $data['minicart_sub_total'] = $sub_total;


        // --- NEW IMPLEMENTATION FOR CATEGORY-BASED PRODUCT DISPLAY ---

        // 1. Get random main categories (e.g., max 3 as discussed)
        $mainCategoriesForDisplay = $this->categorymodel->getRandomMainCategories(3); // Assuming this function is in categorymodel

        // 2. Prepare data structure for the view
        $displayCategoryBlocks = array();
        foreach ($mainCategoriesForDisplay as $category) {
            // Get random products for each category (e.g., max 3 products per category)
            $productsForCategory = $this->productmodel->getRandomProductsByCategory($category->id, 3); // Assuming this function is in productmodel

            // Only add the category block if it actually has products to display
            if (!empty($productsForCategory)) {
                $displayCategoryBlocks[] = (object) [ // Cast to object for consistent access in view
                    'category_info' => $category,
                    'products'      => $productsForCategory
                ];
            }
        }

        // Add the prepared data to the $data array that's passed to the view
        $data['displayCategoryBlocks'] = $displayCategoryBlocks;
       
       $this->load->view('ui/404', $data);
    }
    
   
   

    

    public function logoutuser() {
        $this->session->unset_userdata("user");
        $this->login();
    }

    
}