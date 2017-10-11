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
<script src="ventit.js"></script>
<script>
$(document).ready(function(){
//hide menu on document click
$(document).on('click',function(){
    $('.collapse').collapse('hide');
})
//reply comment hide-show
    $('#vent-it').click(function(){
        $('.hide-form').slideToggle("fast");
    });
    });//end function

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

<!--content START-->
<div class="container" style="max-width: 700px;padding-bottom: 50px;">
<div class="row">
<div class="col-xs-12">
   <h3><strong> Svi korisnici ventil.me prihvataju sljedeća pravila i uslove:</strong></h3>
<p>
Poruke i komentari odražavaju stavove njihovih autora, a ne nužno i stavove ventil.me. Zadržavamo pravo da obrišemo poruku ili komentar bez najave i objašnjenja. Zbog velikog broja komentara ventil.me nije dužan obrisati sve komentare koji krše pravila. Kao čitalac i/ili korisnik također prihvatate mogućnost da među komentarima mogu biti pronađeni sadržaji koji mogu biti u suprotnosti sa vašim vjerskim, moralnim i drugim načelima i uvjerenjima.</p>
<p>
Kao korisnik ventil.me  servisa, obvezujete se da ćete objavljivati isključivo materijale koji ne krše zakonske propise Bosne i Hercegovine kao i međunarodne zakone. To znači da ste odgovorni ukoliko sadržaj koji objavljujete krši autorska i druga vlasnička prava. Uz ostalo, suglasni ste da kao korisnik nećete:</p>
<ul>

<li>objavljivati i/ili prenositi zakonom zaštićene sadržaje;</li>
<li>objavljivati i/ili slati vulgarne, klevetničke, pornografske, uvredljive, prijeteće, rasističke, fašističke i šovinističke sadržaje - tekstove;</li>
<li>objavljivati i/ili slati reklamne ili promotivne materijale;</li>
<li>objavljivati i/ili slati tajne i/ili zaštićene podatake neke fizičke ili pravne osobe;</li>
<li>objavljivati, prenositi i/ili slati informacije koje krše postojeće zakone Bosne i Hercegovine i/ili međunarodne zakone;</li>
<li>vrijeđati druge korisnike;</li>
<li>otvarati više tema sa istim sadržajem;</li>
<li> na bilo koji drugi način zloupotrebljavati ventil.me.</li>
</ul>
<ul>
<br>
<p>Rad administratora i moderatora:</p>
<li> administrator/moderator ima pravo pobrisati svaki sadržaj objavljen na stranici, bez najave i objašnjenja;</li>
<li> administratori/moderatori nisu dužni objašnjavati zašto je neka tema obrisana ili cenzurisana od strane moderatora ili administratora;</li>
<li>administrator/moderator ima pravo ali ne i obavezu brisanja poruka koji su izvan pravila i uslova korištenja;</li>
<li> administrator/moderator zbog obimnosti posla nije u mogućnosti niti je dužan obrisati sve poruke koje krše pravila i uslove korištenja;</li>
<li> administrator/moderator nije dužan niti je u mogućnosti odmah reagovati i ukloniti poruke koje krše pravila i uslove korištenja.</li>
</ul>

<p>Kao korisnik servisa, odgovorni ste za zaštitu svoje privatnosti kroz materijale koje objavljujete na stranici i kroz kontakte koje ostvarite posredstvom servisa.</p>
<p>Svi podaci  koje uneste se pohranjuju u nasoj bazi podataka. Niti jedan podatak ne smije i neće biti dan na uvid trećoj osobi (osim ukoliko je to zakonom zahtijevano). Ventil.me  se ne može držati odgovornim ukoliko uslijed hakerskog napada dođe do uvida u/otkrivanja podataka.
Vi zadržavate svu odgovornost za sadržaj Vaših poruka i odričete svu odgovornost stranice ventil.me po bilo kojem osnovu vezanim za Vaše poruke.</p>
<p>Ventil.me  također zadržava pravo da otkrije informacije koje posjedujemo o Vama u slučaju pritužbe ili pravne akcije koja rezultuje iz poruka koje ste slali na web stranici.</p>
<p><strong>Ventil.me nije odgovoran za sadržaj poruka objavljenih na stranici. Poruke na stranici odražavaju isključivo stavove njihovih autora, a nikako i stavove web stranice ventil.me.</strong></p>

<h3>
Politika privatnosti</h3>
<p>
Ventil.me sakuplja odredene podatke o svojim korisnicima, ali se obavezuje da ih nece dalje distribuirati osim pod specijalnim uslovima koji su niže navedeni.
Imajte na umu da je politika privatnosti dokument koji podliježe stalnoj promjeni, te Vam savjetujemo da regularno provjeravate da li je došlo do izmjena.</p>

<p>
Koje tacno podatke sakuplja ventil.me?</p>
<p>
U trenutnoj fazi ventil.me ne sakuplja nikakve osobne podatke o korisniku, izuzev onih koje se zapisuju u log fajlovima našeg servera, poput IP adrese, vremena posjete, korisnickog pretraživaca i slicno.</p>
<p>Par informacija kolacicima (cookies) i podacima pohranjenim u korisnickoj sesiji
Kolacic (eng. cookie) je mali tekstualni fajl koji se nalazi na kompjuteru korisnika i cuva odredene informacije.
Ventil.me u kolacice koristi samo za sprecavanje višestrukog glasanja na poruke.</p>
<h4>Informacije pohranjene u log fajlovima</h4>
<p>
Log fajlove pravi naš web server i u njih zapisuje osnovne podatke o svakoj posjeti stranice. Tipicne informacije koje se zapisuju u log fajl jesu IP adresa posjetitelja, datum i vrijeme posjete, verzija operativnog sistema i web preglednika, te adresa stranice koja je korisnika dovela na našu stranicu. Sve ove informacije se preuzimaju iz zaglavlja zahtjeva vašeg web preglednika, te su dostupne svakoj web stranici koju posjecujete.</p>
<p>Ventil.me ove informacije koristi prvenstveno za pripremanje statistickih izvještaja koji nam pomažu da poboljšamo naše usluge i približimo ih našim korisnicima.</p>
<p>Ova pravila i uslovi korištenja podložni su izmjenama i dopunama, a važećom verzijom se smatra ona trenutno izložena na stranicama.</p>

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