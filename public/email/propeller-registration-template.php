<h1 style="font-size: 18px;"><?php echo __('Welcome to ', 'propeller-ecommerce') . ' {{var:site.name}}'; ?></h1>
<p>
    <?php echo __("Dear {{var:user.firstName}},", 'propeller-ecommerce'); ?><br>
    <?php echo __("Thank you for registering with {{var:site.name}}.", 'propeller-ecommerce'); ?><br />
    <?php echo __("With your account you can place an order, check your account history and manage your details.", 'propeller-ecommerce'); ?><br />
    <?php echo __("You can log in using your chosen username and password using this <a href=\"{{var:url.login}}\" target=\"_blank\">link</a>.", 'propeller-ecommerce'); ?><br />
</p>