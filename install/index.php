<?php

include '../includes/db/Stores.php';
include '../includes/utils/Shopify.php';
$Shopify = new Shopify();
$Stores = new Stores();
$shop = $_REQUEST['shop'];
$code = isset($_GET["code"]) ? $_GET["code"] : false;

if ($shop && !$code) {
	// validate the shopify url
	if (!$Shopify->validateMyShopifyName($shop)) {
		echo "Invalid shopify url";
	}
	
	$redirect_url = $Shopify->getAuthUrl($shop);
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
	header("Location: https://" . $shop."/admin/apps/double-check/?shop=$shop");
	header("Location: " . APP_URL."/?shop=$shop");
}
if($_SERVER['PHP_SELF'] == '/double-check/app/index.php'){
	header("Location: " . APP_URL."/?shop=$shop");
}
?>

<form action="" method="post">
    <input type="text" name="shop" value="" placeholder="ex. your-store.myshopify.com" />
    <input type="submit" value="Submit" />
</form>