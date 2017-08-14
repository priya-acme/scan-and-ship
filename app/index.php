<?php 
include __DIR__ .'../../includes/utils/Shopify.php';
 include __DIR__ .'../../includes/db/Stores.php';
 $Shopify = new Shopify();
 $Stores = new Stores();
 $shop = $_GET['shop'];
 $code = isset($_GET["code"]) ? $_GET["code"] : false;
 
 if ($shop && !$code) {
 	// validate the shopify url
 	if (!$Shopify->validateMyShopifyName($shop)) {
 		echo "Invalid shopify url";
 	}
 	
 	$redirect_url = $Shopify->getAuthUrl($shop);
 	echo $redirect_url;
 	header("Location: $redirect_url");
 	
 }
 
?>