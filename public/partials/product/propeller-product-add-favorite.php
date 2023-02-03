<div class="favorites">
    <div class="favorite-add-form">
        <form name="add_favorite" class="validate form-handler favorite" method="post" novalidate="novalidate">
            <input type="hidden" name="action" value="favorites_add_item">
            <input type="hidden" name="product_id" value="<?php echo esc_attr($product->productId); ?>">
            <button type="submit" class="btn-favorite" rel="nofollow">
                <svg class="icon icon-product-favorite icon-heart">
                    <use class="header-shape-heart" xlink:href="#shape-favorites"></use>
                </svg>
            </button>
        </form>				
    </div>
</div>