<div class="row no-gutters align-items-end product-price-details">
    <?php foreach ($cluster->formatted_options as $attr_name => $option) { 
        if (!$option->label || !count($option->values)) continue;   
    ?>
        <h5><?= $option->label; ?></h5>

        <?php if ( $option->display === 'dropdown') { ?>
            <select class="cluster-dropdown" name="<?= $attr_name; ?>">
            <?php foreach ($option->values as $val) { ?>
                <?php 
                    $selected = '';
                    
                    foreach ($cluster->selected_options as $sel_option) {
                        if ($sel_option->name == $attr_name && $sel_option->value == $val) {
                            $selected = 'selected="selected"';
                            break;
                        }
                    }
                ?>
                <option value="<?= $val; ?>" <?= $selected; ?>>
                    <?= $val; ?>
                </option>
            <?php } ?>
            </select>
        <?php } else if ( $cluster_option->display === 'radio') { ?>
            <!-- add radio options -->
        <?php } else if ( $option->display === 'image') { ?>
            <!-- add image options probably like radios -->
        <?php } else if ( $option->display === 'color') { ?>
            <!-- add color options probably like radios -->
        <?php } ?>
    <?php } ?>
</div>

<div class="row no-gutters align-items-end product-price-details">
    
    <?= apply_filters('propel_product_add_to_basket', $cluster_product); ?>
    
    <div class="col-auto">
        <?= apply_filters('propel_cluster_add_favorite', $cluster); ?>
    </div>
</div>