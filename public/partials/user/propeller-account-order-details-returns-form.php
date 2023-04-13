<?php

use Propeller\PropellerHelper;

?>
<svg style="display:none">
    <symbol viewBox="0 0 14 14" id="shape-header-close"><title>Close</title> <path d="M13.656 12.212c.41.41.41 1.072 0 1.481a1.052 1.052 0 0 1-1.485 0L7 8.5l-5.207 5.193a1.052 1.052 0 0 1-1.485 0 1.045 1.045 0 0 1 0-1.481L5.517 7.02.307 1.788a1.045 1.045 0 0 1 0-1.481 1.052 1.052 0 0 1 1.485 0L7.001 5.54 12.208.348a1.052 1.052 0 0 1 1.485 0c.41.408.41 1.072 0 1.48L8.484 7.02l5.172 5.192z"/></symbol>
    <symbol viewBox="0 0 16 16" id="shape-error"><title>Error</title> <path d="M15.75 8A7.751 7.751 0 0 0 .25 8 7.75 7.75 0 0 0 8 15.75 7.75 7.75 0 0 0 15.75 8zM8 9.563a1.437 1.437 0 1 1 0 2.874 1.437 1.437 0 0 1 0-2.874zM6.635 4.395A.375.375 0 0 1 7.01 4h1.98c.215 0 .386.18.375.395l-.232 4.25A.375.375 0 0 1 8.759 9H7.24a.375.375 0 0 1-.374-.355l-.232-4.25z" fill="#E02B27"/></symbol>
    <symbol viewBox="0 0 16 12" id="shape-valid"><title>Valid</title><path d="m6.566 11.764 9.2-9.253a.808.808 0 0 0 0-1.137L14.634.236a.797.797 0 0 0-1.131 0L6 7.782 2.497 4.259a.797.797 0 0 0-1.131 0L.234 5.397a.808.808 0 0 0 0 1.137l5.2 5.23a.797.797 0 0 0 1.132 0z" fill="#54A023"/> </symbol>

</svg>
<div id="return_modal_<?php echo esc_attr($order->id); ?>" class="propeller-address-modal propeller-return-modal modal modal-fullscreen-sm-down fade" tabindex="-1" role="dialog" aria-labelledby="propel_modal_edit_title_return">
    <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header propel-modal-header">
                <div id="propel_modal_edit_title_<?php echo esc_attr($order->id); ?>" class="modal-title">
                    <span class="order-modal-title"><?php echo __('Return request for order number', 'propeller-ecommerce'); ?>:</span> <span class="order-number"><?php echo esc_attr($order->id); ?></span>
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">
                        <svg class="icon icon-close">
                            <use class="header-shape-close" xlink:href="#shape-header-close"></use>
                        </svg>
                    </span>
                </button>
            </div>
            <div class="modal-body propel-modal-body" id="propel_modal_edit_body_<?php echo esc_attr($order->id); ?>">
                <form name="return-form" id="return_order<?php echo esc_attr($order->id); ?>" class="form-horizontal validate form-handler modal-edit-form return-form" method="post">
                    <input type="hidden" name="action" value="return_request" />
                    <input type="hidden" name="returned_products" id="returned_products" value="" />
                    <input type="hidden" name="return_order" id="return_order" value="<?php echo esc_attr($order->id); ?>" />
                    <input type="hidden" name="return_email" id="return_email" value="<?php echo esc_attr($order->email); ?>" />
                    
                    <div class="row order-products">
                        <div class="col-12">
                            <fieldset>
                                <legend class="return-header"><?php echo __('Select product(s)', 'propeller-ecommerce'); ?></legend>
                            </fieldset>
                            
                        </div>
                    </div>
                    <div class="order-headers d-none d-lg-flex">
                        <div class="row no-gutters w-100 align-items-center">
                            <div class="col-md-3 description">
                                <?php echo __('Products', 'propeller-ecommerce'); ?>
                            </div>
                            <div class="col-md-1 reference">
                                <?php echo __('Ordered', 'propeller-ecommerce'); ?>
                            </div>
                            <div class="col-md-1 quantity">
                                <?php echo __('Return', 'propeller-ecommerce'); ?>
                            </div>
                            <div class="col-md-1 price-per-item">
                                <?php echo __('Price', 'propeller-ecommerce'); ?>
                            </div>
                            <div class="col-md-2 order-status">
                                <?php echo __('Package opened?', 'propeller-ecommerce'); ?>
                            </div>
                            <div class="col-md-3 order-status">
                                <?php echo __('Return reason', 'propeller-ecommerce'); ?>
                            </div>
                        </div>
                    </div>
                    <div class="order-product-list">
                        <?php foreach($order->items as $item) { 
                            if ( $item->class == 'product' && $item->isBonus != 'Y') { ?>
                            <div class="order-product-item">
                                <div class="row no-gutters align-items-center">        
                                    <div class="col-4 col-lg-1 d-flex align-items-center product-image">
                                        <input 
                                            type="checkbox" 
                                            name="return-product[<?php echo esc_attr($item->id); ?>]"
                                            id="return-product-<?php echo esc_attr($item->id); ?>"
                                            class="return-product" 
                                            value="<?php echo esc_attr($item->id); ?>"
                                            data-name="<?php echo esc_attr($item->product->name[0]->value); ?>"
                                            data-id="<?php echo esc_attr($item->id); ?>">
                                            <label for="return-product-<?php echo esc_attr($item->id); ?>" class="sr-only"><?php echo __('Select at least 1 product'); ?></label>
                                               												 
                                        <img class="img-fluid pr-1" src="<?php echo esc_url($item->product->has_images() ? $item->product->images[0]->images[0]->url : $this->assets_url . '/img/no-image-card.webp'); ?>" alt="<?php echo esc_attr($item->product->name[0]->value); ?>">
                                        
                                    </div>
                                    <div class="col-8 col-lg-2 product-description">            
                                        <span class="product-name">
                                            <?php echo esc_html($item->product->name[0]->value); ?>
                                        </span>
                                        <input type="hidden" data-id="<?php echo esc_attr($item->id); ?>" name="product_name[<?php echo esc_attr($item->id); ?>]" value="<?php echo esc_attr($item->product->name[0]->value); ?>" disabled />
                                    </div>
                                
                                    <div class="col-4 col-lg-1">
                                        <div class="d-block d-lg-none label-title"><?php echo __('Ordered', 'propeller-ecommerce'); ?></div>
                                        <span class="product-quantity"><?php echo esc_html($item->quantity); ?></span>
                                    </div>
                                    <div class="col-4 col-lg-1">
                                        <div class="d-block d-lg-none label-title"><?php echo __('Return', 'propeller-ecommerce'); ?></div>
                                        <input type="number" 
                                            class="return-quantity form-control"  
                                            ondrop="return false;" 
                                            onpaste="return false;"
                                            onkeypress="return event.charCode>=48 && event.charCode<=57"  id="return_quantity_<?php echo esc_attr($item->id); ?>" name="return_quantity[<?php echo esc_attr($item->id); ?>]" data-id="<?php echo esc_attr($item->id); ?>" value="1" max="<?php echo esc_attr($item->quantity); ?>" data-max="<?php echo esc_attr($item->quantity); ?>" disabled/>
                                    </div>
                                    <div class="col-4 col-lg-1 price-per-item">
                                        <div class="d-block d-lg-none label-title"><?php echo __('Price', 'propeller-ecommerce'); ?></div>
                                        <span class="price"><span class="symbol">&euro;&nbsp;</span>
                                        <?php echo PropellerHelper::formatPrice($item->priceTotal); ?>
                                        </span>                    
                                    </div>
                                    <div class="col-4 col-lg-2 order-status">
                                        <div class="d-block d-lg-none label-title"><?php echo __('Package opened?', 'propeller-ecommerce'); ?></div>
                                        <div class="form-row">
                                            <div class="col-auto form-group form-check">
                                                <label class="btn-radio-checkbox form-check-label">
                                                    <input type="radio" class="form-check-input return-package" data-id="<?php echo esc_attr($item->id); ?>" name="return_package[<?php echo esc_attr($item->id); ?>]" value="Y" disabled > <span><?php echo __('Yes', 'propeller-ecommerce'); ?></span>
                                                </label>
                                            </div>
                                            <div class="col-auto form-group form-check">
                                                <label class="btn-radio-checkbox form-check-label">
                                                    <input type="radio" class="form-check-input return-package" data-id="<?php echo esc_attr($item->id); ?>" name="return_package[<?php echo esc_attr($item->id); ?>]" value="N" disabled > <span><?php echo __('No', 'propeller-ecommerce'); ?></span>
                                                </label>
                                            </div>
                                        </div>  
                                                        
                                    </div>
                                    <div class="col-8 col-lg-4 order-status d-lg-flex">
                                        <div class="d-block d-lg-none label-title"><?php echo __('Return reason', 'propeller-ecommerce'); ?></div>
                                        <select name="return_reason[<?php echo esc_attr($item->id); ?>]" data-id="<?php echo esc_attr($item->id); ?>" id="return_reason[<?php echo esc_attr($item->id); ?>]" class="form-control return-reason" disabled>
                                            <option value="1"><?php echo __('Select','propeller-ecommerce'); ?></option>
                                            <option value="2"><?php echo __('Wrongly delivered','propeller-ecommerce'); ?></option>
                                            <option value="3"><?php echo __('Defective','propeller-ecommerce'); ?></option>
                                            <option value="4"><?php echo __('The article does not comply','propeller-ecommerce'); ?></option>
                                            <option value="5"><?php echo __('Other','propeller-ecommerce'); ?></option>
                                        </select>

                                        <input type="hidden" name="return_reason_text[<?php echo esc_attr($item->id); ?>]" id="return_reason_text_<?php echo esc_attr($item->id); ?>" data-id="<?php echo esc_attr($item->id); ?>" value="" disabled />

                                        <div class="return-reason-other" id="return_reason_other_<?php echo esc_attr($item->id); ?>">
                                            <input type="text" name="return_other[<?php echo esc_attr($item->id); ?>]" data-id="<?php echo esc_attr($item->id); ?>" value="" class="form-control return-other" id="return_other_<?php echo esc_html($item->id); ?>" placeholder="<?php echo __('Your reason for return','propeller-ecommerce'); ?>" disabled />
                                        </div>
                                    </div>
                                </div>
                            </div>
                                                    
                        <?php } } ?>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <fieldset>
                                <legend class="return-header"><?php echo __('Other comment', 'propeller-ecommerce'); ?></legend>
                                <div class="row form-group">
                                    <div class="col-form-fields col-12 col-lg-6">                   
                                        <label class="form-label" for="return_comment"><?php echo __('Other comment', 'propeller-ecommerce'); ?></label>
                                        <textarea name="return_comment" value="" class="form-control" id="return_comment" placeholder="<?php echo __('Other comment', 'propeller-ecommerce'); ?>"></textarea>
                                    </div>
                                </div>
                            
                            </fieldset>
                        </div>
                    </div>
                   
                        
                    <div class="row form-group form-group-submit propel-modal-footer">
                        <div class="col-form-fields col-12 col-lg-6">
                            <div class="form-row">
                                <div class="col-6">
                                    <button type="button" class="btn-modal btn-cancel" data-dismiss="modal"><?php echo __('Cancel', 'propeller-ecommerce'); ?></button>
                                </div>
                                <div class="col-6 d-lg-flex justify-content-end">
                                    <button type="submit" class="btn-modal btn-proceed" id="submit_return_form"><?php echo __('Request a return', 'propeller-ecommerce'); ?></button>
                                </div>
                               
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>   
</div>

<div class="propeller-address-modal propeller-return-success-modal modal fade modal-fullscreen-sm-down" tabindex="-1" role="dialog" aria-labelledby="modal-title" id="returnRequestSuccess">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header propel-modal-header">
                <div id="propel_modal_title" class="modal-title">
                    <span><?php echo __('Your return request has been sent successfully', 'propeller-ecommerce'); ?></span>
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">
                        <svg class="icon icon-close">
                            <use class="header-shape-close" xlink:href="#shape-header-close"></use>
                        </svg>
                    </span>
                </button>
            </div>
            <div class="modal-body propel-modal-body" id="propel_modal_body">
                <p>
                    <?php echo __('You will receive a confirmation email at ','propeller-ecommerce'); ?> <span class="return-email"></span>
                    <?php echo __('We will contact you as soon as possible about the further processing of your return for order number ','propeller-ecommerce'); ?>
                    <span class="return-order"></span>.
                </p>
                <p><?php echo __('For more information about the return process, please call us on 088-7863536. ','propeller-ecommerce'); ?> </p>
                <div class="row propel-modal-footer">
                    <div class="col-12"> 
                        <button type="button" class="btn-modal btn-cancel" data-dismiss="modal"><?php echo __('Close', 'propeller-ecommerce'); ?></button>
                    </div>  
                </div>
            </div>
        </div>
    </div>
</div>