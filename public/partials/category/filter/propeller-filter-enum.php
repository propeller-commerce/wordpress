<?php
    $expanded = true;
?>
<div class="filter" id="<?= $filter->searchId; ?>">
    <button class="btn-filter" type="button" href="#filtersForm_<?= $filter->id; ?>" data-toggle="collapse" aria-expanded="<?php echo $expanded ? 'true': 'false'; ?>" aria-controls="filterForm_<?= $filter->id; ?>">
        <span><?= $filter->description; ?></span>
    </button>  
        
    <div class="text-filter collapse <?php echo $expanded ? 'show': ''; ?>" id="filtersForm_<?= $filter->id; ?>">
        <form method="get" class="filterForm collapse <?php echo $expanded ? 'show': ''; ?>" id="filterForm_<?= $filter->id; ?>">
            <input type="hidden" name="prop_value" value="<?= $this->slug; ?>" />
            <input type="hidden" name="prop_name" value="<?= $this->prop; ?>" />
            <input type="hidden" name="action" value="<?= $this->action; ?>" />
            
            <?php foreach ($filter->textFilter as $vals) { if ($vals->value != '') { 
             
                $checked = '';
                if (isset($_REQUEST[$filter->searchId])) {
                    $filter_vals = explode('^', $_REQUEST[$filter->searchId]);

                    if (in_array($vals->value . '~' . $type, $filter_vals))
                        $checked = 'checked';
                }
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
                            class="form-check-label"><span class="value"><?= $vals->value; ?> (<?= $vals->count; ?>)</span>
                    </label>
                </div> 
            <?php } } ?>
        </form>
    </div>
</div>