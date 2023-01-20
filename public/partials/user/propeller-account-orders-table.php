<div class="propeller-account-table">
    <h4><?php echo __('My orders', 'propeller-ecommerce'); ?></h4>

    <?php if (isset($orders) && sizeof($orders)) { ?>
        <div class="order-sorter">
            <?php echo apply_filters('propel_account_orders_table_header', $orders); ?>

            <div class="orders-list propeller-account-list">

                <?php echo apply_filters('propel_account_orders_table_list', $orders, $data, $obj); ?>
                
            </div>
        </div>
    <?php } else { ?>
        <div class="no-results">
            <?php echo __('You have not placed any orders yet.', 'propeller-ecommerce'); ?>
        </div>
    <?php } ?>
</div>