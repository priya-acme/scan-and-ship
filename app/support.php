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
  <link rel="stylesheet" href="css/bootstrap-support.css" type="text/css">
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
<div class="container">
<div class="row">
<div class="col-md-12">
<p>To begin, please click on the GEAR button on the top left-hand corner of the dashboard. You
will need to decide which Validation Roles you would like active – Picker; Shipper; or both
Picker &amp; Shipper. You may do so by clicking the drop-down menu under “SELECT ROLE”</p>
</div>
</div>
<div class="row">
<div class="col-md-12">
<center><img src="/images/2.jpg" class="img-responsive"/></center>
</div>
</div>
<div class="row">
<div class="col-md-12">
<p>Moving onto the Dashboard, you will need to begin by selecting your ROLE – Picker or Shipper
On the top left-hand corner, UNDER the gear button, select the role you desire</p>
</div>
</div>
<div class="row">
<div class="col-md-12">
<center><img src="/images/1.jpg" class="img-responsive"/></center>
<br/>
</div>
</div>
<div class="row">
<div class="col-md-12">
<p>Once you’ve selected your role, type or scan the Order #/Order Barcode into the ORDER
LOOKUP space provided</p>
<p>&nbsp;</p>
<p class="text1">If you’re typing the order #* in manually, click the SEARCH icon (looks like a magnifying glass) or hit “enter” on your keyboard to open the order details page</p>
<p class="text1">If you’re scanning the order barcode, the order details page should automatically refresh
Alternatively, you can also “click + select” the order # from the chronological list
provided</p>

<p style="font-style:italic">*- order # must match dashboard exactly</p>


<p>Once you’re inside the order details page, it’s time to start scanning in the product(s)! The
ACTIVE ROLE (picker or shipper) should already be selected from the previous page</p>

<p class="text1">Scan each product/product code into the space provided + hit “ENTER”
Visual prompts will indicate whether you have scanned the correct item, or if you’ve
accidentally scanned the incorrect item.</p>
<p class="text1">Continue with the next product, until all products have been scanned</p>

<p class="text1">Screenshot of error-message possibly?</p>

<p>There are 3x visual prompts indicating order STATUS – Waiting to be Validated; Partially
Validated; and Validated + Ready to Ship</p>

<div class="row">
<div class="col-md-12">
<img src="/images/3.jpg" class="img-responsive"/>
<br/>
</div>
</div>

<div class="row">
<div class="col-md-12">
<p>Please note that there is an INTERNAL NOTES section provided, should you wish to
communicate any notes between the time the order is placed to the time it ships.</p>
<p>
Also note that in the PRODUCT DETAILS section, the “picked” and “shipped” validations may be
manually selected or de-selected, in the case that a barcode/product is not scanning correctly.
</p>
<p>
All “fulfilled” orders will be available for review in the “FULFILLED ORDERS” page linked on the
dashboard for a total of 45 days. Please <b>contact support</b> if you require any record extensions
beyond this timeframe.
</p>
</div>
</div>

</div>

</div>

</div>
<iframe src="https://services.cognitoforms.com/f/7V6sLEbpNECACUijBJeMyQ?id=1" style="display: block;margin: 0 auto;max-width: 800px;min-width: 500px;position: relative;text-align: center;width: 100%;" frameborder="0" scrolling="yes" seamless="seamless" height="455" width="100%"></iframe>
<script src="https://services.cognitoforms.com/scripts/embed.js"></script>

</body>