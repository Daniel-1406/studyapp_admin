<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Order Confirmation</title>
    <style type="text/css">
        body { margin: 0; padding: 0; min-width: 100%; background-color: #f6f6f6; font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen-Sans, Ubuntu, Cantarell, "Helvetica Neue", sans-serif; }
        .content { width: 100%; max-width: 600px; margin: 0 auto; display: block; }
        .header { padding: 20px; text-align: center; background-color: #ffffff; border-bottom: 1px solid #e9e9e9; }
        .header h1 { margin: 0; color: #333333; font-size: 24px; font-weight: bold; }
        .inner-content { padding: 40px 20px; background-color: #ffffff; }
        .button { display: inline-block; padding: 10px 20px; background-color: #007bff; color: #ffffff; text-decoration: none; border-radius: 5px; font-weight: bold; }
        .order-summary { border-collapse: collapse; width: 100%; margin-top: 20px; }
        .order-summary th, .order-summary td { padding: 12px; border-bottom: 1px solid #dddddd; text-align: left; }
        .order-summary th { background-color: #f2f2f2; font-weight: bold; }
        .footer { text-align: center; padding: 20px; font-size: 12px; color: #999999; }
        .footer p { margin: 0; }
    </style>
</head>
<body style="margin: 0; padding: 0; background-color: #f6f6f6;">
    <center class="wrapper" style="width: 100%; table-layout: fixed; -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%;">
        <table role="presentation" class="main" width="100%" cellpadding="0" cellspacing="0" style="border-collapse: collapse; max-width: 600px; margin: 0 auto; background-color: #ffffff;">
            <tr>
                <td style="padding: 0;">
                    <div class="header" style="padding: 20px; text-align: center; background-color: #ffffff; border-bottom: 1px solid #e9e9e9;">
                        <h1 style="margin: 0; color: #333333; font-size: 24px; font-weight: bold;"><?php echo html_escape($store_info['name']); ?></h1>
                    </div>
                </td>
            </tr>
            <tr>
                <td style="padding: 0;">
                    <div class="inner-content" style="padding: 40px 20px; background-color: #ffffff;">
                        <h2 style="color: #333333; text-align: center;">Order Confirmation</h2>
                        <p style="color: #666666; line-height: 1.6; text-align: center;">Hello <?php echo html_escape($order->shipping_full_name); ?>,</p>
                        <p style="color: #666666; line-height: 1.6; text-align: center;">Thanks for your purchase! Your order #<?php echo html_escape($order->id); ?> has been successfully placed.</p>
                        <p style="color: #666666; line-height: 1.6; text-align: center;">You can track the progress of your order and view all the details by logging into your account dashboard on our website.</p>
                        <div style="margin-top: 30px;">
                            <h3 style="color: #333333;">Order Summary</h3>
                            <p style="color: #666666; margin: 0;"><strong>Total Amount:</strong> ₦<?php echo number_format($order->total_cost, 2); ?></p>
                            <p style="color: #666666; margin: 0;"><strong>Payment Method:</strong> Paystack</p>
                            <p style="color: #666666; margin: 0;"><strong>Order Status:</strong> <?php echo html_escape(ucfirst($order->status)); ?></p>
                            <p style="color: #666666; margin: 0;"><strong>Payment Reference:</strong> <?php echo html_escape(ucfirst($order->payment_reference)); ?></p>
                        </div>

                        <div style="margin-top: 30px;">
                            <h3 style="color: #333333;">Shipping Information</h3>
                            <p style="color: #666666; margin: 0;"><strong>Name:</strong> <?php echo html_escape($order->shipping_full_name); ?></p>
                            <p style="color: #666666; margin: 0;"><strong>Address:</strong> <?php echo html_escape($order->shipping_address); ?></p>
                            <p style="color: #666666; margin: 0;"><strong>Phone:</strong> <?php echo html_escape($order->shipping_phone); ?></p>
                        </div>
                        
                        <div style="text-align: center; margin-top: 40px;">
                            <a href="<?php echo site_url('home/dashboard'); ?>" class="button" style="display: inline-block; padding: 12px 25px; background-color: #007bff; color: #ffffff; text-decoration: none; border-radius: 5px; font-weight: bold;">View Your Dashboard</a>
                        </div>
                    </div>
                </td>
            </tr>
            <tr>
                <td style="padding: 0;">
                    <div class="footer" style="text-align: center; padding: 20px; font-size: 12px; color: #999999;">
                        <p>&copy; <?php echo date('Y'); ?> <?php echo html_escape($store_info['name']); ?>. All rights reserved.</p>
                    </div>
                </td>
            </tr>
        </table>
    </center>
</body>
</html>