
<?php 
    include("header.php");
    include('functies/upgradefunctie.php');
    if($loggedin == false){
   header("Location: ./inloggen.php");         
        }
    if($verkopertest == true){
   header("Location: ./profielpagina.php");         
        }
?>

<div class="row">
<div class="medium-6 columns text-center">
<div class="login-box">
  <div class="row collapse expanded">
      <form method="POST" action="#" class="login-box-form-section">
        <h1 class="login-box-title">Maak uzelf een verkoper</h1>
            <?php
            echo $errorupgraden;
            ?>
        <input class="login-box-input" type="text" name="bank" placeholder="Bank" value="<?php echo $bank ?>" minlength="3" maxlength="25" required />
        <input class="login-box-input" type="text" name="rekeningnummer" placeholder="Rekeningnummer" value="<?php echo $rekeningnummer?>" minlength="6" maxlength="25" required/>
        <label class="text-left">Verificatiemethode. Kiest u voor verificatie via post dan hoeft u bij de creditcard niets in te vullen.</label>
        <select name="verificatiemethode">
            <option name="Post" value="Post">Post</option>
            <option name="Creditcard" value="Creditcard">Creditcard</option>
        </select>
        <input class="login-box-input" type="text" name="creditcardnummer" placeholder="Creditcardnummer & controlenummer" maxlength="19"/>
        <input class="button large primary" type="submit" name="upgrade_submit" value="Upgrade mij!" />

    </form>
  </div>
</div>         
</div>


    <div class="medium-6 columns">
<div class="blockquote-container">
  <div class="callout">
    <h4 class="blockquote-title">Dit is de gebruikershandleiding voor het upgraden van uw account.</h4>
    <blockquote>
      <p class="blockquote-content">Om volledig gebruik te kunnen maken van dit profiel dient u gebruik te maken van het upgradeformulier. Als eerste voert u alle gegevens in, dit doet u door een creditcard in te voeren of te upgraden via de post. Ook dient u een bank en rekeningnummer in te voeren. Vervolgens op de knop "Upgrade mij!" te drukken.</p>
    </blockquote>
  </div>
</div>
</div>
    </div>
<?php 
  include("footer.php");
?>