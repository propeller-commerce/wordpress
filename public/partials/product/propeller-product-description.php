<?php if(!empty($product->description[0]->value)) { ?>   
    <div id="pane-description" class="product-pane">
        <div class="row">
            <div class="col-12">
                <?php echo $product->description[0]->value; ?>
            </div>
        </div>
    </div>
<?php } ?>