<?php
include __DIR__ .'../../includes/utils/Shopify.php';
include __DIR__ .'../../includes/db/Stores.php';
$Shopify = new Shopify();
$Stores = new Stores();
$shop = $_REQUEST['shop'];
$shop_info = $Stores->is_shop_exists($shop);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Double Check - <?php echo $_GET['id']?></title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="css/bootstrap.min.css" type="text/css">
  <link rel="stylesheet" href="css/style.css" type="text/css">
  <link rel="stylesheet" href="font-awesome/css/font-awesome.css" type="text/css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script src="js/bootstrap.min.js"></script>
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i,800,800i" rel="stylesheet">
  <script src="https://cdn.shopify.com/s/assets/external/app.js"></script>
  <script type="text/javascript">
  var getUrlParameter = function getUrlParameter(sParam) {
	    var sPageURL = decodeURIComponent(window.location.search.substring(1)),
	        sURLVariables = sPageURL.split('&'),
	        sParameterName,
	        i;

	    for (i = 0; i < sURLVariables.length; i++) {
	        sParameterName = sURLVariables[i].split('=');

	        if (sParameterName[0] === sParam) {
	            return sParameterName[1] === undefined ? true : sParameterName[1];
	        }
	    }
	};
	var shop_url = getUrlParameter('shop');
  ShopifyApp.init({
	  apiKey: "ed1b619b0d4433048a3fd866d1ae5f7f",
	  shopOrigin:"https://"+shop_url,
	  debug: false,
	  forceRedirect: true
	});
 </script>
<style>
.cognito .c-forms-form{margin: 0 auto; }
</style>
<script>
$(document).bind("contextmenu",function(e){
	  return false;
});
</script>
</head>
<body>
<div class="margtop30 ordered-header-fixed">
<div class="container">
<div class="row">
<div class="col-sm-12">
<div class="right-icon">
<div class="order-btn">
<a class="order" href="/double-check/app/summary_page.php?shop=<?php echo $shop; ?>">BACK TO ORDER LOOKUP</a>
</div>
<a href="/double-check/app/settings.php?shop=<?php echo $shop; ?>" class="seting-icon">
<i class="fa fa-cog" aria-hidden="true"></i>
</a>
<a href="/double-check/app/support.php?shop=<?php echo $shop; ?>" class="support_link">Support</a>
</div>
</div>
</div>

</div>
</div>
<iframe src="https://services.cognitoforms.com/f/7V6sLEbpNECACUijBJeMyQ?id=1" style="display: block;margin: 0 auto;max-width: 800px;min-width: 500px;position: relative;text-align: center;width: 100%;" frameborder="0" scrolling="yes" seamless="seamless" height="455" width="100%"></iframe>
<script src="https://services.cognitoforms.com/scripts/embed.js"></script>

</body>