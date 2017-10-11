<?php

// db connection
require '../connection.php';

$ip=$_SERVER['REMOTE_ADDR'];

function checkIfVoted($id)  {
    global $ip, $conn;

    $stmt = $conn->prepare("SELECT * FROM ipadr WHERE adresa = INET6_ATON(?) and vote_id=? ");
    $stmt->bind_param("ss", $ips, $ids);
    $ips=$ip;
    $ids=$id;

    $stmt->execute();
    $result = $stmt->get_result();//query

if ($result->num_rows > 0) 
    {
        return true;
    }
    else
    {
        return false;
    }
    
}

if($_POST)
{
    //get type of vote from client
    $user_vote_type = mysqli_real_escape_string($conn,$_POST["vote"]);
    $vote_type = mysqli_real_escape_string($conn,$_POST["vote_type"]);

    //get unique content ID and sanitize it
    $unique_content_id = filter_var(trim($_POST["unique_id"]),FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH);
    
    //check if its an ajax request, exit if not
    if(!isset($_SERVER['HTTP_X_REQUESTED_WITH']) AND strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
        die();
    } 

if ($vote_type==="comment")
    {
    $datatable="comments";
    }
else if ($vote_type==="post")
    {
    $datatable="ventovi";
    }

switch ($user_vote_type)
    {           
        ##### User liked the content #########
        case 'up': 
            if (checkIfVoted($unique_content_id))
            {
                header('HTTP/1.1 500 Already Voted'); //cookie found, user has already voted
                exit(); //exit script
            } 

            //increase vote by 1
            $stmt = $conn->prepare("UPDATE ".$datatable." SET vote_sum=vote_sum+1 WHERE id=?");
            $stmt->bind_param("s", $ids);
            $ids=$unique_content_id;

            $stmt->execute();

            //set voted IP
            $stmt = $conn->prepare("INSERT INTO ipadr(adresa,vote_id) VALUES (inet6_aton(?), ?)"); 
            $stmt->bind_param("ss", $ips, $ids);
            $ips=$ip;
            $ids=$unique_content_id;

            $stmt->execute();
   
            break;  


        ##### User disliked the content #########
        case 'down': 
            if (checkIfVoted($unique_content_id))
            {
                header('HTTP/1.1 500 Already Voted'); //cookie found, user has already voted
                exit(); //exit script
            } 

            //increase vote by 1
            $stmt = $conn->prepare("UPDATE ".$datatable." SET vote_sum=vote_sum-1 WHERE id=?");
            $stmt->bind_param("s", $ids);
            $ids=$unique_content_id;

            $stmt->execute();

            //set voted IP
            $stmt = $conn->prepare("INSERT INTO ipadr(adresa,vote_id) VALUES (inet6_aton(?), ?)"); 
            $stmt->bind_param("ss", $ips, $ids);
            $ips=$ip;
            $ids=$unique_content_id;

            $stmt->execute();
   
            break; 


        ##### respond votes for each content #########      
        case 'fetch':
            //get vote_up and vote_down value from db using unique_content_id
            $stmt = $conn->prepare("SELECT vote_sum FROM ".$datatable." WHERE id=? LIMIT 1"); 
            $stmt->bind_param("s", $ids);
   
            $ids=$unique_content_id;

            $stmt->execute();

            $result = $stmt->get_result();//query
            $row = $result->fetch_assoc();         

            $vote_difference  = ($row["vote_sum"])?$row["vote_sum"]:0; 
            
            //build array for php json
            $send_response = array('current_vote'=>$vote_difference);
            echo json_encode($send_response); //display json encoded values
            break;
    }
}
$conn->close();
?>