<?php
## STO SE DOGADJA KADA POZOVEN OVAJ FILE DIREKTNO!
require '../connection.php';

// reCaptcha check
/*Captcha hidden function isValid() 
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
} //end captcha function */

$errors         = array();      // array to hold validation errors
$data           = array();      // array to pass back data

$name=mysqli_real_escape_string($conn, $_POST['name']);
$comment=$_POST['comment'];
$post_id=mysqli_real_escape_string($conn, $_POST['comment_post_id']);
$parrent=mysqli_real_escape_string($conn, $_POST['parrent']);

// validate the variables ======================================================
    // if any of these variables don't exist, add an error to our $errors array

    if (empty($name))
        $errors['name'] = 'Ime je prazno.';

    if (empty($comment))
        $errors['comment'] = 'Poruka je prazna.';
        else if (strlen($comment)<3)
            $errors['comment'] = 'Poruka treba više sadržaja.';

    /* Captcha hidden if(isValid()==false)
            $errors['captcha'] = 'Captcha nije ispravna, klikni na "Nisam robot" i pokušaj ponovo.';*/

// return a response ===========================================================

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

        if ($parrent==="zero"){ //main comment
            // prepare and bind
            $stmt = $conn->prepare("INSERT INTO comments(ventoviid, ime, poruka) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $id, $nam, $com);

            // set parameters and execute
            $id = $post_id;
            $nam = $name;
            $com = $comment;
        }

        if ($parrent>0){ //main comment
            $stmt = $conn->prepare("INSERT INTO comments(ventoviid, ime,poruka,parent_id) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $id, $nam, $com, $parr);

            // set parameters and execute
            $id = $post_id;
            $nam = $name;
            $com = $comment;
            $parr = $parrent;
        }

        if ($stmt->execute() === TRUE) {

        // if there are no errors process our form, then return a message
        // show a message of success and provide a true success variable
          $data['success'] = true;
          $data['message'] = 'Poruka poslana!';}//end if

          else{
            $errors['comment'] = 'Greska s upisom u bazu. Kontaktiraj administratora, do njega je';
            $data['success'] = false;
            $data['errors']  = $errors;
        }

    }

    // return all our data to an AJAX call
    echo json_encode($data);


$conn->close();
?>