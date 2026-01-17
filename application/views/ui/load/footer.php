<div class="footer-content background-footer-03 pt-2" style="margin-top:5px;">
            <div class="container">
                <div class="row">
                    <div class="col-lg-4 col-md-4 col-sm-9">
                        <section class="footer-item">
                            <a href="" class="logo footer-logo"><img src="<?php print base_url()?>images/<?php echo $logo ?>" id="store-logo" alt="biolife logo" width="135" height="36"></a>
                            <div class="footer-phone-info">
                                <i class="biolife-icon icon-head-phone"></i>
                                <p class="r-info">
                                    <span>For Enquiries ?</span>
                                    <span id=""><?php echo isset($telephone) ? htmlspecialchars($telephone) : ''; ?></span>
                                </p>
                            </div>
                            <!-- <div class="newsletter-block layout-01">
                                <h4 class="title">Newsletter Signup</h4>
                                <div class="form-content">
                                    <form action="#" name="new-letter-foter">
                                        <input type="email" class="input-text email" value="" placeholder="Your email here...">
                                        <button type="submit" class="bnt-submit" name="ok">Sign up</button>
                                    </form>
                                </div>
                            </div> -->
                        </section>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-6 md-margin-top-5px sm-margin-top-50px xs-margin-top-40px">
                        <section class="footer-item">
                            <h3 class="section-title">Useful Links</h3>
                            <div class="row">
                                <div class="col-lg-6 col-sm-6 col-xs-6">
                                    <div class="wrap-custom-menu vertical-menu-2">
                                        <ul class="menu">
                                            <li><a href="<?php echo site_url()?>">Home</a></li>
                                            <li><a href="<?php echo site_url('home/cart')?>">Shopping Cart</a></li>
                                            <li><a href="<?php echo site_url('home/dashboard')?>">My Account</a></li>
                                            <li><a href="<?php echo site_url('home/createaccount')?>">Create Account</a></li>
                                            <li><a href="<?php echo site_url()?>">Privacy Policy</a></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-sm-6 col-xs-6">
                                    <div class="wrap-custom-menu vertical-menu-2">
                                        <ul class="menu">
                                            <li><a href="<?php echo site_url('home/about')?>">About Us</a></li>
                                            <li><a href="<?php echo site_url('home/shop')?>">Shop</a></li>
                                            <li><a href="<?php echo site_url('home/checkout')?>">Checkout</a></li>
                                            <li><a href="<?php echo site_url('home/contact')?>">Contact Us</a></li>
                                            <li><a href="<?php echo site_url('home/signinuser')?>">Login</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </section>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-6 md-margin-top-5px sm-margin-top-50px xs-margin-top-40px">
                        <section class="footer-item">
                            <h3 class="section-title">Contact Information</h3>
                            <div class="contact-info-block footer-layout xs-padding-top-10px">
                                <ul class="contact-lines">
                                    <li>
                                        <p class="info-item">
                                            <i class="biolife-icon icon-location"></i>
                                            <b class="desc" id="footerAdress"><?php echo isset($address) ? htmlspecialchars($address) : ''; ?></b>
                                        </p>
                                    </li>
                                    <li>
                                        <p class="info-item">
                                            <i class="biolife-icon icon-phone"></i>
                                            <b class="desc" id="footerTelephone"><?php echo isset($telephone) ? htmlspecialchars($telephone) : ''; ?></b> </p>
                                    </li>
                                    <li>
                                        <p class="info-item">
                                            <i class="biolife-icon icon-letter"></i>
                                            <b class="desc" >Email:   <span id="footerEmail"><?php echo isset($email) ? htmlspecialchars($email) : ''; ?></span></b> </p>
                                    </li>
                                    <li>
                                        <p class="info-item">
                                            <i class="biolife-icon icon-clock"></i>
                                            <b class="desc" id="storeHours"><?php if(isset($open_hours)) echo $open_hours; ?></b> </p>
                                    </li>
                                </ul>
                            </div>
                            <div class="biolife-social inline">
                                <ul class="socials" id="footerSocialLinks"> </ul>
                            </div>
                        </section>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12">
                        <div class="separator sm-margin-top-62px xs-margin-top-40px"></div>
                    </div>
                    <div class="col-lg-4 col-sm-12 col-xs-12">
                        <div class="payment-methods">
                            <ul>
                                <li><a href="#" class="payment-link"><img src="<?php echo base_url();?>ui_assets/images/paystack.png" width="51" height="36" alt=""></a></li>
                                <li><a href="#" class="payment-link"><img src="<?php echo base_url();?>ui_assets/images/visa.png" width="51" height="36" alt=""></a></li>
                                <li><a href="#" class="payment-link"><img src="<?php echo base_url();?>ui_assets/images/verve.png" width="51" height="36" alt=""></a></li>
                                <li><a href="#" class="payment-link"><img src="<?php echo base_url();?>ui_assets/images/mastercard.png" width="51" height="36" alt=""></a></li>
                                <li><a href="#" class="payment-link"><img src="<?php echo base_url();?>ui_assets/images/american_express.png" width="51" height="36" alt=""></a></li>
                                <li><a href="#" class="payment-link"><img src="<?php echo base_url();?>ui_assets/images/apple_pay.png" width="51" height="36" alt=""></a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-4 col-sm-12 col-xs-12">
                        <div class="copy-right-text"><p>&copy; <?= date('Y') ?> <?= html_escape($name) ?>. All rights reserved.</p></div>
                    </div>
                    <div class="col-lg-4 col-sm-12 col-xs-12">
                        <div class="copy-right-text"><p>Developed By <a href="https://devdnl.vercel.app">Dev Dnl</a></p></div>
                    </div>
                    
                </div>
                <a href="https://wa.me/<?php if(isset($whatsapp)) echo htmlspecialchars($whatsapp)?>" class="whatsapp-float" target="_blank" rel="noopener noreferrer">
                    <img src="<?php echo base_url('images/whatsapp_icon.png')?>" alt="WhatsApp Chat" class="whatsapp-icon">
                    <span class="whatsapp-text">Chat Admin</span>
                </a>
            </div>
        </div>
      