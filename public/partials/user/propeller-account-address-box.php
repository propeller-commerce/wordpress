<div class="col-12 col-md-6 order-2 mb-4">
    <div class="address-box">
        <div class="row">
            <div class="col-12">
                <?php if ($show_title) { ?> 
                    <div class="addr-title"><?= $title ?></div>
                <?php } ?>
                <div class="user-addr-details">
                    <?= $address->company; ?><br>
                    <?= $obj->get_salutation($address) ?>
                    <?= $address->firstName; ?> <?= $address->lastName; ?><br>
                    <?= $address->street; ?> <?= $address->number; ?> <?= $address->numberExtension; ?><br>
                    <?= $address->postalCode; ?> <?= $address->city; ?><br>
                    <?php 
                        $code = $address->country;
                        
                        $countries = include PROPELLER_PLUGIN_DIR . '/includes/Countries.php'; 
                        echo !isset($countries[$code]) ? $code : $countries[$code];
                    ?>                    
                </div>
            </div>
        </div>

        <div class="row address-links">
            <div class="col-12">
                <?php 
                    if ($show_modify)
                        apply_filters('propel_address_modify', $address);

                    if ($show_delete)
                        apply_filters('propel_address_delete', $address);

                    if ($show_set_default && $address->isDefault != 'Y')
                        apply_filters('propel_address_set_default', $address);
                ?>
            </div>
        </div>        
    </div>
</div>