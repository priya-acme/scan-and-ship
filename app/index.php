<?php 
include __DIR__ .'../../includes/utils/Shopify.php';
 include __DIR__ .'../../includes/db/Stores.php';
 $Shopify = new Shopify();
 $Stores = new Stores();
 $shop = $_GET['shop'];
 $code = isset($_GET["code"]) ? $_GET["code"] : false;
 //echo $code;
 if ($shop && !$code) {
 	// validate the shopify url
 	if (!$Shopify->validateMyShopifyName($shop)) {
 		echo "Invalid shopify url";
 	}
 	
 	$redirect_url = $Shopify->getAuthUrl($shop);
 	//echo $redirect_url;
 	header("Location: $redirect_url");
 	
 }
 
 if ($code) {
 	// we want to exchange the temp token passed by the shopify server during the installation process
 	// in exchange of a permanent token which we need in order to get/gain access on the shopify store
 	$exchange_token_response = $Shopify->exchangeTempTokenForPermanentToken($shop, $code);
 	
 	// validate access token
 	if(!isset($exchange_token_response->access_token) && isset($exchange_token_response->errors)) {
 		// access token is not valid, redirect user to error page
 		echo "<pre>";
 		print_r($exchange_token_response->errors);
 		echo "</pre>";
 	}
 	
 	$access_token = $exchange_token_response->access_token;
 	//echo $access_token;
 	// we check if it's a fresh installation
 	$shop_info = $Stores->is_shop_exists($shop);
 	
 	if (empty($shop_info)) {
 		$Stores->addData(array(
 				"store_url" => $shop,
 				"access_key" => SHOPIFY_API_KEY,
 				"token" => $access_token,
 				"created_at" => date("Y-m-d")
 		));
 	} else {
 		$Stores->updateData(array(
 				"access_token" => $access_token,
 				"access_key" => SHOPIFY_API_KEY,
 				"created_at" => date("Y-m-d")
 		), "store_url = '$shop'");
 	}
 	//echo APP_URL;
 	//header("Location: " . APP_URL."/?shop=$shop");
 }
 $_SESSION[$shop] = $shop;
 //echo $_SESSION[$shop];
 ?>
<?php include 'header.php' ?>
<!-- <div id="content"> -->
<?php header("location:https://$shop/admin/apps/double-check"); ?>

<?php include 'footer.php' ?>
<script>
alert(window.location.href);
</script>