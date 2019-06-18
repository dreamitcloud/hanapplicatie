<?php


session_start();



include 'verbinding.php';
    
$loggedin = false;
$verkopertest = false;

if(isset($_SESSION['ingelogd']) && $_SESSION['ingelogd'] == true){
$loggedin = true;


$gebruikersnaam = $_SESSION['gebruikersnaam']; 
$verkoper = ("SELECT welnietindicator FROM gebruiker WHERE gebruikersnaam =?");
    $verkopercheck =queryDatabase($verkoper, array($gebruikersnaam));

   
if($verkopercheck[0]['welnietindicator'] == 1){ 
    $verkopertest = true;
  
}    
}
?>