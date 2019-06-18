<?php
$bericht ='';
$voorwerpnummer = $_GET['id'];



 
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['bodbedrag'])) {
       
        
    
        $bodbedrag = $_POST['bodbedrag'];
        
        $check = queryDatabase("SELECT verkoper, startprijs,  veilinggesloten from voorwerp where voorwerpnummer = ?", array($voorwerpnummer));
                
        $huidigebod = queryDatabase("SELECT bodbedrag FROM gebruiker AS g LEFT JOIN bod AS b ON g.gebruikersnaam = b.gebruikersnaam
            WHERE voorwerpnummer = ? ORDER BY bodbedrag DESC", array($voorwerpnummer));
        $veilingtest = $check[0]['veilinggesloten'];
        $startprijs = $check[0]['startprijs'];
        $overboden = $startprijs;
        if(count($huidigebod)){
        $overboden = $huidigebod[0]['bodbedrag'];
        }
        $verkopercheck = $check[0]['verkoper'];
        
        
        if($loggedin == false){
            header("Location: ./inloggen.php");
        }elseif($veilingtest == 1){
            $bericht= 'Deze veiling is gesloten!';
        } elseif($verkopercheck == $_SESSION['gebruikersnaam']){
           $bericht = "U kunt niet op uw eigen voorwerp bieden!";
       } elseif($bodbedrag < $startprijs){
           $bericht = "U moet hoger bieden dan de startprijs: €" .number_format($startprijs, 2, ',', '.');
        }elseif($bodbedrag == $overboden){
            $bericht = "Bod bedrag mag niet even hoog zijn als het huidige hoogste bod!";        
        }elseif($overboden < 50 &&  $bodbedrag < $overboden + 0.5){
            $minimaal = $overboden +0.5;
            $bericht = "bodverhoging is te laag! minimaal: €" .$minimaal;
        }elseif($overboden >= 50 && $overboden < 500 && $bodbedrag < $overboden + 1){
            $minimaal = $overboden +1;
            $bericht = "bodverhoging is te laag! minimaal: €" .$minimaal;
        }elseif($overboden >= 500 && $overboden < 1000 && $bodbedrag < $overboden + 5){
            $minimaal = $overboden +5;
            $bericht = "bodverhoging is te laag! minimaal: €" .$minimaal;
        }elseif($overboden >= 1000 && $overboden < 5000 && $bodbedrag < $overboden + 10){
            $minimaal = $overboden +10;
            $bericht = "bodverhoging is te laag! minimaal: €" .$minimaal;
        }else{
        
        $gebruikersnaam = $_SESSION['gebruikersnaam'];
        $sql = "Insert into bod(voorwerpnummer,gebruikersnaam,bodbedrag) values(?,?,?);";
        queryDatabase($sql, array($voorwerpnummer,$gebruikersnaam, $bodbedrag));
        $titelvoorwerp = queryDatabase("SELECT titel from voorwerp where voorwerpnummer = ?",array($voorwerpnummer)); 
        $titel = $titelvoorwerp[0]['titel'];
        
        

            
            $adress = "SELECT emailadres FROM gebruiker WHERE gebruikersnaam = ?";
            $adress1 = queryDatabase($adress, array($gebruikersnaam));
            $mailadres = $adress1[0]['emailadres'];
            
            
            
            
            $to = $mailadres;
            $subject = $titel.' bod conformatie ';
            $message = 'uw bod op '.$titel. ' is successvol geplaatst';
            $headers = 'From:noreply@eenxanderx.com' . "\r\n";
            mail($mailadres, $subject, $message, $headers);
            
            if(count($huidigebod)){
                
            $adressover = "SELECT emailadres FROM gebruiker AS g LEFT JOIN bod AS b ON g.gebruikersnaam = b.gebruikersnaam
            WHERE bodbedrag != '$bodbedrag' and voorwerpnummer = ? ORDER BY bodbedrag DESC";
            $adress2 = queryDatabase($adressover, array($voorwerpnummer));
            
            $mailadres2 = $adress2[0]['emailadres'];
            $to2 = $mailadres2;
            $subjectover = $titel. ' geplaatst bod';
            $messageover = 'uw bod op '.$titel. ' is overboden';
            $headers = 'From:noreply@eenxanderx.com' . "\r\n";
            mail($mailadres2, $subjectover, $messageover, $headers);
            }
       
       
   
    }
         $bericht = "<p style='color:red;'>$bericht</p>";
    }

?>