<?php 
    namespace Propeller\Frontend\Templates;

    use Propeller\Includes\Controller\PageController;
    use Propeller\Includes\Controller\SessionController;
    use Propeller\Includes\Enum\PageType;
    
    $user_prices = SessionController::get(PROPELLER_SPECIFIC_PRICES);

    $cluster_product = $this->product->defaultProduct ? $this->product->defaultProduct : $this->products[0];
?>
<svg style="display:none">
    <symbol viewBox="0 0 23 20" id="shape-shopping-cart"><title>Shopping cart</title><path d="M18.532 20c.72 0 1.325-.24 1.818-.723a2.39 2.39 0 0 0 .739-1.777c0-.703-.253-1.302-.76-1.797a.899.899 0 0 0-.339-.508 1.002 1.002 0 0 0-.619-.195H7.55l-.48-2.5h13.26a.887.887 0 0 0 .58-.215.995.995 0 0 0 .34-.527l1.717-8.125a.805.805 0 0 0-.18-.781.933.933 0 0 0-.739-.352H5.152L4.832.781a.99.99 0 0 0-.338-.566.947.947 0 0 0-.62-.215H.48a.468.468 0 0 0-.34.137.45.45 0 0 0-.14.332V.78c0 .13.047.241.14.332a.468.468 0 0 0 .34.137h3.155L6.43 15.82c-.452.47-.679 1.042-.679 1.72 0 .676.247 1.256.74 1.737.492.482 1.098.723 1.817.723.719 0 1.324-.24 1.817-.723.493-.481.739-1.074.739-1.777 0-.443-.12-.86-.36-1.25h5.832c-.24.39-.36.807-.36 1.25 0 .703.246 1.296.74 1.777.492.482 1.097.723 1.816.723zm1.518-8.75H6.83l-1.438-7.5h16.256l-1.598 7.5zm-11.742 7.5c-.347 0-.646-.124-.899-.371s-.38-.54-.38-.879c0-.339.127-.632.38-.879s.552-.371.899-.371c.346 0 .645.124.898.371s.38.54.38.879c0 .339-.127.632-.38.879s-.552.371-.898.371zm10.224 0c-.346 0-.645-.124-.898-.371s-.38-.54-.38-.879c0-.339.127-.632.38-.879s.552-.371.898-.371c.347 0 .646.124.899.371s.38.54.38.879c0 .339-.127.632-.38.879s-.552.371-.899.371z" fill-rule="nonzero"/> </symbol>
    <symbol viewBox="0 0 20 18" id="shape-favorites"><title>Favorites</title><path d="M14.549.506a4.485 4.485 0 0 1 3.204 1.106c1.103.982 1.682 2.349 1.741 3.734.06 1.4-.41 2.823-1.417 3.894l-7.525 8.004c-.186.157-.39.242-.588.242a.675.675 0 0 1-.495-.222L1.93 9.248a5.704 5.704 0 0 1-1.4-3.965c.06-1.377.637-2.707 1.718-3.67.94-.838 2.127-1.185 3.298-1.1 1.22.087 2.42.64 3.32 1.597L10 3.309l1.126-1.193C12.191 1.074 13.362.565 14.55.506z" fill="none"/></symbol>
    <symbol viewBox="0 0 14 10" id="shape-checkmark"><title>Checkmark</title><path d="M11.918.032 4.725 7.225 2.082 4.582a.328.328 0 0 0-.464 0l-.773.773a.328.328 0 0 0 0 .465l3.648 3.648a.328.328 0 0 0 .464 0l8.198-8.198a.328.328 0 0 0 0-.464l-.773-.774a.328.328 0 0 0-.464 0z"/></symbol>
    <symbol viewBox="0 0 25 25" id="shape-plus"><title>Plus</title><g fill="none" fill-rule="evenodd"><path d="M12.5 0C5.595 0 0 5.595 0 12.5S5.595 25 12.5 25 25 19.405 25 12.5 19.405 0 12.5 0z" fill="#62BC5E" fill-rule="nonzero"/><path d="M12.5 6A1.5 1.5 0 0 1 14 7.5V11h3.5a1.5 1.5 0 0 1 0 3H14v3.5a1.5 1.5 0 0 1-3 0V14H7.5a1.5 1.5 0 0 1 0-3H11V7.5A1.5 1.5 0 0 1 12.5 6z" fill="#FFF"/></g></symbol>
    <symbol viewBox="0 0 25 25" id="shape-equals"><title>Equals</title><g fill="none" fill-rule="evenodd"><path d="M12.5 0C5.595 0 0 5.595 0 12.5S5.595 25 12.5 25 25 19.405 25 12.5 19.405 0 12.5 0z" fill="#62BC5E" fill-rule="nonzero"/><rect fill="#FFF" x="7" y="8" width="11" height="3" rx="1.5"/><rect fill="#FFF" x="7" y="14" width="11" height="3" rx="1.5"/></g></symbol>
</svg>

<div class="container-fluid px-0 propeller-product-details <?= apply_filters('propel_product_details_classes', ''); ?>">
        <?= apply_filters('propel_cluster_gecommerce', $this->product, $cluster_product, $this); ?>
        
        <span class="text-danger">(temp) Cluster type: <?= $this->product->cluster_type; ?></span>
        
        <div class="row">
            <div class="col">
                <?php
                    $breadcrumb_paths = [];

                    if ($cluster_product->category) {
                        $breadcrumb_paths[] = [
                            $this->buildUrl(PageController::get_slug(PageType::CATEGORY_PAGE), $cluster_product->category->slug[0]->value),
                            $cluster_product->category->name[0]->value
                        ];
                    }   
                        
                    $breadcrumb_paths[] = [
                        $this->buildUrl(PageController::get_slug(PageType::PRODUCT_PAGE), $cluster_product->slug[0]->value),
                        $cluster_product->name[0]->value
                    ];

                    apply_filters('propel_breadcrumbs', $breadcrumb_paths);
                ?>
            </div>
        </div>
    
        <div class="row product-gallery-price-wrapper">
            <!-- Product gallery -->
            <div class="col-12 col-lg-7 gallery-wrapper">
                <?= apply_filters('propel_cluster_name_mobile', $this->product); ?>

                <?= apply_filters('propel_cluster_product_name_mobile', $cluster_product); ?>
                
                <?= apply_filters('propel_cluster_meta_mobile', $this->product, $cluster_product, $this); ?>

                <?= apply_filters('propel_cluster_gallery', $cluster_product, $this); ?>

                <?= apply_filters('propel_cluster_desc_media', $this->product, $cluster_product, $this); ?>
            </div>
            
            <!-- Product name, pricing, short description -->
            <div class="col-12 col-lg-5">
                <div class="product-price-description-wrapper">
                    <?= apply_filters('propel_cluster_name', $this->product); ?>

                    <?= apply_filters('propel_cluster_product_name', $cluster_product); ?>

                    <?= apply_filters('propel_cluster_meta', $this->product, $cluster_product, $this); ?>
                    
                    <?= apply_filters('propel_cluster_price_details', $cluster_product); ?>

                    <?= apply_filters('propel_cluster_options', $this->product, $cluster_product, $this); ?>
                </div>
            </div>
        </div>

        <?= apply_filters('propel_cluster_bundles', $cluster_product, $this); ?>

        <?php if ($this->product->has_crossupsells()) { ?>
            <?= apply_filters('propel_cluster_crossupsells_ajax', $this); ?>
        <?php } ?>
        
        <div id="fixed-wrapper" class="d-md-none fixed-wrapper <?php if (sizeof($cluster_product->bulkPrices) > 1) { ?>has-bulk-prices<?php } ?>">
            <?= apply_filters('propel_product_bulk_prices', $cluster_product); ?>

            <div class="row align-items-center justify-content-between">
                <?= apply_filters('propel_product_add_to_basket', $cluster_product); ?>

                <?php /*
                <div class="col pr-0 add-to-basket pl-30">
                    <form class="add-to-basket-form d-flex" name="add-product" action="#" method="post">
                        <input type="hidden" name="product_id" value="<?= $cluster_product->productId; ?>">
                        <input type="hidden" name="action" value="cart_add_item">
                        <div class="input-group product-quantity align-items-center">
                            <label class="sr-only" for="quantity-item-<?= $cluster_product->productId; ?>"> <?php echo __('Quantity', 'propeller-ecommerce'); ?></label> 
                            <span class="input-group-prepend incr-decr">
                                <button type="button" class="btn-quantity" 
                                data-type="minus">-</button>
                            </span>
                            <input
                                type="number"
                                ondrop="return false;" 
                                onpaste="return false;"
                                onkeypress="return event.charCode>=48 && event.charCode<=57"
                                id="quantity-item-<?= $cluster_product->productId; ?>"
                                class="quantity large form-control input-number product-quantity-input"
                                name="quantity"
                                autocomplete="off"
                                min="<?= $cluster_product->minimumQuantity; ?>"
                                value="<?= $cluster_product->minimumQuantity; ?>"
                                data-min="<?= $cluster_product->minimumQuantity; ?>"
                                data-unit="<?= $cluster_product->unit; ?>"
                                >
                            <span class="input-group-append incr-decr">
                                <button type="button" class="btn-quantity" data-type="plus">+</button>
                            </span>
                        </div>                       
                        <button class="btn-addtobasket d-flex justify-content-center align-items-center" type="submit">
                            <?php echo __('In cart', 'propeller-ecommerce'); ?>
                        </button>
                    </form>
                </div>
                */ ?>
                <div class='col-auto pr-30'>
                    <?= apply_filters('propel_product_add_favorite', $cluster_product); ?>

                    <?php /*
                    <div class="favorites">
                        <div class="favorite-add-form">
                            <form name="add_favorite" class="validate form-handler favorite" method="post" novalidate="novalidate">
                                <input type="hidden" name="action" value="favorites_add_item">
                                <input type="hidden" name="product_id" value="<?= $cluster_product->productId; ?>">                            
                                <button type="submit" class="btn-favorite" rel="nofollow">
                                    <svg class="icon icon-product-favorite icon-heart">
                                        <use class="header-shape-heart" xlink:href="#shape-favorites"></use>
                                    </svg>
                                </button>
                            </form>				
                        </div>
                    </div>
                    */ ?>
                </div>     
            </div>
        </div> 
    </div>
</div>