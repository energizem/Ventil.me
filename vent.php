<?php 
require '../connection.php';
include 'helper.php';
?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Piši anonimno, Ventaj se!</title>

<?php
$post_id = ( isset( $_GET['id'] ) && is_numeric( $_GET['id'] ) ) ? mysqli_real_escape_string($conn, $_GET['id']) : 0;

    $stmt = $conn->prepare("SELECT * FROM ventovi WHERE id = ? LIMIT 1 ");
    $stmt->bind_param("s", $id);

    $id = $post_id;
    $stmt->execute();

    $result = $stmt->get_result();

    $row = $result->fetch_assoc();

?>

<meta name="viewport" content="width=device-width, initial-scale=1">

<meta property="og:url"                content="http://www.ventil.me/vent.php?id=<?=$post_id?>" />
<meta property="og:type"               content="blog" />
<meta property="og:image"              content="http://ventil.me/images/fbbg.png" />
<meta property="og:title"              content="<?=htmlspecialchars($row['poruka'],ENT_QUOTES)?>" />

<link rel="icon" type="image/ico" href="favicon.ico?v_2" sizes="16x16">

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>

<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="css/bootstrap.min.css">

<!-- Optional theme -->
<link rel="stylesheet" href="css/bootstrap-theme.min.css">

<!-- Latest compiled and minified JavaScript -->
<script src="js/bootstrap.min.js"></script>
<!-- Custom CSS -->
<link rel="stylesheet" href="css/stil.css">
<!--Captcha hidden<script src="https://www.google.com/recaptcha/api.js?onload=CaptchaCallback&render=explicit&hl=hr" async defer></script>-->
 <script src="https://www.google.com/recaptcha/api.js?hl=hr"></script> 
<script src="ventit.js"></script>
<script src="voting.js"></script>
<script>
//generate unique captchas
/*Captcha hiddenvar CaptchaCallback = function() {
    jQuery('.g-recaptcha').each(function(index, el) {
        var widgetId = grecaptcha.render(el, {'sitekey' : '6LcRsRUUAAAAAMffkTt537yQri0M4p2tZAk_T7xQ'});
        jQuery(this).attr('data-widget-id', widgetId);
    });
};*/

$(function(){
//hide menu on document click
$(document).on('click',function(){
    $('.collapse').collapse('hide');
})

//Ventaj se! hide-show
        $('#vent-it').click(function(){
        $('.hide-form').slideToggle("fast");
    });

    //reply comment hide-show
    $('.reply-comment').on('click', function(e){
        e.preventDefault();
        $(this).next('.hide-form2').slideToggle();
    });

    $('form').submit(function(event) {
        $('.alert').remove(); //remove previuos alert 
        $('.name-group').removeClass('has-error'); // remove the error class
        $('.comment-group').removeClass('has-error'); // remove the error class 

        var form = $(this); //store current form
// process the form
$.ajax({
    type        : 'POST', 
    url         : 'comment_process.php', 
    data        : $(this).serialize(), 
    dataType    : 'json'
})

    .done(function(data) { 

//reset current captcha
   //Captcha hidden grecaptcha.reset(jQuery(form).find('.g-recaptcha').attr('data-widget-id'));

        // handle errors and validation messages
        if ( ! data.success) {

            // handle errors for name ---------------
            if (data.errors.name) {
                form.find('.name-group').addClass('has-error'); // red input
                form.find('.result').append('<div class="alert alert-danger">' + data.errors.name + '</div>');
            }

            // handle errors for comment ---------------
            if (data.errors.comment) { 
                form.find('.comment-group').addClass('has-error'); // red input
                // add the error class to show red input
                form.find('.result').append('<div class="alert alert-danger">' + data.errors.comment + '</div>');
            }

           /*Captcha hidden if (data.errors.captcha) { 
                form.find('.g-recaptcha').addClass('has-error'); // red input
                form.find('.result').append('<div class="alert alert-danger">' + data.errors.captcha + '</div>'); // add the actual error message under our input
            }*/

        } else {

            //show the success message!
            form.find('.result').append('<div class="alert alert-success">' + data.message + '</div>');
            window.setTimeout(function(){location.reload()},2000);
        }
    });
        // stop the form from submitting the normal way and refreshing the page
        event.preventDefault();
    });

      });
 </script>

</head>

<body id="top">
<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/bs_BA/sdk.js#xfbml=1&version=v2.8";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>

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
<?php


if ( $post_id != 0 ){



if ($result->num_rows > 0) {
    // output data of each row
?>

<div class="container" style="max-width: 700px;">

<!-- Show single vent -->
<div class="row">
    <div class="panel panel-default">
        <div class="panel-heading text">

                <a href='vent.php?id=<?=$row['id']?>'>#<?=$row['id']?> <?=htmlspecialchars($row['ime'],ENT_QUOTES)?></a> prije: <?=nicetime(htmlspecialchars($row['reg_date']));?>
        </div> <!--end panel heading-->
        <div class="panel-body wrap-it">

                <a href='vent.php?id=<?=$row['id']?>' class="vent-link" style="font-size: 16px;"><?=htmlspecialchars($row['poruka'],ENT_QUOTES)?></a>
        </div><!--end panel body-->    
        <div class="panel-footer text" style="padding-bottom: 23px;">
            <!-- voting markup DON'T remove main_post class-->
            <div class="col-xs-4 voting_wrapper main_post" id="<?=$row['id']?>">
                <div class="voting_btn">
                    <div class="up_button">&nbsp;</div><span class="vote_score">0</span>
                </div>
                <div class="voting_btn">
                    <div class="down_button">&nbsp;</div>
                </div>
            </div>
        <!-- voting markup end -->
            <div class="col-xs-8">
                <div class="fb-share-button pull-right" 
                    data-href="http://www.ventil.me/vent.php?id=<?=$post_id?>" 
                    data-layout="button_count">
                </div>
            </div>
        </div><!-- end footer -->
    </div><!-- end main panel -->
</div><!-- end row -->
<!-- end single vent show -->

<?php
### DISPALY COMMENTS ###
$data = array();
$index = array();

    $stmt = $conn->prepare("SELECT * FROM comments where ventoviid=? order by vote_sum desc");
    $stmt->bind_param("s", $id);

    $id = $post_id;
    $stmt->execute();

    $result = $stmt->get_result();

##assign values from DB to Arrays
if ($result->num_rows > 0) {
    // output data of each row
    while ($row = $result->fetch_assoc()) {
    $id = $row["id"]; //comment id
    $parent_id = $row["parent_id"] === NULL ? "NULL" : $row["parent_id"];// if parent_id=null assign null, else assign parrent_id
    $data[$id] = $row; //array with comment id as key gets row
    $index[$parent_id][] = $id;
}
    
    ##show number of comments on vent    
    $stmt = $conn->prepare("SELECT COUNT(ventoviid) as totalComments FROM comments WHERE ventoviid=?");

    $stmt->bind_param("s", $ids);

    $ids=$post_id;

    $stmt->execute();

    $result2 = $stmt->get_result();//query
    $row2 = $result2->fetch_assoc();
    $total_comments=$row2["totalComments"];

 echo "<h4>Komentara&nbsp;<span class='badge' style='font-size:18px;'>".htmlspecialchars($total_comments,ENT_QUOTES)."</span></h4>";
} else {
    echo "<h4>Nema komentara</h4>";
}

//start from null array postion, if array have childs foreach them
function display_child_nodes($parent_id, $level)
{
    global $data, $index, $post_id; // reference to global arrays outside function

    $parent_id = $parent_id === NULL ? "NULL" : $parent_id; // assign null, on next recursive call assign parent ID

    if (isset($index[$parent_id])) { //doens't compute if NULL, does compute if "NULL"
        foreach ($index[$parent_id] as $id) { 
            ###### CHILD ######
            if ($data[$id]["parent_id"]>0) 
            { ?>
<div class="row">
    <div class="col-xs-1"></div>
    <div class="col-xs-11"> 
        <div class="panel panel-default">
            <div class="panel-heading text">        
                    <?=htmlspecialchars($data[$id]["ime"],ENT_QUOTES)?> prije: <?=nicetime(htmlspecialchars($data[$id]["reg_date"]),ENT_QUOTES)?>
            </div>
            <div class="panel-body wrap-it">
                    <?=htmlspecialchars($data[$id]["poruka"],ENT_QUOTES)?>
            </div>
            <div class="panel-footer">
            <!-- voting markup DON'T remove main_post class-->
                <div class="voting_wrapper" id="<?=$data[$id]["id"]?>" style="padding-left: 10px;padding-top: 2px;">
                    <div class="voting_btn">
                        <div class="up_button">&nbsp;</div><span class="vote_score">0</span>
                    </div>
                    <div class="voting_btn">
                        <div class="down_button">&nbsp;</div>
                    </div>
                </div>
        <!-- voting markup end -->
            </div>
        </div><!-- End panel -->
    </div><!-- End xs 11 -->
</div><!-- End row -->
    <?php }
            ####### MAIN COMMENT #######
            else { ?>

<div class="row">
    <div class="panel panel-default">
        <div class="panel-heading text">
                <?=htmlspecialchars($data[$id]["ime"],ENT_QUOTES)?> prije: <?=nicetime(htmlspecialchars($data[$id]["reg_date"],ENT_QUOTES))?>
        </div>
        <div class="panel-body wrap-it">
            <?=htmlspecialchars($data[$id]["poruka"],ENT_QUOTES)?>
        </div>
        <div class="panel-footer">
         <!-- voting markup DON'T remove main_post class-->
                    <div class="col-xs-4 voting_wrapper" id="<?=$data[$id]["id"]?>">
                        <div class="voting_btn">
                            <div class="up_button">&nbsp;</div><span class="vote_score">0</span>
                        </div>
                        <div class="voting_btn">
                            <div class="down_button">&nbsp;</div>
                        </div>
                    </div>
        <!-- voting markup end -->
                    <a href="" class="reply-comment text"><i class="glyphicon glyphicon-share-alt gly-rotate-270"></i> Odgovori </a>

                    <form action="comment_process.php" method="POST" class="hide-form2"  style="padding: 0px 25px"> 
                        <!-- name -->
                        <div class="name-group input-group form-group">
                            <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                            <input type="text" class="form-control" name="name" value="Anonimno">
                        </div>
                        <!-- comment -->
                        <div class="comment-group input-group form-group">
                            <span class="input-group-addon"><i class="glyphicon glyphicon-comment"></i></span>
                            <textarea rows="5" class="form-control" name="comment" placeholder="Komentar" ></textarea>
                        </div>
                        <input type="hidden" name="parrent" value="<?=$data[$id]["id"]?>">
                        <input type="hidden" name="comment_post_id" value="<?=$post_id?>">
                        <!--<div class="g-recaptcha form-group" data-sitekey="6LcRsRUUAAAAAMffkTt537yQri0M4p2tZAk_T7xQ" data-callback="correctCaptcha" align="center"></div>-->

                        <button type="submit" class="btn btn-success">Pošalji <span class="fa fa-arrow-right"></span></button>
                         <div class="result"></div> 
                    </form>
        </div> <!--end panel footer -->
    </div> <!--end  main panel -->
</div><!-- End row -->


    <?php }
        display_child_nodes($id, $level + 1); //go until there is no more IDs in index array

        }//end for

    }//end if
}

//generate all comments and subomments for current post
display_child_nodes(NULL, 0);

$conn->close();
?>
 <!-- comment section end -->

<!-- main comment section start -->
<h4 style="margin-top: 20px">Komentiraj vent</h4>

    <form action="comment_process.php" method="POST" class="vent-form" style="margin-bottom: 50px">  
        <!-- name -->
        <div class="name-group form-group input-group">
            <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
            <input type="text" class="form-control" name="name" value="Anonimno">
        </div>
        <!-- comment -->
        <div class="comment-group form-group input-group">
            <span class="input-group-addon"><i class="glyphicon glyphicon-comment"></i></span>
            <textarea rows="5" class="form-control" name="comment" placeholder="Komentar"></textarea>
        </div>
        <input type="hidden" name="parrent" value="zero">
        <input type="hidden" name="comment_post_id" value="<?=$post_id?>">

        <!--Captcha hidden<div class="g-recaptcha form-group" data-sitekey="6LcRsRUUAAAAAMffkTt537yQri0M4p2tZAk_T7xQ" data-callback="correctCaptcha" align="center"></div>-->
        <button type="submit" class="btn btn-success btn-block" style="line-height: 35px">Pošalji!</button>
         <div class="result"></div>
    </form>
<!-- main comment section end -->

</div> <!--Container END-->
<?php  
} else { // else vent is numeric value but not exists in db
    echo "<div class='result'></div> "."Vent nije pronadjen";
}
}//end post_id if
else {// else non numeric value
    echo "<div class='result'></div> "."Vent nije pronadjen";
}
?>

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