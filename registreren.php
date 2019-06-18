<?php 
  include("header.php");
    include("functies/functieregistratie.php");
?>
<div class="row">
      <div class="medium-6 columns text-center">
<div class="registreer-box">
  <div class="row collapse expanded">

      <div class="login-box-form-section">
           <form action="#" method="POST">
        <h1 class="login-box-title">Genereer registratiecode</h1>
               
 <?php      
  echo $erroractiveren;
?>
               
<input class="login-box-input" type="text"     name="emailcode"            placeholder="E-mail" />
        <input class="button large primary" type="submit" name="signup_submit" value="Genereer mijn code!" />
                </form>
      </div>

  </div>
</div>
   




<div class="login-box">
  <div class="row collapse expanded">
      <div class="login-box-form-section">
          <form action="#" method="POST">
        <h1 class="login-box-title">Registreer uzelf</h1>
    <?php   
              echo $errorregistreren;
?>
        <input class="login-box-input" type="text"      name="gebruikersnaam"  placeholder="Gebruikersnaam" value="<?php echo $gebruikersnaam?>" minlength="3" maxlength="12" required />
         <input class="login-box-input" type="text"      name="emailadres"     placeholder="Emailadres" value="<?php echo $emailadres ?>" maxlength="50" required/>
        <input class="login-box-input" type="text"      name="controlecode"    placeholder="Controlecode" value="<?php echo $controlecode ?>"required />
        <input class="login-box-input" type="password"  name="wachtwoord"      placeholder="Wachtwoord" minlength="7" required/>
        <input class="login-box-input" type="password"  name="wachtwoord2"     placeholder="Herhaal wachtwoord" minlength="7" required/>
        <input class="login-box-input" type="text"      name="voornaam"        placeholder="Voornaam" value="<?php echo $voornaam ?>" minlength="1" maxlength="50" required/>
        <input class="login-box-input" type="text"      name="achternaam"      placeholder="Achternaam" value="<?php echo $achternaam ?>" minlength="1" maxlength="50" required/>
              <label class="text-left"> Vul hier uw geboortdatum in
        <input class="login-box-input" type="date"  type="number"    name="geboortedatum"   placeholder="dd-mm-jjjj" value="<?php echo $geboortedatum ?>" maxlength="10"required/>
                  </label>
        <input class="login-box-input" type="text"      name="landnaam"        placeholder="Land van herkomst" value="<?php echo $landnaam ?>" maxlength="50" required/>
        <input class="login-box-input" type="text"      name="plaatsnaam"      placeholder="Plaatsnaam" value="<?php echo $plaatsnaam ?>" maxlength="50" required/>
        <input class="login-box-input" type="text"      name="postcode"        placeholder="Postcode" value="<?php echo $postcode ?>" minlength="4" maxlength="7" required/>
        <input class="login-box-input" type="text"      name="adresregel"      placeholder="Adres" value="<?php echo $adresregel ?>"  maxlength="50" required/>
        <input class="login-box-input" type="text"      name="adresregel2"     placeholder="2de adres" value="<?php echo $adresregel2 ?>" maxlength="50" />
         <input class="login-box-input" type="number"      name="telefoonnummer"      placeholder="Telefoonnummer" value="<?php echo $telefoonnummer ?>" minlength="7" maxlength="20" required/>
        <input class="login-box-input" type="number"      name="telefoonnummer2"     placeholder="2de telefoonnummer" value="<?php echo $telefoonnummer2 ?>"  maxlength="20" />
        <label class="text-left">Geheime vraag
<!--        //<select>-->
            <select name="vraagnummer">
          <option name ="1" value="1">In welke straat ben je geboren?</option>
          <option name ="2" value="2">Wat is de meisjesnaam je moeder?</option>
          <option name ="3" value="3">Wat is je lievelingsgerecht? </option>
          <option name ="4" value="4">Hoe heet je oudste zusje?</option>
            <option name ="5" value="5">Hoe heet je huisdier?</option>
        </select>
      </label>
            </div>
        <input class="login-box-input" type="text"      name="antwoord"         placeholder="Antwoord" value="<?php echo $antwoord ?>" minlength="1" maxlength="100" required/>
         <input class="button large primary" type="submit" name="signup_submit" value="Registreer mij!" />
           </form>
      </div>
     
  </div>
</div>         
    


    <div class="medium-6 columns">
<div class="blockquote-container">
  <div class="callout">
    <h4 class="blockquote-title">Dit is de gebruikershandleiding voor het registreren van een account.</h4>
    <blockquote>
      <p class="blockquote-content">Om volledig gebruik te kunnen maken van deze website dient u gebruik te maken van het registratieformulier. Als eerste genereert u een activatiecode, dit doet u door uw E-mail adres in te voeren en op de knop "Genereer mijn code!" te drukken. Binnen enkele minuten ontvangt u een E-mail met uw activiatiecode. U heeft slechts 4 uur de tijd om met deze code uzelf te registreren. Het daadwerkelijke registreren doet u onder het kopje "Registreer uzelf", hierbij kiest u een unieke gebruikersnaam en voert vervolgens uw controlecode in. Alle velden zijn verplicht om in te vullen m.u.v. het tweede telefoonnummer en het tweede adres. Nadat u alles heeft ingevuld kunt u de registratie afsluiten door op de knop "Registreer mij!" te klikken. Vervolgs krijgt u een notificatie op het scherm of dat u succesvol bent geregistreerd, anders krijgt u een melding dat u het een en ander moet aanpassen. </p>
    </blockquote>
  </div>
</div>
</div>
    </div>


<?php 
  include("footer.php");
?>