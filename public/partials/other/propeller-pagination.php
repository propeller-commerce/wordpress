<?php if ($paging_data->pages > 1) { ?>
<div class="col-12">
    <div class="row propeller-listing-pagination" data-min="1" data-max="<?php echo $paging_data->pages; ?>" data-current="<?php echo $paging_data->page; ?>">
        <div class="col-12 d-flex align-items-center justify-content-center ">
            <a class="previous page-item" data-prop_name="<?php echo $prop_name; ?>" data-prop_value="<?php echo $prop_value; ?>" data-page="<?php echo $prev; ?>" data-action="<?php echo $do_action; ?>" <?php echo $prev_disabled; ?> >
                <?php echo __('Previous', 'propeller-ecommerce') ?>
            </a>
            <?php for($key = 1; $key <= $paging_data->pages; $key++) { 
                if ($key == 2) { ?>
					<span class="page-item dots" id="dots-prev">&hellip;</span>
				<?php } if ($key == $paging_data->pages) { ?> 
                    <span class="page-item dots" id="dots-next">&hellip;</span>
                <?php } ?>	
                <a class="page-item <?php if ($key == $paging_data->page) echo 'active'; ?>" data-prop_name="<?php echo $prop_name; ?>" data-prop_value="<?php echo $prop_value; ?>" data-page="<?php echo $key; ?>" data-action="<?php echo $do_action; ?>" <?php if($key == 1) echo $prev_disabled; ?> <?php if($key == $paging_data->pages) echo $next_disabled; ?>>
               <?php echo $key; ?>
            </a>    
            <?php } ?>
            <a class="next page-item" data-prop_name="<?php echo $prop_name; ?>" data-prop_value="<?php echo $prop_value; ?>" data-page="<?php echo $next; ?>" data-action="<?php echo $do_action; ?>" <?php echo $next_disabled; ?>>
                <?php echo __('Next', 'propeller-ecommerce') ?>
            </a>   
        </div>                    
    </div>
</div>
<?php } ?>