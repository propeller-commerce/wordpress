<h1 style="font-size: 18px;"><?php echo __('Your price request', 'propeller-ecommerce'); ?></h1>
<p><?php echo __('Thank you for your price request.', 'propeller-ecommerce'); ?></p>
<p><?php echo __('We will contact you as soon as possible about the requested pricings.', 'propeller-ecommerce'); ?></p>

<div style="margin-bottom: 10px;">
    <strong><?php echo __('E-mail', 'propeller-ecommerce'); ?></strong>: <?php echo esc_html($user_email); ?>
    <strong><?php echo __('Name', 'propeller-ecommerce'); ?></strong>: <?php echo esc_html(join(' ', [$user_data->firstName, $user_data->middleName, $user_data->lastName])); ?>
    <strong><?php echo __('Company', 'propeller-ecommerce'); ?></strong>: <?php echo esc_html($user_data->company->name); ?>
    <strong><?php echo __('Cust. Number', 'propeller-ecommerce'); ?></strong>: <?php echo esc_html($user_data->debtorId); ?>
</div>

<div><strong><?php echo __('Requested products', 'propeller-ecommerce'); ?>:</strong></div>
    <?php 
        foreach($data['product-code-row'] as $index => $product) { 
            if (empty($product))
                continue;        
    ?>
           <div style="border-bottom: 1px solid #f5f5f5; margin-bottom: 10px; margin-left: 5px;">
                <p><strong><?php echo __('Product', 'propeller-ecommerce'); ?></strong>: <?php echo esc_html($data['product-code-row'][$index] . ' / ' . $data['product-name-row'][$index]); ?></p>
                <p><strong><?php echo __('Quantity', 'propeller-ecommerce'); ?></strong>: <?php echo esc_html($data['product-quantity-row'][$index]); ?></p>
            </div>
    <?php } ?>
<div><strong><?php echo __('Comments','propeller-ecommerce'); ?></strong>: <?php echo esc_html($data['request_comment']); ?></div>