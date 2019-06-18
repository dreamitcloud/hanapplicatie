<?php
 include("functies/session.php");


    
    $voorwerpSqlHeader = "select top 10 voorwerp.* from voorwerp where veilinggesloten = 0 order by eindelooptijd ASC ";
   
    $voorwerpenHeader = queryDatabase($voorwerpSqlHeader, array());
    $veilingenHeader = array();
    
    for($i = 0, $len = count($voorwerpenHeader); $i<$len;$i++){
      
      $voorwerpnummerHeader = $voorwerpenHeader[$i]['voorwerpnummer'];
      $eindelooptijdHeader = $voorwerpenHeader[$i]['eindelooptijd']; 

	  $verkoopprijsHeader = $voorwerpenHeader[$i]['verkoopprijs'];
      if(!$verkoopprijsHeader){ 
        $verkoopprijsHeader = $voorwerpenHeader[$i]['startprijs'];
      }
      $verkoopprijsHeader = number_format($verkoopprijsHeader, 2, ',', '.');
      
        $titelHeader = $voorwerpenHeader[$i]['titel'];
	$bestandSql = "select min(bestandsnaam) as bestand from bestand where voorwerpnummer = ? ";
	$bestand = queryDatabase($bestandSql, array($voorwerpnummerHeader));
	$imglinkHeader = $bestand[0]['bestand'];

      $veilingenHeader[$i] ='
       <div class=" product-card">
          <div class="product-card-thumbnail">
              <center><a href="productpagina.php?id='.$voorwerpnummerHeader.'"><img src="'.$imglinkHeader.'"></a></center>
          </div>
          <h2 class="product-card-title">
              <a href="productpagina.php?id='.$voorwerpnummerHeader.'">'.htmlentities($titelHeader).'</a>
          </h2>
          <center><span id="'.$voorwerpnummerHeader.'" data-voorwerpnummer="'.$voorwerpnummerHeader.'" class="product-card-price">€ '.$verkoopprijsHeader.'</span></center>
          <center><span class="timer" data-date="'.$eindelooptijdHeader.'"></span></center>
          <a class="button primary expanded" href="productpagina.php?id='.$voorwerpnummerHeader.'">Bied Mee</a>
          </div>
      ';
    }

?>

<!doctype html>

<html class="no-js" lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eenmaal Andermaal</title>
    <link rel="stylesheet" href="css/foundation.css">
    <link rel="stylesheet" href="css/font-awesome.min.css">
    <link rel="stylesheet" href="css/stylesheet.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/animate.css">
    <script type="text/javascript" src="timer/jquery.min.js"></script>
    <script type="text/javascript" src="timer/jquery.flipcountdown.js"></script>
    <link rel="stylesheet" type="text/css" href="timer/jquery.flipcountdown.css" />
	<link rel="shortcut icon" type="image/x-icon" href="img/favicon.ico">
    <link rel="icon" type="image/png" href="img/favicon.png">
    <link rel="apple-touch-icon" href="img/favicon.png">
  </head>
  <body>

 <header id="header" class="topbar-sticky-shrink-header hide-for-medium-only hide-for-small-only">
<div class="ecommerce-product-slider orbit" role="region" aria-label="Favorite Space Pictures" data-orbit>
  <ul class="orbit-container">
    <button class="orbit-previous"><span class="show-for-sr">Previous Slide</span>&#9664;&#xFE0E;</button>
    <button class="orbit-next"><span class="show-for-sr">Next Slide</span>&#9654;&#xFE0E;</button>
    <li class="is-active orbit-slide">
      <div class="row small-up-2 medium-up-4 large-up-5 align-center">
        <div class="column">
          <?php echo $veilingenHeader[0]; ?>
        </div>
        <div class="column">
          <?php echo $veilingenHeader[1]; ?>
        </div>
        <div class="column hide-for-small-only">
          <?php echo  $veilingenHeader[2]; ?>
        </div>
        <div class="column show-for-large">
          <?php echo  $veilingenHeader[3]; ?>
        </div>
        <div class="column show-for-large">
          <?php echo $veilingenHeader[4]; ?>
        </div>
      </div>
    </li>
    <li class="is-active orbit-slide">
      <div class="row small-up-2 medium-up-4 large-up-5 align-center">
        <div class="column">
          <?php echo $veilingenHeader[5]; ?>
        </div>
        <div class="column">
          <?php echo $veilingenHeader[6]; ?>
        </div>
        <div class="column hide-for-small-only">
          <?php echo $veilingenHeader[7]; ?>
        </div>
        <div class="column show-for-large">
          <?php echo $veilingenHeader[8]; ?>
        </div>
        <div class="column show-for-large">
          <?php echo $veilingenHeader[9]; ?>
        </div>
      </div>
    </li>
 </ul>
</div>




</header>

<div class="fixed-height hide-for-small-only" data-sticky-container>
  <div data-sticky data-margin-top='0' data-top-anchor="header:bottom" >
    <div class="top-bar topbar-sticky-shrink">
      <div class="top-bar-title">
        <a href="/"><img src="img/logo.png" alt="" /></a>
      </div>
       <div class="top-bar-search hide-for-medium-only hide-for-small-only">
       <form action="/" method="get">
        <input class="app-dashboard-search" type="search" name="s" placeholder="Search">
        <input type="submit" 
        style="position: absolute; left: -9999px; width: 1px; height: 1px;"
        tabindex="-1" />
       </form>
       </div>
        <div class="top-bar-right hide-for-small-only">
        <ul class="menu">
            
          <?php
               
                if($verkopertest == true){ ?>
                    <li><a href="formulierpagina.php">Advertentie Toevoegen</a></li>
                <?php } ?>
                <?php if($loggedin){ ?>
                    <li><a href="profielpagina.php">Accountgegevens</a></li>
                    <li><a href="uitloggen.php">Uitloggen</a></li>
        <?php } else{ ?>
        
                    <li><a href="inloggen.php">Inloggen</a></li>
                    <li><a href="registreren.php">Registreren</a></li>
        <?php } ?>
        </ul>
      </div>
    </div>
  </div>

</div>
      
<div class="mobile-nav-bar title-bar show-for-small-only">
          <ul class="menu vertical text-center ">
           <a href="/"><img src="img/logo.png" alt="" /></a>
         <ul class=" vertical dropdown menu text-center" data-accordion-menu>
             <li>
                  <a href="#" ><div class="app-dashboard-sidebar-text">
                      <b >Menu</b></div></a>

            <ul class="menu vertical nested">

                <?php
               
                if($verkopertest == true){ ?>
                    <li><a href="formulierpagina.php">Advertentie Toevoegen</a></li>
                <?php } ?>
                <?php if($loggedin){ ?>
                    <li><a href="profielpagina.php">Acountgegevens</a></li>
                    <li><a href="uitloggen.php">Uitloggen</a></li>
        <?php } else{ ?>
        
                    <li><a href="inloggen.php">Inloggen</a></li>
                    <li><a href="registreren.php">Registreren</a></li>
        <?php } ?>
	  	  </li>
        </ul>
     </li>
    </ul>
   </ul> 
</div>
     



<div id="content" class="small-12 medium-12 large-12 columns">
