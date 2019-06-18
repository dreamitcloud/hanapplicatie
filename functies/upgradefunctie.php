<?php  



$errorupgraden = "";
$bank = "";
$rekeningnummer = "";
$controleoptie = "";
$creditcard = null;


if(isset ($_POST['bank'])){
    $bank = $_POST['bank'];
    $rekeningnummer = $_POST['rekeningnummer'];
    $creditcard = $_POST['creditcardnummer']; 
    $controleoptie = $_POST['verificatiemethode'];
    $username   =   $_SESSION['gebruikersnaam'];
    
   
    
    if(!preg_match('/^[0-9]*$/', $creditcard)){
        $errorupgraden = "geen geldig creditcard!";
    }elseif(strlen($creditcard) > 19){
        $errorupgraden = "het creditcardnummer is te lang!";
    }elseif (($_POST['verificatiemethode'] == 'Post') && (!empty($creditcard))){
       $errorupgraden = "Creditcardnummer hoeft niet ingevuld te worden als er voor de optie post gekozen is."; 
    }elseif (($_POST['verificatiemethode'] == 'Creditcard') && (empty($creditcard))){
       $errorupgraden = "Creditcardnummer moet ingevuld worden als er gekozen is voor de optie Creditcard!"; 
        
    }elseif(($_POST['verificatiemethode'] == 'Creditcard') && (!empty($creditcard))){
     $welnietindicator = ("update gebruiker set welnietindicator = 1 where gebruikersnaam = ?;");
     queryDatabase($welnietindicator, array($username));
     $verkopertabelcreditcard = "insert into verkoper (gebruikersnaam, bank, rekeningnummer, controleoptie, creditcardnummer) values (?,?,?,?,?)";
    
    queryDatabase($verkopertabelcreditcard, array($username,$bank,$rekeningnummer,$controleoptie,$creditcard));
     header("Location: ./index.php");
        
    }elseif(($_POST['verificatiemethode'] == 'Post') && (empty($creditcard))){
    $welnietindicator = ("update gebruiker set welnietindicator = 1 where gebruikersnaam = ?;");
     queryDatabase($welnietindicator, array($username));
    $verkopertabelpost = "insert into verkoper (gebruikersnaam, bank, rekeningnummer, controleoptie) values (?,?,?,?)";
    
                    queryDatabase($verkopertabelpost, array($username, $bank, $rekeningnummer, $controleoptie));
                     header("Location: ./index.php");
                           
                }
    $errorupgraden = "<p style='color:red;'>$errorupgraden</p>";
}


?>