<?php 
    include("header.php");
    include("functies/functiemailen.php");
  
   include("functies/registratiebod.php");
    include("functies/feedback.php");
  
// hoogste bod
  
//bodlijst
$i=0;

$voorwerpnummer=$_GET['id'];
$sth = queryDatabase("SELECT gebruikersnaam, boddatumtijd, bodbedrag  FROM bod WHERE voorwerpnummer = ? ORDER BY boddatumtijd DESC", array($voorwerpnummer));
$voorwerpinfo = queryDatabase("SELECT * FROM voorwerp WHERE voorwerpnummer = ? ", array($voorwerpnummer));
$titel = $voorwerpinfo[0]['titel'];
$gebruikersnaam = $voorwerpinfo[0]['verkoper'];
$verzendkosten = $voorwerpinfo[0]['verzendkosten'];
$verzendinstructies = $voorwerpinfo[0]['verzendinstructies'];
$beschrijving = $voorwerpinfo[0]['beschrijving'];
$betalingwijze = $voorwerpinfo[0]['betalingwijze'];
$betalingsinstructie = $voorwerpinfo[0]['betalingsinstructie'];
$plaatsnaam = $voorwerpinfo[0]['plaatsnaam'];
$landnaam = $voorwerpinfo[0]['landnaam'];
$startprijs = $voorwerpinfo[0]['startprijs'];
$voorwerpfoto = queryDatabase("SELECT * FROM bestand WHERE voorwerpnummer = ? ", array($voorwerpnummer));
$bestandnaam = $voorwerpfoto[0]['bestandsnaam'];
$aantal = count($voorwerpfoto);
$veilinggesloten = $voorwerpinfo[0]['veilinggesloten'];
$disable = 'data-open="exampleModal1" ';
if($veilinggesloten == 1){
    $geboden = 'Deze veiling is gesloten';
    $disable = 'disabled';
}else if(!count($sth)){
    $geboden = 'Nog niet geboden';
}else{
    $geboden = 'Hoogste bod: €'. number_format($sth[0]['bodbedrag'], 2, ',', '.');
}

$rubriekinfo = queryDatabase("SELECT * FROM voorwerpinrubriek WHERE voorwerpnummer = ? ", array($voorwerpnummer));
$aantalrubrieken = count($rubriekinfo);
?>
<style>
#beschrijving {
	height: 300px;
	overflow: auto;
}

</style>


<div class="row">
    <div class="medium-9 columns">
<h3><?php echo htmlentities($titel) ?></h3>
    </div>
    <div class="medium-3 columns text-right">

        <label>Looptijd</label>
		<span class="timer" data-date="<?php print($voorwerpinfo[0]['eindelooptijd']); ?>"></span>
        
    </div>
	<hr>
</div>
<div class="row">
<div class="medium-4 columns">
    <label>Rubriek(en)</label>

        <p><?php
for($i = 0; $i < $aantalrubrieken; $i++){
$rubriek = $rubriekinfo[$i]['rubrieknummer'];
$rubrieknaaminfo = queryDatabase("SELECT * FROM rubriek WHERE rubrieknummer = ? ", array($rubriek));
$rubrieknaam = $rubrieknaaminfo[0]['rubrieknaam'];
		if($i < $aantalrubrieken-1){
		echo htmlentities($rubrieknaam)." / ";
		} else {
		echo htmlentities($rubrieknaam);
		} }
		?></p>
    </div>
    
   <div class="medium-4 columns">
       <a href="profielpagina.php?g=<?php echo htmlentities($gebruikersnaam); ?>&id=<?php echo htmlentities($voorwerpnummer); ?>"><h3><?php echo htmlentities($gebruikersnaam) ?></h3></a>
       
    
<!--    <div class="medium-2 columns">-->

       <?php feedbackSterren(htmlentities($gebruikersnaam), null); ?>

    </div> 

<div class="medium-4 columns text-center">
    <h3><?php echo htmlentities($geboden) ?> </h3>
    
   <a id="NuBiedenKnop" class="button large primary " <?php echo $disable ?>>Nu bieden</a>
</div>
        </div>


    <div class="reveal" id="exampleModal1" data-reveal>
        <form class="log-in-form"  method="post">
            <h4 class="text-center">Plaats uw bod.</h4>
                <div class="row">
                    <div class="medium-12 columns">
                        
                            
                            <input type="number" name="bodbedrag" placeholder="0" min="0.00" max="999999999.00" step="any">
                            <button style="width: 50%; margin-left: 25%; margin-right: 25%" formaction="#" class="button large primary" >plaats bod</button>
                        
                    </div>
                    </div>
            </form>
        <button class="close-button" data-close aria-label="Close modal" type="button">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>



<!--</div>-->
<div class="row">
     
           <div class="medium-4 columns">
    <div class="row">
  <div class="columns">
    <div class="orbit" role="region" aria-label="Favorite Space Pictures" data-orbit>
      <ul class="orbit-container">
        <button class="orbit-previous"><span class="show-for-sr">Previous Slide</span>&#9664;&#xFE0E;</button>
        <button class="orbit-next"><span class="show-for-sr">Next Slide</span>&#9654;&#xFE0E;</button>
        <li class="is-active orbit-slide">
          <img class="orbit-image" src="<?php echo htmlentities($bestandnaam) ?>" alt="Space">
        </li>   
		<?php if($aantal > 1){
	for($i = 1; $i < $aantal; $i++){
		$bestandnaam = $voorwerpfoto[$i]['bestandsnaam'];
?>
		<li class="orbit-slide">
          <img class="orbit-image" src="<?php echo htmlentities($bestandnaam)?>" alt="Space">
        </li>
		<?php }} ?>
      </ul>
      <nav class="orbit-bullets">
        <button class="is-active" data-slide="0"><span class="show-for-sr">First slide details.</span><span class="show-for-sr">Current Slide</span></button>
        <button data-slide="1"><span class="show-for-sr">Second slide details.</span></button>
        <button data-slide="2"><span class="show-for-sr">Third slide details.</span></button>
        <button data-slide="3"><span class="show-for-sr">Fourth slide details.</span></button>
      </nav>
    </div>
  </div>
</div>
</div>
    <div class="medium-4 columns">
    <label>Beschrijving</label>
        <hr>
    <p id="beschrijving"><?php echo htmlentities($beschrijving) ?></p>
   </div> 
    <div class="medium-4 columns">
        <table>
  <thead>
    <tr>
      <th width="150" height="10">Gebruiker</th>
      <th width="100">Bod</th>
      <th width="150">Datum</th>
    </tr>
  </thead>
  <tbody>
      <?php echo $bericht; ?>
      <?php foreach($sth as $index => $row){  ?>
      <?php if ($index >= 4) {break;} ?>
      
      <tr>
          <td><a href="profielpagina.php?g=<?php echo $row['gebruikersnaam']; ?>&id=<?php echo $voorwerpnummer; ?>"><?php echo $row['gebruikersnaam']; ?></a></td>
          <td><?php echo "€" . number_format($row['bodbedrag'], 2, ',', '.'); ?></td>
          <td><?php echo $row['boddatumtijd']; ?></td>
      </tr>
    
      <?php } ?>
  </tbody>
</table>
        <a data-open="exampleModal2" style="float: right"><p>Zie meer</p></a>

        <div class="reveal" id="exampleModal2" data-reveal><p>Alle biedingen</p>
            <table>
            <tbody>
            <tr>
                <?php foreach($sth as $row){ ?>
            <tr>
                <td><a href="profielpagina.php?g=<?php echo $row['gebruikersnaam']; ?>&id=<?php echo $voorwerpnummer; ?>"><?php echo $row['gebruikersnaam']; ?></a></td>
                <td><?php echo "€" . number_format($row['bodbedrag'], 2, ',', '.'); ?></td>
                <td><?php echo $row['boddatumtijd']; ?></td>
            </tr>
            <?php } ?>
            </tbody>
                </table>
        </div>
    </div>
	
</div>
<div class="row">
      <div class="medium-4 columns">
          <label>Startprijs</label>
          <hr>
    <p><?php echo "€".number_format(htmlentities($startprijs), 2, ',', '.'); ?></p>
    </div>
     <div class="medium-4 columns">
         <label>Betalingswijze</label>
         <hr>
    <p><?php echo htmlentities($betalingwijze) ?></p>
    </div>
     <div class="medium-4 columns">
         <label>Betalingsinstructies</label>
         <hr>
    <p><?php echo htmlentities($betalingsinstructie) ?></p>
    </div>
</div>
<div class="row">
    <div class="medium-4 columns">
         <label>Voorwerplocatie</label>
         <hr>
            <p><?php echo htmlentities($plaatsnaam).", ".htmlentities($landnaam) ?></p>
    </div>
     <div class="medium-4 columns">
         <label>Verzendkosten</label>
         <hr>
            <p> <?php echo "€".number_format(htmlentities($verzendkosten), 2, ',', '.'); ?></p>
    </div>
    <div class="medium-4 columns">
         <label>Verzendinstructies</label>
        <hr>
            <p><?php echo htmlentities($verzendinstructies) ?></p>
    </div>
</div>
<div class="row">
 <div class="small-12 medium-6 large-4 small-centered columns text-center">
<form action="#" method="POST">
      <h3 class="contact-us-header">Stel een vraag aan de verkoper</h3>
    <p>U hoeft alleen uw vraag te noteren</p>
        <input type="text" name="naam" placeholder="Volledige naam" required>
        <input type="email" name="email" placeholder="Email" required>
        <textarea name="message" id="" rows="12" placeholder="Vul hier uw vraag in voor de verkoper" required></textarea>
        <div class="contact-us-form-actions">
          <input type="submit" class="button" value="Verzenden" />
          
        </div>
      </form>
    </div>
</div>


<?php 
  include("footer.php");
?>