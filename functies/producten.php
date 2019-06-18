<?php
  /**
   * Geeft alle producten van de verkoper weer op aparte pagina's
   * @param String $profielnaam de profielnaam van de verkoper
   * @param boolean $gesloten of de weergegeven veilingen al gesloten moeten zijn
   * @return boolean|void een foutmelding of niets
   */
  function geefProducten($profielnaam, $gesloten) {
    //Filter speciale tekens uit profielnaam
    $profielnaam = filter_var($profielnaam, FILTER_SANITIZE_STRING);
    
    //Bepaal het veilingen-label
    $veilingenLabel = (!$gesloten) ? 'Veilingen' : 'Gesloten veilingen';
    
    //Haal de pagina op uit de GET
    if (!$gesloten) {
      $paginaLabel = "p";
    } else {
      $paginaLabel = "pg";
    }
    $productPagina = null;
    if (isset($_GET[$paginaLabel])) {
      $productPagina = $_GET[$paginaLabel];
    }
    
    //Bepaal de producten per pagina
    $geslotenBit = ($gesloten) ? 1 : 0;
    $nProducten = queryDatabase("
      select  count(*) as 'aantal'
      from    Voorwerp
      where   verkoper = ? and
              veilinggesloten = ?
    ", array($profielnaam, $geslotenBit));
    $aantalProducten = $nProducten[0]['aantal'];
    if ($aantalProducten < 1) {
      ?>
        <h4><?php echo $veilingenLabel; ?></h4>
        <p class="comment">
      <?php
        if (!$gesloten) {
          echo 'De verkoper heeft geen actieve veilingen';
        } else {
          echo 'De verkoper heeft geen gesloten veilingen';
        }
      ?>
        </p>
      <?php
      return false;
    }
    $productenPerPagina = 12;
    $aantalPaginas      = ceil($aantalProducten / $productenPerPagina);
    
    //Bepaal de huidige pagina
    $pagina = 1;
    if ($productPagina !== null and
        is_numeric($productPagina)) {
      
      //Typecast naar int
      $productPagina = (int) floor($productPagina);
      //Beperk het paginanummer tussen 1 en $aantalPaginas
      if ($productPagina > $aantalPaginas):
        $productPagina = $aantalPaginas;
      elseif ($productPagina < 1):
        $productPagina = 1;
      endif;
      
      $pagina = $productPagina;
    }
    
    //Haal producten op uit de database
    $productenOverslaan = $productenPerPagina * ($pagina - 1);
    $producten = queryDatabase("
      select      V.voorwerpnummer,
                  V.titel,
                  afbeelding = (
                    select  top 1 Bestand.bestandsnaam
                    from    Bestand
                    where   Bestand.voorwerpnummer = V.voorwerpnummer
                  ),
                  bodbedrag = (
                    select  top 1 max(bodbedrag)
                    from    Bod
                    where   Bod.voorwerpnummer = V.voorwerpnummer
                  ),
                  V.startprijs,
                  V.eindelooptijd
      from        Voorwerp as V
      where       V.verkoper = ? and
                  V.veilinggesloten = ?
      order by    dateadd(day, V.looptijd, V.beginlooptijd) ASC
      offset      $productenOverslaan rows
      fetch next  $productenPerPagina rows only
    ", array($profielnaam, $geslotenBit));
    
    ?>
    <h4><?php echo $veilingenLabel . ' (' . $aantalProducten . ')'; ?></h4>
    <?php
    foreach ($producten as $product) {
      $afbeelding = (isset($product['afbeelding'])) ? $product['afbeelding'] : "http://placehold.it/180x180";
      ?>
        <div class="small-12 medium-6 large-3 columns .begin">
          <div class=" product-card">
            <div class="product-card-thumbnail">
              <center><a href="productpagina.php?id=<?php echo $product['voorwerpnummer']; ?>"><img src="<?php echo $afbeelding; ?>"></a></center>
            </div>
            <h2 class="product-card-title">
              <a href="productpagina.php?id=<?php echo $product['voorwerpnummer']; ?>"><?php echo htmlentities($product['titel']); ?></a>
            </h2>
            <center><span class="product-card-price">€
              <?php
                $bedrag = ($product['bodbedrag'] !== null) ? $product['bodbedrag'] : $product['startprijs'];
                echo number_format($bedrag, 2, ',', '.');
              ?>
            </center></span>
            <center><span class="timer" data-date="<?php echo $product['eindelooptijd']; ?>"></span></center>
            <a class="button primary expanded" href="productpagina.php?id=<?php echo $product['voorwerpnummer']; ?>">
            <?php
              if (!$gesloten) {
                echo 'Bied Mee';
              } else {
                echo 'Gesloten';
              }
            ?>
            </a>
          </div>
        </div>
      <?php
    }
      
    //Pagina navigatie
    $basisUrl = "profielpagina.php?";
    foreach ($_GET as $element => $waarde) {
      if ($element != $paginaLabel) {
        $basisUrl .= $element . "=" . $waarde . "&";
      }
    }
    $basisUrl .= $paginaLabel . "=";
    
    ?>
    <div class="small-12 medium-12 large-12 columns">
    <ul class="pagination text-center" role="navigation" aria-label="Pagination" data-page="6" data-total="16">
    <?php
      if ($pagina == 1) {
        ?> <li class="pagination-previous disabled">Previous <span class="show-for-sr">page</span></li> <?php
      } else {
        ?> <li class="pagination-previous"><a href="<?php echo $basisUrl . ($pagina - 1); ?>" aria-label="Previous page">Previous <span class="show-for-sr">page</span></a></li> <?php
      }
      
      $maxAantalPaginas = 9;
      if ($aantalPaginas < $maxAantalPaginas) {
        
        for ($i = 1; $i <= $aantalPaginas; $i++) {
          if ($i == $pagina) {
            ?> <li class="current"><span class="show-for-sr">You're on page</span> <?php echo $i; ?></li> <?php
          } else {
            ?> <li><a href="<?php echo $basisUrl . $i; ?>" aria-label="Page <?php echo $i; ?>"><?php echo $i; ?></a></li> <?php
          }
        }
        
      } else {
  
        $aantalPaginasVoor = ($pagina > $aantalPaginas / 2) ? floor($maxAantalPaginas / 2) : $maxAantalPaginas - floor($maxAantalPaginas / 2) + 1;
        for ($i = 1; $i <= $maxAantalPaginas; $i++) {
          
          if ($pagina <= $aantalPaginas / 2) {
            
            if ($i < $aantalPaginasVoor) {
              $pageNumber = $i - 1 + $pagina;
            } elseif ($i > $aantalPaginasVoor) {
              $pageNumber = $aantalPaginas - $maxAantalPaginas + $i;
            } else {
              ?> <li class="ellipsis" aria-hidden="true"></li> <?php
              continue;
            }
           
          } else {
            
            if ($i < $aantalPaginasVoor) {
              
              $pageNumber = $i;
            } elseif ($i > $aantalPaginasVoor) {
              $pageNumber = $i + $pagina - 2 * ($maxAantalPaginas - $aantalPaginasVoor);
              if ($maxAantalPaginas % 2 != 0) {
                $pageNumber++;
              }
            } else {
              ?> <li class="ellipsis" aria-hidden="true"></li> <?php
              continue;
            }

          }
          if ($pageNumber == $pagina) {
            ?> <li class="current"><span class="show-for-sr">You're on page</span> <?php echo $pageNumber; ?></li> <?php
          } else {
            ?> <li><a href="<?php echo $basisUrl . $pageNumber; ?>" aria-label="Page <?php echo $pageNumber; ?>"><?php echo $pageNumber; ?></a></li> <?php
          }
          
        }
        
      }
      
      if ($pagina == $aantalPaginas) {
        ?> <li class="pagination-next disabled">Next <span class="show-for-sr">page</span></li> <?php
      } else {
        ?> <li class="pagination-next"><a href="<?php echo $basisUrl . ($pagina + 1); ?>" aria-label="Next page">Next <span class="show-for-sr">page</span></a></li> <?php
      }
    ?>
    </ul>
    </div>
    <?php
  }
?>