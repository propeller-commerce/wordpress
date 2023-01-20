<div class="row">
    <div class="col-12">
        <?php if(!empty($product->shortDescription)) { ?>   
            <div class="product-short-description">
                <?php echo $product->shortDescription[0]->value;?>
            </div>
        <?php }?>
    </div>
</div>