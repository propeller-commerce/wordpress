<?php
    $expanded = true;
?>

<div class="filter" id="<?php echo $filter->searchId; ?>">
    <button class="btn-filter" type="button" href="#filtersForm_<?php echo $filter->id; ?>" data-toggle="collapse" aria-expanded="<?php echo $expanded ? 'true': 'false'; ?>" aria-controls="filterForm_<?php echo $filter->id; ?>">
        <span><?php echo $filter->description; ?></span>
    </button>  

    <div class="text-filter collapse <?php echo $expanded ? 'show': ''; ?>" id="filtersForm_<?php echo $filter->id; ?>">
        <form method="get" class="filterForm collapse <?php echo $expanded ? 'show': ''; ?>" id="filterForm_<?php echo $filter->id; ?>">
            <input type="hidden" name="prop_value" value="<?php echo $this->slug; ?>" />
            <input type="hidden" name="prop_name" value="<?php echo $this->prop; ?>" />
            <input type="hidden" name="action" value="<?php echo $this->action; ?>" />
            
            <?php 
                sort($filter->textFilter);

                foreach ($filter->textFilter as $vals) { if ($vals->value != '') { 
                    $checked = '';
                    $count = $vals->count;

                    if (isset($_REQUEST[$filter->searchId])) {
                        $filter_val_parts = explode('^', $_REQUEST[$filter->searchId]);
                        foreach($filter_val_parts as $filter_val_part) {
                            $_filter_val_parts = explode('~', $filter_val_part);
                            $filter_vals[] = wp_unslash($_filter_val_parts[0]);
                        }

                        if (in_array(wp_unslash($vals->value), $filter_vals)) {
                            $checked = 'checked';
                        }                                    
                    }

                    if (isset($_REQUEST['active_filter']) && $_REQUEST['active_filter'] == $filter->id)
                        $count = $vals->countActive;
                    else if ($vals->count == 0 && $vals->countActive > 0)
                        $count = $vals->countActive;

                    if ($count == 0 && !$vals->isSelected)
                        continue;
            ?>
                    <div class="form-check test">
                        <input 
                            type="checkbox" 
                            data-id="<?php echo $filter->id; ?>"
                            name="<?php echo $filter->searchId; ?>" 
                            class="form-check-input styled-checkbox" 
                            id='filterForm_<?php echo $filter->id; ?>_<?php echo $vals->value; ?>' 
                            value='<?php echo $vals->value . '~' . $type; ?>'
                            <?php echo $checked; ?>>
                            <label for='filterForm_<?php echo $filter->id; ?>_<?php echo $vals->value; ?>' title='<?php echo $vals->value; ?>' 
                                class="form-check-label"><span class="value"><?php echo $vals->value; ?></span> <span class="totals"> (<span class="filter-count"><?php echo $count; ?></span>)</span>
                        </label>
                    </div> 
            <?php } } ?>
        </form>
    </div>
</div>