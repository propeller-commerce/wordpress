<?php if(!empty($cart->actionCode)) { ?>
    <div class="container-fluid px-0 checkout-bonus-wrapper">
        <div class="row align-items-start">
            <div class="col mr-auto checkout-header">
                <h5><?php echo __('Action code', 'propeller-ecommerce'); ?></h5>
            </div>
        </div>
        <div class="row sc-bonus-item no-gutters align-items-center justify-content-space-between">
            <div class="col-11 sc-bonus-code">							
                <div class="action-code">
                    <?php echo esc_html($cart->actionCode); ?>
                </div>
            </div>
            <div class="col-1 sc-remove-code text-right">
                <form name="delete-actioncode" method="post" class="basket-remove-voucher-form">
                    <input type="hidden" name="action" value="cart_remove_action_code" />
                    <input type="hidden" name="actionCode" value="<?php echo esc_attr($cart->actionCode); ?>">
                    <button class="btn-remove" type="submit">
                        <svg class="icon icon-remove" aria-hidden="true">
                            <use xlink:href="#shape-remove"></use>
                        </svg>
                    </button>
                </form>
            </div>
        </div>
    </div>
<?php } else if(is_array($cart->vouchers) && !empty($cart->vouchers)) { ?>
    <div class="container-fluid px-0 checkout-bonus-wrapper">
        <div class="row align-items-start">
            <div class="col mr-auto checkout-header">
                <h5><?php echo __('Voucher code', 'propeller-ecommerce'); ?></h5>
            </div>
        </div>
        <?php foreach($cart->vouchers as $voucher) { ?>
            <div class="row sc-bonus-item no-gutters align-items-center justify-content-space-between">
                <div class="col-11 sc-bonus-code">							
                    <div class="action-code">
                        <?php echo esc_html($voucher->code); ?>
                    </div>
                </div>
                <div class="col-1 sc-remove-code text-right">
                    <form name="delete-actioncode" method="post" class="basket-remove-voucher-form">
                        <input type="hidden" name="action" value="cart_remove_action_code" />
                        <input type="hidden" name="actionCode" value="<?php echo esc_attr($voucher->code); ?>">
                        <button class="btn-remove" type="submit">
                            <svg class="icon icon-remove" aria-hidden="true">
                                <use xlink:href="#shape-remove"></use>
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        <?php } ?>
    </div>
<?php } ?>