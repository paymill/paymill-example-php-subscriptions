var PAYMILL_PUBLIC_KEY = '354904370799397448c0339fa45e3aa8';
var PAYMILL_TEST_MODE  = true;

$(document).ready(function() {
  $( "#card-tds-form" ).submit(function(event) {
    event.preventDefault();
    try {
      paymill.createToken( {
        number:     $('#card-tds-form .card-number').val(),
        exp_month:  $('#card-tds-form .card-expiry-month').val(),
        exp_year:   $('#card-tds-form .card-expiry-year').val(),
        cvc:        $('#card-tds-form .card-cvc').val(),
        cardholder: $('#card-tds-form .card-holdername').val(),
        amount:     $('#card-tds-form .card-amount').val(),
        currency:   $('#card-tds-form .card-currency').val()
      }, PaymillResponseHandler);
    } catch(e) {
      logResponse(e.message);
    }
  });

  function PaymillResponseHandler( error, result ) {
    if( error ) {
      // Shows the error above the form
      $( ".errors.panel.panel-danger").removeClass( "hide" );
      $( ".errors.panel-heading" ).text( error.apierror );
      $( ".submit-button" ).removeAttr( "disabled" );
    } else {
      var form = $( "#card-tds-form" );
      // Output token
      var token = result.token;
      // Insert token into form in order to submit to server
      form.append(
        "<input type='hidden' name='card[token]' value='"+token+"'/>"
      );
      form.get(0).submit();
    }
  }

  function logResponse(res) {
    // create console.log to avoid errors in old IE browsers
    if (!window.console) console = {log:function(){}};
    console.log(res);
  }

  $( '.llama-tooltip' ).tooltip('hide');
});
