<?php

use Propeller\PropellerHelper;

?>
<?php if (sizeof($product->bulkPrices) > 1) { ?>
    <div class="bulk-prices-wrapper d-none d-md-block">
        <div class="row bulk-prices">
            <div class="col-12">
                <?php foreach ($product->bulkPrices as $bulkPrice) { ?>
                    <div class="row justify-content-between no-gutters">
                        <div class="col"><?= $bulkPrice->from; ?><?php if (isset($bulkPrice->to)) { ?>-<?= $bulkPrice->to; ?><?php } else { echo '+'; } ?></div>
                        <div class="col-6 d-flex justify-content-end"><span class="symbol">&euro;&nbsp;</span><?= PropellerHelper::formatPrice($bulkPrice->gross); ?></div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
<?php } ?>