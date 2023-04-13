<?php

use Propeller\PropellerHelper;

?>
<?php if (sizeof($product->bulkPrices) > 1) { ?>
    <div class="bulk-prices-wrapper d-none d-md-block">
        <div class="row bulk-prices">
            <div class="col-12">
                <?php foreach ($product->bulkPrices as $bulkPrice) { ?>
                    <div class="row justify-content-between no-gutters">
                        <div class="col"><?php echo esc_html($bulkPrice->from); ?><?php if (isset($bulkPrice->to)) { ?>-<?php echo esc_html($bulkPrice->to); ?><?php } else { echo '+'; } ?></div>
                        <div class="col-6 d-flex justify-content-end"><span class="symbol">&euro;&nbsp;</span><?php echo PropellerHelper::formatPrice($bulkPrice->gross); ?></div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
<?php } ?>