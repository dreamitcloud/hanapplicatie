

<?php 
  include("header.php");
  include('functies/inloggenfunctie.php');
include("functies/wachtwoordvergeten.php");
if($loggedin == true){
   header("Location: ./index.php");         
        }
?>


<form method="POST" action="#" class="log-in-form">

  <h4 class="text-center">Inloggen met uw gebruikersaccount</h4>
    <?php echo $msg; ?>
  <label>Gebruikersnaam
    <input type="text" name = "username" placeholder="username">
  </label>
  <label>Wachtwoord
    <input type="password" name="password" placeholder="password">
  </label>
<div class="text-center">
  <input type="submit" class="button large primary" value="Log in" text-center>
    
    <a data-open="exampleModal2" class="button large primary">Wachtwoord vergeten</a>
    
    </div>
</form>

<!-- This is the first modal -->
<div class="reveal" id="exampleModal2" data-reveal>
    <form class="log-in-form"  action="#" method="post">
        <h4 class="text-center">Vul uw gebruikersnaam in</h4>
         <div class="row">
            <div class="medium-12 columns">
                <input type="text" name="vergeten" placeholder="gebruikersnaam">
                 <div class="text-center">
<!--            <a href="#" class="button large primary" data-open="exampleModal3" type="submit">Versturen</a>-->
     <input class="button large primary" type="submit"  value="Verzenden" />
                            
                            
  <button class="close-button" data-close aria-label="Close reveal" type="button">
    <span aria-hidden="true">&times;</span>
  </button>
             </div>  
        </div>
     </div>
 </form>
</div>

<!-- This is the nested modal -->
<?php if(isset($_POST['vergeten'])){ 
echo  "<script>
 $( document ).ready(function() {
    $('#exampleModal3').foundation('open');

});
</script>";
 }; ?>

<?php if(isset($_POST['vraag'])){ 
echo  "<script>
 $( document ).ready(function() {
    $('#exampleModal4').foundation('open');

});
</script>";
 }; ?>

<!--data-open="exampleModal2"-->
<div class="reveal" id="exampleModal3" data-reveal>
    
   
    
    <form class="log-in-form"  action="#" method="post">
  <h4 class="text-center"><?php echo $vraaggebruiker; ?></h4>
         <div class="row">
            
             
         
            
            <div class="medium-12 columns">
                <input class="login-box-input" type="text"      name="gebruikertest" value="<?php echo htmlentities($gebruikersnaam) ?>" readonly/>
                <input type="text" name="vraag" placeholder="antwoord">
                 <div class="text-center">
           
     <input class="button large primary" type="submit"  value="Verzenden" />
                     
      <button class="close-button" data-close aria-label="Close reveal" type="button">
    <span aria-hidden="true">&times;</span>
  </button>
</div>
             </div>
    </div>
    </form>
</div>
<div class="reveal" id="exampleModal4" data-reveal>
  
        <div class="row">
            <div class="medium-12 columns">
  <h4 class="text-center"><?php echo $bericht; ?></h4>
        </div>
        </div>
      <button class="close-button" data-close aria-label="Close reveal" type="button">
    <span aria-hidden="true">&times;</span>
  </button>
</div>
 






<?php 
  include("footer.php");
?>  
