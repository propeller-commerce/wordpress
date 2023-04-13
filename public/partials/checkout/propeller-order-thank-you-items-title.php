<div class="col-12">
    <div class="order-summary-title">
        <?php 
            if ($order->status == 'REQUEST') 
                echo __('Your quote request', 'propeller-ecommerce'); 
            else 
                echo __('Your order', 'propeller-ecommerce'); 
        ?>
    </div>
</div>