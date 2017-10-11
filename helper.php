<?php 
## Return random vent "challenge" message
function randomMsg()
{
	$messages = array(
		"Piši anonimno, Ventaj se!",
		"Imaš problem? Ventaj se!",
		"Nešto te muči? Ventaj se!",
		"'Zaboravio' si reći u lice? Ventaj se!",
		"Netko ti je ukrao biciklo? Ventaj se!",
		"Baš te briga za sve? Ventaj se!",
		"Ti si najbolji? Ventaj se!",
		"Stvari ne idu od ruke? Ventaj se!",
		"Neće biti bolje? Ventaj se!",
		"Bit će bolje? Ventaj se!",
		"Samo jednom se živi Ventaj se!",
		"Drugi te ne žele slušati? Ventaj se!",
		"Caps lock? Nema problema, Ventaj se!",
		"Nisi važan? Ventaj se!",
		"Nečeš stići na vrijeme? Ventaj se!",
		"Danas ti je super? Ventaj se!",
		"Nisi sav svoj? Ventaj se!",
		"Osječaš se dobro? Ventaj se!",
		"Ljubavni problem? Ventaj se!",
		"Financijski problem? Ventaj se!",
		"Grize te savjest? Ventaj se!",
		"Olakšaj dušu, Ventaj se!");

	return $messages [array_rand($messages, 1)];
}


## Show time ago in a friendly manner
function nicetime($date)
{
    date_default_timezone_set("Europe/Sarajevo"); 

    $periods         = array("sec", "min", "sat", "dan", "tjedan", "mjesec", "godina", "dekada");
    $lengths         = array("60","60","24","7","4.35","12","10");
    
    $now             = time();
    $unix_date         = strtotime($date);
    
       // check validity of date
    if(empty($unix_date)) {    
        return "Pogresan datum";
    }

    // is it future date or past date
    if($now > $unix_date) {    
        $difference     = $now - $unix_date;    
    } else {
        return "Greška s datumom";
    }
   
    for($j = 0; $difference >= $lengths[$j] && $j < count($lengths)-1; $j++) {
        $difference /= $lengths[$j];
    }
    
    $difference = round($difference);
    
    
    //add sufixes
    if (($periods[$j]=="dan" && $difference > 1)|| ($periods[$j]=="mjesec" && $difference > 1 && $difference < 5) || ($periods[$j]=="sat" && $difference > 1 && $difference < 5) || ($periods[$j]=="sat" && $difference > 21))
        $periods[$j].= "a";

    if (($periods[$j]=="mjesec" && $difference > 4) || ($periods[$j]=="sat" && $difference > 4 && $difference < 21) )
        $periods[$j].= "i";

    if ($periods[$j]=="godina" && $difference > 1 && $difference < 5)
        $periods[$j]= "godine";

    if ($periods[$j]=="tjedan" && $difference > 1)
        $periods[$j]= "tjedna";
    
    if ($periods[$j]=="sec" || $periods[$j]=="min")
        $periods[$j].= "...";


    return "$difference $periods[$j]";
}


?>
