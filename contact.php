<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Piši anonimno, Ventaj se!</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>

<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="css/bootstrap.min.css">

<!-- Optional theme -->
<link rel="stylesheet" href="css/bootstrap-theme.min.css">

<!-- Latest compiled and minified JavaScript -->
<script src="js/bootstrap.min.js"></script>
<!-- Moj CSS -->
<link rel="stylesheet" href="css/stil.css">

<script src="https://www.google.com/recaptcha/api.js?onload=CaptchaCallback&render=explicit&hl=hr" async defer></script>
<script src="ventit.js"></script>
<script>
var CaptchaCallback = function() {
    jQuery('.g-recaptcha').each(function(index, el) {
        var widgetId = grecaptcha.render(el, {'sitekey' : '6LcRsRUUAAAAAMffkTt537yQri0M4p2tZAk_T7xQ'});
        jQuery(this).attr('data-widget-id', widgetId);
    });
};
$(document).ready(function(){
    //hide menu on document click
$(document).on('click',function(){
    $('.collapse').collapse('hide');
})

//Ventaj se! hide-show
        $('#vent-it').click(function(){
        $('.hide-form').slideToggle("fast");
    });

//ajax
   // process the form
    $('form').submit(function(event) {
    $('.form-group').removeClass('has-error'); // remove the error class
    $('.alert').remove(); // remove the error text

    var form = $(this); //store current form

// process the form
$.ajax({
    type        : 'POST',
    url         : 'contact_process.php',
    data        : $(this).serialize(),
    dataType    : 'json' // what type of data do we expect back from the server
})

    // using the done promise callback
    .done(function(data) { //this used to be called .success but that has since been deprecated in jQuery 1.8+

        // here we will handle errors and validation messages
 //reset current captcha
   grecaptcha.reset(jQuery(form).find('.g-recaptcha').attr('data-widget-id'));

        if ( ! data.success) {

            if (data.errors.email) {
                form.find('.email-group').addClass('has-error'); // red input
                form.find('.result').append('<div class="alert alert-danger">' + data.errors.email + '</div>');
            }

            if (data.errors.subject) { 
                form.find('.subject-group').addClass('has-error'); // red input
                // add the error class to show red input
                form.find('.result').append('<div class="alert alert-danger">' + data.errors.subject + '</div>');
            }

            if (data.errors.message) { 
                form.find('.message-group').addClass('has-error'); // red input
                // add the error class to show red input
                form.find('.result').append('<div class="alert alert-danger">' + data.errors.message + '</div>');
            }

            if (data.errors.captcha) { 
                form.find('.captcha-group').addClass('has-error'); // red input
                // add the error class to show red input
                form.find('.result').append('<div class="alert alert-danger">' + data.errors.captcha + '</div>'); 
            }
        } 
        else  {
            //show the success message!
            form.find('.result').append('<div class="alert alert-success">' + data.Smessage + '</div>');
        }
    });
        // stop the form from submitting the normal way and refreshing the page
        event.preventDefault();
    });

    });//end function
</script>

</head>
<body id="top">
<nav role="navigation" class="navbar navbar-default navbar-fixed-top">
  <div class="container-fluid">
    <div class="navbar-header navbar-left pull-left">
      <a class="navbar-brand" href="index.php"><img src="images/logo.svg" style="background: white"></a>
    </div>
    <div class="navbar-header navbar-right pull-right">
      <ul class="nav pull-left">
        <li class="dropdown pull-right">
<a href="#" class="btn-3d red" id="vent-it">Ventaj se!</a>
        </li>
      </ul>
      <button type="button" data-toggle="collapse" data-target=".navbar-collapse" class="navbar-toggle">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
    </div>
    <div class="visible-xs-block clearfix"></div>
    <div class="collapse navbar-collapse">
      <ul class="nav navbar-nav navbar-left">
        <li><a href="index.php?orderBy=novi">Novi</a></li>
        <li><a href="index.php?orderBy=popularno">Popularni</a> </li>
        <li><a href="index.php?orderBy=comment">Top komentirani</a></li>
        <li><a href="index.php?orderBy=trend">Trending</a></li>
      </ul>
    </div>
  </div>
</nav>

    <form action="vent_process.php" method="POST"  class="hide-form text-left vent-form main-vent">
        <h4 style="color: #000;text-shadow: none">Piši što god ti je na umu</h4>
        <!-- NAME -->
        <div id="name-group" class="form-group input-group">
            <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
            <input id="msg" type="text" class="form-control" name="name" value="Anonimno">
        </div>

        <!-- vent -->
        <div id="vent-group" class="form-group input-group">
            <span class="input-group-addon"><i class="glyphicon glyphicon-comment"></i></span>
            <textarea rows="5" class="form-control" name="vent" placeholder="U čemu je stvar?" ></textarea>
        </div>

     <div class="g-recaptcha form-group" data-sitekey="6LcRsRUUAAAAAMffkTt537yQri0M4p2tZAk_T7xQ" data-callback="correctCaptcha" align="center" style="display: none"></div>

        <div class="result"></div>
        <button type="submit" class="btn btn-success btn-block" style="line-height: 35px">Pošalji!</button>

    </form>
<!--content START-->
<div class="container" style="max-width: 700px;">
    <div class="row">
        <div class="col-xs-12">
        <p>Ako imate pitanja, problema, sugestija, pohvala ili bilo što drugo što vam budi interesovanje a da je vezano za stranicu slobodno kontaktirajte na:
        <script type="text/javascript">
            var string1 = "info";
            var string2 = "@";
            var string3 = "ventil.me";
            var string4 = string1 + string2 + string3;
            document.write("<a href=" + "mail" + "to:" + string1 + string2 + string3 + ">" + string4 + "</a>");
        </script></p>
        <p>Ili koristite formu:</p>
            <form action="contact_process.php" method="POST" class="vent-form" style="margin-bottom: 50px">
                <!-- Email -->
                <div class="email-group form-group input-group">
                    <span class="input-group-addon" id="basic-addon1">@</span>
                    <input id="msg" type="text" class="form-control" name="email" placeholder="Vaš email">
                </div>
                <!-- Subject -->
                <div class=" subject-group form-group input-group">
                    <span class="input-group-addon"><i class="glyphicon glyphicon-pencil"></i></span>
                    <input id="msg" type="text" class="form-control" name="subject" placeholder="Naslov Poruke">
                </div>
                <!-- Message -->
                <div class="message-group form-group input-group">
                    <span class="input-group-addon"><i class="glyphicon glyphicon-comment"></i></span>
                    <textarea rows="5" class="form-control" name="message" placeholder="Poruka" ></textarea>
                </div>
                <!-- Captcha -->
              <div class="g-recaptcha form-group captcha-group" data-sitekey="6LcRsRUUAAAAAMffkTt537yQri0M4p2tZAk_T7xQ" data-callback="correctCaptcha" align="center"></div>
                <div class="result"></div>
                <button type="submit" class="btn btn-success btn-block" style="line-height: 35px">Pošalji!</button>
            </form>
        </div>
    </div>
</div>
<!--content END-->

<!--footer START-->
<div class="footer">
    <div class="footer-box">
        <a href="#top">
        <div class="back-to-top">
            <img src="images/thumbs-up.svg">
        </div></a>
        <div class="container">
            <div class="col-xs-6" style="font-size: 12px;"><img class="footer-logo" src="images/logo.svg"><strong>Zašto Ventil?</strong> Često se nađete u situaciji kada ne možete reći što želite, a da pri tome ne povrijedite osobu ili budete kritikovani. Svakodnevni stres i problemi, a nemate kome da se požalite? Ova stranica ima samo jednu svrhu, to je da pruži, poptuno anonimni ispušni ventil.
            </div>
            <div class="col-xs-6 text-center">
                <a href="https://www.facebook.com/ventil.me"><img src="images/facebook.png"></a>
                <div class="row text-center" style="margin-top:10px;">
                    <a href="index.php">Novi</a> | <a href="index.php?orderBy=popularno">Popularni</a> | <a href="index.php?orderBy=comment">Top komentirani</a> | <a href="index.php?orderBy=trend">Trending</a>
                </div>
                <div class="row text-center"> 
                    <a href="pravila.php">Uslovi korištenja</a> | <a href="contact.php">Kontakt</a> <a href="#"></a>
                </div>
                <div class="row text-center">
                    Copyright © 2017 
                </div>
            </div>
        </div>
    </div>
</div>
<!--footer END-->
</body>
</html>