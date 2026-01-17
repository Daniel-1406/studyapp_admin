<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif; line-height: 1.6; color: #333; background-color: #f4f4f4; margin: 0; padding: 20px 0; }
        .email-container { max-width: 600px; margin: 0 auto; background-color: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.05); }
        .header { text-align: center; padding-bottom: 20px; border-bottom: 1px solid #eee; }
        .header h2 { color: #d9534f; margin: 0; }
        .content { padding: 20px 0; }
        .order-details p { margin: 5px 0; font-size: 1.1em; }
        .order-details strong { display: inline-block; width: 120px; }
        .alert { background-color: #fff3cd; color: #856404; padding: 15px; border: 1px solid #ffeeba; border-radius: 8px; margin-top: 20px; }
        .cta-links { margin-top: 20px; text-align: center; }
        .cta-links a { display: inline-block; background-color: #28a745; color: #fff; padding: 10px 20px; border-radius: 5px; text-decoration: none; margin: 5px; }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <h2>🚨 <?php if($order->method == 'paystack'){echo " Payment Via Paystack. ";}else{echo " New Bank Deposit Order! ";}?>  🚨</h2>
        </div>
        <div class="content">
            <p class="alert">A new order has been placed with the <?php if($order->method == 'paystack'){echo " Paystack Payment method. ";} else{ echo "Bank Deposit payment method. Payment proof has been uploaded and requires your immediate verification.";}?></p>

            <div class="order-details">
                <h3>Order Information</h3>
                <p><strong>Order ID:</strong> #<?= html_escape($order->id) ?></p>
                <p><strong>Total Amount:</strong> ₦<?= number_format($order->total_cost, 2) ?></p>
                <p><strong>Payment Method:</strong>  <?= html_escape(ucfirst(str_replace('_', ' ', $order->method))) ?></p>
                <p><strong>Payment Reference:</strong> <?= html_escape($order->payment_reference) ?></p>
                <p><strong>Current Status:</strong> <?= html_escape(ucfirst(str_replace('_', ' ', $order->status))) ?></p>
                <p><strong>Additional Notes:</strong> <?= html_escape( $order->add_notes) ?></p>
            </div>

            <div class="order-details">
                <h3>Customer Details</h3>
                <p><strong>Name:</strong> <?= html_escape($order->shipping_full_name) ?></p>
                <p><strong>Email:</strong> <?= html_escape($order->shipping_email) ?></p>
                <p><strong>Phone:</strong> <?= html_escape($order->shipping_phone) ?></p>
                <p><strong>Region:</strong> <?= html_escape($order->shipping_region) ?></p>
                <p><strong>Shipping Address:</strong> <?= html_escape($order->shipping_address) ?></p>
            </div>

        
            
        </div>
        <div class="footer">
            <p>This is an automated notification. Please do not reply.</p>
        </div>
    </div>
</body>
</html>