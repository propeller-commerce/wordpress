<?php if(!empty($cluster->description[0]->value)) { ?>   
    <div id="pane-desc" class="product-pane">
        <div class="row">
            <div class="col-12">
                <?= $cluster->description[0]->value; ?>
            </div>
        </div>
    </div>
<?php } ?>