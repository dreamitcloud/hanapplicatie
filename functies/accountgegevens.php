<?php
  /**
   * Haalt de accountgegevens van de gespecificeerde gebruiker op uit de database
   * @param String $profielnaam de profielnaam van de gebruiker
   * @return String|array reactie van de database of een foutmelding
   */
  function accountOphalen($profielnaam) {
    //Filter speciale tekens uit de opgegeven profielnaam
    $profielnaam = filter_var($profielnaam, FILTER_SANITIZE_STRING);
    
    //Controleer of het profiel bestaat en haal data op uit de database
    $accountgegevens = queryDatabase("
      select top 1
        gebruikersnaam,
        voornaam,
        achternaam,
        geboortedatum,
        welnietindicator as 'isVerkoper',
        emailadres,
        adresregel as 'adresregel1',
        adresregel2,
        postcode,
        plaatsnaam as 'plaats',
        landnaam as 'land'
      from
        gebruiker
      where
        gebruikersnaam = ?
    ", array($profielnaam));
    if (count($accountgegevens) < 1) {
      return "error.account.niet.gevonden";
    }
    $accountgegevens = $accountgegevens[0];
    
    return $accountgegevens;
  }
  
  /**
   * Geeft de accountgegevens van de gebruiker weer op de webpagina
   * @param String $profielnaam de profielnaam van de gebruiker
   * @param boolean $isGebruiker of de gespecificeerde gebruiker de ingelogede gebruiker is
   * @return String|void een foutmelding of niets
   */
  function accountgegevensWeergeven($profielnaam, $isGebruiker) {
    //Haal het profiel op uit de database
    $accountgegevens = accountOphalen($profielnaam);
    if ($accountgegevens === "error.account.niet.gevonden") {
      ?>
        <p class="comment">Het opgevraagde account is niet gevonden</p>
      <?php
      return "error.account.niet.gevonden";
    }
    
    //Telefoonnummers voor het account ophalen
    $telefoonnummers = queryDatabase("
      select
        telefoonnummer
      from
        gebruikerstelefoon
      where
        gebruikersnaam = ?
      order by
        volgordenr ASC
    ", array($profielnaam));
    
    //Accountgegevens weergeven
    ?>
    <section class="block-list">
      <h5>
        <header>Gebruiker</header>
      </h5>
      <p class="list-subheader dark"><?php echo htmlentities($accountgegevens['voornaam']) . ' ' . htmlentities($accountgegevens['achternaam']); ?></p>
      <?php
        if ($isGebruiker) {
          ?>
            <p class="list-subheader dark"><?php echo htmlentities($accountgegevens['geboortedatum']); ?></p>
          <?php
        }
      ?>
    </section>
    <section class="block-list">
      <h5>
        <header>Contact</header>
      </h5>
      <p class="list-subheader dark"><?php echo htmlentities($accountgegevens['emailadres']); ?></p>

	  <p class="list-subheader dark">
        <?php
          foreach ($telefoonnummers as $volgordenr => $telefoonnummer) {
            if ($volgordenr != 0) {
              echo '<br>';
            }
            echo htmlentities($telefoonnummer['telefoonnummer']);
          }
        ?>
      </p>
    </section>
    <section class="block-list">
      <h5>
        <header>Locatie</header>
      </h5>
      <p class="list-subheader dark"><?php echo htmlentities($accountgegevens['adresregel1']) . '<br> ' . htmlentities($accountgegevens['adresregel2']); ?></p>
      <p class="list-subheader dark"><?php echo htmlentities($accountgegevens['plaats']); ?></p>
      <p class="list-subheader dark"><?php echo htmlentities($accountgegevens['postcode']); ?></p>
      <p class="list-subheader dark"><?php echo htmlentities($accountgegevens['land']); ?></p>
    </section>
  <section class="block-list">
      <h5>
        <header>Verkoper</header>
      </h5>
      <?php
        if ($isGebruiker) {
          
          if ($accountgegevens['isVerkoper']){
            echo '<p class="list-subheader dark">U bent een verkoper</p>';
          } else {
            echo '<p class="list-subheader dark">U bent (nog) geen verkoper</p>';
            echo '<a href="upgraden.php"><button class="button">Upgraden naar verkoper</button></a>';
          }
          
        } else {
          
          if ($accountgegevens['isVerkoper']){
            echo '<p class="list-subheader dark">Ja</p>';
          } else {
            echo '<p class="list-subheader dark">Nee</p>';
          }
          
        }
      ?>
    </section>
    <?php
  }
?>