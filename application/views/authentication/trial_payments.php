<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<link rel="stylesheet" type="text/css" href="<?php echo site_url('/assets/css/pay/example1.css') ?>">
<!-- <script type="text/javascript" src="<?php echo site_url('/assets/js/pay/example1.js') ?>"></script> -->
<?php $this->load->view('authentication/includes/head.php'); ?>
<body>
  <div class="container">
    <div class="row" style="display: flex;justify-content: center;margin-top: 20%">
      <div class="col-md-6">
        <div class="cell example example1" id="example-1">
          <form action="<?php echo admin_url('authentication/charge')?>" method="post" id="payment-form" style="padding-top: 45px; padding-bottom: 45px;">
          <input type="hidden" name="<?php  echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">

          <fieldset>
            <div class="row">
              <label for="example1-first_name" data-tid="elements_examples.form.name_label">First Name</label>
              <input id="example1-first_name" data-tid="elements_examples.form.name_placeholder" type="text" name="first_name" value="<?php echo $first_name;?>">
            </div>

            <div class="row">
              <label for="example1-last_name" data-tid="elements_examples.form.name_label">Last Name</label>
              <input id="example1-last_name" data-tid="elements_examples.form.name_placeholder" type="text" name="last_name" value="<?php echo $last_name;?>">
            </div>

            <div class="row">
              <label for="example1-email" data-tid="elements_examples.form.email_label">Email</label>
              <input id="example1-email" data-tid="elements_examples.form.email_placeholder" type="email" name="email" value="<?php echo $email;?>">
            </div>
            <input type="hidden" name="password" id="password" class="form-control" value="<?php echo $password;?>">
          </fieldset>

          <fieldset>
            <div class="row">
              <div id="example1-card"></div>
              <div id="card-errors" role="alert"></div>
            </div>
          </fieldset>

          <button type="submit" data-tid="elements_examples.form.pay_button">Pay $50</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</body>
</html>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="https://js.stripe.com/v3/"></script>
<script type="text/javascript">
      // Create a Stripe client.
    var stripe = Stripe('pk_test_XppBWeORbJbO75rWp6lUz46d00vpp35zIp');
    // // Create an instance of Elements.
    var elements = stripe.elements();

    var card = elements.create('card', {
    iconStyle: 'solid',
    style: {
      base: {
        iconColor: '#c4f0ff',
        color: '#fff',
        fontWeight: 500,
        fontFamily: 'Roboto, Open Sans, Segoe UI, sans-serif',
        fontSize: '16px',
        fontSmoothing: 'antialiased',

        ':-webkit-autofill': {
          color: '#fce883',
        },
        '::placeholder': {
          color: '#87BBFD',
        },
      },
      invalid: {
        iconColor: '#FFC7EE',
        color: '#FFC7EE',
      },
    },
   });
    card.mount('#example1-card');


    // // Create an instance of the card Element.
    // var card = elements.create('card', {style: style});

    // // Add an instance of the card Element into the `card-element` <div>.
    // card.mount('#card-element');

    // Handle real-time validation errors from the card Element.
    card.addEventListener('change', function(event) {
      var displayError = document.getElementById('card-errors');
      if (event.error) {
        displayError.textContent = event.error.message;
      } else {
        displayError.textContent = '';
      }
    });

    // Handle form submission.
    var form = document.getElementById('payment-form');
    form.addEventListener('submit', function(event) {
      event.preventDefault();

      stripe.createToken(card).then(function(result) {
        if (result.error) {
          // Inform the user if there was an error.
          var errorElement = document.getElementById('card-errors');
          errorElement.textContent = result.error.message;
        } else {
          // Send the token to your server.
          stripeTokenHandler(result.token);
        }
      });
    });

    // Submit the form with the token ID.
    function stripeTokenHandler(token) {
      // Insert the token ID into the form so it gets submitted to the server
      var form = document.getElementById('payment-form');
      var hiddenInput = document.createElement('input');
      hiddenInput.setAttribute('type', 'hidden');
      hiddenInput.setAttribute('name', 'stripeToken');
      hiddenInput.setAttribute('value', token.id);
      form.appendChild(hiddenInput);

      // Submit the form
      form.submit();
    }
</script>