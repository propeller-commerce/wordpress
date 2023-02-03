<div class="col-12 col-md-6 mb-4">
    <div class="address-box">
        <div class="row">
            <div class="col-12">
                <div class="addr-title"><?php echo __('Company details', 'propeller-ecommerce'); ?></div>
                <div class="user-addr-details">
                    <?php 
                        if (isset($user->company)) {
                            if (is_object($user->company))
                                echo esc_html($user->company->name);
                        }   
                    ?>
                    
                    <?php if (!empty($user->cocNumber)) { ?>
                        <span><?php echo __('Company Reg No.:', 'propeller-ecommerce'); ?>&nbsp;</span><?php echo esc_html($user->cocNumber); ?><br>
                    <?php } ?>
                    <?php if (!empty($user->taxNumber)) { ?>
                        <span><?php echo __('VAT number:', 'propeller-ecommerce'); ?>&nbsp;</span><?php echo esc_html($user->taxNumber); ?>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>