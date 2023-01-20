<div class="row align-items-end product-price-details mb-4 mt-4">
    <?php foreach ($cluster->formatted_options as $attr_name => $option) { 
        if (!$option->label || !count($option->values)) continue;   
    ?>
        <div class="col-12 col-md">
            <h5><?php echo $option->label; ?></h5>

            <?php if ( $option->display === 'dropdown') { ?>
                <select class="cluster-dropdown" name="<?php echo $attr_name; ?>" data-cluster_id="<?php echo $cluster->clusterId ?>">
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
                    <option value="<?php echo $val; ?>" <?php echo $selected; ?>>
                        <?php echo $val; ?>
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
        </div>
    <?php } ?>
</div>

<div class="row align-items-end product-price-details">
    
    <?php echo apply_filters('propel_product_add_to_basket', $cluster_product); ?>
    
    <div class="col-auto">
        <?php echo apply_filters('propel_cluster_add_favorite', $cluster); ?>
    </div>
</div>