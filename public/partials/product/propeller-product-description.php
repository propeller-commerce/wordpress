<?php if(!empty($product->description[0]->value)) { ?>   
    <div id="pane-description" class="product-pane">
        <div class="row">
            <div class="col-12">
                <?php echo wp_kses($product->description[0]->value, wp_kses_allowed_html('post')); ?>
            </div>
        </div>
    </div>
<?php } ?>