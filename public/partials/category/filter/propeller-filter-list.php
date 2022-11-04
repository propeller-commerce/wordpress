<?php
    $expanded = 'true';
?>
<div class="filter">
    <button class="btn-filter" type="button" href="#filterForm_<?= $filter->id; ?>" data-toggle="collapse" aria-expanded="<?= $expanded; ?>" aria-controls="filterForm_<?= $filter->id; ?>">
        <span><?= $filter->description; ?></span>
    </button>  
        
    <div class="text-filter collapse show" id="filterForm_<?= $filter->id; ?>">
        <form method="get" action="" class="filterForm collapse show" id="filterForm_<?= $filter->id; ?>">
            <input type="hidden" name="prop_value" value="<?= $this->slug; ?>" />
            <input type="hidden" name="prop_name" value="<?= $this->prop; ?>" />
            <input type="hidden" name="action" value="<?= $this->action; ?>" />
            
            <?php foreach ($filter->textFilter as $vals) { if ($vals->value != '') { ?>
                <div class="form-check">
                    <?php 
                        $checked = '';
                        if (isset($_REQUEST[$filter->searchId])) {
                            $filter_vals = explode(',', $_REQUEST[$filter->searchId]);

                            if (in_array($vals->value . '~' . $type, $filter_vals))
                                $checked = 'checked';
                        }
                    ?>

                    <input 
                        type="checkbox" 
                        name="<?= $filter->searchId; ?>" 
                        class="form-check-input styled-checkbox" 
                        id="filterForm_<?= $filter->id; ?>_<?= $vals->value; ?>" 
                        value="<?= $vals->value . '~' . $type; ?>"
                        <?= $checked; ?>>
                        <label for="filterForm_<?= $filter->id; ?>_<?= $vals->value; ?>" title="<?= $vals->value; ?>" 
                            class="form-check-label"><span class="value"><?= $vals->value; ?> (<?= $vals->count; ?>)</span>
                    </label>
                </div> 
            <?php } } ?>
            <!-- <button class=" d-none d-md-flex btn-apply-filters" type="submit"><?php echo __('Filter', 'propeller-ecommerce'); ?></button> -->
        </form>
    </div>
</div>