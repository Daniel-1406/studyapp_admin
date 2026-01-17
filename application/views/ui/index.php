<!DOCTYPE html>
<html class="no-js" lang="en">

<head>
<?php 
    $this->load->view("ui/load/headlinks");
    ?>
    <style>
        .intro {
   
    
    /* The added shadow */
    box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2);
}


    </style>
</head>
<body class="biolife-body">

    <!-- Preloader -->
     <div id="biof-loading">
        <div class="biof-loading-center">
            <div class="biof-loading-center-absolute">
                <div class="dot dot-one"></div>
                <div class="dot dot-two"></div>
                <div class="dot dot-three"></div>
            </div>
        </div>
    </div>
    

    <!-- HEADER -->
    <header id="header" class="header-area style-01 layout-04">
    <?php 
    $this->load->view("ui/load/head");
    ?>
    </header>

    <!-- Page Contain -->
    <div class="page-contain">

        <!-- Main content -->
        <div id="main-content" class="main-content">

        <?php
        $this->load->view("ui/load/carousel-slide"); 
        ?>

        

            <!--
            
            <div class="banner-block sm-margin-bottom-57px xs-margin-top-80px sm-margin-top-30px">
                <div class="container">
                    <ul class="biolife-carousel nav-center-bold nav-none-on-mobile" data-slick='{"rows":1,"arrows":true,"dots":false,"infinite":false,"speed":400,"slidesMargin":30,"slidesToShow":3, "responsive":[{"breakpoint":1200, "settings":{ "slidesToShow": 3}},{"breakpoint":992, "settings":{ "slidesToShow": 2}},{"breakpoint":768, "settings":{ "slidesToShow": 2}}, {"breakpoint":500, "settings":{ "slidesToShow": 1}}]}'>
                        <li>
                            <div class="biolife-banner biolife-banner__style-08">
                                <div class="banner-contain">
                                    <div class="media">
                                        <a href="#" class="bn-link"><img src="assets/images/home-04/bn_style08.png" width="193" height="185" alt=""></a>
                                    </div>
                                    <div class="text-content">
                                        <span class="text1">Sumer Fruit</span>
                                        <b class="text2">100% Pure Natural Fruit Juice</b>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="biolife-banner biolife-banner__style-09">
                                <div class="banner-contain">
                                    <div class="media">
                                        <a href="#" class="bn-link"><img src="assets/images/home-04/bn_style09.png" width="191" height="185" alt=""></a>
                                    </div>
                                    <div class="text-content">
                                        <span class="text1">California</span>
                                        <b class="text2">Fresh Fruit</b>
                                        <span class="text3">Association</span>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="biolife-banner biolife-banner__style-10">
                                <div class="banner-contain">
                                    <div class="media">
                                        <a href="#" class="bn-link"><img src="assets/images/home-04/bn_style10.png" width="185" height="185" alt=""></a>
                                    </div>
                                    <div class="text-content">
                                        <span class="text1">Naturally fresh taste</span>
                                        <p class="text2">With <span>25% Off</span> All Teas</p>
                                    </div>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div> -->

            <!--Block 03: Categories-->
            
            <div class="wrap-category xs-margin-top-20px xs-margin-bottom-0px">
                <div class="container">
                    <div class="biolife-title-box style-02 p-2 xs-margin-bottom-0px xs-margin-top-20px">
                        <span class="subtitle">Hot Categories</span>
                        <h3 class="main-title">Featured Categories</h3>
                        <p class="desc">Explore our featured categories, showcasing the best of what we have to offer.</p>
                    </div>
                    <ul class="biolife-carousel nav-center-bold nav-none-on-mobile" data-slick='{"arrows":true,"dots":false,"infinite":false,"speed":400 ,"autoplay": true, "autoplaySpeed": 2000, "slidesMargin":30,"slidesToShow":4, "responsive":[{"breakpoint":1200, "settings":{ "slidesToShow": 3}},{"breakpoint":992, "settings":{ "slidesToShow": 3}},{"breakpoint":768, "settings":{ "slidesToShow": 2, "slidesMargin":10}}, {"breakpoint":500, "settings":{ "slidesToShow": 1}}]}'>
                        <?php echo $categoryWithProductNum?>
                    </ul>
                </div>
            </div>

            <!--Block 05: Banner Promotion-->
            <div class="banner-promotion-04 xs-margin-top-0px sm-margin-top-0px">
                <div class="biolife-banner promotion4 biolife-banner__promotion4 v2">
                    <div class="container">
                        <div class="banner-contain">
                            <?php
                            // Make sure $special_offer_data is available from the controller
                            // Provide a safe default if it's not set or decoding failed
                            $special_offer_decoded = isset($special_offer) ? json_decode($special_offer, true) : [];

                            $special_offer_decoded['spe_desc'] = $special_offer_decoded['spe_desc'] ?? '';
                            $special_offer_decoded['spe_link'] = $special_offer_decoded['spe_link'] ?? '';
                            $special_offer_decoded['spe_ends_at'] = $special_offer_decoded['spe_ends_at'] ?? '';

                            // Extract specific values, with sensible defaults
                            $spe_desc = html_escape($special_offer_decoded['spe_desc'] ?? 'Special discount for amazing products');
                            $spe_link = html_escape($special_offer_decoded['spe_link'] ?? '#');
                            $spe_ends_at_raw = $special_offer_decoded['spe_ends_at'] ?? '2025-12-01T12:00'; // Default to a valid date if not set

                            $spe_ends_at_formatted = str_replace('T', ' ', $spe_ends_at_raw);


                            $spe_ends_at_countdown = $spe_ends_at_formatted . ':00 +00:00'; // Add seconds and UTC offset
                            ?>
                            <div class="media">
                                <div class="img-moving position-1">
                                    <img src="<?php print base_url()?>images/<?php echo "specialoff.png" ?>" width="680" height="300" alt="img msv">
                                </div>
                                <div class="img-moving position-2">
                                    <img src="<?php print base_url()?>images/<?php echo "special.png" ?>" width="149" height="139" alt="img msv">
                                </div>
                                
                            </div>

                            <div class="text-content">
                                <span class="sub-line" style="color:<?php echo isset($colour) ? htmlspecialchars($colour) : '#ff9702'; ?>; text-shadow: 1px 1px 2px #000000;">Special Offer!</span>
                                <b class="first-line fw-bold" style="color:#aaa; text-shadow: 1px 1px 2px #000000;"><?php echo $spe_desc; ?></b>                                
                                <div class="biolife-countdown" data-datetime="<?php echo $spe_ends_at_countdown; ?>"></div>
                                
                                <p class="buttons">
                                    <a href="<?php echo $spe_link; ?>" class="btn btn-bold">See Offer Now!</a>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


             

            <!--Block 04: Product Tab-->
            <div id="product-tab" class="product-tab z-index-20 sm-margin-top-59px xs-margin-top-20px">
            <div class="container">
                <div class="biolife-title-box slim-item">
                    <span class="subtitle">All the best item for You</span>
                    <h3 class="main-title">Our Products</h3>
                </div>
                <div class="biolife-tab biolife-tab-contain sm-margin-top-23px">
                    <div class="tab-head tab-head__sample-layout">
                        <?php echo $getTabbedProducts['tab_head']; ?>
                    </div>
                    <?php echo $getTabbedProducts['tab_content']; ?>
                </div>
            </div>
            </div>
            <!--Block 05: Banner promotion 02-->
            <div class="banner-promotion-02 z-index-20" >
                <div class="biolife-banner promotion2 biolife-banner__promotion2 advance mt-1">
                    <div class="banner-contain">
                        <div class="container">
                            <div class="media"></div>
                            <div class="text-content">
                                <span class="second-line"><i><?php echo isset($welcome_quote) ? htmlspecialchars($welcome_quote) : ''; ?></i></span>
                                <p class="third-line"><?php echo isset($welcome_text) ? htmlspecialchars($welcome_text) : ''; ?></p>
                                <p class="buttons">
                                    <a href="<?php echo base_url("home/shop");?>" class="btn btn-bold">Shop Now</a>
                                    <a href="<?php echo base_url("home/about");?>" class="btn btn-thin">About Us</a>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!--Block 08: Products-->
            <div class="container mt-0">
                <div class="row">
                    <?php if (!empty($displayCategoryBlocks)): ?>
                        <?php foreach ($displayCategoryBlocks as $block): ?>
                            <div class="col-sm-6 col-md-4 col-xs-12">
                                <div class="advance-product-box">
                                    <div class="biolife-title-box bold-style biolife-title-box__bold-style mobile-tiny sm-margin-bottom-36px my-4">
                                        <h3 class="title"><?php echo htmlspecialchars($block->category_info->cat_name); ?></h3>
                                    </div>
                                    <ul class="products-list vertical-layout products-list__vertical-layout">
                                        <?php if (!empty($block->products)): ?>
                                            <?php foreach ($block->products as $product): ?>
                                                <li class="product-item">
                                                    <div class="contain-product contain-product__right-info-layout2">
                                                        <div class="product-thumb">
                                                            <a href="<?php echo base_url("home/product/".$product->pr_url)?>" class="link-to-product">
                                                                <img src="<?php echo htmlspecialchars("images/".$product->photo); ?>" alt="<?php echo htmlspecialchars($product->pr_name); ?>" style="height:100px; border-radius:10px; box-shadow: 0 4px 8px rgba(0,0,0,0.1);" class="product-thumnail">
                                                            </a>
                                                        </div>
                                                        <div class="info">
                                                            <h4 class="product-title"><a href="<?php echo base_url("home/product/".$product->pr_url)?>" class="pr-name"><?php echo htmlspecialchars($product->pr_name); ?></a></h4>
                                                            <div class="price ">
                                                            <?php
                                                                // Ensure pr_sell_price is not null
                                                                if ($product->pr_sell_price !== null) {
                                                                    // 1. Remove commas from the string price
                                                                    $clean_sell_price_string = str_replace(',', '', $product->pr_sell_price);
                                                                    // 2. Cast to float and then format
                                                                    $formatted_current_price = number_format((float)$clean_sell_price_string, 2);
                                                                    ?>
                                                                    <ins><span class="price-amount"><span class="currencySymbol">₦ </span><?php echo htmlspecialchars($formatted_current_price); ?></span></ins>
                                                                <?php
                                                                }

                                                                // Check if pr_slash_price is not null AND is greater than pr_sell_price before displaying
                                                                if (
                                                                    isset($product->pr_slash_price) &&
                                                                    $product->pr_slash_price !== null &&
                                                                    // Important: Convert both to float AFTER removing commas for a correct comparison
                                                                    (float)str_replace(',', '', $product->pr_slash_price) > (float)str_replace(',', '', $product->pr_sell_price)
                                                                ) {
                                                                    // 1. Remove commas from the string slash price
                                                                    $clean_slash_price_string = str_replace(',', '', $product->pr_slash_price);
                                                                    // 2. Cast to float and then format
                                                                    $formatted_old_price = number_format((float)$clean_slash_price_string, 2);
                                                                    ?>
                                                                    <del><span class="price-amount"><span class="currencySymbol">₦ </span><?php echo htmlspecialchars($formatted_old_price); ?></span></del>
                                                                <?php
                                                                }
                                                                ?>
                                                            </div>
                                                            <div class="rating">
                                                                <p class="star-rating"><span class="width-100percent"></span></p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </li>
                                            <?php endforeach; ?>
                                            <div class="col-sm-6 col-md-4 col-xs-12 sm-margin-top-54px md-margin-top-0">
                                                <div class="wrap-custom-menu vertical-menu-2">
                                                    <ul class="menu">
                                                        <li><a href="<?php $encoded_cat_name = urlencode($block->category_info->cat_name); echo site_url('home/results?q=' . $encoded_cat_name); ?>">More ...</a></li> </ul>
                                                </div>
                                            </div>
                                        <?php else: ?>
                                            <li class="product-item">No products found for this category.</li>
                                        <?php endif; ?>
                                    </ul>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="col-xs-12">
                            <p>No main categories with products found to display.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            <!--Block 09: Blog Post-->


            <!--Block 03: Categories-->
            
            <div class="wrap-category xs-margin-top-20px xs-margin-bottom-0px">
                <div class="container">
                    <div class="biolife-service type01 biolife-service__type01 xs-margin-top-3px sm-margin-top-3px ">
                    <?php 
                    $icon ='';
                $services_decoded = isset($services) ? json_decode($services, true) : [];
                for ($i = 1; $i <= 3; $i++) {
                    $services_decoded['service' . $i] = $services_decoded['service' . $i] ?? '';
                }
                ?>
                        <ul class="services-list">
                        <?php for ($i = 1; $i <= 3; $i++) : 
                            if($i == 1){
                                $icon = ' fa-award';
                            }else if($i == 2){
                                $icon = 'fa-shipping-fast';
                            }else{
                                $icon = 'fa-headset';
                            }
                            ?>
                            

                            <li>
                                <div class="service-inner color-reverse">
                                    <span class="number"><?php echo $i; ?></span>
                                    <span class="biolife-icon"> <i class="fas <?php echo $icon?>"></i> </span>
                                    <a class="srv-name" href="javascript:void(0)"><?php echo $services_decoded['service' . $i]?></a>
                                </div>
                            </li>
                            <?php endfor; ?>
                        </ul>
                    </div>
                </div>
            </div>


            <!-- Tab info -->
            <div class="product-tabs single-layout biolife-tab-contain p-5 mt-2">
                    <div class="tab-head">
                        <ul class="tabs">
                            <li class="tab-element active" ><a href="#tab_3rd" class="tab-link">Frequently Asked Questions (FAQs)</a></li>
                        </ul>
                    </div>
                    <div id="tab_3rd" class="tab-contain shipping-delivery-tab">
                            <div class="accodition-tab biolife-accodition">
                                <ul class="tabs">
                                    
                                    <?php 
                                    // Loop through the array of FAQs passed from the controller
                                    // The structure here matches the static example you provided
                                    if (!empty($faqs)) {
                                        foreach ($faqs as $faq) {
                                    ?>
                                            <li class="tab-item">
                                                <span class="title btn-expand"><?php echo htmlspecialchars($faq['question']); ?></span>
                                                <div class="content">
                                                    <p><?php echo $faq['answer']; ?></p>
                                                    </div>
                                            </li>
                                    <?php 
                                        }
                                    } else {
                                        // Optional: Display a message if no FAQs are found
                                    ?>
                                        <li class="tab-item">
                                            <p>No FAQs available at this time.</p>
                                        </li>
                                    <?php
                                    }
                                    ?>
                                    
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>



        </div>

    </div>

    <!-- FOOTER -->
    <footer id="footer" class="footer layout-03">
        <?php $this->load->view("ui/load/footer");?>
    </footer>

    <!--Footer For Mobile-->
    <div class="mobile-footer">
    <?php $this->load->view("ui/load/mobile_footer");?>
    </div>

    <div class="mobile-block-global">
    <?php $this->load->view("ui/load/mobile_footer_global");?>

    </div>
     <!--Newletter Popup-->
     <?php 
        // Renders the HTML ONLY IF the 'user' session data IS empty ("")
        if ($this->session->userdata("user") == ""): 
        ?>
            <div id="biolife-newsletter-popup" class="modal fade biolife-popup biolife-newsletter-popup-contain" role="dialog" >
            <div class="modal-dialog">
                <div class="modal-content" >
                    <div class="modal-body" >
                        <button type="button" class="close" data-dismiss="modal"><span class="biolife-icon icon-close-menu"></span></button>
                        <div class="newsletter-block popup-layout newsletter-block_popup-layout">
                            <div class="">
                                <div class="register-in-container"  style="margin:0px;">
                                    <div class="intro ">
                                        <h4 class="box-title"><b>Sign In</b></h4>
                                        <p class="sub-title">Sign In and you’ll be able to:</p>
                                        <ul class="lis">
                                            <li>Save items to your cart</li>
                                            <li>Track new orders</li>
                                            <li>Access your order history</li>
                                            <li>Check out faster</li>
                                            <li>Save shipping address</li>
                                        </ul>
                                        <div class="form-content">
                                            <?php echo form_open("home/signinuser");?>
                                            <p>
                                                <input type="email" name="login-email" class="input-text" value="" placeholder="Enter Email here...">
                                            </p>
                                                <input type="password" name="login-password" class="input-text" value="" placeholder="Enter Password here...">
                                            </p>
                                            <button type="submit" class="bnt-submit" name="ok">Sign In</button>
                                                <a href="home/createaccount" class="close dismiss-newsletter">New Here? Sign Up Here</a>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php 
        // End the conditional block
        endif; 
        ?>
    <?php 
    $this->load->view("ui/load/footerlinks");
    ?>

</body>

</html>