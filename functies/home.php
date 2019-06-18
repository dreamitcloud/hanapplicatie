<?php

    $rubriekSql = "select * from rubriek";
    $rubrieken = queryDatabase($rubriekSql, array());
    $rubriekenHTML ='';
    $rubriekenActief='';
    $topRubriekNaam='';
    $breadcrumHTML='';

// Alle parent id's
$parent_ids = array();
foreach ($rubrieken as $item) {
    $parent_ids[]  = $item['subrubriek'];
}

array_multisort($parent_ids, SORT_ASC, $rubrieken);

// Alle rubrieken die geen parent zijn
$notparent_ids = array();
foreach($rubrieken as $item){
if(!in_array($item['rubrieknummer'], $parent_ids)){

$notparent_ids = $item['rubrieknummer'];
}
}


function getSub($rubrieknummer, $rubrieken, $parent_ids) {
  
  $rubriekenHTML = '<ul class="menu vertical nested" id="subcategorie"><li><a href="/?id='.$rubrieknummer.'">Alle</a></li>';
    $rubriekenActief ='';
      foreach($rubrieken as $item){
    if($rubrieknummer == $item['subrubriek']){
      $rubriekenActief .= ', '.$item['rubrieknummer'];
      $rubriekenHTML .=  '<li><a href="/?id='.$item['rubrieknummer'].'">'.$item['rubrieknaam'].'</a>';
    if(in_array($item['rubrieknummer'], $parent_ids)){
       list($x,$y) = getSub($item['rubrieknummer'], $rubrieken,$parent_ids);
  $rubriekenHTML .= $x;
  $rubriekenActief .= ''.$y;
    }
    $rubriekenHTML .= '</li>';
    }
    
  }
  
  $rubriekenHTML .= '</ul>';

  return array($rubriekenHTML,$rubriekenActief);

}

function breadcrumMe($rubrieken, $laagsteRubriek, $breadcrum, $current){


foreach($rubrieken as $item){
  
  if($item['rubrieknummer'] == $laagsteRubriek){
    $parent = $item['subrubriek'];
    $naam = $item['rubrieknaam'];
    if($parent == 0){
      $naam = 'Alle Categorieen';
    }
    if($current){
    $breadcrum .= ':<li><span class="show-for-sr">Current: </span>'.$naam.'</li>' ;
  
}else{
    $breadcrum .= ':<li><a href="/?id='.$item['rubrieknummer'].'">'.$naam.'</a></li>' ;
}
if($parent != '0'){
       $breadcrum = breadcrumMe($rubrieken,$parent, $breadcrum, false);
    }
  }
}

      return $breadcrum;
}
function laadRubrieken($topRubriek, $rubrieken, $parent_ids){
// laad per hoofdcat(-1) alle subrubrieken in totdat rubrieknummer in geen parent voorkomen

  $rubriekenHTML = '<li><a href="#">Alle</a></li>';

  $rubriekenActief = ''.$topRubriek;

foreach($rubrieken as $item){
if($item['rubrieknummer'] == $topRubriek){
  $topRubriekNaam = $item['rubrieknaam'];
}
if($item['subrubriek'] == $topRubriek){
  $rubriekenActief .= ', '.$item['rubrieknummer'];
  $rubriekenHTML .= '<li><a href="#">'.$item['rubrieknaam'].'</a>';
  list($x,$y) = getSub($item['rubrieknummer'], $rubrieken,$parent_ids);
  $rubriekenHTML .= $x;
  $rubriekenActief .= ''.$y;

  $rubriekenHTML .= '</li>';

}
}

  $breadcrum = breadcrumMe($rubrieken, $topRubriek , '', true);
  $breadcrumPieces = explode(":", $breadcrum);
  $breadcrumHTML ='  <nav aria-label="You are here:" role="navigation">
  <ul class="breadcrumbs">';
  for ($i = 1, $len = count($breadcrumPieces); $i <= $len; $i++) {
    
  $breadcrumHTML.= ''. $breadcrumPieces[$len-$i];

  }
  
  $breadcrumHTML.='
  </ul>
</nav>';


  return array($rubriekenHTML,$rubriekenActief,$breadcrumHTML);
}

if(isset($_GET['id']) && $_GET['id']!='0' && $_GET['id']!='-1'){
  $id=$_GET['id'];
    $idUrl='id='.$_GET['id'].'&';
}else{
 $id = '-1';
   $idUrl='';
    $topRubriekNaam = '';
}


list($rubriekenHTML,$rubriekenActief,$breadcrumHTML) = laadRubrieken($id, $rubrieken,$parent_ids);

if(isset($_GET['s'])){
$search = $_GET['s'];
 $searchQuery="and titel like ?";
$sqlArray = array('\'%'.$search.'%\'');
}else{
 $searchQuery="";
 $searchHTML="";
$sqlArray = array();
}

if(isset($_GET['s'])){
  $breadcrumHTML='';
}
      $nVoorwerpSql = "select count(*) as n from voorwerp 
	inner join voorwerpinrubriek on voorwerp.voorwerpnummer = voorwerpinrubriek.voorwerpnummer where rubrieknummer in (".$rubriekenActief.") ".$searchQuery."";
  
    $nVoorwerpen = queryDatabase($nVoorwerpSql, $sqlArray);

    $nPaginas = floor($nVoorwerpen[0]['n']/16);
    $paginanummer='1';
    $prePagination='<li class="pagination-previous disabled">Previous <span class="show-for-sr">page</span></li>';
    $postPagination='';
    $pagination='';
    
    if(isset($_GET['p'])){
    $paginanummer = $_GET['p'];
    }
    $searchUrl='';
  if(isset($_GET['s'])){
	 $nZoekresultatenSql = "select count(*) as nZ from voorwerp where titel like '%".$search."%'";
	 $nZoekresultaten = queryDatabase($nZoekresultatenSql, $sqlArray);
    $topRubriekNaam=$nZoekresultaten[0]['nZ'] ." Zoekresultaten voor $search";
    $searchUrl='s='.$search.'&';
	}

    if($paginanummer > $nPaginas  && $nPaginas > 0){
      $paginanummer = $nPaginas;
    }
    
     $beforeThis = intval($paginanummer) -1;
     $beforeThat = intval($paginanummer) -2;
     $beforeThose = intval($paginanummer) -3;
     $afterThis = intval($paginanummer) +1;
     $afterThat = intval($paginanummer) +2;
     $afterThose = intval($paginanummer) +3;
     $beforeLast = intval($nPaginas) -1;
     
     if($paginanummer > '1' && $nPaginas != 0){
       $prePagination ='<li class="pagination-previous"><a href="/?'.$idUrl.''.$searchUrl.'p='.$beforeThis.'" aria-label="Previous page">Previous <span class="show-for-sr">page</span></a></li>';
     }

    if($paginanummer == $nPaginas || $nPaginas == 0){
       $postPagination ='<li class="pagination-next disabled">Next <span class="show-for-sr">page</span></li>';
     }else{
       $postPagination ='<li class="pagination-next"><a href="/?'.$idUrl.''.$searchUrl.'p='.$afterThis.'" aria-label="Next page">Next <span class="show-for-sr">page</span></a></li>';
     }

if($nPaginas <= 7){

  for($i=1;$i<=($nPaginas);$i++){
    if($paginanummer == $i){
    $pagination .= '<li class="current"><span class="show-for-sr">You\'re on page</span> '.$paginanummer.'</li>';
    }else{
    $pagination .= '<li><a href="/?'.$idUrl.''.$searchUrl.'p='.$i.'" aria-label="Page '.$i.'">'.$i.'</a></li>';
    }
  }
}elseif($paginanummer >= $nPaginas-3){
      $pagination = '  
<li><a href="/?'.$idUrl.''.$searchUrl.'p=1" aria-label="Page 1">1</a></li>
  <li><a href="/?'.$idUrl.''.$searchUrl.'p=2" aria-label="Page 2">2</a></li>
  <li class="ellipsis" aria-hidden="true"></li>
  <li><a href="/?'.$idUrl.''.$searchUrl.'p='.$beforeThose.'" aria-label="Page '.$beforeThose.'">'.$beforeThose.'</a></li>
  <li><a href="/?'.$idUrl.''.$searchUrl.'p='.$beforeThat.'" aria-label="Page '.$beforeThat.'">'.$beforeThat.'</a></li>
  <li><a href="/?'.$idUrl.''.$searchUrl.'p='.$beforeThis.'" aria-label="Page '.$beforeThis.'">'.$beforeThis.'</a></li>
  <li class="current"><span class="show-for-sr">You\'re on page</span> '.$paginanummer.'</li>

  ';
    }else{
      $pagination = '
      
  <li class="current"><span class="show-for-sr">You\'re on page</span> '.$paginanummer.'</li>

<li><a href="/?'.$idUrl.''.$searchUrl.'p='.$afterThis.'" aria-label="Page '.$afterThis.'">'.$afterThis.'</a></li>
  <li><a href="/?'.$idUrl.''.$searchUrl.'p='.$afterThat.'" aria-label="Page '.$afterThat.'">'.$afterThat.'</a></li>
  <li><a href="/?'.$idUrl.''.$searchUrl.'p='.$afterThose.'" aria-label="Page '.$afterThose.'">'.$afterThose.'</a></li>
  <li class="ellipsis" aria-hidden="true"></li>
  <li><a href="/?'.$idUrl.''.$searchUrl.'p='.$beforeLast.'" aria-label="Page '.$beforeLast.'">'.$beforeLast.'</a></li>
  <li><a href="/?'.$idUrl.''.$searchUrl.'p='.$nPaginas.'" aria-label="Page '.$nPaginas.'">'.$nPaginas.'</a></li>
  
      ';

    }

    /*-------------------------------------------------------------------------------------------------------------------------*/	  
	  
    $aanbevolenHTML =''; 
    
    if(isset($_SESSION['gebruikersnaam'])){

    $huidigegebruiker = $_SESSION['gebruikersnaam'];
     
	  $voorwerpSql2 = "select top 4 * from Voorwerp
		where Voorwerp.voorwerpnummer in (
		select voorwerpnummer from voorwerpinrubriek where rubrieknummer in (
		select rubrieknummer from rubriek where subrubriek in (
		select subrubriek from rubriek where rubrieknummer in (
		select rubrieknummer from voorwerpinrubriek where voorwerpnummer in (
		select voorwerpnummer from bod where gebruikersnaam = ?
		)))))
		and Voorwerp.voorwerpnummer not in (
		select voorwerpnummer from bod where gebruikersnaam = ?
		)
		";
		
	  $voorwerpen2 = queryDatabase($voorwerpSql2, array($huidigegebruiker, $huidigegebruiker));
	  
	$count2=0;
    foreach($voorwerpen2 as $voorwerp2){
      
     $count2++;
     if($count2 == 1){
          $aanbevolenHTML .='<div class="row">
                        <div class="large-12 columns">
                              <h2>Aanbevolen</h2>
                        </div>
                     </div><div class="row">';
     }

      $voorwerpnummer2 = $voorwerp2['voorwerpnummer'];
      $eindelooptijd2 = $voorwerp2['eindelooptijd'];      
	  
	  $verkoopprijs2 = $voorwerp2['verkoopprijs'];
      if(!$verkoopprijs2){
        $verkoopprijs2 = number_format($voorwerp2['startprijs'], 2, ',', '.');
      }
      $titel2 = $voorwerp2['titel'];
			$bestandSql2 = "select min(bestandsnaam) as bestand from bestand where voorwerpnummer = ? ";
			$bestand2 = queryDatabase($bestandSql2, array($voorwerpnummer2));
			$imglink2 = $bestand2[0]['bestand'];
			
	  $aanbevolenHTML .='
      <div class="small-12 medium-6 large-3 columns">
       <div class=" product-card">
          <div class="product-card-thumbnail">
		  
              <center><a href="productpagina.php?id='.$voorwerpnummer2.'"><img  src="'.$imglink2.'"></a></center>
          </div>
          <h2 class="product-card-title">
              <a href="productpagina.php?id='.$voorwerpnummer2.'">'.$titel2.'</a>
          </h2>
          <center><span id="'.$voorwerpnummer2.'" data-voorwerpnummer="'.$voorwerpnummer2.'" class="product-card-price">€ '.$verkoopprijs2.'</span></center>
          <center><span class="timer" data-date="'.$eindelooptijd2.'"></span></center>
          <a class="button primary expanded" href="productpagina.php?id='.$voorwerpnummer2.'">Bied Mee</a>
          </div>
      </div>    
      ';
	  if($count2 == 4){
          $aanbevolenHTML .='</div>
       <hr></hr>';     
        $count2=0;
     }
    }
    }
/*--------------------------------------------------------------------------------------------------------------------------*/	  	  
    
    $startRij = (16 * (intval($paginanummer) - 1));
    $eindRij = $startRij + 16;
    if($searchQuery!=""){
        $sqlArray2 = array('%'.$search.'%');
        
    }else{
        $sqlArray2 = array();
    }
    $voorwerpSql = "select * FROM (select voorwerp.*, rubrieknummer, ROW_NUMBER() OVER (ORDER BY verkoopprijs) as row from voorwerp 
	inner join voorwerpinrubriek on voorwerp.voorwerpnummer = voorwerpinrubriek.voorwerpnummer where rubrieknummer in (".$rubriekenActief.") ".$searchQuery.") a WHERE row > $startRij and row <= $eindRij";
   
    $voorwerpen = queryDatabase($voorwerpSql,$sqlArray2);

    $count=0;
    $veilingenHTML =''; 
    if($topRubriekNaam !=''){
    $veilingenHTML ='<div class="row">
                        <div class="large-12 columns">
                              <h3>'.$topRubriekNaam.'</h3>
                        </div>
                     </div>';
    }
    foreach($voorwerpen as $voorwerp){
      
     $count++;
     if($count == 1){
          $veilingenHTML .='<div class="row">';
     }

      $voorwerpnummer = $voorwerp['voorwerpnummer'];
      $eindelooptijd = $voorwerp['eindelooptijd']; 
	  
	  $verkoopprijs = $voorwerp['verkoopprijs'];
    
      if(!$verkoopprijs){
        $verkoopprijs = number_format($voorwerp['startprijs'], 2, ',', '.');
      }
      $titel = $voorwerp['titel'];
			$bestandSql = "select min(bestandsnaam) as bestand from bestand where voorwerpnummer = ? ";
			$bestand = queryDatabase($bestandSql, array($voorwerpnummer));
			$imglink = $bestand[0]['bestand'];
      $veilingenHTML .='
      <div class="small-12 medium-6 large-3 columns">
       <div class=" product-card">
          <div class="product-card-thumbnail">
              <center><a href="productpagina.php?id='.$voorwerpnummer.'"><img src="'.$imglink.'"></a></center>
          </div>
          <h2 class="product-card-title">
              <a href="productpagina.php?id='.$voorwerpnummer.'">'.$titel.'</a>
          </h2>
          <center><span id="'.$voorwerpnummer.'" data-voorwerpnummer="'.$voorwerpnummer.'" class="product-card-price">€ '.$verkoopprijs.'</span></center>
          <center><span class="timer" data-date="'.$eindelooptijd.'"></span></center>
          <a class="button primary expanded" href="productpagina.php?id='.$voorwerpnummer.'">Bied Mee</a>
          </div>
      </div>    
      ';
     if($count == 4){
          $veilingenHTML .='</div>';
          
        $count=0;
     }
    }

$paginationHTML='
<div class="small-12 medium-12 large-12 columns">
<ul class="pagination text-center" role="navigation" aria-label="Pagination" data-page="6" data-total="16">
'.$prePagination.'
'.$pagination.'
'.$postPagination.'
</ul>
</div>
';
?>