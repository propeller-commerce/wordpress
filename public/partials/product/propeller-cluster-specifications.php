<div id="pane-spec" class="product-pane">
    <div class="row">
        <div class="col-12">
            <h3><?php echo __('Specifications', 'propeller-ecommerce'); ?></h3>
        </div>
        <div class="col-12">
            <?php if(!empty($cluster_product->eanCode)) { ?> 
                <div class="row no-gutters product-specs">
                    <div class="col col-sm-6">
                    <?php echo __('EAN code', 'propeller-ecommerce'); ?>
                    </div>
                    <div class="col-auto">
                        <?php echo $cluster_product->eanCode; ?>
                    </div>
                </div>
            <?php } ?>
            <?php if(!empty($cluster_product->manufacturer)) { ?> 
                <div class="row no-gutters product-specs">
                    <div class="col col-sm-6">
                    <?php echo __('Brand', 'propeller-ecommerce'); ?>
                    </div>
                    <div class="col-auto">
                        <?php echo $cluster_product->manufacturer; ?>
                    </div>
                </div>
            <?php } ?>
            <?php 

            if($cluster_product->has_attributes()){
                foreach ($cluster_product->get_attributes() as $attribute) {
                    if (($attribute->get_type() == 'text' || 
                         $attribute->get_type() == 'list' || 
                         $attribute->get_type() == 'enumlist' ||
                         $attribute->get_type() == 'integer' ||
                         $attribute->get_type() == 'decimal') && $attribute->has_value()){ ?>
                            <div class="row no-gutters product-specs">
                                <div class="col col-sm-6">
                                    <?php echo $attribute->get_description(); ?>
                                </div>
                                <div class="col-6">
                                    <?php echo $attribute->get_value(); ?>
                                </div>
                            </div>
                        <?php 
                    }
                }
            }
            ?>
        </div>
    </div>
</div>