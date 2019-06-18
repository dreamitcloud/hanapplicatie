<?php
  /**
   * Geeft de feedback die de gebruiker heeft ontvangen weer op de webpagina
   * @param $profielnaam de profielnaam van de gebruiker
   * @return void niets
   */
  function displayFeedback($profielnaam) {
    if ($profielnaam === null) {
      return "error.profiel.niet.gevonden";
    }
    
    //Filter speciale tekens uit profielnaam
    $profielnaam = filter_var($profielnaam, FILTER_SANITIZE_STRING);
    
    //Feedback van de kopers waar de gebruiker de verkoper is
    $feedbackWaarVerkoper = queryDatabase("
      select
        G.gebruikersnaam as 'naam',
        V.titel as 'voorwerp',
        convert(varchar(10), F.feedbackdatumtijd, 110) as 'datum',
        convert(varchar(8), F.feedbackdatumtijd, 108) as 'tijd',
        F.feedbacksoortnaam as 'beoordeling',
        F.commentaar,
        G.welnietindicator as 'verkoper',
        F.voorwerpnummer
      from
      Feedback as F
      inner join Voorwerp as V
        on F.voorwerpnummer = V.voorwerpnummer
      inner join Gebruiker as G
        on V.koper = G.gebruikersnaam
      where
        V.verkoper = ? and
        F.soortgebruiker = 'koper'
      order by
        F.feedbackdatumtijd DESC,
        G.gebruikersnaam ASC
    ", array($profielnaam));
    //Stel de gebruiker in als verkoper
    foreach ($feedbackWaarVerkoper as &$commentaar) {
      $commentaar['verkoper'] = 1;
    }
    unset($commentaar);
    
    //Feedback van de verkopers waar de gebruiker de koper is
    $feedbackWaarKoper = queryDatabase("
      select
        G.gebruikersnaam as 'naam',
        V.titel as 'voorwerp',
        convert(varchar(10), F.feedbackdatumtijd, 110) as 'datum',
        convert(varchar(8), F.feedbackdatumtijd, 108) as 'tijd',
        F.feedbacksoortnaam as 'beoordeling',
        F.commentaar,
        G.welnietindicator as 'verkoper',
        F.voorwerpnummer
      from
      Feedback as F
      inner join Voorwerp as V
        on F.voorwerpnummer = V.voorwerpnummer
      inner join Gebruiker as G
        on V.verkoper = G.gebruikersnaam
      where
        V.koper = ? and
        F.soortgebruiker = 'verkoper'
      order by
        F.feedbackdatumtijd DESC,
        G.gebruikersnaam ASC
    ", array($profielnaam));
    //Stel de gebruiker in als koper
    foreach ($feedbackWaarKoper as &$commentaar) {
      $commentaar['verkoper'] = 0;
    }
    unset($commentaar);
    
    $feedback = array_merge($feedbackWaarVerkoper, $feedbackWaarKoper);
    
    //Geef alle feedback voor de gebruiker weer op de webpagina
    ?>
    <div class="small-12 medium-12 large-12 columns">
      <div class="comment-section-container">
        <?php
          if (count($feedback) >= 1) {
            ?> <h4>Commentaar (<?php echo count($feedback); ?>)</h4> <?php
          } else {
            ?> <h4>Commentaar</h4>
            <p class="comment">Geen commentaar</p> <?php
          }
          foreach ($feedback as $commentaar) {
            ?>
            <div class="small-10 medium-10 large-10 columns">
              <div class="comment-section-author">
                <div class="comment-section-name">
                  <h5>
                    <a href="profielpagina.php?g=<?php echo htmlentities($commentaar['naam']); ?>"><?php echo htmlentities($commentaar['naam']); ?></a>
                    <br>
                    <a href="productpagina.php?id=<?php echo $commentaar['voorwerpnummer']; ?>"><?php echo $commentaar['voorwerp']; ?></a>
                  </h5>
                </div>
              </div>
              <div class="comment-section-text">
                <p>
                  <?php
                    echo $commentaar['datum'] . ' om ' . $commentaar['tijd'] . '<br>';
                    echo $commentaar['commentaar'];
                  ?>
                </p>
              </div>
            </div>
            <div class="small-2 medium-2 large-2 columns text-right">
              <h5>
                <?php
                  feedbackSterren($profielnaam, $commentaar['voorwerpnummer']);
                  if ($commentaar['verkoper'] == 1) {
                    echo 'Verkoper';
                  } 
                ?>
              </h5>
            </div>
            <?php
          } 
        ?>
      </div>
    </div>
    <?php
  }
  
  /**
   * Bepaalt of de gebruiker feedback kan geven
   * @param int $voorwerpnummer het voorwerpnummer van de veiling waar op moet worden gereageerd
   * @param String $afzender de gebruikersnaam van de gebruiker die de feedback moet sturen
   * @param String $profiel de gebruikersnaam van het profiel vanaf waar de afzender feedback moet sturen
   * @return String|void een foutmelding of niets
   */
  function feedbackToegestaan($voorwerpnummer, $afzender, $profiel) {
    //Controleer of de afzender is gespecificeerd
    if ($afzender === null) {
      return "Log in om feedback te geven";
    }
    
      //Filter speciale tekens uit afzender
      $afzender = filter_var($afzender, FILTER_SANITIZE_STRING);
      
      //Controleer of de afzender bestaat
      $accountData = queryDatabase("
        select
          gebruikersnaam
        from
          Gebruiker
        where
          gebruikersnaam = ?
      ", array($afzender));
      if (count($accountData) < 1) {
        return "Log in om feedback te geven";
      }
    
    //Controleer of een voorwerpnummer is gespecificeerd
    if ($voorwerpnummer === null) {
      return "Voorwerp niet gevonden";
    }
    
      //Controleer of het voorwerpnummer daadwerkelijk een nummer is
      if (!ctype_digit($voorwerpnummer)) {
        return "Voorwerp niet gevonden";
      }
      
      //Controleer of het voorwerp bestaat
      $voorwerpData = queryDatabase("
        select top 1
          veilinggesloten,
          verkoper,
          koper
        from
          Voorwerp
        where
          voorwerpnummer = ?
      ", array($voorwerpnummer));
      if (count($voorwerpData) < 1) {
        return $error = "Voorwerp niet gevonden";
      }
      $voorwerpData = $voorwerpData[0];
    
    //Bepaal of de afzender de koper of verkoper van het voorwerp is
    $koper = false;
    $verkoper = false;
    if ($voorwerpData['koper'] == $afzender) {
      $koper = true;
    }
    if ($voorwerpData['verkoper'] == $afzender) {
      $verkoper = true;
    }
    if (!$koper and !$verkoper) {
      return $error = "U heeft geen relatie met een voorwerp van deze verkoper";
    }
    
    //Bepaal of het bezochte profiel van de koper of verkoper is
    if ($profiel !== null) {
      
      //Filter speciale tekens uit profiel
      $profiel = filter_var($profiel, FILTER_SANITIZE_STRING);
      
      if ($voorwerpData['koper'] != $profiel and
            $voorwerpData['verkoper'] != $profiel) {
       
        return "U heeft geen relatie met een voorwerp van deze verkoper";
      }
    }
    
    //Controleer of het type gebruiker al heeft gereageerd
    $feedbackData = queryDatabase("
      select
        soortgebruiker
      from
        feedback
      where
        voorwerpnummer = ?
    ", array($voorwerpnummer));
    foreach ($feedbackData as $comment) {
      if ( ($koper and $comment['soortgebruiker'] == 'koper') or
        ($verkoper and $comment['soortgebruiker'] == 'verkoper') ) {
        
        return "U heeft al gereageerd";
      }
    }
    
    //Controleer of de veiling nog bezig is
    if (!$voorwerpData['veilinggesloten']) {
      return "De veiling is nog bezig";
    }
    
    //Toegestaan
    return true;
  }
  
  /**
   * Verstuurt gegeven feedback naar de database
   * @param int $voorwerpnummer het voorwerpnummer van de veiling waar op moet worden gereageerd
   * @param String $afzender de gebruikersnaam van de gebruiker die de feedback moet sturen
   * @param String $profiel de gebruikersnaam van het profiel vanaf waar de afzender feedback moet sturen
   * @param int $beoordeling het waardeoordeel dat de afzender aan de ontvanger geeft
   * @param String $commentaar het commentaar dat de afzender aan de ontvanger geeft
   * @return String|void een foutmelding of niets
   */
  function feedbackVersturen($voorwerpnummer, $afzender, $profiel, $beoordeling, $commentaar) {
    //Bepaal of feedback is meegegeven
    if ($beoordeling === null or
        $commentaar === null or
        $commentaar ==="") {
      
      return "error.geen.commentaar";
    }
    
    //Filter speciale tekens uit beoordeling
    $beoordeling = filter_var($beoordeling, FILTER_SANITIZE_STRING);
    
    //Filter speciale tekens uit beoordeling
    $commentaar = filter_var($commentaar, FILTER_SANITIZE_STRING); 
    
    //Bepaal of de gebruiker de koper, of de verkoper is
    $voorwerpData = queryDatabase("
      select top 1
        koper,
        verkoper
      from
        Voorwerp
      where
        voorwerpnummer = ?
    ", array($voorwerpnummer));
    $soortgebruiker = null;
    $voorwerpData = $voorwerpData[0];
    
    if ($voorwerpData['koper'] == $afzender) {
      $soortgebruiker = 'koper';
    } elseif ($voorwerpData['verkoper'] == $afzender) {
      $soortgebruiker = 'verkoper';
    } else {
      return "error.ongerelateerd";
    }
    
    //Voorkom het nogmaals versturen van feedback
    $alGegeven = queryDatabase("
      select  voorwerpnummer 
      from    feedback 
      where   voorwerpnummer = ? 
                and soortgebruiker = ?
    ", array($voorwerpnummer, $soortgebruiker));
    if (count($alGegeven) >= 1) {
      return "Feedback is al gegeven";
    }
    
    queryDatabase("
      insert into
        Feedback
        (voorwerpnummer, feedbacksoortnaam, soortgebruiker, commentaar)
      values
        (?, ?, ?, ?)
    ", array($voorwerpnummer, $beoordeling, $soortgebruiker, $commentaar));
  }
  
  /**
   * Bepaalt of de gebruiker feedback moet zien en of kunnen sturen en laadt de bijbehorende elementen op de webpagina
   * @param int $voorwerpnummer het voorwerpnummer van de veiling waar op moet worden gereageerd
   * @param String $afzender de gebruikersnaam van de gebruiker die de feedback moet sturen
   * @param String $profiel de gebruikersnaam van het profiel vanaf waar de afzender feedback moet sturen
   * @param boolean $kanInvoeren of de gebruiker in staat moet zijn om feedback de sturen
   * @param int $beoordeling het waardeoordeel dat de afzender aan de ontvanger geeft
   * @param String $commentaar het commentaar dat de afzender aan de ontvanger geeft
   * @return String|void een foutmelding of niets
   */
  function feedback($voorwerpnummer, $afzender, $profiel, $kanInvoeren, $beoordeling, $commentaar) {
    //Controleer of feedback kan worden gegeven in de huidige situatie
    $toegestaan = feedbackToegestaan($voorwerpnummer, $afzender, $profiel);
    
    //Verstuur feedback
    $verstuur = false;
    if ($beoordeling !== false and
        $commentaar !== false) {
      
      $verstuur = feedbackVersturen($voorwerpnummer, $afzender, $profiel, $beoordeling, $commentaar);
      
      //Controleer nogmaals of feedback kan worden gegeven in de huidige situatie
      $toegestaan = feedbackToegestaan($voorwerpnummer, $afzender, $profiel);
    }
    
    displayFeedback($profiel);
    
    if ($kanInvoeren) {
 
      if ($toegestaan === true) {
        //Haal voorwerpdata op
        $voorwerpData = queryDatabase("
          select top 1 
            titel
          from
            Voorwerp
          where
            voorwerpnummer = ?
        ", array($voorwerpnummer));
        $voorwerpData = $voorwerpData[0];
      }
      if ($toegestaan === "error.niet.gegeven") {
        return "error.niet.gegeven";
      }
      
      ?>
        <div class="small-12 medium-12 large-12 columns">
          <form class="comment-section-form" method="post">
            <br>
            <div class="comment-section-box">
              <h4>Geef commentaar</h4>
              <?php 
                if ($toegestaan !== true) {
                  
                  //Geef een foutmelding weer
                  echo '<p class="comment">' . $toegestaan . '</p>';
                    
                } else {
                  
                  //Geef het invoerveld weer
                  ?>
                  <a href="productpagina.php?id=<?php echo $voorwerpnummer; ?>">
                    <h5 class="comment">
                      <?php
                        echo $voorwerpData['titel'];
                      ?>
                    </h5>
                  </a>
                  <label>Beoordeling
                    <div class="row">
                      <div class="small-3 medium-2 columns">
                        <select required name="beoordeling">
                          <option value="" disabled selected hidden>...</option>
                          <option value="1">&#9733;&#9734;&#9734;&#9734;&#9734;</option>
                          <option value="2">&#9733;&#9733;&#9734;&#9734;&#9734;</option>
                          <option value="3">&#9733;&#9733;&#9733;&#9734;&#9734;</option>
                          <option value="4">&#9733;&#9733;&#9733;&#9733;&#9734;</option>
                          <option value="5">&#9733;&#9733;&#9733;&#9733;&#9733;</option>
                        </select>
                      </div>
                    </div>
                  </label>
                  <?php
                    if ($verstuur === "error.geen.commentaar") {
                      ?> <h5 class="comment">Geef commentaar mee</h5> <?php
                    }
                  ?>
                  <label>Commentaar
                    <textarea rows="10" name="commentaar"></textarea>
                  </label>
                  <button class="button expanded" type="submit">Versturen</button>
                  <?php 
                }
              ?>
            </div>
          </form>
        </div>
      <?php
    }
  }
  
  /**
   * Geeft de beoordeling van de gebruiker weer in sterren
   * @param String $profielnaam de gebruikersnaam van het profiel vanaf waar de afzender feedback moet sturen
   * @param int $voorwerpnummer het voorwerpnummer van de veiling waar op moet worden gereageerd
   * @return void
   */
  function feedbackSterren($profielnaam, $voorwerpnummer) {
    $nSterren = 5;
    
    //Filter speciale tekens uit de profielnaam
    $profielnaam = filter_var($profielnaam, FILTER_SANITIZE_STRING);
    
    //Als een voorwerp is gespecificeerd: Geef de beoordeling bij dat voorwerp. Zo niet, dan het gemiddelde van alle voorwerpen
    if ($voorwerpnummer === null) {
      //Gemiddelde van alle beoordelingen
      
        //Ophalen
        $beoordelingen = queryDatabase("
          select
            F.feedbacksoortnaam as 'beoordeling'
          from
            Feedback as F
            inner join Voorwerp as V
              on F.voorwerpnummer = V.voorwerpnummer
          where
              (V.koper = ? and
               F.soortgebruiker = 'verkoper')
            or
              (V.verkoper = ? and
               F.soortgebruiker = 'koper')
        ", array($profielnaam, $profielnaam));
        
    } else {
      
      //Filter speciale tekens uit het voorwerpnummer
      $voorwerpnummer = filter_var($voorwerpnummer, FILTER_SANITIZE_STRING);
      
      //Ophalen
      $beoordelingen = queryDatabase("
        select top 1
          F.feedbacksoortnaam as 'beoordeling'
        from
          Feedback as F
          inner join Voorwerp as V
            on F.voorwerpnummer = V.voorwerpnummer
        where
          F.voorwerpnummer = ?
          and (
              (V.koper = ? and
               F.soortgebruiker = 'verkoper')
            or
              (V.verkoper = ? and
               F.soortgebruiker = 'koper')
          )
      ", array($voorwerpnummer, $profielnaam, $profielnaam));
      
    }
    if (count($beoordelingen) < 1) {
      echo '<h5>Geen beoordelingen</h5>';
      return;
    }

    //Gemiddelde
    $beoordeling = 0;
    foreach ($beoordelingen as $reactie) {
      $beoordeling += $reactie['beoordeling'];
    }
    $beoordeling = $beoordeling / count($beoordelingen);
    
    //Zet om naar sterren
    ?>
      <h3 class="comment">
    <?php
    for ($i = 1; $i <= $nSterren; $i++) {
        
      if ($i <= $beoordeling) {
        echo '&#9733;';
      } else {
        echo '&#9734;';
      }
        
    }
    ?>
      </h3>
    <?php
  }
?>