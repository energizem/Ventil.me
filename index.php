<?php 
require '../connection.php';
include 'helper.php';
?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Piši anonimno, Ventaj se!</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

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

 <script src="https://www.google.com/recaptcha/api.js?hl=hr"></script> 
<script src="voting.js"></script>
<script src="ventit.js"></script>
<script>
$(document).ready(function(){

//hide menu on document click
$(document).on('click',function(){
    $('.collapse').collapse('hide');
})

//shorten long vents
    var showChar = 1250;
    $('.vent-link').each(function() {
        var content = $(this).html();
        if(content.length > showChar) {
            var c = content.substr(0, showChar);
            var h = content.substr(showChar-1, content.length - showChar);
            var html = c + '<span> ...&nbsp;</span><span class="more-content"><span>' + h + '</span>&nbsp;&nbsp;<a href="" class="more-link"> Pročitaj sve</a></span>';
            $(this).html(html);
        }
    });

    $(".more-link").click(function(){
        if($(this).hasClass("less")) {
            $(this).removeClass("less");
            $(this).html("Pročitaj sve");
        } else {
            $(this).addClass("less");
            $(this).html("sakrij");
        }
        $(this).parent().prev().toggle();
        $(this).prev().toggle();
        return false;
    });

//Ventaj se! hide-show
    $('#vent-it').click(function(){
        $('.hide-form').slideToggle("fast");
    });
    });
</script>

</head>
<?php
$results_per_page = 15; // number of results per page
$trending_date=  date('Y-m-d', strtotime('-4 days'));

if (isset($_GET["page"])) // provjerava ako je varijabla postavljana i nije NULL
	{ 
	$page  = $_GET["page"]; //kada page nije null ili postavljena, dobij naziv iz GET page
	} 
else { 
	$page=1; 
	};
$start_from = ($page-1) * $results_per_page; //trenutna stranica prikaza * broj prikaza
?>
<!-- Vent yourself start -->
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
        <li <?=isset($_GET['orderBy']) && $_GET['orderBy']=='novi' ? 'class="active"' : '';?>
            <?=!isset($_GET['orderBy']) ? 'class="active"' : '';?>><a href="index.php?orderBy=novi">Novi</a></li>
        <li <?=isset($_GET['orderBy']) && $_GET['orderBy']=='popularno' ? 'class="active"' : '';?>><a href="index.php?orderBy=popularno">Popularni</a> </li>
        <li <?=isset($_GET['orderBy']) && $_GET['orderBy']=='comment' ? 'class="active"' : '';?>> <a href="index.php?orderBy=comment">Top komentirani</a></li>
        <li <?=isset($_GET['orderBy']) && $_GET['orderBy']=='trend' ? 'class="active"' : '';?>><a href="index.php?orderBy=trend">Trending</a></li>
      </ul>
    </div>
  </div>
</nav>

    <form action="vent_process.php" method="POST"  class="hide-form text-left vent-form main-vent">
        <h4 style="color: #000;text-shadow: none">Piši što god ti je na umu</h4>
        <!-- NAME -->
        <div class="form-group input-group name-group">
            <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
            <input id="msg" type="text" class="form-control" name="name" Value="Anonimno">
        </div>

        <!-- vent -->
        <div class="form-group input-group vent-group">
            <span class="input-group-addon"><i class="glyphicon glyphicon-comment"></i></span>
            <textarea rows="5" class="form-control" name="vent" placeholder="U čemu je stvar?" ></textarea>
        </div>


        <div class="g-recaptcha form-group" align="center" data-sitekey="6LcRsRUUAAAAAMffkTt537yQri0M4p2tZAk_T7xQ" style="display: none"></div>

        <div class="result"></div>
        <button type="submit" class="btn btn-success btn-block" style="line-height: 35px">Pošalji!</button>
    </form>
<!-- Vent display -->
<?php 

//order by most popular
if (isset($_GET['orderBy']) && $_GET['orderBy']==="popularno") {
    $order="popularno"; 
    $stmt = $conn->prepare("SELECT * FROM ventovi ORDER BY vote_sum DESC LIMIT ?, ? ");
    $stmt->bind_param("ss", $start, $results);

    $start=$start_from;
    $results=$results_per_page;

    $stmt->execute();

}
else if (isset($_GET['orderBy']) && $_GET['orderBy']==="comment" )
{
    $order="comment";

    $stmt = $conn->prepare("SELECT p.*
        FROM ventovi p
        LEFT JOIN (
            SELECT ventoviid, COUNT(*) cnt
            FROM comments
            GROUP BY ventoviid)
        c ON p.id = c.ventoviid
        ORDER BY c.cnt DESC, p.id DESC LIMIT ?, ?");
    $stmt->bind_param("ss", $start, $results);    
    $start=$start_from;
    $results=$results_per_page;

    $stmt->execute();

}
else if (isset($_GET['orderBy']) && $_GET['orderBy']==="trend") {
$stmt = $conn->prepare("SELECT p.*
        FROM ventovi p
        LEFT JOIN (
            SELECT ventoviid, COUNT(*) cnt
            FROM comments
            GROUP BY ventoviid)
        c ON p.id = c.ventoviid
        WHERE p.reg_date >= ? and (p.vote_sum>5 or c.cnt>0) ORDER BY p.vote_sum DESC LIMIT ?, ?");
    $stmt->bind_param("sss", $date, $start, $results);  
    $date=$trending_date;  
    $start=$start_from;
    $results=$results_per_page;

    $stmt->execute();

// definicija trendinga:
/* u zadnja 4 dana postovi s najvise reputacije prvi parametar, zatim postovi s najvise komentara. AKo neki post ima isti broj reputacije sortirati po ID-u.*/ 

}
else{
     $order="novi";
     $stmt = $conn->prepare("SELECT * FROM ventovi ORDER BY ID DESC LIMIT ?, ?");

    $stmt->bind_param("ss", $start, $results);

    $start=$start_from;
    $results=$results_per_page;

    $stmt->execute();
}
$result = $stmt->get_result();//query
?>
 <!-- vent display start -->
<div class="container" style="max-width: 700px;">
    <div class="row">
<?php 
if ($result->num_rows > 0){
 while($row = $result->fetch_assoc()) {

?> 

        <div class="panel panel-default">
            <div class="panel-heading text">
                <a href='vent.php?id=<?=$row['id']?>'>
                		#<?=$row['id']?> <?=htmlspecialchars($row['ime'],ENT_QUOTES)?> prije: <?=nicetime(htmlspecialchars($row['reg_date']));?>
                </a>
            </div> <!--end panel heading-->

            <div class="panel-body wrap-it">
                <a href='vent.php?id=<?=$row['id']?>' class="vent-link"><?=htmlspecialchars($row['poruka'],ENT_QUOTES)?></a>
            </div><!--end panel body-->   
            <div class="panel-footer text" style="padding-bottom: 25px;">
        <!-- voting markup -->
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
                <?php
                ##show number of comments on vent
                $realID=$row['id'];
                
                $stmt = $conn->prepare("SELECT COUNT(ventoviid) as totalComments FROM comments WHERE ventoviid=?");

                $stmt->bind_param("s", $ids);

                $ids=$realID;

                $stmt->execute();

                $result2 = $stmt->get_result();//query
                $row2 = $result2->fetch_assoc();
                $total_comments=$row2["totalComments"];
                ?>  <a href='vent.php?id=<?=$row['id']?>'>Komentara&nbsp;<span class="badge"><?=htmlspecialchars($total_comments,ENT_QUOTES)?></span></a>  

                    <div  class="fb-share-button pull-right" 
                        data-href="http://www.ventil.me/vent.php?id=<?=$realID?>" 
                        data-layout="button_count">
                    </div>
                </div><!-- end comment/share section -->
            </div><!-- end panel footer -->
        </div><!-- end panel main -->
<?php 
};//end while
}//end if
else{//display no results if thre is no rows in query
    echo"Nema rezultata";}
?> 
</div>  <!--end row -->
</div> <!--end container -->
 <!-- vent display end -->
<?php 
//create count of total vents case trending selected
if (isset($_GET['orderBy']) && $_GET['orderBy']==="trend") {

     $stmt = $conn->prepare("SELECT count(id) as total
        FROM ventovi
                LEFT JOIN (
            SELECT ventoviid, COUNT(*) cnt
            FROM comments
            GROUP BY ventoviid)
        c ON ventovi.id = c.ventoviid
        WHERE ventovi.reg_date >= ? and (ventovi.vote_sum>5 or c.cnt>0 )");

    $stmt->bind_param("s", $date);

    $date=$trending_date;
    $stmt->execute();
    } 

else{//every other select is at max ID
    $stmt = $conn->prepare("SELECT COUNT(ID) AS total FROM ventovi");
    $stmt->execute();
} 

$result = $stmt->get_result();//query
$row = $result->fetch_assoc(); //row je array u kojem su spremljeni rezulatati query-a
$total_pages = ceil($row["total"] / $results_per_page); // calculate total pages with results

if ($total_pages==1){
    echo "";
}
else{
    echo("<div align='center'><ul class='pagination pagination-sm'>");
$plus_pages=$page+2;
$minus_pages=$page-2;
if ($page>1) {
    echo "<li><a href='index.php?page=".($page-$page+1)."&orderBy=".($order)."'>Prva</a></li>";
    echo "<li><a href='index.php?page=".($page-1)."&orderBy=".($order)."'><</a></li>";
}

for ($i=$minus_pages;$i<$page;$i++)
{
        if ($i>0){
            echo "<li><a href='index.php?page=".$i."&orderBy=".($order)."'";
            echo ">".$i."</a></li> ";}
}
for ($i=$page;$i<=$plus_pages;$i++)
{
    if ($i<=$total_pages){
                     if ($i==$page)  echo " <li class='active' >";
                     else echo " <li>";
                echo "<a href='index.php?page=".$i."&orderBy=".($order)."'";

                echo ">".$i."</a></li> ";  }  
}
if ($page<$total_pages) {
    echo "<li> <a  href='index.php?page=".($page+1)."&orderBy=".($order)."'>></a></li>"; 
    echo "<li> <a href='index.php?page=".($total_pages)."&orderBy=".($order)."'>Zadnja</a></li>";}
        echo("</ul></div>");
} //end else
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