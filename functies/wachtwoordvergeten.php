<?php
$bericht = '';

if(isset($_POST['vergeten'])){
$gebruikersnaam = $_POST['vergeten'];
   
 $sql = queryDatabase("select tekstvraag from vraag where vraagnummer in (select vraagnummer from gebruiker where gebruikersnaam = ?)", array($gebruikersnaam));
  
$vraaggebruiker = $sql[0]['tekstvraag']; 
    
     
 
} 
  if(isset($_POST['vraag'])){  
     $gebruikersnaam = $_POST['gebruikertest'];
   $antwoord = queryDatabase("select antwoordtekst from gebruiker where gebruikersnaam = ?", array($gebruikersnaam));
   $antwoordgebruiker = $antwoord[0]['antwoordtekst'];

if ($antwoordgebruiker != $_POST['vraag']){
     $bericht = 'Antwoord op de geheime vraag klopt niet!';
  }else{
    
    ///////////////////////////////////////////////////////////  
    
    function generateRandomString($length = 8) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}
    $wachtwoord = generateRandomString();
/////////////////////////////////////////////////////////////
    
    $updaten = "update gebruiker SET wachtwoord = ?  WHERE gebruikersnaam =?";  
    queryDatabase($updaten, array($wachtwoord,$gebruikersnaam));
    
     $bericht = "Er is een mail met een nieuw wachtwoord naar uw emailadres verstuurd.";
    
    $email = queryDatabase("select emailadres from gebruiker where gebruikersnaam = ?", array($gebruikersnaam));
    $emailgebruiker = $email[0]['emailadres'];
    
/////////////////////////////////////////////////////////////
    
$to      = $emailgebruiker; 
$subject = 'Nieuw wachtwoord voor '.$gebruikersnaam;  
$message = '
 Beste '.$gebruikersnaam.',
 
 hierbij uw nieuwewachtwoord:  '. $wachtwoord .'
 Met dit nieuwe wachtwoord kunt u weer gebruik maken van uw account.

 
-------------------------

';
 

 
               
$headers = 'From:noreply@eenxanderx.com' . "\r\n"; 
mail($to, $subject, $message, $headers); 
    
    
}
}






?>