<?php
  /**
   * Geeft de beidingen die de gebruiker heeft gedaan weer op de webpagina
   * @param String $profielnaam de profielnaam van de gebruiker
   * @return boolean|void dat er fout is opgetreden of niets
   */
  function geefBiedingen($profielnaam) {
    //Filter speciale tekens uit profielnaam
    $profielnaam = filter_var($profielnaam, FILTER_SANITIZE_STRING);
    
    //Haal de biedingen van de gebruiker op
    $biedingen = queryDatabase("
      select	  V.titel,
                B1.voorwerpnummer as 'id',
                B1.gebruikersnaam as 'hoogsteBieder',
                V.veilinggesloten
      from	    bod as B1
                inner join Voorwerp as V
                  on B1.voorwerpnummer = V.voorwerpnummer
      where	    ? in (
                  select	gebruikersnaam
                  from	  bod as B3
                  where	  B3.voorwerpnummer = B1.voorwerpnummer
                ) and
                bodbedrag = (
                  select	max(bodbedrag)
                  from	  bod as B2
                  where	  B2.voorwerpnummer = B1.voorwerpnummer
                )
      order by  eindelooptijd ASC
    ", array($profielnaam));
    //Biedingen weergeven
    if (count($biedingen) < 1) {
      ?>
        <h4>Biedingen</h4>
        <p class="comment">U heeft geen biedingen gedaan</p>
        <a href="."><button class="button">Zoek naar veilingen</button></a>
        <p></p>
      <?php
      return false;
    }
    ?>
      <h4>Biedingen (<?php echo count($biedingen); ?>)</h4>
      <table>
        <thead>
          <tr>
            <th>Veiling</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody>
          <?php
            foreach ($biedingen as $bod) {
              ?>
                <tr>
                  <td><a href="productpagina.php?id=<?php echo htmlentities($bod['id']); ?>"><h5 class="comment"><?php echo htmlentities($bod['titel']); ?></h5></a></td>
                  <td>
                    <?php
                      if ($bod['veilinggesloten']) {
                        echo 'Veiling gesloten';
                      } elseif ($bod['hoogsteBieder'] == $profielnaam) {
                        echo 'U heeft het hoogste bod';
                      } else {
                        echo 'U bent overboden';
                      }
                    ?>
                  </td>
                </tr>
              <?php
            }
          ?>
        </tbody>
      </table>
      <br>
    <?php
  }
?>