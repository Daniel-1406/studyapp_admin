<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Secrets {

    protected $CI;
    protected $paystack_secret_key;
    protected $paystack_public_key;
    protected $app_password;

    public function __construct() {
        // Assign the CodeIgniter super-object
        $this->CI =& get_instance();

        // Load the storeinfo model
        $this->CI->load->model('storeinfo');

        // Fetch all secret keys from the database
        $secretKeys = $this->CI->storeinfo->getSecretKeys();

        // Check if the keys were successfully retrieved
        if ($secretKeys) {
            // The model now returns a clean object with decrypted, formatted properties,
            // so we can assign them directly.
            $this->paystack_public_key = $secretKeys->paystack_public_key;
            $this->paystack_secret_key = $secretKeys->paystack_secret_key;
            $this->app_password = $secretKeys->app_password;
        }
        //var_dump($secretKeys);

    }

    /**
     * Get the decrypted Paystack secret key.
     * @return string
     */
    public function getPaystackSecretKey() {
        return $this->paystack_secret_key;
    }

    /**
     * Get the decrypted Paystack public key.
     * @return string
     */
    public function getPaystackPublicKey() {
        return $this->paystack_public_key;
    }

    /**
     * Get the decrypted app password.
     * @return string
     */
    public function getAppPassword() {
        return $this->app_password;
    }
}
