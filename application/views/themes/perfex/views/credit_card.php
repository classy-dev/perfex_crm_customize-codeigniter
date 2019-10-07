<?php defined('BASEPATH') or exit('No direct script access allowed');
/**
 * Stripe Credit Cards UPDATE
 */
?>
<div class="panel_s section-heading section-credit-card">
    <div class="panel-body">
        <h4 class="no-margin section-text"><?php echo _l('update_credit_card'); ?></h4>
    </div>
</div>
<div class="panel_s">
    <div class="panel-body credit-card">
        <?php if(!empty($stripe_customer->default_source)){ ?>
        <h4><?php echo _l('credit_card_update_info'); ?></h4>
        <hr />
        <?php echo form_open(); ?>
        <script
            src="https://checkout.stripe.com/checkout.js" class="stripe-button"
            data-key="<?php echo $stripe_pk; ?>"
            data-name="<?php echo get_option('companyname'); ?>"
            data-panel-label="<?php echo _l('update_card_details'); ?>"
            data-label="<?php echo _l('update_card_btn'); ?> (<?php echo $stripe_customer->default_source->brand; ?> <?php echo $stripe_customer->default_source->last4; ?>)"
            data-allow-remember-me=false
            data-email="<?php echo $contact->email; ?>"
            data-locale="auto">
        </script>
    <?php echo form_close(); ?>
    <?php } else { ?>
    <?php echo _l('no_credit_card_found'); ?>
    <?php } ?>
</div>
</div>

