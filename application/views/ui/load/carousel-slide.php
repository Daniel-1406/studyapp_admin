
            <!--Block 01: Vertical Menu And Main Slide-->
            <div class="container-fluid">

                <div class="row">
                    
                    <div class="col-lg-12 col-md-12 col-xs-12">
                    <div class="main-slide block-slider nav-change hover-main-color type02">
                    <ul class="biolife-carousel" data-slick='{"arrows": true, "dots": false, "slidesMargin": 0, "slidesToShow": 1, "infinite": true, "speed": 800,"autoplay": true, "autoplaySpeed": 5000}' >
    <?php if (!empty($carousels)): ?>
        <?php foreach ($carousels as $index => $carousel): ?>
            <li>
                <div class="slide-contain slider-opt04__layout01 light-version <?= ($index === 0) ? 'first-slide' : '' ?>">
                    <div class="media" style="background-image: url('<?= base_url('images/' . $carousel->photo) ?>');"></div>
                    <div class="text-content">
                    <i class="first-line"><?= htmlspecialchars($name ?? '') ?></i>
                        <h3 class="second-line"><?= htmlspecialchars($carousel->header) ?></h3>
                        <p class="third-line"><?= htmlspecialchars($carousel->text) ?></p>
                        <p class="buttons">
                            <a href="<?php echo site_url('home/shop');?>" class="btn btn-bold">Our Products</a>
                            <a href="<?php echo site_url('home/createaccount');?>" class="btn btn-thin">Sign Up</a>
                        </p>
                    </div>
                </div>
            </li>
        <?php endforeach; ?>
    <?php endif; ?>
</ul>
                        </div>
                    </div>
                </div>

            </div>