<?php
//include 'verbinding2.php';
$errorRubriek = '';
$errorVerzend = '';
$errorFoto = '';
$errorBetaling = '';
$verkoper = '';
$titel = '';
$beschrijving = '';
$betalingswijze = '';
$betalingsinstructie = '';
$startprijs = '';
$doorlooptijd = '';
$plaatsnaam = '';
$landnaam = '';
$verzendinstructie = '';
$verzendkosten = '';
$verkoper = $_SESSION['gebruikersnaam'];

if(isset($_POST['titel'])){
$titel = $_POST['titel'];
$beschrijving = $_POST['beschrijving'];
$betalingswijze = $_POST['betalingswijze'];
$betalingsinstructie = $_POST['betalingsinstructie'];
$startprijs = $_POST['startprijs'];
$doorlooptijd = $_POST['doorlooptijd'];
$plaatsnaam = $_POST['plaatsnaam'];
$landnaam = $_POST['landnaam'];
$verzendinstructie = $_POST['verzendinstructie'];
$verzendkosten = $_POST['verzendkosten'];
$selectMax = "SELECT MAX(voorwerpnummer) AS nummer FROM voorwerp";
$hoogstenummer = queryDatabase($selectMax);
$oudvoorwerpnummer = $hoogstenummer[0]['nummer'];
$nieuwvoorwerpnummer = $oudvoorwerpnummer + 1;

$checked_arr = $_POST['categorie'];
$countr = count($checked_arr);
if($countr > 2){
$errorRubriek = "Teveel rubrieken geselecteerd!";
}
elseif($countr < 1){
$errorRubriek = "Geen rubrieken geselecteerd!";
}
elseif(($verzendinstructie == 'Verzenden' && strlen($verzendkosten) == 0) || ($verzendinstructie == 'Ophalen of verzenden' && strlen($verzendkosten) == 0)){
$errorVerzend = "Voer verzendkosten in!";
}
elseif($betalingswijze == 'Anders' && strln($betalingsinstructie) == 0 ){
$errorBetaling = "Voer betalingsinstructie in!";
}
else{


$valid_formats = array("jpg", "png");
$path = "img/"; // Upload directory
$i = 1;
$ongeldig = 0;
	// Loop $_FILES to execute all files
	foreach ($_FILES['fileToUpload']['name'] as $f => $name) {  
	    if ($_FILES['fileToUpload']['error'][$f] == 0) {	           	
			if( ! in_array(pathinfo($name, PATHINFO_EXTENSION), $valid_formats) ){
				$ongeldig++;	
		}}
	}
if($ongeldig > 0){
	$errorFoto = "Geen geldig formaat, alleen .jpg en .png";
} else {
$sql = "INSERT INTO voorwerp(voorwerpnummer, titel, looptijd, verkoper, startprijs, verzendkosten, verzendinstructies, beschrijving, betalingwijze, plaatsnaam, landnaam)
 VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
queryDatabase($sql, array( htmlentities($nieuwvoorwerpnummer),  htmlentities($titel),  htmlentities($doorlooptijd),  htmlentities($verkoper),  htmlentities($startprijs),  htmlentities($verzendkosten),  htmlentities($verzendinstructie),  htmlentities($beschrijving),  htmlentities($betalingswijze),  htmlentities($plaatsnaam),  htmlentities($landnaam)));


foreach($_POST['categorie'] as $selected){
$insertVwrp = "INSERT INTO voorwerpinrubriek(rubrieknummer, voorwerpnummer) VALUES (?, ?)";
queryDatabase($insertVwrp, array($selected, $nieuwvoorwerpnummer));
}	
	
	foreach ($_FILES['fileToUpload']['name'] as $f => $name) {           
if(move_uploaded_file($_FILES["fileToUpload"]["tmp_name"][$f], $path.$name)){
	        $naam = 'dt_' . $i . '_' . $nieuwvoorwerpnummer. '.jpg';	
			$i++;
			rename("img/".$name, "img/".$naam);  
						$bestand = $path.$naam;

		$insertBestand = "INSERT INTO bestand(voorwerpnummer, bestandsnaam) VALUES (?,?) ";
				queryDatabase($insertBestand, array($nieuwvoorwerpnummer,$bestand));
	    }
			}	

header("Location: ./profielpagina.php");
exit();		
			
	}

}
$errorRubriek = "<p style='color:red;'>$errorRubriek</p>";
$errorVerzend = "<p style='color:red;'>$errorVerzend</p>";
$errorFoto = "<p style='color:red;'>$errorFoto</p>";
$errorBetaling = "<p style='color:red;'>$errorBetaling</p>"; 
}

?>