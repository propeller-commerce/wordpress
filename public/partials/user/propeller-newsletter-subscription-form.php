<form name="newsletter-subscribe-form" class="newsletter-subscribe-form validate form-handler" method="post">
    <input type="hidden" name="action" value="submit_newsletter_form">
    <label class="sr-only" for="footer-newsletter"><?php echo __('E-mail address', 'propeller-ecommerce'); ?></label>
    <div class="input-group">
        <input type="email" name="user_mail" id="footer-newsletter" class="form-control required email" value="" placeholder="<?php echo __('E-mail address', 'propeller-ecommerce'); ?>">
        <button type="submit" class="btn-email"><?php echo __('Sign up', 'propeller-ecommerce'); ?></button>
    </div>
</form>