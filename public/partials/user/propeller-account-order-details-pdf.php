<a href="data:<?= $order->pdf->contentType; ?>;base64,<?= $order->pdf->base64 ?>" download="<?= $order->pdf->fileName?>"><?php echo __('Order Confirmation (PDF)', ''); ?></a>