<?php

use Propeller\Includes\Enum\OrderStatus;

$prev = $data->page - 1;
$prev_disabled = false;

if ($prev < 1) {
    $prev = 1;
    $prev_disabled = 'disabled';
}
    
$next = $data->page + 1;
$next_disabled = false;

if ($data->page == $data->pages)
    $next_disabled = 'disabled';
    
if ($data->pages > 1) { ?>
    <div class="col-12">
        <div class="row propeller-account-pagination" data-status="<?php echo OrderStatus::ORDER_STATUS_NEW; ?>"  data-action="get_orders" data-min="1" data-max="<?php echo (int) $data->pages; ?>" data-current="<?php echo (int) $data->page; ?>">
            <div class="col-12 d-flex align-items-center justify-content-center ">
                <a class="previous page-item <?php echo esc_attr($prev_disabled); ?>" data-page="<?php echo esc_attr($prev); ?>" <?php echo esc_attr($prev_disabled); ?> >
                    <span class="icon">
                        <svg class="icon icon-svg" aria-hidden="true">
                            <use xlink:href="#shape-arrow-left"></use>
                        </svg>
                    </span>
                </a>
                
                <span class="page-totals"><?php echo __('page', 'propeller-ecommerce'); ?> <?php echo (int) $data->page; ?> <?php echo __('from', 'propeller-ecommerce'); ?> <?php echo (int) $data->pages; ?></span>
                
                <a class="next page-item <?php echo esc_attr($next_disabled); ?>" data-page="<?php echo esc_attr($next); ?>" <?php echo esc_attr($next_disabled); ?>>
                    <span class="icon">
                        <svg class="icon icon-svg" aria-hidden="true">
                            <use xlink:href="#shape-arrow-right"></use>
                        </svg>
                    </span>
                </a>    
            </div>                    
        </div>
    </div>

<?php } ?>