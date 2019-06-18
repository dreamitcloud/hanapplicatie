<?php

// als alle velden ingevuld zijn wordt er vanuit de database de gegevens van de verkoper gehaald en in variabelen gezet.
if(!empty($_POST['naam']) && !empty($_POST['email']) && !empty(  $_POST['message'] )){
    $naam = $_POST['naam'];
    $emailgebruiker = $_POST['email'];
    $bericht = $_POST['message'];
    $voorwerpnummer = $_GET['id'];
    
    
    $emailverkoper = ("SELECT emailadres, voornaam, achternaam FROM gebruiker WHERE gebruikersnaam IN (SELECT Verkoper FROM voorwerp where voorwerpnummer = ?)");
    $titeluitvoorwerp = queryDatabase("SELECT titel from voorwerp where voorwerpnummer = '?'", array($voorwerpnummer)); 
    
    $q_verkoper = queryDatabase($emailverkoper, array($voorwerpnummer));
    $titel = $titeluitvoorwerp[0]['titel'];
    $a_emailverkoper = $q_verkoper[0]['emailadres'];
    $a_voornaamverkoper = $q_verkoper[0]['voornaam'];
    $a_achternaamverkoper = $q_verkoper[0]['achternaam'];
///////////////////////////////////////////////////////////////////
 //hier wordt de mail verstuurd naar de verkoper   
$to      = $a_emailverkoper; 
$subject = 'vraag van een gebruiker over voorwerp: ' .$titel; 
$message = ' Beste '. $a_voornaamverkoper .' ' . $a_achternaamverkoper .',

hierbij de volgende vraag over '.$titel.':  '.$bericht .'

Met vriendelijke groet,
'.$naam .'
 
-------------------------

Antwoord kan verstuurd worden naar: '.$emailgebruiker;
$headers = 'From:noreply@eenxanderx.com' . "\r\n";
mail($to, $subject, $message, $headers); // Send our email
////////////////////////////////////////////////////////////////
}
?>