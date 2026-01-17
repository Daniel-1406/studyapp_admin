<?php if (isset($minicart_items) && $minicart_items): ?>
    <?php foreach ($minicart_items as $item): ?>
        <li>
            <div class="minicart-item">
                <div class="thumb">
                    <a href="#">
                        <img src="<?php echo base_url('images/' . $item->photo); ?>" width="90" height="90" style="height:90px; width:90px;" alt="<?php echo $item->pr_name; ?>">
                    </a>
                </div>
                <div class="left-info">
                    <div class="product-title"><a href="#" class="product-name"><?php echo $item->pr_name; ?></a></div>
                    <div class="price">
    <?php
    // --- FIX: Remove commas from price strings before casting to float ---

    // Clean the sell price string by removing commas
    $clean_sell_price_string = str_replace(',', '', $item->pr_sell_price);
    // Cast to float. Using ?? 0.00 provides a default if the value is null/empty.
    $pr_sell_price_float = (float)($clean_sell_price_string ?? 0.00);

    // Clean the slash price string by removing commas
    $clean_slash_price_string = str_replace(',', '', $item->pr_slash_price);
    // Cast to float. Using ?? 0.00 provides a default if the value is null/empty.
    $pr_slash_price_float = (float)($clean_slash_price_string ?? 0.00);
    ?>

    <?php if ($item->pr_sell_price !== null): ?>
        <ins><span class="price-amount"><span class="currencySymbol">₦ </span><?php echo number_format($pr_sell_price_float, 2); ?></span></ins>
    <?php endif; ?>

    <?php
    // Ensure the comparison also uses the correctly converted float values
    if ($pr_slash_price_float > 0 && $pr_slash_price_float != $pr_sell_price_float):
    ?>
        <del><span class="price-amount"><span class="currencySymbol">₦ </span><?php echo number_format($pr_slash_price_float, 2); ?></span></del>
    <?php endif; ?>
</div>

                    <div class="qty">
                        <label for="cart[<?php echo $item->cart_id; ?>][qty]">Qty:</label>
                        <input type="number" class="input-qty" data-cart-id="<?php echo $item->cart_id; ?>" value="<?php echo (int)$item->qty; ?>">
                    </div>
                </div>
                <div class="action">
                    <a href="javascript:void(0);" class="remove-from-cart" data-cart-id="<?php echo $item->cart_id; ?>"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
                </div>
            </div>
        </li>
    <?php endforeach; ?>
<?php else: ?>
    <li class="empty-cart-message">
        <p>Your cart is empty.</p>
    </li>
<?php endif; ?>