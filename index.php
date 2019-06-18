<?php 
  include("headerhome.php");
  include("functies/home.php");
  
?>
<!-- -->
<div class="sidemenu show-for-large-up large-3 columns">
 <ul class="menu vertical">
		  <ul class=" vertical dropdown menu" data-accordion-menu>
  <li>
    <a href="#" class="button"><div class="app-dashboard-sidebar-text">Categorie</div></a>
    <ul class=" is-active menu vertical nested">
       <?php echo $rubriekenHTML; ?>
    </ul>
  </li>
</ul>

</div> 

<div class="contentbody small-12 medium-12 large-9 columns">

       <?php echo $aanbevolenHTML; ?>
       <?php echo $breadcrumHTML; ?>
	   <?php echo $veilingenHTML; ?>
       <?php echo $paginationHTML; ?>
</div>
<?php 
  include("footer.php");
?>