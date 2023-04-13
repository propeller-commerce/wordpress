<?php if ($paging_data->pages > 1) { ?>
<div class="col-12">
    <div class="row propeller-listing-pagination" data-min="1" data-max="<?php echo esc_attr($paging_data->pages); ?>" data-current="<?php echo esc_attr($paging_data->page); ?>">
        <div class="col-12 d-flex align-items-center justify-content-center ">
            <a class="previous page-item" data-prop_name="<?php echo esc_attr($prop_name); ?>" data-prop_value="<?php echo esc_attr($prop_value); ?>" data-page="<?php echo esc_attr($prev); ?>" data-action="<?php echo esc_attr($do_action); ?>" data-obid="<?php echo esc_attr($obid); ?>" <?php echo esc_html($prev_disabled); ?> >
                <?php echo __('Previous', 'propeller-ecommerce') ?>
            </a>
            <?php for($key = 1; $key <= $paging_data->pages; $key++) { 
                if ($key == 2) { ?>
					<span class="page-item dots" id="dots-prev">&hellip;</span>
				<?php } if ($key == $paging_data->pages) { ?> 
                    <span class="page-item dots" id="dots-next">&hellip;</span>
                <?php } ?>	
                <a class="page-item <?php if ($key == $paging_data->page) echo 'active'; ?>" data-prop_name="<?php echo esc_attr($prop_name); ?>" data-prop_value="<?php echo esc_attr($prop_value); ?>" data-page="<?php echo esc_attr($key); ?>" data-action="<?php echo esc_attr($do_action); ?>" data-obid="<?php echo esc_attr($obid); ?>" <?php if($key == 1) echo esc_attr($prev_disabled); ?> <?php if($key == $paging_data->pages) echo esc_attr($next_disabled); ?>>
               <?php echo esc_html($key); ?>
            </a>    
            <?php } ?>
            <a class="next page-item" data-prop_name="<?php echo esc_attr($prop_name); ?>" data-prop_value="<?php echo esc_attr($prop_value); ?>" data-page="<?php echo esc_attr($next); ?>" data-action="<?php echo esc_attr($do_action); ?>" data-obid="<?php echo esc_attr($obid); ?>" <?php echo esc_html($next_disabled); ?>>
                <?php echo __('Next', 'propeller-ecommerce') ?>
            </a>   
        </div>                    
    </div>
</div>
<?php } ?>