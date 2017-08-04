<?php
include __DIR__ .'../../includes/utils/Shopify.php';
include __DIR__ .'../../includes/db/Stores.php';
$Shopify = new Shopify();
$Stores = new Stores();
$shop = $_REQUEST['shop'];
$shop_info = $Stores->is_shop_exists($shop);
$create_fulfillment = $Shopify->create_fulfillment_order($shop, $shop_info['access_token'],$_REQUEST['order_id'],array("fulfillment"=>array("id"=>"","order_id"=>$_REQUEST['order_id'],"status"=>"success","created_at"=>"2017-02-17T11:27:28-05:00",
		"service"=>"manual")));
$updateorder = $Shopify->updateOrderInfo($shop, $shop_info['access_token'],$_REQUEST['order_id'],array("order" =>array("tags" => "Double-Check")));
//echo "<pre>";
?>
