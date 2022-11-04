<svg style="display:none">
    <symbol viewBox="0 0 14 14" id="shape-header-close"><title>Close</title> <path d="M13.656 12.212c.41.41.41 1.072 0 1.481a1.052 1.052 0 0 1-1.485 0L7 8.5l-5.207 5.193a1.052 1.052 0 0 1-1.485 0 1.045 1.045 0 0 1 0-1.481L5.517 7.02.307 1.788a1.045 1.045 0 0 1 0-1.481 1.052 1.052 0 0 1 1.485 0L7.001 5.54 12.208.348a1.052 1.052 0 0 1 1.485 0c.41.408.41 1.072 0 1.48L8.484 7.02l5.172 5.192z"/></symbol>
</svg>
<div id="delete_address_modal_<?= $address->id; ?>" class="propeller-address-modal modal modal-fullscreen-sm-down fade" tabindex="-1" role="dialog" aria-labelledby="modal-title">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header propel-modal-header">
                <div id="propel_modal_title" class="modal-title">
                    <span><?php echo __('Delete delivery address', 'propeller-ecommerce'); ?></span>
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">
                        <svg class="icon icon-close">
                            <use class="header-shape-close" xlink:href="#shape-header-close"></use>
                        </svg>
                    </span>
                </button>
            </div>
            <div class="modal-body propel-modal-body" id="propel_modal_body">
                <form name="delete-address-form" id="delete_address<?= $address->id; ?>" class="form-horizontal validate form-handler modal-form modal-edit-form" method="post">
                    <input type="hidden" name="id" value="<?= $address->id; ?>">
                    <input type="hidden" name="action" value="delete_address">
                    <div class="form-row">
                        <div class="col-12">
                            <p><?php echo __('Are you sure you want to delete this address?', 'propeller-ecommerce'); ?></p>
                        </div>
                        <div class="address-details col-12">   
                            <div class="address">
                                <?= $address->company; ?><br>
                               <?= $address->firstName; ?>  <?= $address->lastName; ?><br>
                               <?= $address->street; ?>  <?= $address->number; ?>  <?= $address->numberExtension; ?><br>
                               <?= $address->postalCode; ?>  <?= $address->city; ?><br>
                               <?php 
                                    $code = $address->country;
                                    $countries = include PROPELLER_PLUGIN_DIR . '/includes/Countries.php'; 

                                    echo !$countries[$code] ? $code : $countries[$code];
                                ?>
                            </div>
                        </div>
                    </div>
                    <div class="row form-group form-group-submit propel-modal-foote">
                        <div class="col-form-fields col-12">
                            <div class="form-row">
                                <div class="col-6">
                                    <input type="submit" class="btn-modal btn-proceed" id="submit_delete_address<?= $address->id; ?>" value="<?php echo __('Delete', 'propeller-ecommerce'); ?>">
                                </div>
                                <div class="col-6">
                                    <button type="button" class="btn-modal btn-cancel" data-dismiss="modal"><?php echo __('Cancel', 'propeller-ecommerce'); ?></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
   
</div>
<div id="propel_modal_recycle"></div>