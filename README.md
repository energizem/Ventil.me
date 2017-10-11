# README #

Vent je kodno ime za projekat.

Potreban je xampp. Uvesti mydb.sql opreko localhost/phpmyadmin za pocetnu bazu.
Ovaj file updatati, svaki put kada se urade neke izmjene na bazi.

Test folder je za testiranje stvari i drugih skripti.

Backend TODO:
[X] github public push
[X] koment sistem, da ima koment na koment, mysql hijerarhija closure ili nesto slicno
[X] koment sistem, dodati voting
[X] kada korisnik posalje vent ili komentar da se pojavi neka traka pri vrhu koja javlja ako je uspjesno poslano u bazu
[X] integrirati facebook like i/ili share
[X] sto s ../connection.php, jel ok da tamo stoji user i pass od db? Prebaceno van root direktorija + zasticeno htaccessom
[ ] jesu li js cookies potencijalna sigurnosna prijetnja?
[X] mysql injection provjeriti sve i osigurati
		X comment_process.php
		X index.php
		X vent.php
		X vent_process.php
		X vote_process.php
[X] postaviti recaptcha za slanje venta i uraditi ajax
[X] Kada se desi da vent ima mnogo komentara i/ili subkomentara stranica ce se scrollat jako dugo, pronaci neko rjesenje, ili ostaviti kako jest
[-] Ograniciti broj komentara po ip adresi na neku pristojnu kolicinu, da se sprijeci spam ako je moguce (Nisam siguran da ovo zelim)
[X] Napraviti IP adresu i za voting, kao dodatno osigranje pored cookie-a
[X] Cookie ne radi kada je site live, vise na: http://stackoverflow.com/questions/16878911/cookies-work-on-localhost-but-not-web-host(rjeseno s JS cookies)
[X] Ako je id od comment i venta isti i korisnik glasuje, nece moci glasovati za oba. Moze se rijesiti id od komenta krece od 1 mil pa da se ne sretnu isti id-ovi ili nesto drugo.
[X] Kada vent ima mnogo slova, na pocetnoj sranici skratiti text s linkom procitaj jos gdje se otvara vetnt u svom okruzenju.
[X] Pokusati recaptcha da bude preko google servera verifikacija direktno, bez recatpchalib.php file-a
[X] Trending kada nema rezultata neka prikaze poruku. Dodan if kada je rezultat querya 0, vazi i za sve ostale filtere sortiranja
[ ] Uraditi dodatni research na temu xss, htmlspecialchars htmlputrifier, slanje ajax i xss.
[X] Ako korisnik stavi vent s "" znakovima, los je prikaz, rjesiti ili s htmlputricom (provjeriti load time, posto je ovaj lib dosta tezak) ili nesto drugo, to je zbog htmlspecialchars. Uzokovano je s mysqli_real_escape_string, nema potebe za ovom funkcijom kod prepared statementa, pa sam je uklonio.
[X] Vent i index, imaju html atribute bez ""
[X] CTRF+F _get i provjeriti sve, ako treba dodatno osiguranje
[ ] Captcha je na low postavljena u gogole adminu, staviti medium
[X] Ipadr tebelu nastimati da se brise automatski nakon 1-2 dana. MySql event rjeseno svaka 3 dana
[X] Email za kontakt unijeti ispravan kada budem imao.
[X] contact_process.php izmjeniti linije 103 112 s prigodnim informacijama kada budu bile dostupne. Google smtp ostaviti isti ali staviti pass vazeci
[X] Captcha staviti HR jezik.
[X] Broj karaktera za ime u bazi ograniciti na 12-ak, postavljeno na 16, vent 8000 comment 2000 charactera
[X] Slanje venta preko vent.php ne radi zbog vise ajax requesta.
[X] Napraviti da se menu sklanja kada se klikne bilo gdje u dokumentu
[ ] Kada je komentar poslan, da se stranica ucita na mjesto gdje je komentar
[ ] Captcha je onemugucena, sve je stavljeno u komentare pocienje s Captcha hidden, captcha za Ventaj se je samo postavljena da se ne vidi jer stvara problem kada se vuce preko importovanog .js file-a. Dodana je linija  <script src="https://www.google.com/recaptcha/api.js?hl=hr"></script>  ovo treba izbrisati i omoguciti Captcha hidden liniju ako ima.

