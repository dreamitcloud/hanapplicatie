<?php

include 'functies/verbinding.php';

$voorwerpnummers = $_GET['n'];

if (substr($voorwerpnummers, -1, 1) == '-')
{
  $voorwerpnummers = substr($voorwerpnummers, 0, -1);
}

$voorwerpnummers = str_replace("undefined-", "", $voorwerpnummers);
$voorwerpnummers = str_replace("-", ", ", $voorwerpnummers);

$count=substr_count($voorwerpnummers, ",");
$vraagtekens = "?";
for($i=0;$i<$count;$i++){
 $vraagtekens .= ", ?"; 
}

$voorwerpnummers = explode(",", $voorwerpnummers);

$sql = "select voorwerpnummer, verkoopprijs from voorwerp where voorwerpnummer in ($vraagtekens)";
$myJSONString = json_encode(queryDatabase($sql, $voorwerpnummers));

echo $myJSONString;
?>

