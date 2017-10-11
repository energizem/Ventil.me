<?php

require 'lib/mail/PHPMailerAutoload.php';
// reCaptcha check
function isValid() 
{
    try {
        $url = 'https://www.google.com/recaptcha/api/siteverify';
        $data = ['secret'   => '6LcRsRUUAAAAAJa-2COxLLAwD9RlxE27xBfkcbET',
                 'response' => $_POST['g-recaptcha-response'],
                 'remoteip' => $_SERVER['REMOTE_ADDR']];

        $options = [
            'http' => [
                'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                'method'  => 'POST',
                'content' => http_build_query($data) 
            ]
        ];

        $context  = stream_context_create($options);
        $result = file_get_contents($url, false, $context);
        return json_decode($result)->success;
    }
    catch (Exception $e) {
        return null;
    }
} //end captcha function

$errors         = array();      // array to hold validation errors
$data           = array();      // array to pass back data

$email=$_POST['email'];
$subject=$_POST['subject'];
$message=$_POST['message'];


// validate the variables ======================================================
    // if any of these variables don't exist, add an error to our $errors array


    if (empty($email))
        $errors['email'] = 'Email polje je prazno.';

    if (filter_var($email, FILTER_VALIDATE_EMAIL)===false) 
         $errors['email'] = 'Email nije ispravan.';


    if (empty($subject))
        $errors['subject'] = 'Naslov email-a je prazan.';


    if (empty($message))
        $errors['message'] = 'Poruka je prazna.';
        else if (strlen($message)<5)
            $errors['message'] = 'Poruka treba više sadržaja.';

    if(isValid()==false)
            $errors['captcha'] = 'Captcha nije ispravna, klikni na "Nisam robot" i pokušaj ponovo.';

    // if there are any errors in our errors array, return a success boolean of false
    if ( ! empty($errors)) {
        // if there are items in our errors array, return those errors
        $data['success'] = false;
        $data['errors']  = $errors;
    } else {
        //check if its an ajax request, exit if not
        if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) AND strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
        die();
        } 

            //Create a new PHPMailer instance
            $mail = new PHPMailer;

            //Tell PHPMailer to use SMTP
            $mail->isSMTP();

            //Enable SMTP debugging
            $mail->SMTPDebug = 0;

            //Ask for HTML-friendly debug output
            $mail->Debugoutput = 'html';

            //Set the hostname of the mail server
            $mail->Host = 'smtp.gmail.com';
            // use
            // $mail->Host = gethostbyname('smtp.gmail.com');
            // if your network does not support SMTP over IPv6

            //Set the SMTP port number - 587 for authenticated TLS, a.k.a. RFC4409 SMTP submission
            $mail->Port = 587;

            //Set the encryption system to use - ssl (deprecated) or tls
            $mail->SMTPSecure = 'tls';

            //Whether to use SMTP authentication
            $mail->SMTPAuth = true;

            //Username to use for SMTP authentication - use full email address for gmail
            $mail->Username = "xykroz22@gmail.com";

            //Password to use for SMTP authentication
            $mail->Password = "mreza328";

            //Set who the message is to be sent from
            $mail->setFrom($email, 'Vent Web Kontakt');

            //Set an alternative reply-to address
            $mail->addReplyTo($email, 'Email kontakta');

            //Set who the message is to be sent to
            $mail->addAddress('info@ventil.me', 'Web Admin');

            //Set the subject line
            $mail->Subject = $subject;

            //Read an HTML message body from an external file, convert referenced images to embedded,
            //convert HTML into a basic plain-text alternative body
            $mail->msgHTML($message);

            //Replace the plain text body with one created manually
            $mail->AltBody = $message;

            if($mail->send()){
                $data['success'] = true;
                $data['Smessage'] = 'Email je uspješno poslan! <br> Hvala što ste nas kontaktirali.';
            }
            else{
                    $errors['captcha'] = 'Greška s slanjem email-a.';
                    $data['success'] = false;
                    $data['errors']  = $errors;
            }
            
    } //end no errors  else

    // return all our data to an AJAX call
    echo json_encode($data);