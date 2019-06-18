<?php 
  include 'header.php';
  include 'functies/formulier.inc.php'; 

if($verkopertest == false){
   header("Location: ./profielpagina.php");         
        }
if($loggedin == false){
   header("Location: ./index.php");         
}


    $rubriekSql = "select * from rubriek";
    $rubrieken = queryDatabase($rubriekSql, array());
    $rubriekenHTML ='';
// Alle parent id's
$parent_ids = array();
foreach ($rubrieken as $item) {
    $parent_ids[]  = $item['subrubriek'];
}

array_multisort($parent_ids, SORT_ASC, $rubrieken);


function getSub($rubrieknummer, $rubrieken, $parent_ids) {
	$rubriekenHTML='';
  $rubriekenHTML .= '<ul class="menu vertical nested" id="subcategorie">';
      foreach($rubrieken as $item){
    if($rubrieknummer == $item['subrubriek']){
     if(in_array($item['rubrieknummer'], $parent_ids)){
		$rubriekenHTML .=  '<li><a href="#?id='.$item['rubrieknummer'].'">'.$item['rubrieknaam'].'</a>';
     	$rubriekenHTML .= getSub($item['rubrieknummer'], $rubrieken, $parent_ids);
    }else{
     $rubriekenHTML .=  '<li><input type="checkbox" name="categorie[]" value="'.$item['rubrieknummer'].'"><label>'.$item['rubrieknaam'].'</label></li>';
 	}
    $rubriekenHTML .= '</li>';
    }
  }
  $rubriekenHTML .= '</ul>';

  return $rubriekenHTML;

}

$rubriekenHTML .='<ul class="menu vertical nested">';
foreach($rubrieken as $item){
if($item['subrubriek'] == '-1'){
  $rubriekenHTML .= '<li><a>'.$item['rubrieknaam'].'</a>';
  $rubriekenHTML .= getSub($item['rubrieknummer'], $rubrieken,$parent_ids);
  $rubriekenHTML .= '</li>';
}
}
$rubriekenHTML .='</ul>';


?>

<style>

.rubriek {
  overflow-y: scroll;
  height: 320px;
				box-shadow: 0px 0px 1px 1px #666;

  }
  

#startprijs {
	width: 100px;
}

#verzendprijs {
	width: 100px;
}

#euroteken {
	float: left;
	margin: 10px 5px 5px 5px;	
	
}
</style>

<div class = "row">
<h1>Advertentie plaatsen</h1>
</div>

<form method="POST" action="" enctype="multipart/form-data" >
     
		  <div class = "row">
		  
			<div class = "medium-6 columns">
               <label>Titel *<input type="text" name="titel" placeholder="Titel" value="<?php echo $titel ?>" required ></label>
            </div>	
			<div class = "medium-3 columns">
               <label>Plaatsnaam *
			   <input type="text" name="plaatsnaam" placeholder="Plaatsnaam" value="<?php echo $plaatsnaam ?>" required></input> 
			   </label></div>
			               <div class = "medium-3 columns">
               <label>Land *
			   <input type="text" name="landnaam" placeholder="Land" value="<?php echo $landnaam ?>" required></input> 
			   </label>
            </div>
         </div>
		 
         <div class = "row">
            <div class = "medium-6 columns">
               <label>Beschrijving *
                  <textarea rows="5" placeholder="Vul hier de beschrijving van het product in." name="beschrijving" value="<?php echo $beschrijving ?>" required></textarea>
               </label>
            </div>

	
	
            <div class = "medium-3 columns">
               <label>Doorlooptijd *
        <select name="doorlooptijd" required>
		  <option value="" disabled selected hidden>...</option>
          <option value="1">1 dag</option>
          <option value="3">3 dagen </option>
          <option value="5">5 dagen</option> 
		  <option value="7">7 dagen</option>
          <option value="10">10 dagen</option>
        </select>
      </label>
            </div>
	            <div class = "medium-3 columns">
               <label>Startprijs *<br>			 
         <div class = "row">			  
			  <i class="large fa fa-euro" id="euroteken"></i><input type="text" name="startprijs" id="startprijs" placeholder="0.00" value="<?php echo $startprijs ?>" title="Gebruik indien nodig . en geen ," required>	
		    <script>
$(function() {
  var regExp = /[0-9\.]/;
  $('#startprijs').on('keydown keyup', function(e) {
    var value = String.fromCharCode(e.which) || e.key;
    console.log(e);
    // Alleen nummers en punten
    if (!regExp.test(value)
      && e.which != 190 // .
      && e.which != 8   // backspace
      && e.which != 46  // delete
	  && e.which != 110
      && (e.which < 37  // arrow keys
        || e.which > 40)
      && (e.which < 96 
        || e.which > 105)		
		) {
          e.preventDefault();
          return false;
    }
  });
});
</script>			  
			</div>
			  </label>
            </div>	
	           
         </div>		

    <div class="row">
	
		<div class="medium-4 columns">
<label>Rubriek(en) (max. 2) * <?php echo $errorRubriek ?></label>   
	
			<div class="rubriek">
<ul class=" vertical dropdown menu" data-accordion-menu>
  <li>
    <a href="#" class="button">Rubriek</a>
  <?php echo $rubriekenHTML; ?>
  </li></ul>

	</div></div>
            <div class="medium-4 columns">
 <label>Afbeeldingen (alleen .jpg en .png toegestaan) * <?php echo $errorFoto ?></label>			
  <div class="product-image-gallery">
	<input type="file" name="fileToUpload[]" id="fileToUpload[]" required>
	<input type="file" name="fileToUpload[]" id="fileToUpload[]">
    <input type="file" name="fileToUpload[]" id="fileToUpload[]">
	<input type="file" name="fileToUpload[]" id="fileToUpload[]">
	</div>	
		</div>

      <div class = "medium-2 columns">
               <label>Betalingswijze *
			   <select name="betalingswijze" required>
			   <option value="" disabled selected hidden>...</option>
			   <option value="Contant">Contant</option>
			   <option value="Bank/giro">Bank/giro</option>
			   <option value="Anders">Anders</option>
			   </select>
				</label>
            </div>
	  <div class = "medium-4 columns">
               <label>Betalingsinstructie *(alleen bij 'Anders') <?php echo $errorBetaling ?><textarea name="betalingsinstructie" placeholder="Betalingsinstructie" value="<?php echo $betalingsinstructie ?>"></textarea></label>
            </div>
            <div class = "small-12 medium-2 columns">
               <label>Verzendinstructie *
			   <select name="verzendinstructie"  required>
			   <option value="" disabled selected hidden>...</option>
			   <option>Ophalen</option>
			   <option>Verzenden</option>
			   <option>Ophalen of verzenden</option>

			   </select>
			   </label>
			   
            </div> 
			<div class = "small-12 medium-4 columns">
               <label>Verzendkosten *(alleen bij verzenden)<br><?php echo $errorVerzend ?>
			           <div class = "row">
			  <i class="large fa fa-euro" id="euroteken"></i><input type="text" name="verzendkosten" id="verzendprijs" placeholder="0.00" value="<?php echo $verzendkosten ?>" title="Gebruik indien nodig . en geen ,"></input>
			    <script>
$(function() {
  var regExp = /[0-9\.]/;
  $('#verzendprijs').on('keydown keyup', function(e) {
    var value = String.fromCharCode(e.which) || e.key;
    console.log(e);
    // Alleen nummers en punten
    if (!regExp.test(value)
      && e.which != 190 // .
      && e.which != 8   // backspace
      && e.which != 46  // delete
	  && e.which != 110
      && (e.which < 37  // arrow keys
        || e.which > 40)
      && (e.which < 96 
        || e.which > 105)		
		) {
          e.preventDefault();
          return false;
    }
  });
});
</script>
			  </div></label>
            </div>
</div>


	
    <div class ="row">
        <div class="small-12 medium-6 large-4 small-centered columns text-center">
        
   <input type="submit" value="Advertentie plaatsen" class="button large primary" id="formbutton"> * vereist
</div>

<?php

$sql3 = "SELECT MAX(voorwerpnummer) AS nummer FROM voorwerp";
$max = queryDatabase($sql3, array());
$nummer = $max[0]['nummer'];
$voorwerpnummer = $nummer + '1';

?>
        </div>     
            <div class = "row">
            <div class = "medium-4 columns">
               <p> Voorwerpnummer: <?php echo $voorwerpnummer ?>
            </div>
            
            <div class = "medium-4 columns">
            <div name="beginlooptijd" id=time></div></p>
<script>
function checkTime(i) {
  if (i < 10) {
    i = "0" + i;
  }
  return i;
}

function startTime() {
  var today = new Date();
  var dag = today.getDate();
  var maand = today.getMonth();
  var jaar = today.getFullYear();
  var uur = today.getHours();
  var minuut = today.getMinutes();
  var seconde = today.getSeconds();
  var arraymaand = ["januari", "februari", "maart", "april", "mei", "juni", "juli", "augustus", "september", "oktober", "november", "december"];
  maand = arraymaand[maand];
 
 // add a zero in front of numbers<10
  dag = checkTime(dag);
  uur = checkTime(uur);
  minuut = checkTime(minuut);
  seconde = checkTime(seconde);

 document.getElementById('time').innerHTML = "Begin looptijd: " + dag + " " + maand + " " + jaar + " om " +  uur + ":" + minuut + ":" + seconde;
  t = setTimeout(function() {
    startTime()
  }, 1000);
}
startTime();
</script>		
            </div>
         </div>  
      </form>

      <script src = "https://cdnjs.cloudflare.com/ajax/libs/foundation/6.0.1/js/vendor/jquery.min.js"></script>
      <script src = "https://cdnjs.cloudflare.com/ajax/libs/foundation/6.0.1/js/foundation.min.js"></script>
      
      <script>
         $(document).ready(function() {
            $(document).foundation();
         })
      </script>

<?php 
  include("footer.php");
?>