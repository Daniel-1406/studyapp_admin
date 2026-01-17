<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif; line-height: 1.6; color: #333; background-color: #f4f4f4; margin: 0; padding: 20px 0; }
        .email-container { max-width: 600px; margin: 0 auto; background-color: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.05); }
        .header { text-align: center; padding-bottom: 20px; border-bottom: 1px solid #eee; }
        .header img { max-width: 150px; }
        .content { padding: 20px 0; }
        .order-summary, .shipping-details { border: 1px solid #eee; border-radius: 8px; padding: 15px; margin-top: 20px; }
        .order-summary h3, .shipping-details h3 { margin-top: 0; color: #555; }
        .order-summary p, .shipping-details p { margin: 5px 0; }
        .order-summary ul { list-style: none; padding: 0; }
        .order-summary ul li { padding: 10px 0; border-bottom: 1px solid #f9f9f9; display: flex; justify-content: space-between; align-items: center; }
        .order-summary ul li:last-child { border-bottom: none; }
        .button-container { text-align: center; margin-top: 30px; }
        .button { background-color: #007bff; color: #ffffff; padding: 12px 24px; border-radius: 5px; text-decoration: none; font-weight: bold; }
        .footer { text-align: center; font-size: 0.8em; color: #999; margin-top: 20px; }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <h1><?= html_escape($order->store_name) ?></h1>
        </div>
        <div class="content">
            <h2>Hi, <?= html_escape($order->shipping_full_name) ?>! 👋</h2>
            <p>Thanks for your purchase! Your order #<?php echo html_escape($order->id); ?> has been successfully placed.</b>.</p>
            <p>You can track the progress of your order and view all the details by logging into your account dashboard on our website.</p>

            <div class="order-summary">
                <h3>Order Summary</h3>
                <p><strong>Order ID:</strong> #<?= html_escape($order->id) ?></p>
                <p><strong>Payment Reference:</strong> #<?= html_escape($order->payment_reference) ?></p>
                <p><strong>Order Date:</strong> <?= date('F j, Y', strtotime($order->order_date)) ?></p>
                <p><strong>Total Amount:</strong> ₦<?= number_format($order->total_cost, 2) ?></p>
            </div>

            <div class="shipping-details">
                <h3>Shipping Details</h3>
                <p><strong>Name:</strong> <?= html_escape($order->shipping_full_name) ?></p>
                <p><strong>Region:</strong> <?= html_escape($order->shipping_region) ?></p>
                <p><strong>Address:</strong> <?= html_escape($order->shipping_address) ?></p>
                <p><strong>Phone:</strong> <?= html_escape($order->shipping_phone) ?></p>
            </div>

            <div class="button-container">
                <a href="<?= site_url('home/dashboard') ?>" class="button">View My Dashboard</a>
            </div>

            <p style="text-align:center; margin-top: 20px;">If you have any questions, reply to this email.</p>
        </div>
        <div class="footer">
            <p>&copy; <?= date('Y') ?> <?= html_escape($order->store_name) ?>. All rights reserved.</p>
        </div>
    </div>
</body>
</html>