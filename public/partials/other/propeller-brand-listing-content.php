    <div class="container-fluid px-0 propeller-product-listing-brand">
        <div class="row">
            <div class="col-12">
                <?php 
                $brands_array = get_posts('category_name=brands');
                if(isset($brands_array) && !empty($brands_array)) {   
                    foreach($brands_array as $brand) {
                        
                        if ($brand->post_name == strtolower($term)) {
                            $brandImage = wp_get_attachment_image_src( get_post_thumbnail_id( $brand->ID ), 'single-post-thumbnail');
                        ?>
                            <h1 class="title <?php echo apply_filters('propel_listing_title_classes', ''); ?>"><?php echo $brand->post_title; ?></h1>
                            <div class="row">
                                <div class="col-12 col-md-8 order-2 orer-md-1">
                                    <?php 
                                        $bHasLongDescription = false;
                                    
                                        if (strlen($brand->post_content) > 500) 
                                            $bHasLongDescription = true;
                                    ?>
                                    <div class="category-description brand-description product-truncate">
                                        <div class="product-truncate-content <?php if($bHasLongDescription) echo 'show-more' ;?>">
                                            <?php echo $brand->post_content; ?>  
                                        </div>  
                                        <?php if($bHasLongDescription) { ?>
                                            <div class="product-truncate-button">
                                                <a class="btn-read-more" href="#">
                                                    <span class="read-more"><?php echo __('Read more','propeller-ecommerce'); ?></span>
                                                    <span class="read-less"><?php echo __('Read less','propeller-ecommerce'); ?></span>
                                                </a>
                                            </div>
                                        <?php } ?>
                                    </div>
                                </div>
                                <div class="col-12 col-md-4 order-1 order-md-2">
                                    <?php if(isset($brandImage) && !empty($brandImage)) { ?>
                                        <div class="brand-logo d-flex align-items-center justify-content-center">
                                            <img data-src="<?php echo $brandImage[0]; ?>" class="img-fluid lazy" alt="<?php echo $brand->post_title; ?>">
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                        
                <?php } } } ?>
            </div>
        </div>
    </div>