<div class="col-12 col-md-6 col-lg-4 ml-lg-auto">
    <svg style="display:none">
        <symbol viewBox="0 0 14 14" id="shape-header-close"><title>Close</title> <path d="M13.656 12.212c.41.41.41 1.072 0 1.481a1.052 1.052 0 0 1-1.485 0L7 8.5l-5.207 5.193a1.052 1.052 0 0 1-1.485 0 1.045 1.045 0 0 1 0-1.481L5.517 7.02.307 1.788a1.045 1.045 0 0 1 0-1.481 1.052 1.052 0 0 1 1.485 0L7.001 5.54 12.208.348a1.052 1.052 0 0 1 1.485 0c.41.408.41 1.072 0 1.48L8.484 7.02l5.172 5.192z"/></symbol>
    </svg>
    <div class="shopping-cart-order-type">
        <div class="row no-gutters align-items-baseline">
            <div class="col-12 d-flex align-items-center justify-content-between">
                <div class="sc-title"><?php

    use Propeller\Includes\Controller\SessionController;

    echo __('Type of order', 'propeller-ecommerce'); ?></div>
            </div>
        </div>
        <div class="order-type" id="orderType">
            <form name="order-type" class="order-type-form" method="post">
                <input type="hidden" name="action" value="cart_change_order_type">                      
                <div class="row form-group">
                    <div class="col-form-fields col-12">
                        <div class="form-row">
                            <div class="col-12 form-group form-check">
                                <label class="btn-radio-checkbox form-check-label ">
                                    <input type="radio" class="form-check-input" name="order_type" value="regular" <?php echo SessionController::get(PROPELLER_ORDER_TYPE) == 'regular' ? 'checked' : ''; ?>> <span><?php echo __('Regular order', 'propeller-ecommerce'); ?></span>
                                </label>
                            </div>
                            <div class="col-12 form-group form-check">
                                <label class="btn-radio-checkbox form-check-label ">
                                    <input type="radio" class="form-check-input" name="order_type" value="dropshipment" <?php echo SessionController::get(PROPELLER_ORDER_TYPE) == 'dropshipment' ? 'checked' : ''; ?>> <span><?php echo __('Dropshipment order', 'propeller-ecommerce'); ?></span>
                                </label>
                            </div>
                        </div>  
                    </div>
                </div>
            </form>
            <div class="row">
                <div class="col-12">
                    <a data-target="#orderTypeModal" data-toggle="modal"  href="#orderTypeModal" class="order-type-modal"><?php echo __('Find out more about different order types here', 'propeller-ecommerce'); ?></a>
                </div>
            </div>
        </div>        
    </div>
    <div class="propeller-order-modal modal fade modal-fullscreen-sm-down" tabindex="-1" role="dialog" aria-labelledby="modal-title" id="orderTypeModal">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header propel-modal-header">
                    <div id="propel_modal_title" class="modal-title">
                        <span><?php echo __('Different order types', 'propeller-ecommerce'); ?></span>
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
                    <div class="order-title">
                        <?php echo __('Regular order', 'propeller-ecommerce'); ?>
                    </div>
                    <div class="order-description">
                        <?php echo __('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas sodales congue ipsum id ultrices. Quisque eu nisl sapien. In auctor pulvinar lorem, ac posuere mauris sagittis at. Integer maximus elementum pulvinar. Donec commodo quam id tellus fermentum.', 'propeller-ecommerce'); ?>
                    </div>
                    <div class="order-title">
                        <?php echo __('Dropshipment', 'propeller-ecommerce'); ?>
                    </div>
                    <div class="order-description">
                        <?php echo __('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas sodales congue ipsum id ultrices. Quisque eu nisl sapien. In auctor pulvinar lorem, ac posuere mauris sagittis at. Integer maximus elementum pulvinar. Donec commodo quam id tellus fermentum.', 'propeller-ecommerce'); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>