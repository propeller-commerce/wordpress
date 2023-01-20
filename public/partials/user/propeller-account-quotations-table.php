<div class="propeller-account-table">
    <h4><?php echo __('My quotes', 'propeller-ecommerce'); ?></h4>

    <?php if (isset($orders) && sizeof($orders)) { ?>
        <div class="order-sorter">
            
            <?php echo apply_filters('propel_account_quotations_table_header', $orders); ?>

            <div class="quotations-list propeller-account-list">
                
                <?php echo apply_filters('propel_account_quotations_table_list', $orders, $data, $obj); ?>
                
            </div>  
        </div>
    <?php } else { ?>
        <div class="no-results">
            <?php echo __('You have no quotes.', 'propeller-ecommerce'); ?>
        </div>
    <?php } ?>
    </div>