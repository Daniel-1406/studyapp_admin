<?php

class Storeinfo extends CI_Model {

    public function getStoreInfo() {
        // Explicitly select all columns except for the sensitive ones.
        $this->db->select('id, name, information, address, telephone, whatsapp, instagram, facebook, youtube, googlemap, email, logo, welcome_text, welcome_quote, open_hours, colour, core_values, services, special_offer, meta_keywords, acct_details');
    
        $query = $this->db->get_where('about', array('id' => 1));
    
        $record = array();
        if ($query->num_rows() > 0) {
            $row = $query->row_array();
            $record = $row;
        } else {
            $record = [
                "id" => "",
                "name" => "",
                "information" => "",
                "address" => "",
                "telephone" => "",
                "whatsapp" => "",
                "instagram" => "",
                "facebook" => "",
                "youtube" => "",
                "googlemap" => "",
                "email" => "",
                "logo" => "",
                "welcome_text" => "",
                "welcome_quote" => "",
                "open_hours" => "",
                "colour" => "",
                "core_values" => "{}",
                "services" => "{}",
                "special_offer" => "{}",
                "meta_keywords" => "{}",
                "acct_details" => "{}"
            ];
        }
        return $record;
    }
    public function getSecretKeys() {
        // Select only the sensitive columns.
        $this->db->select('paystack_keys, app_password');
        $query = $this->db->get_where('about', array('id' => 1));
    
        // Check if the CodeIgniter Encryption Library is available.
        // if (!isset($this->encryption)) {
        //     log_message('error', 'CI Encryption Library not loaded. Please ensure it is loaded in autoload.php or your controller.');
        //     return null;
        // }
    
        if ($query->num_rows() > 0) {
            $row = $query->row();
            
            try {
                // Decrypt the raw data from the database
                // $paystack_keys_json = $this->encryption->decrypt($row->paystack_keys);
                // $app_password_decrypted = $this->encryption->decrypt($row->app_password);
                $paystack_keys_json = $row->paystack_keys;
                $app_password_decrypted = $row->app_password;
            } catch (Exception $e) {
                // If decryption fails (e.g., due to invalid key or data), log the error
                log_message('error', 'Decryption failed: ' . $e->getMessage());
                return null;
            }
    
            // Decode the Paystack keys JSON
            $paystack_data = json_decode($paystack_keys_json, true);
    
            // Create a new object to hold the formatted data
            $result = new stdClass();
            $result->paystack_public_key = isset($paystack_data['public_key']) ? $paystack_data['public_key'] : '';
            $result->paystack_secret_key = isset($paystack_data['private_key']) ? $paystack_data['private_key'] : '';
            $result->app_password = $app_password_decrypted;
            
            return $result;
        }
    
        return null;
    }

    

    public function updateStoreInfo($storeInfo = array()) {
        // Ensure the ID is present and valid
        if (empty($storeInfo['id'])) {
            log_message('error', 'Attempted to update store info without an ID.');
            return false;
        }

        $this->db->where('id', $storeInfo['id']);
        if ($this->db->update("about", $storeInfo)) {
            return true; // Success
        } else {
            // Log the database error if needed for debugging
            log_message('error', 'Database error updating store info: ' . $this->db->error()['message']);
            return false; // Failure
        }
    }

    public function update_last_orderlist_view() {
        $timestamp = date('Y-m-d H:i:s');
        $data = array(
            'last_orderlist_view' => $timestamp
        );
    
        // Update the 'about' table where the 'id' column is equal to 1.
        $this->db->where('id', 1);
        $this->db->update('about', $data);
    }

    public function get_last_orderlist_view() {
        // Select the 'last_orderlist_view' column
        $this->db->select('last_orderlist_view');
        // From the 'about' table
        $this->db->from('about');
        // Where the 'id' is 1
        $this->db->where('id', 1);
        // Limit the result to one row
        $query = $this->db->get();
    
        // Check if a row was returned
        if ($query->num_rows() > 0) {
            // Return the value of the 'last_orderlist_view' column
            $row = $query->row();
            return $row->last_orderlist_view;
        }
    
        // Return null if no data is found
        return null;
    }
    // In application/models/Ordermodel.php

    public function count_new_orders() {
    
        // This function assumes you have a way to get the timestamp of the last time someone viewed the orders list.
        // Let's assume a method like this exists in your model.
        $last_view_timestamp = $this->get_last_orderlist_view();
    
        // If there is no last view timestamp, return the count of all orders.
        if (!$last_view_timestamp) {
            return $this->db->count_all('orders');
        }
    
        // Count orders where the creation date is greater than the last view timestamp.
        // Assuming your orders table has a 'created_at' or similar timestamp column.
        $this->db->where('order_date >', $last_view_timestamp);
        $this->db->from('orders');
        $count = $this->db->count_all_results();
    
        // Return the count, formatted as a badge if the count is greater than 0.
        if ($count > 0) {
            return '<span class="badge bg-danger right">'.$count.'</span>';
        } else {
            return null;
        }
    }
// In application/models/Usermodel.php

public function count_new_users() {
    
    $last_view_timestamp = $this->get_last_userlist_view();

    // If there is no last view timestamp, return the count of all users.
    if (!$last_view_timestamp) {
        return $this->db->count_all('users');
    }

    // Count users where the creation date is greater than the last view timestamp.
    $this->db->where('created_at >', $last_view_timestamp);
    $this->db->from('users');
    $count = $this->db->count_all_results();

    // Return the count, formatted as a badge if the count is greater than 0.
    if ($count > 0) {
        return '<span class="badge bg-danger right">'.$count.'</span>';
    } else {
        return null; // A hard-coded value, perhaps a placeholder.
    }
}
    
public function update_last_userlist_view() {
    $timestamp = date('Y-m-d H:i:s');
    $data = array(
        'last_userlist_view' => $timestamp
    );
    $this->db->where('id', 1);
    $this->db->update('about', $data);
}

// Function to get the last user list view timestamp
public function get_last_userlist_view() {
    $this->db->select('last_userlist_view');
    $this->db->from('about');
    $this->db->where('id', 1);
    $query = $this->db->get();

    if ($query->num_rows() > 0) {
        $row = $query->row();
        return $row->last_userlist_view;
    }

    return null;
}

public function pendingorders() {
    $this->db->where('deleted', 'f');
    $this->db->where_in('status', ['pending_payment_verification', 'processing', 'pending']);
    return $this->db->count_all_results('orders');
}
public function get_all_order_revenue() {
    $this->db->select_sum('total_cost');
    $this->db->from('orders');
    $this->db->where_in('status', ['successful', 'delivered']);
    $this->db->where('deleted', 'f');
    $query = $this->db->get();
    if ($query->num_rows() > 0) {
        $row = $query->row();   
        return $row->total_cost ?: 0.00;
    }
    return 0.00; 
}
public function get_monthly_revenue_sum() {
    $this->db->select("YEAR(order_date) AS order_year, MONTH(order_date) AS order_month, SUM(total_cost) AS monthly_revenue");
    $this->db->where_in('status', ['successful', 'delivered']);
    $this->db->where('deleted', 'f');
    $this->db->group_by('order_year, order_month');
    $this->db->order_by('order_year', 'ASC');
    $this->db->order_by('order_month', 'ASC');
    $query = $this->db->get('orders');
    return $query->result_array();
}
public function get_daily_revenue_sum() {
    $this->db->select("DATE(order_date) AS order_day, SUM(total_cost) AS daily_revenue");
    $this->db->where_in('status', ['successful', 'delivered']);
    $this->db->where('deleted', 'f');
    $this->db->group_by('order_day');
    $this->db->order_by('order_day', 'ASC');
    $query = $this->db->get('orders');
    return $query->result_array();
}
public function get_weekly_revenue_sum() {
    $this->db->select("YEAR(order_date) AS order_year, WEEK(order_date, 1) AS order_week, SUM(total_cost) AS weekly_revenue");
    $this->db->where_in('status', ['successful', 'delivered']);
    $this->db->where('deleted', 'f');
    $this->db->group_by('order_year, order_week');
    $this->db->order_by('order_year', 'ASC');
    $this->db->order_by('order_week', 'ASC');
    $query = $this->db->get('orders');
    return $query->result_array();
}
public function get_weekly_order_count() {
    $this->db->select("YEAR(order_date) AS order_year, WEEK(order_date, 1) AS order_week, COUNT(id) AS weekly_orders");
    $this->db->where('deleted', 'f');
    $this->db->group_by('order_year, order_week');
    $this->db->order_by('order_year', 'ASC');
    $this->db->order_by('order_week', 'ASC');
    $query = $this->db->get('orders');
    return $query->result_array();
}
public function get_monthly_order_count() {
    $this->db->select("YEAR(order_date) AS order_year, MONTH(order_date) AS order_month, COUNT(id) AS monthly_orders");
    $this->db->where('deleted', 'f');
    $this->db->group_by('order_year, order_month');
    $this->db->order_by('order_year', 'ASC');
    $this->db->order_by('order_month', 'ASC');
    $query = $this->db->get('orders');
    return $query->result_array();
}
public function get_daily_order_count() {
    $this->db->select("DATE(order_date) AS order_day, COUNT(id) AS daily_orders");
    $this->db->where('deleted', 'f');
    $this->db->group_by('order_day');
    $this->db->order_by('order_day', 'ASC');
    $query = $this->db->get('orders');
    return $query->result_array();
}



/**
 * Counts the number of orders that are marked as 'successful'.
 *
 * @return int The total number of orders with a 'successful' status.
 */
public function successfulorders() {
    // Add a condition to only count orders that have not been deleted.
    $this->db->where('deleted', 'f');
    $this->db->where('status =', 'successful');
    return $this->db->count_all_results('orders');
}

/**
 * Counts the number of orders that are marked as 'delivered'.
 *
 * @return int The total number of orders with a 'delivered' status.
 */
public function deliveredorders() {
    // Add a condition to only count orders that have not been deleted.
    $this->db->where('deleted', 'f');
    $this->db->where('status =', 'delivered');
    return $this->db->count_all_results('orders');
}
public function verifiedUsers() {
    // Add a condition to only count users that have not been deleted.
    $this->db->where('deleted', 'f');
    $this->db->where('status =', 'verified');
    return $this->db->count_all_results('users');
}
public function countActiveProducts() {
    // Add a condition to only count products that have not been deleted.
    $this->db->where('deleted', 'f');
    // Execute the count query and return the total number of rows.
    return $this->db->count_all_results('products');
}


    

}

?>
