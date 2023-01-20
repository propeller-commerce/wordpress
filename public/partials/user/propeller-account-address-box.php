<div class="col-12 col-md-6 order-2 mb-4">
    <div class="address-box">
        <div class="row">
            <div class="col-12">
                <?php if ($show_title) { ?> 
                    <div class="addr-title"><?php echo $title ?></div>
                <?php } ?>
                <div class="user-addr-details">
                    <?php echo $address->company; ?><br>
                    <?php echo $obj->get_salutation($address) ?>
                    <?php echo $address->firstName; ?> <?php echo $address->lastName; ?><br>
                    <?php echo $address->street; ?> <?php echo $address->number; ?> <?php echo $address->numberExtension; ?><br>
                    <?php echo $address->postalCode; ?> <?php echo $address->city; ?><br>
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