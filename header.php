
<?php
 include("functies/session.php");
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
    <link rel="stylesheet" href="css/app.css">
    <link rel="stylesheet" href="css/stylesheet.css">
    <link rel="stylesheet" href="css/style.css">
    <script type="text/javascript" src="timer/jquery.min.js"></script>
    <script type="text/javascript" src="timer/jquery.flipcountdown.js"></script>
    <link rel="stylesheet" type="text/css" href="timer/jquery.flipcountdown.css" />
	<link rel="shortcut icon" type="image/x-icon" href="img/favicon.ico">
    <link rel="icon" type="image/png" href="img/favicon.png">
    <link rel="apple-touch-icon" href="img/favicon.png">
  </head>
  <body>

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
     

<div id="content" class="small-12 medium-12 large-12 columns offset-top">
