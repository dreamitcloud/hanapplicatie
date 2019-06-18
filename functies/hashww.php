<?php
include('verbinding.php');
$test = queryDatabase("select gebruikersnaam, wachtwoord from gebruiker where wachtwoord = 'Wachtw00rd!'");


foreach($test as $row){
    $row1 = $row['gebruikersnaam'];
   $hashed_wachtwoord = password_hash($row['wachtwoord'], PASSWORD_DEFAULT);
   echo "3"; 
     querydatabase("update gebruiker set wachtwoord = '$hashed_wachtwoord' where gebruikersnaam = '$row1' ");
    
}
 echo "2";
?>
