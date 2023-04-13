<?php

use Propeller\PropellerHelper;

    // wp_enqueue_script('quick_add_to_basket', $this->assets_url . '/js/parts/quickAddToBasket.js', array( 'jquery'), null, true);
    $default_rows_amount = 5;
?>
<svg style="display:none">
    <symbol viewBox="0 0 22 22" id="shape-info"><title>Info</title> <path d="M10.656 1.375c5.097 0 9.281 4.128 9.281 9.281a9.279 9.279 0 0 1-9.28 9.281 9.279 9.279 0 0 1-9.282-9.28c0-5.123 4.15-9.282 9.281-9.282zm0-1.375C4.771 0 0 4.773 0 10.656c0 5.887 4.771 10.656 10.656 10.656 5.885 0 10.656-4.77 10.656-10.656C21.312 4.773 16.542 0 10.656 0zM9.11 14.781a.516.516 0 0 0-.515.516v.344c0 .284.23.515.515.515h3.094c.285 0 .516-.23.516-.515v-.344a.516.516 0 0 0-.516-.516h-.516V8.766a.516.516 0 0 0-.515-.516H9.109a.516.516 0 0 0-.515.516v.343c0 .285.23.516.515.516h.516v5.156h-.516zM10.656 4.47a1.375 1.375 0 1 0 0 2.75 1.375 1.375 0 0 0 0-2.75z" fill="#005FAD"/></symbol>
    <symbol viewBox="0 0 14 14" id="shape-remove"><title>Remove</title><path d="M13.656 12.212c.41.41.41 1.072 0 1.481a1.052 1.052 0 0 1-1.485 0L7 8.5l-5.207 5.193a1.052 1.052 0 0 1-1.485 0 1.045 1.045 0 0 1 0-1.481L5.517 7.02.307 1.788a1.046 1.046 0 0 1 0-1.481 1.052 1.052 0 0 1 1.485 0L7.001 5.54 12.207.348a1.052 1.052 0 0 1 1.486 0c.41.408.41 1.072 0 1.48L8.484 7.02l5.172 5.192z" fill="#005FAD"/></symbol>
</svg>
<div class="container-fluid px-0 propeller-quick-order <?php echo apply_filters('propel_quick_order_classes', ''); ?>">
    <div class="row">
        <div class="col-auto col-lg-4 d-none d-md-block">
            <div class="h3">
                <?php echo __('Upload Excel file','propeller-ecommerce') ?>
            </div>
            <div class="upload-excel-example">
                <a href="<?php echo esc_url($this->assets_url . '/files/quickorder.xlsx'); ?>" class="d-flex align-items-center" target="_blank" rel="noopener nofollow">
                    <?php echo __('Download XLSX template','propeller-ecommerce') ?>
                    <svg class="icon icon-info">
                        <use class="shape-info" xlink:href="#shape-info"></use>
                    </svg>
                </a>
            </div>
            <div class="upload-excel-file">
                <form method="post" enctype="multipart/form-data">
                    <input type="hidden" id="nonce" name="_wpnonce" value="">
                    <input type="hidden" id="upload-file">
                    <input type="hidden" name="action" value="upload_excel_file">
                    <div class="form-row">
                        <?php if ($error) { ?>
                            <span class="text-danger"><?php echo esc_html($error) ?></span>
                        <?php } ?>
                        <div class="col form-group input-group col-attachment">
                            <div class="file-group input-group-prepend col px-0">
						        <input type="file" id="fileUpload" class="form-control" name="attachment" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" placeholder="<?php echo __('Select your Excel file','') ?>"/>
                                <label for="fileUpload"><span><?php echo __('Select your Excel file','') ?></span></label>
                            </div>
                            <input type="hidden" name="_wpnonce" value="<?php echo wp_create_nonce( PROPELLER_NONCE_KEY_FRONTEND ); ?>">
                            <button class="btn-upload" id="upload-excel" value="submit"><?php echo __('Upload','propeller-ecommerce') ?></button>
                        </div>
                    </div>		
                </form>
            </div>
        </div>
        <div class="col-12 col-lg-8">
            <div class="h3">
                <?php echo __('Add your products manually','propeller-ecommerce') ?>
            </div>
            <div class="quick-order-table" id="quick-order-table">
                <div class="quick-order-table-header row ">
                    <div class="col-2 product-code"><?php echo __('Article no. / SKU','propeller-ecommerce') ?></div>
                    <div class="col-4 product-name pl-0"><?php echo __('Product name','propeller-ecommerce') ?></div>
                    <div class="col-2 product-price pl-0"><?php echo __('excl. VAT','propeller-ecommerce') ?></div>
                    <div class="col-1 product-quantity pl-0"><?php echo __('Quantity','propeller-ecommerce') ?></div>
                    <div class="col-2 product-total pl-0"><?php echo __('Total price','propeller-ecommerce') ?></div>
                </div>
                <?php if (count($products)) { ?>
                    <?php 
                        $index = 0;
                        foreach ($products as $product) { ?>
                        <div class="quick-order-row row" id="row-<?php echo (int) $index; ?>">
                            <div class="col-2 product-code">
                                <input type="text" name="product-code-row-<?php echo (int) $index; ?>" value="<?php echo esc_attr($product->code); ?>" class="form-control product-code" id="product-code-row-<?php echo (int) $index; ?>" data-row="<?php echo esc_attr($index); ?>">
                            </div>
                            <div class="col-4 product-name pl-0">
                                <input type="text" name="product-name-row-<?php echo (int) $index; ?>" value="<?php echo esc_attr($product->name); ?>" disabled class="form-control product-name" id="product-name-row-<?php echo (int) $index; ?>" data-row="<?php echo esc_attr($index); ?>">
                            </div>
                            <div class="col-2 product-price pl-0">
                                <input type="text" name="product-price-row-<?php echo (int) $index; ?>" value="<?php echo PropellerHelper::formatPrice($product->net_price); ?>" disabled class="form-control product-price" id="product-price-row-<?php echo (int) $index; ?>" data-row="<?php echo esc_attr($index); ?>" data-price="<?php echo esc_attr($product->net_price); ?>">
                            </div>
                            <div class="col-1 product-quantity pl-0">
                                <input type="number" ondrop="return false;" onpaste="return false;" onkeypress="return event.charCode>=48 && event.charCode<=57" name="product-quantity-row-<?php echo (int) $index; ?>" value="<?php echo esc_attr($product->quantity); ?>" class="form-control product-quantity" id="product-quantity-row-<?php echo (int) $index; ?>" data-row="<?php echo esc_attr($index); ?>" data-id="<?php echo esc_attr($product->id); ?>">
                            </div>
                            <div class="col-2 product-total-price pl-0">
                                <input type="text" name="product-total-row-<?php echo (int) $index; ?>" value="<?php echo PropellerHelper::formatPrice($product->total); ?>" disabled class="form-control product-total" id="product-total-row-<?php echo (int) $index; ?>" data-row="<?php echo esc_attr($index); ?>" data-total="<?php echo esc_attr($product->total); ?>">
                            </div>
                            <div class="remove-row col-1 d-flex align-items-center" data-row="<?php echo esc_attr($index); ?>">
                                <button type="button" class="remove-row">
                                    <svg class="icon icon-remove">
                                        <use class="shape-remove" xlink:href="#shape-remove"></use>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    <?php 
                        $index++;
                    } ?>
                        <div class="quick-order-row row" id="row-<?php echo (int) $index; ?>">
                            <div class="col-2 product-code">
                                <input type="text" name="product-code-row-<?php echo (int) $index; ?>" value="" class="form-control product-code" id="product-code-row-<?php echo (int) $index; ?>" data-row="<?php echo esc_attr($index); ?>">
                                <input type="hidden" name="product-id-row-<?php echo (int) $index; ?>" value=""  class="product-id" id="product-id-row-<?php echo (int) $index; ?>">
                            </div>
                            <div class="col-4 product-name pl-0">
                                <input type="text" name="product-name-row-<?php echo (int) $index; ?>" value="" disabled class="form-control product-name" id="product-name-row-<?php echo (int) $index; ?>" data-row="<?php echo esc_attr($index); ?>">
                            </div>
                            <div class="col-2 product-price pl-0">
                                <input type="text" name="product-price-row-<?php echo (int) $index; ?>" value="" disabled class="form-control product-price" id="product-price-row-<?php echo (int) $index; ?>" data-row="<?php echo esc_attr($index); ?>" data-price="">
                            </div>
                            <div class="col-1 product-quantity pl-0">
                                <input type="number" ondrop="return false;" onpaste="return false;" onkeypress="return event.charCode>=48 && event.charCode<=57"name="product-quantity-row-<?php echo (int) $index; ?>" value="" class="form-control product-quantity" id="product-quantity-row-<?php echo (int) $index; ?>" data-row="<?php echo esc_attr($index); ?>" data-id="">
                            </div>
                            <div class="col-2 product-total-price pl-0">
                                <input type="text" name="product-total-row-<?php echo (int) $index; ?>" value="" disabled class="form-control product-total" id="product-total-row-<?php echo (int) $index; ?>" data-row="<?php echo esc_attr($index); ?>" data-total="">
                            </div>
                            <div class="remove-row col-1 d-flex align-items-center" data-row="<?php echo esc_attr($index); ?>">
                                <button type="button" class="remove-row">
                                    <svg class="icon icon-remove">
                                        <use class="shape-remove" xlink:href="#shape-remove"></use>
                                    </svg>
                                </button>
                            </div>
                        </div>
                <?php } else { ?>
                    <?php for ($i = 0; $i < $default_rows_amount; $i++) { ?>
                        <div class="quick-order-row row" id="row-<?php echo (int) $i; ?>">
                            <div class="col-2 product-code">
                                <input type="text" name="product-code-row-<?php echo (int) $i; ?>" value="" class="form-control product-code" id="product-code-row-<?php echo (int) $i; ?>" data-row="<?php echo esc_attr($i); ?>">
                                <input type="hidden" name="product-id-row-<?php echo (int) $i; ?>" value="" class="product-id" id="product-id-row-<?php echo (int) $i; ?>">
                            </div>
                            <div class="col-4 product-name pl-0">
                                <input type="text" name="product-name-row-<?php echo (int) $i; ?>" value="" disabled class="form-control product-name" id="product-name-row-<?php echo (int) $i; ?>" data-row="<?php echo esc_attr($i); ?>">
                            </div>
                            <div class="col-2 product-price pl-0">
                                <input type="text" name="product-price-row-<?php echo (int) $i; ?>" value="" disabled class="form-control product-price" id="product-price-row-<?php echo (int) $i; ?>" data-row="<?php echo esc_attr($i); ?>" data-price="">
                            </div>
                            <div class="col-1 product-quantity pl-0">
                                <input type="number" ondrop="return false;" onpaste="return false;" onkeypress="return event.charCode>=48 && event.charCode<=57"name="product-quantity-row-<?php echo (int) $i; ?>" value="" class="form-control product-quantity" id="product-quantity-row-<?php echo (int) $i; ?>" data-row="<?php echo esc_attr($i); ?>" data-id="">
                            </div>
                            <div class="col-2 product-total-price pl-0">
                                <input type="text" name="product-total-row-<?php echo (int) $i; ?>" value="" disabled class="form-control product-total" id="product-total-row-<?php echo (int) $i; ?>" data-row="<?php echo esc_attr($i); ?>" data-total="">
                            </div>
                            <div class="remove-row col-1 d-flex align-items-center" data-row="<?php echo esc_attr($i); ?>">
                                <button type="button" class="remove-row">
                                    <svg class="icon icon-remove">
                                        <use class="shape-remove" xlink:href="#shape-remove"></use>
                                    </svg>
                                </button>
                            </div>
                        </div>

                    <?php } ?>
                <?php } ?>

            </div>
            <div class="add-quick-order-row row">
                <div class="col-12">
                    <button type="button" class="add-order-row" id="add-row"><?php echo __('Add more rows','propeller-ecommerce'); ?></button>
                </div>
            </div>
            <div class="add-quick-order-row row">
                <div class="col-12">
                    <div class="quick-order-errors"></div>
                </div>
            </div>
            <div class="row">
                <div class="col-12 col-md-7 ml-md-auto mr-md-5">
                    <div class="quick-order-totals">
                        <div class="row align-items-baseline">
                            <div class="col-12">
                                <div class="sc-items"><?php echo __('Total', 'propeller-ecommerce'); ?> (<span class="propel-total-quick-items"><?php echo count($products); ?></span> <?php echo __('products', 'propeller-ecommerce'); ?>)</div>
                                <hr>
                            </div>
                        </div>
                        <div class="row align-items-baseline sc-calculation">
                            <div class="col-8 col-lg-6 col-xl-5"><?php echo __('Subtotal excl. VAT', 'propeller-ecommerce'); ?></div>
                            <div class="col-4 col-lg-4 ml-auto sc-price text-right">
                                <div class="sc-total">
                                    <span class="symbol">&euro;&nbsp;</span><span class="propel-total-quick-subtotal" data-subtotal="<?php echo esc_attr($subtotal); ?>"><?php echo PropellerHelper::formatPrice($subtotal); ?></span>
                                </div>
                            </div>
                        </div>
                    
                        <div class="row align-items-baseline sc-calculation">
                            <div class="col-8 col-lg-6 col-xl-5"><?php echo __('21% VAT', 'propeller-ecommerce'); ?></div>
                            <div class="col-4 col-lg-4 ml-auto sc-price text-right">
                                <div class="sc-total">
                                    <span class="symbol">&euro;&nbsp;</span><span class="propel-total-quick-excl-btw" data-exclbtw="<?php echo esc_attr($exclbtw); ?>"><?php echo PropellerHelper::formatPrice($exclbtw); ?></span>
                                </div>
                            </div>
                        </div>
                        <div class="sc-grand-total">
                            <div class="row align-items-baseline">
                                <div class="col-8 col-lg-6 col-xl-5"><?php echo __('Total', 'propeller-ecommerce'); ?></div>
                                <div class="col-4 col-lg-4 ml-auto sc-price text-right">
                                    <div class="sc-total">
                                        <span class="symbol">&euro;&nbsp;</span><span class="propel-total-quick-price" data-total="<?php echo esc_attr($total); ?>"><?php echo PropellerHelper::formatPrice($total); ?></span>
                                    </div>
                                </div> 
                            </div>
                            
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col col-sm-6 ml-auto mr-md-5 d-flex justify-content-end">
                    <form name="add-to-basket" id="replenish_form" class="replenish-form" method="post">
                        <input type="hidden" name="action" value="do_replenish">
                        <input type="hidden" name="items" value="">
                        <button type="submit" class="btn-quick-order">
                            <?php echo __('Add to cart', 'propeller-ecommerce'); ?>   
                        </button>       
                    </form>             
                </div>
            </div>
           
        </div>
    </div>
</div>