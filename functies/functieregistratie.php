<?php


$errorregistreren='';
$erroractiveren='';
$gebruikersnaam = '';
$emailadres = '';
$controlecode = '';
$wachtwoord = '';
$wachtwoord2 = '';
$voornaam = '';
$achternaam = '';
$geboortedatum = '';
$landnaam = '';
$plaatsnaam = '';
$postcode = '';
$adresregel = '';
$adresregel2 = '';
$telefoonnummer = '';
$telefoonnummer2 = '';
$vraagnummer = '';
$antwoord = '';

if(isset($_POST['gebruikersnaam'])){
$gebruikersnaam =  $_POST['gebruikersnaam'];
$emailadres = $_POST['emailadres'];
$controlecode = $_POST['controlecode'];
$wachtwoord = $_POST['wachtwoord'];
$wachtwoord2 = $_POST['wachtwoord2'];
$voornaam = $_POST['voornaam'];
$achternaam = $_POST['achternaam'];
$geboortedatum = $_POST['geboortedatum'];
$landnaam = $_POST['landnaam'];
$plaatsnaam = $_POST['plaatsnaam'];
$postcode = $_POST['postcode'];
$adresregel = $_POST['adresregel'];
$adresregel2 = $_POST['adresregel2'];
$telefoonnummer = $_POST['telefoonnummer'];
$telefoonnummer2 = $_POST['telefoonnummer2'];
$vraagnummer = $_POST['vraagnummer'];
$antwoord = $_POST['antwoord'];
  
$c_code = "select activatiecode from activeren where emailadres=?";
$c_datum = "select datum from activeren where emailadres=?";
$gebruikernaamtest = ("SELECT gebruikersnaam FROM gebruiker WHERE gebruikersnaam = ?");

/////////////////////////////////////////////////

	$v_code = queryDatabase($c_code, array($emailadres));
    $v_datum = queryDatabase($c_datum, array($emailadres));
    $testgebruikernaam = queryDatabase($gebruikernaamtest, array($gebruikersnaam));
    
    $g_code = $v_code[0]['activatiecode'];
    $g_datum = $v_datum[0]['datum'];
    $hourdiff = round((strtotime(date("y-m-d H:i:s")) - strtotime($g_datum))/3600, 1);
    
    $time = strtotime("-18 year", time());
    $date = date("Y-m-d", $time);
     
   
    
/////////////////////////////////////////////////
 if( strlen( $gebruikersnaam) < 3 && strlen($gebruikersenaam) >12){
     $errorregistreren = 'De gebruikersnaam moet minstens 3 en maximaal 12 characters lang zijn!';

 }elseif ($_POST['wachtwoord']!= $_POST['wachtwoord2']) {	       
    $errorregistreren = 'Wachtwoord komt niet overeen!';
	
}elseif( strlen( $wachtwoord) < 7){
     $errorregistreren = 'Wachtwoord moet minstens 7 characters lang zijn!';
        
}elseif( $geboortedatum >= $date){
     $errorregistreren = 'je moet ouder dan 18 jaar zijn!';
        
}elseif ($g_code != $_POST['controlecode']){
     $errorregistreren = 'Code klopt niet!';
        
}elseif( $hourdiff >= 4) {
     $errorregistreren = 'Code is verlopen!';
        
 }elseif(count($testgebruikernaam) > 0){
     $errorregistreren = 'Gebruikersnaam is al in gebruik!';
   
	}else {
 //als er geen errors zijn wordt de gebruiker in de database geplaatst. 
     $hashed_wachtwoord = password_hash($wachtwoord, PASSWORD_DEFAULT);
$sql = "insert into gebruiker (gebruikersnaam, wachtwoord, voornaam, achternaam, emailadres, geboortedatum, vraagnummer, antwoordtekst, adresregel, adresregel2, plaatsnaam, postcode, landnaam) 
		values (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,?) ";
queryDatabase($sql, array(htmlentities($gebruikersnaam), htmlentities($hashed_wachtwoord), htmlentities($voornaam), htmlentities($achternaam), htmlentities($emailadres), htmlentities($geboortedatum), htmlentities($vraagnummer), htmlentities($antwoord), htmlentities($adresregel), htmlentities($adresregel2), htmlentities($plaatsnaam), htmlentities($postcode), htmlentities($landnaam)));
     
$telefoon = "insert into gebruikerstelefoon (gebruikersnaam, volgordenr, telefoonnummer) values(?, 1 , ?)";
querydatabase($telefoon, array( htmlentities($gebruikersnaam),  htmlentities($telefoonnummer)));
// indien er een tweede telefoonnummer is ingevuld wordt deze ook in de database geplaatst.
if(!empty($_POST['telefoonnummer2'])){
$telefoon2 = "insert into gebruikerstelefoon (gebruikersnaam, volgordenr, telefoonnummer) values(?, 2 , ?)";
querydatabase($telefoon2, array( htmlentities($gebruikersnaam), htmlentities($telefoonnummer2)));
}
    
    
    $_SESSION['gebruikersnaam'] = $gebruikersnaam;
     $_SESSION['ingelogd']=true;
    header("Location: ./index.php");
    
       }
    
$errorregistreren = "<p style='color:red;'>$errorregistreren</p>";
   
}
if(isset($_POST['emailcode']) ){
     $emailcode = $_POST['emailcode'];
    
    
///////////////////////////////////////////////////////////    
    
    $emailcheck = ("SELECT emailadres FROM activeren WHERE emailadres = ?");
    $testcheck =queryDatabase($emailcheck, array($emailcode));
   
///////////////////////////////////////////////////////////  
   // deze functie genereert een code van 20 karakters lang. 
    function generateRandomString($length = 20) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}
    $code = generateRandomString();
/////////////////////////////////////////////////////////////
    if(!count($testcheck)){ 
      if(!filter_var($emailcode, FILTER_VALIDATE_EMAIL)) {
		$erroractiveren = 'Vul een geldig emailadres in!';
       
    }else{
          // als het emailadres in een geldig format staat mag deze in de database worden ingevuld
    $sql = "insert into activeren (emailadres, activatiecode) 
		values (?, ?) ";
          queryDatabase($sql, array($emailcode,$code));
    
        $erroractiveren = 'Code verzonden!';  
        
$to      = $emailcode; 
$subject = 'Signup | Verification';  
$message = '
 Beste klant, 
    hierbij uw activatiecode:  '.$code.'
 voer de code in bij het registeren van uw account

 
-------------------------';
 

 
               
$headers = 'From:noreply@eenxanderx.com' . "\r\n"; 
mail($to, $subject, $message, $headers); 
   
      }
    }else{
/////////////////////////////////////////////////////////
    $c_datum = "select datum from activeren where emailadres=?";
    $v_datum = queryDatabase($c_datum, array($emailcode));
    $g_datum = $v_datum[0]['datum'];
    $hourdiff = round((strtotime(date("y-m-d H:i:s")) - strtotime($g_datum))/3600, 1);
////////////////////////////////////////////////////////
      
    if( $hourdiff >= 4) {
    $updaten = "update activeren SET activatiecode = ?, datum = GETDATE() WHERE emailadres =?";  
    queryDatabase($updaten, array($code, $emailcode));

$to      = $emailcode; 
$subject = 'Signup | Verification';  
$message = '
 Beste klant, 
    hierbij uw activatiecode:  '. $code .'
 voer de code in bij het registeren van uw account

 
-------------------------';
 

 
               
$headers = 'From:noreply@eenxanderx.com' . "\r\n"; 
mail($to, $subject, $message, $headers); 
   
     $erroractiveren = 'Code opnieuw verzonden!';      
   
    }else{
        $erroractiveren = 'U heeft nog een geldige code, controleer uw email!';      
       }
  }
    $erroractiveren = "<p style='color:red;'>$erroractiveren</p>";
}
?>  


