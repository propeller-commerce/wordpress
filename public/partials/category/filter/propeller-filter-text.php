<?php
    $expanded = true;
?>

<div class="filter" id="<?= $filter->searchId; ?>">
    <button class="btn-filter" type="button" href="#filterForm_<?= $filter->id; ?>" data-toggle="collapse" aria-expanded="<?php echo $expanded ? 'true': 'false'; ?>" aria-controls="filterForm_<?= $filter->id; ?>">
        <span><?= $filter->description; ?></span>
    </button>  

    <div class="text-filter collapse <?php echo $expanded ? 'show': ''; ?>" id="filterForm_<?= $filter->id; ?>">
        <form method="get" action="" class="filterForm collapse <?php echo $expanded ? 'show': ''; ?>" id="filterForm_<?= $filter->id; ?>">
            <input type="hidden" name="prop_value" value="<?= $this->slug; ?>" />
            <input type="hidden" name="prop_name" value="<?= $this->prop; ?>" />
            <input type="hidden" name="action" value="<?= $this->action; ?>" />
            
            <?php 
                sort($filter->textFilter);

                foreach ($filter->textFilter as $vals) { if ($vals->value != '') { 
                    $checked = '';
                    $count = $vals->count;

                    if (isset($_REQUEST[$filter->searchId])) {
                        $filter_vals = explode(',', $_REQUEST[$filter->searchId]);

                        if (in_array($vals->value . '~' . $type, $filter_vals)) {
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
                    <div class="form-check">
                        <input 
                            type="checkbox" 
                            data-id="<?= $filter->id; ?>"
                            name="<?= $filter->searchId; ?>" 
                            class="form-check-input styled-checkbox" 
                            id="filterForm_<?= $filter->id; ?>_<?= $vals->value; ?>" 
                            value="<?= $vals->value . '~' . $type; ?>"
                            <?= $checked; ?>>
                            <label for="filterForm_<?= $filter->id; ?>_<?= $vals->value; ?>" title="<?= $vals->value; ?>" 
                                class="form-check-label"><span class="value"><?= $vals->value; ?></span> <span class="totals"> (<span class="filter-count"><?= $count; ?></span>)</span>
                        </label>
                    </div> 
            <?php } } ?>

            <!-- <button class=" d-none d-md-flex btn-apply-filters" type="submit"><?php echo __('Filter', 'propeller-ecommerce'); ?></button> -->
        </form>
    </div>
</div>