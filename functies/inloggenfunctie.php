<?php    
$msg = "";

if(isset ($_POST['username'], $_POST['password'])){

    
$username   =  $_POST['username'];
$password   =  $_POST['password'];
    
$wachtwoord = ("select * from gebruiker where gebruikersnaam = ?");
$wachtwoordcontrol = queryDatabase($wachtwoord, array($username));
$hash = $wachtwoordcontrol[0]['wachtwoord']; 



 if(password_verify($password, $hash)){
   
    $inloggegevens = ("SELECT gebruikersnaam , wachtwoord FROM gebruiker WHERE gebruikersnaam = ? AND wachtwoord = ?");
    $inloggegevenscontroleren =queryDatabase($inloggegevens, array($username,$hash));
    
    if(count($inloggegevenscontroleren)){
               
        $_SESSION['gebruikersnaam']= $username; 
        $_SESSION['ingelogd']=true;
        
      header("Location: ./index.php");
        exit();
        
     
      
      }
  }else{
      $msg =' De combinatie tussen gebruikersnaam en wachtwoord klopt NIET ';
 }

 $msg = "<p style='color:red;'>$msg</p>";  
}
?>