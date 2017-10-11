<?php
// process.php

require '../connection.php';

// reCaptcha check
/*
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
} */
//end captcha function

$errors         = array();      // array to hold validation errors
$data           = array();      // array to pass back data

$name=mysqli_real_escape_string($conn,$_POST['name']);
$vent=$_POST['vent'];
// validate the variables ======================================================
    // if any of these variables don't exist, add an error to our $errors array

    if (empty($name))
        $errors['name'] = 'Ime je prazno.';

    if (empty($vent))
        $errors['vent'] = 'Poruka je prazna.';
        else if (strlen($vent)<5)
            $errors['vent'] = 'Poruka treba više sadržaja.';

   /*if(isValid()==false)
            $errors['captcha'] = 'Captcha nije ispravna, klikni na "Nisam robot" i pokušaj ponovo.';*/

    // if there are any errors in our errors array, return a success boolean of false
    if ( ! empty($errors)) {
        // if there are items in our errors array, return those errors
        $data['success'] = false;
        $data['errors']  = $errors;
    } else {
        //check if its an ajax request, exit if not
        if(!isset($_SERVER['HTTP_X_REQUESTED_WITH']) AND strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
        die();
        } 
            $stmt = $conn->prepare("INSERT INTO ventovi(ime, poruka) VALUES (?, ?)");
            $stmt->bind_param("ss",  $nam, $ven);

            $nam = $name;
            $ven= $vent;

        $sql="INSERT INTO ventovi(ime,poruka) VALUES ('$name','$vent')";

        if ($stmt->execute() === TRUE) {
        // if there are no errors process our form, then return a message
        // show a message of success and provide a true success variable
        $data['success'] = true;
        $data['message'] = 'Vent uspješno poslan!';}//end if
        else{
        $errors['vent'] = 'Greska s upisom u bazu.';
        $data['success'] = false;
        $data['errors']  = $errors;
        }

    } //end else

    // return all our data to an AJAX call
    echo json_encode($data);