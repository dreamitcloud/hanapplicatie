



</div>

<div class="large-12 columns" style="margin-top:50px;">
  <div class="ecommerce-footer-bottom-bar row">
    <div class="small-12 medium-5 columns">
      <ul class="bottom-links">
        <li><a href="over_ons.php">Over Ons</a></li>
      </ul>
    </div>
    <div class="small-12 medium-2 columns ecommerce-footer-logomark">
      <img src="img/footerlogo.png">
    </div>
    <div class="small-12 medium-5 columns">
      <div class="bottom-copyright">
        <span>©2017 EenXAnderX. Alle rechten voorbehouden.</span>
      </div>
    </div>
  </div>
</div>

  <script>
  $('[data-app-dashboard-toggle-shrink]').on('click', function(e) {
  e.preventDefault();
  $(this).parents('.app-dashboard').toggleClass('shrink-medium').toggleClass('shrink-large');
});
  </script>
  <script>
  
  var interval = 1000;  // 1000 = 1 second, 3000 = 3 seconds
var voorwerpnummers = "";
var count = 0;
    	jQuery(function($){
		$( ".product-card-price" ).each(function() {
      voorwerpnummers += ($(this).attr("data-voorwerpnummer") + "-");
		});
	});


function doAjax() {
      
      $.ajax({    //create an ajax request to load_page.php
        type: "GET",
        url: "updateprijs.php?n=" + voorwerpnummers,             
        dataType: "html",   //expect html to be returned                
        success: function(response){       
          
          var obj = $.parseJSON(response);  
                $.each(obj, function(index, element) {
          
                      var nieuwprijs = element.verkoopprijs;

                  if(nieuwprijs != null){
                    count++;
                    nieuwprijs = '€ ' + parseFloat(Math.round(element.verkoopprijs * 100) / 100).toFixed(2);
                    nieuwprijs = nieuwprijs.toString().replace(/\./g, ',');
                    if(count > 50){
                             	jQuery(function($){
		$( ".product-card-price" ).each(function() {
      $(this).removeClass("animated pulse");
		});
	}); count = 0;
                    }
                    var oudeTekst = $('#' + element.voorwerpnummer).text();
                    oudeTekst.toString().replace(/\./g, '');
                       $('#' + element.voorwerpnummer).html(nieuwprijs);
                       if(oudeTekst != nieuwprijs){
                         $('#' + element.voorwerpnummer).addClass('animated pulse');
                       }
                  }
  
          });
        },
            complete: function (response) {
                    // Schedule the next
                    
                    setTimeout(doAjax(), interval);
            }
    });
}
setTimeout(doAjax(), interval);


  </script>
	<script>
	jQuery(function($){
		$( ".timer" ).each(function() {
		var date = $(this).attr("data-date");
			$(this).flipcountdown({
				size:'xs',
				beforeDateTime: date
			});
		});
	});
	</script>
    <script src="js/vendor/jquery.js"></script>
    <script src="js/vendor/what-input.js"></script>
    <script src="js/vendor/foundation.js"></script>
    <script src="js/app.js"></script>
  </body>
</html>