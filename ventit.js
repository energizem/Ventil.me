$(document).ready(function(){
//ajax
   // process the form
$('.main-vent').submit(function(event) {
    $('.form-group').removeClass('has-error'); // remove the error class
    $('.alert').remove(); // remove the error text

    var form = $(this); //store current form

// process the form
$.ajax({
    type        : 'POST', // define the type of HTTP verb we want to use (POST for our form)
    url         : 'vent_process.php', // the url where we want to POST
    data        : $(this).serialize(), // our data object
    dataType    : 'json' // what type of data do we expect back from the server
})
    // using the done promise callback
    .done(function(data) { //this used to be called .success but that has since been deprecated in jQuery 1.8+
//reset current captcha
    grecaptcha.reset(jQuery(form).find('.g-recaptcha').attr('data-widget-id'));
        // here we will handle errors and validation messages
        if ( ! data.success) {
            // handle errors for name ---------------
            if (data.errors.name) {
                form.find('.name-group').addClass('has-error'); // red input
                form.find('.result').append('<div class="alert alert-danger">' + data.errors.name + '</div>'); // add the actual error message under our input
            }

            // handle errors for comment ---------------
            if (data.errors.vent) { 
                form.find('.vent-group').addClass('has-error'); // red input
                // add the error class to show red input
                form.find('.result').append('<div class="alert alert-danger">' + data.errors.vent + '</div>'); // add the actual error message under our input
            }

            /*Captcha hidden if (data.errors.captcha) { 
                form.find('.g-recaptcha').addClass('has-error'); // red input
                // add the error class to show red input
                form.find('.result').append('<div class="alert alert-danger">' + data.errors.captcha + '</div>'); // add the actual error message under our input
            }*/

        } else {

            // ALL GOOD! just show the success message!
            $('form').append('<div class="alert alert-success">' + data.message + '</div>');

             window.setTimeout(function(){window.location.href='index.php'},1);
        }

    });
        // stop the form from submitting the normal way and refreshing the page
        event.preventDefault();
    });

    });//end function