<?php
  include("header.php");
  include("functies/accountgegevens.php");
  include("functies/feedback.php");
  include("functies/producten.php");
  include("functies/biedingen.php");
  
  //Gebruiker
  $gebruikersnaam = null;
  if (isset($_SESSION['gebruikersnaam'])) {
    $gebruikersnaam = $_SESSION['gebruikersnaam'];
  }
  
  //Profiel
  $profielnaam = null;
  if (isset($_GET['g'])) {
    $profielnaam = $_GET['g'];
  }
  if ($profielnaam === null) {
    $profielnaam = $gebruikersnaam;
  }
  $accountgegevens = accountOphalen($profielnaam);
  
  $profielGevonden = ($accountgegevens === "error.account.niet.gevonden") ? false : true;
  $isVerkoper = false;
  $isGebruiker = false;
  
  if ($profielGevonden) {
    $isVerkoper = ($accountgegevens['isVerkoper']) ? true : false;
    $isGebruiker = ($accountgegevens['gebruikersnaam'] == $gebruikersnaam) ? true : false;
  }
  
  //Feedback
  //Voorwerpnummer ophalen
  $voorwerpnummer = null;
  if (isset($_GET['id'])) {
    $voorwerpnummer = $_GET['id'];
  }
  
  //Behandel feedback
  $beoordeling = false;
  $commentaar = false;
  
  if (isset($_POST['beoordeling'])) {
    $beoordeling = $_POST['beoordeling'];
  }
  if (isset($_POST['commentaar'])) {
    $commentaar = $_POST['commentaar'];
  }
?>

  <div class="small-12 columns">
    <div class="small-8 columns">
      <header>
        <h3>
          <?php
            if ($profielGevonden) {
              if ($accountgegevens['gebruikersnaam'] !== "") {
                echo $accountgegevens['gebruikersnaam'];
              } else {
                echo '...';
              }
            } else {
              echo 'Profiel niet gevonden';
            }
          ?>
        </h3>
      </header>
    </div>
    <div class="small-3 columns text-right">
      <?php
        if ($profielGevonden) {
          feedbackSterren($profielnaam, null);
        }
      ?>
    </div>
  </div>

  <div class="large-12 columns">
    <div class="medium-2 columns">
      <?php
        accountgegevensWeergeven($profielnaam, $isGebruiker);
      ?>
    </div>

    <div class="medium-10 columns">
      <?php
        if ($isGebruiker) {
          ?>
            <div class="small-12 medium-12 large-12 columns">
              <?php geefBiedingen($accountgegevens['gebruikersnaam']); ?>
            </div>
          <?php
        }
        if ($isVerkoper) {
          ?>
          
            <div class="small-12 medium-12 large-12 columns">
              <?php geefProducten($accountgegevens['gebruikersnaam'], false); ?>
            </div>
            
            <div class="small-12 medium-12 large-12 columns">
              <?php geefProducten($accountgegevens['gebruikersnaam'], true); ?>
            </div>
            
          <?php
        } else {
          if ($profielGevonden) {
            feedback($voorwerpnummer, $gebruikersnaam, $accountgegevens['gebruikersnaam'], !$isGebruiker, $beoordeling, $commentaar);
          }
        }
      ?>
    </div>
  </div>
  
  <?php
    if ($isVerkoper) {
      ?>
      <div class="small-12 small-centered columns">
        <br>
        <?php
          if ($profielGevonden) {
            feedback($voorwerpnummer, $gebruikersnaam, $accountgegevens['gebruikersnaam'], !$isGebruiker, $beoordeling, $commentaar);
          }
        ?>
      </div>
      <?php
    }
  ?>
  <br>

<?php
  include("footer.php");
?>