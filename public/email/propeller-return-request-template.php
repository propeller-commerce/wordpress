<h1 style="font-size: 18px;"><?php echo __('Your return request','propeller-ecommerce'); ?></h1>
<p><?php echo __('Thank you for your return request. Below you will find the information as it is known to us and an overview of your return.','propeller-ecommerce'); ?></p>
<p><?php echo __('We will contact you as soon as possible about the further handling of your return.','propeller-ecommerce'); ?></p>


<div style="margin-bottom: 10px;"><strong><?php echo __('Order number','propeller-ecommerce'); ?></strong>: <?php echo esc_html($args['return_order']); ?></div>
<div style="margin-bottom: 10px;"><strong><?php echo __('E-mail','propeller-ecommerce'); ?></strong>: <?php echo esc_html($args['return_email']); ?></div>

<div><strong><?php echo __('Returned products','propeller-ecommerce'); ?>:</strong></div>
    <?php 
        foreach($args['return-product'] as $productId) { ?>
           <div style="border-bottom: 1px solid #f5f5f5; margin-bottom: 10px; margin-left: 5px;">
                <p><strong><?php echo __('Returned product','propeller-ecommerce'); ?></strong>: <?php echo esc_html($args['product_name'][$productId]); ?></p>
                <p><strong><?php echo __('Returned quantity','propeller-ecommerce'); ?></strong>: <?php echo esc_html($args['return_quantity'][$productId]); ?></p>
                <p><strong><?php echo __('Package opened','propeller-ecommerce'); ?></strong>: <?php echo esc_html($args['return_package'][$productId]); ?></p>
                <p><strong><?php echo __('Return reason','propeller-ecommerce'); ?></strong>: <?php echo esc_html($args['return_reason_text'][$productId]); ?></p>
                <?php if (!empty($args['return_other'][$productId])) { ?>
                    <p><strong><?php echo __('Return reason other','propeller-ecommerce'); ?></strong>: <?php echo esc_html($args['return_other'][$productId]); ?></p>
                <?php } ?>
            </div>
    <?php } ?>
<div><strong><?php echo __('Other comment','propeller-ecommerce'); ?></strong>: <?php echo esc_html($args['return_comment']); ?></div>