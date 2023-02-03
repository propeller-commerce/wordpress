<?php
    $obj->sort_arr = [
        "dateChanged" => __('Date changed', 'propeller-ecommerce'),
        "dateCreated" => __('Date created', 'propeller-ecommerce'),
        "name" => __('Name', 'propeller-ecommerce'),
        "price" => __('Price', 'propeller-ecommerce'),
        "relevance" => __('Relevance', 'propeller-ecommerce'),
        "sku" => __('SKU', 'propeller-ecommerce'),
        "supplierCode" => __('Supplier code', 'propeller-ecommerce'),
    ];

    $obj->sort_order = [
        "asc" => __('Asc', 'propeller-ecommerce'),
        "desc" => __('Desc', 'propeller-ecommerce'),
    ];

    $obj->offset_arr = [12, 24, 48];
?>
<div class="col-auto d-none d-sm-flex align-items-center catalog-offset">
    <label class="label"><?php echo __('Show per page', 'propeller-ecommerce'); ?></label>
    <div class="dropdown sticky-dropdown-menu">
        <select name="catalog-offset" class="form-control" data-prop_name="<?php echo esc_attr($prop_name); ?>" data-prop_value="<?php echo esc_attr($prop_value); ?>" data-action="<?php echo esc_attr($do_action); ?>">
        <?php foreach ($obj->offset_arr as $o) { ?>
            <option value="<?php echo esc_attr($o); ?>" <?php echo (isset($_REQUEST['offset']) && (int) $_REQUEST['offset'] == $o) ? 'selected' : ''; ?>><?php echo esc_html($o); ?></option>
        <?php } ?>
        </select>
    </div>
</div>
<div class="col-auto d-flex align-items-center catalog-sort">
    <label class="label"><?php echo __('Sort by', 'propeller-ecommerce'); ?></label>
    <div class="dropdown sticky-dropdown-menu">
        <select name="catalog-sort" class="form-control" data-prop_name="<?php echo esc_attr($prop_name); ?>" data-prop_value="<?php echo esc_attr($prop_value); ?>" data-action="<?php echo esc_attr($do_action); ?>">
        <?php foreach ($obj->sort_arr as $sort_key => $sort_val) { ?>
            <?php foreach ($obj->sort_order as $order_key => $order_val) { ?>
                <option value="<?php echo esc_attr($sort_key . ',' . $order_key); ?>" <?php echo ($sort == $sort_key . ',' . $order_key) ? 'selected' : ''; ?>><?php echo __($sort_val . ' ' . $order_val, 'propeller-ecommerce'); ?></option>
            <?php } ?>
        <?php } ?>
        </select>
    </div>
</div>