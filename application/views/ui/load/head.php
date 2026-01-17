
        <div class="header-top bg-main hidden-xs">
            <div class="container">
                <div class="top-bar left">
                    <ul class="horizontal-menu">
                        <li><a id="storeEmail" href="#"><i class="fa fa-envelope" aria-hidden="true"></i><?php echo isset($email) ? htmlspecialchars($email) : ''; ?></a></li>
                        <li><a id="storeAddress" href="#"><?php echo isset($address) ? htmlspecialchars($address) : ''; ?></a></li>
                    </ul>
                </div>
                <div class="top-bar right">
                    <ul class="social-list" id="socialLinks">
                      </ul>
                    <ul class="horizontal-menu">
                      
                        <li class="horz-menu-item lang">
                            <select name="language">
                                <option value="ng" selected>₦ (NGN)</option>
                            </select>
                        </li>
                        <li><a href="<?php echo base_url("home/login")?>" class="login-link"><i class="biolife-icon icon-login"></i>Account</a></li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="header-middle biolife-sticky-object ">
            <div class="container">
                <div class="row">
                    <div class="col-lg-3 col-md-2 col-md-6 col-xs-6">
                        <a href="#" class="biolife-logo"><img src="<?php print base_url()?>images/<?php echo $logo ?>" alt="<?php echo $name?>" id="store-logo" width="135" height="20" style="height:40px;width:70px;"></a>
                    </div>
                    <div class="col-lg-6 col-md-7 hidden-sm hidden-xs">
                        <div class="primary-menu">
                        <ul>
                            <?php echo $menu?>        
                        </ul>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-3 col-md-6 col-xs-6">
                        <div class="biolife-cart-info">
                            <div class="mobile-search">
                                <a href="javascript:void(0)" class="open-searchbox"><i class="biolife-icon icon-search"></i></a>
                                <div class="mobile-search-content">
                                    <form action="<?php echo base_url('home/results'); ?>" class="form-search" name="mobile-seacrh" method="get">
                                        <a href="#" class="btn-close"><span class="biolife-icon icon-close-menu"></span></a>
                                        <input type="text" name="q" class="input-text" value="<?php echo isset($search_query) ? htmlspecialchars($search_query) : ''; ?>" placeholder="Search here...">
                                        
                                        <button type="submit" class="btn-submit">go</button>
                                    </form>
                                   
                                </div>
                            </div>
                            <!-- <div class="wishlist-block hidden-sm hidden-xs">
                                <a href="#" class="link-to">
                                    <span class="icon-qty-combine">
                                        <i class="icon-heart-bold biolife-icon"></i>
                                        <span class="qty">4</span>
                                    </span>
                                </a>
                            </div> -->
                            <div class="minicart-block">
    <div class="minicart-contain">
        <a href="javascript:void(0)" class="link-to">
            <span class="icon-qty-combine">
                <i class="icon-cart-mini biolife-icon"></i>
                <span class="qty minicart-qty"><?php echo isset($minicart_total_qty) ? (int)$minicart_total_qty : 0; ?></span>
            </span>
            <span class="title">My Cart</span>
            <!-- <span class="sub-total minicart-subtotal">₦ <?php echo isset($minicart_sub_total) ? number_format($minicart_sub_total, 2) : '0.00';?></span> -->
        </a>
        <div class="cart-content" id="minicart-dropdown">
            <div class="cart-inner">
                <ul class="products minicart-products-list">
                    <?php if (isset($minicart_items) && $minicart_items): ?>
                        <?php foreach ($minicart_items as $item): ?>
                            <li>
                                <div class="minicart-item">
                                    <div class="thumb">
                                        <a href="JavaScript:void(0)">
                                            <img src="<?php echo base_url('images/' . $item->photo); ?>" style="height:90px; width:90px; border-radius:9px;" alt="<?php echo $item->pr_name; ?>">
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
                                        <a href="<?php echo site_url("home/deletethiscartproduct/".$item->cart_id);?>" class="remove-from-cart" data-cart-id="<?php echo $item->cart_id; ?>"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
                                    </div>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <li class="empty-cart-message">
                            <p>Your cart is empty.</p>
                        </li>
                    <?php endif; ?>
                </ul>
                <p class="btn-control">
                    <a href="<?php echo base_url('home/cart'); ?>" class="btn view-cart">view cart</a>
                    <a href="<?php echo base_url('home/checkout'); ?>" class="btn">checkout</a>
                </p>
            </div>
        </div>
    </div>
</div>
                            <div class="mobile-menu-toggle">
                                <a class="btn-toggle" data-object="open-mobile-menu" href="javascript:void(0)">
                                    <span></span>
                                    <span></span>
                                    <span></span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="header-bottom hidden-sm hidden-xs">
            <div class="container">
                <div class="row">
                    <div class="col-lg-3 col-md-4">
                        <div class="vertical-menu vertical-category-block">
                            <div class="block-title">
                                <span class="menu-icon">
                                    <span class="line-1"></span>
                                    <span class="line-2"></span>
                                    <span class="line-3"></span>
                                </span>
                                <span class="menu-title">Menu</span>
                                <span class="angle" data-tgleclass="fa fa-caret-down"><i class="fa fa-caret-up" aria-hidden="true"></i></span>
                            </div>
                            <div class="wrap-menu">
                                <ul class="menu clone-main-menu">                                   
                                        <?php echo $menu?>        
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-9 col-md-8 padding-top-2px">
                        <div class="header-search-bar layout-01">
                            <form action="<?php echo base_url('index.php/home/results'); ?>" class="form-search" name="desktop-seacrh" method="get">
                                <input type="text" name="q" class="input-text" value="<?php echo isset($search_query) ? htmlspecialchars($search_query) : ''; ?>" placeholder="Search here...">
                                <button type="submit" class="btn-submit"><i class="biolife-icon icon-search"></i></button>
                            </form>
                        </div>
                        <div class="live-info">
                            <p class="telephone"><i class="fa fa-phone" aria-hidden="true"></i><b class="phone-number"><?php echo isset($telephone) ? htmlspecialchars($telephone) : ''; ?></b></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>