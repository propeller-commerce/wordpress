<?php 

use Propeller\Includes\Controller\PageController;
use Propeller\Includes\Enum\PageType;
use Propeller\Includes\Enum\AddressType;

?>

<svg style="display:none;">
    <symbol viewBox="0 0 5 8" id="shape-arrow-left"><title>Arrow left</title><path d="M4.173 7.85a.546.546 0 0 1-.771-.02L.149 4.375a.545.545 0 0 1 0-.75L3.402.17a.546.546 0 0 1 .792.75L1.276 4l2.918 3.08a.545.545 0 0 1-.021.77z" /></symbol>  
</svg>
<div class="container-fluid px-0 propeller-account-wrapper">
    <?php 
        echo '<script type="application/ld+json">
        {
            "@context": "http://schema.org",
            "@type": "BreadcrumbList",
            "itemListElement": [
                { 
                    "@type": "ListItem",
                    "position": 1,
                    "item": {
                        "@type": "Thing",
                        "@id": " '.  home_url() .' " , 
                        "name": "'?> <?php echo __("Home","propeller-ecommerce") ?><?php echo '"
                    }
                },
                {
                    "@type": "ListItem",
                    "position": 2,
                    "item": {
                        "@type": "Thing",
                        "@id": "' . $this->buildUrl('',PageController::get_slug(PageType::MY_ACCOUNT_PAGE)) . '" , 
                        "name": "'?> <?php  echo __("My account", "propeller-ecommerce") ?><?php echo '"
                    }
                },
                {
                    "@type": "ListItem",
                    "position": 3,
                    "item": {
                        "@type": "Thing",
                        "@id": "' . $this->buildUrl('',PageController::get_slug(PageType::ADDRESSES_PAGE)) . '" , 
                        "name": "'?> <?php  echo __("My addresses", "propeller-ecommerce") ?><?php echo '"
                    }
                }
            ]
        }
        </script>';
    ?>
    <div class="row">
        <div class="col">
            <?php 
                $breadcrumb_paths = [
                    [
                        $this->buildUrl('',PageController::get_slug(PageType::MY_ACCOUNT_MOBILE_PAGE)), 
                        __('My account', 'propeller-ecommerce')
                    ],
                    [
                        $this->buildUrl('',PageController::get_slug(PageType::MY_ACCOUNT_PAGE)),
                        __('My account', 'propeller-ecommerce')
                    ],
                    [
                        $this->buildUrl('',PageController::get_slug(PageType::ADDRESSES_PAGE)),
                        __('My addresses', 'propeller-ecommerce')
                    ]
                ];

                apply_filters('propel_breadcrumbs', $breadcrumb_paths);
            ?>
        </div>
    </div>
    <div class="row">

        <?php echo apply_filters('propel_my_account_title', __('My account', 'propeller-ecommerce')); ?>

    </div>
    <div class="row">
        <div class="col-12 col-lg-3">

            <?php echo apply_filters('propel_my_account_menu', $this); ?>

        </div>
        <div class="col-12 col-lg-9">
            <div class="propeller-account-table">
                <div class="row address-title">

                    <?php echo apply_filters('propel_my_account_addresses_title', __('My addresses', 'propeller-ecommerce')); ?>    

                </div>
                <div class="default-addresses">
                    <div class="row">
                        <div class="col-12">
                            <h5><?php echo __('Default addresses', 'propeller-ecommerce'); ?></h5>
                        </div>
                    </div>
                    <div class="row">

                        <?php echo apply_filters('propel_address_box', $this->get_default_address(AddressType::INVOICE), $this, __('Default billing address', 'propeller-ecommerce'), true, true); ?>

                        <?php echo apply_filters('propel_address_box', $this->get_default_address(AddressType::DELIVERY), $this, __('Default delivery address', 'propeller-ecommerce'), true, true); ?>

                    </div>
                </div>

                <?php if ($this->get_default_address(AddressType::INVOICE)) { ?>
                    <div class="invoice-addresses">
                        <div class="row">
                            <div class="col-12">
                                <h5><?php echo __('Other billing adresses', 'propeller-ecommerce'); ?></h5>
                            </div>
                        </div>
                        <div class="row">
                            <?php 
                                foreach ($this->addresses as $address) { 
                                    if ($address->type === 'invoice' && $address->isDefault === 'N')
                                        apply_filters('propel_address_box', $address, $this, '', false, true, true, true);
                                } 
                            ?>
                        </div>
                    </div>
                <?php } ?>

                <?php echo apply_filters('propel_address_add', AddressType::INVOICE, __('Add billing address', 'propeller-ecommerce'), $this); ?>
                
                <?php if ($this->get_default_address(AddressType::DELIVERY)) { ?>
                    <div class="delivery-addresses">
                        <div class="row">
                            <div class="col-12">
                                <h5><?php echo __('Other delivery addresses', 'propeller-ecommerce'); ?></h5>
                            </div>
                        </div>
                        <div class="row">
                            <?php 
                                foreach($this->addresses as $address) { 
                                    if ($address->type === 'delivery' && $address->isDefault === 'N')
                                        apply_filters('propel_address_box', $address, $this, '', false, true, true, true);
                                } 
                            ?>
                        </div>
                    </div>
                <?php } ?>

                <?php echo apply_filters('propel_address_add', AddressType::DELIVERY, __('Add delivery address', 'propeller-ecommerce'), $this); ?>

            </div>           
        </div>        
    </div>    
</div>