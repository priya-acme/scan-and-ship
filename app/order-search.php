<?php 
include __DIR__ .'../../includes/utils/Shopify.php';
 include __DIR__ .'../../includes/db/Stores.php';
 $Shopify = new Shopify();
 $Stores = new Stores();
 $shop = $_SESSION['shop_name'];
 $shop_info = $Stores->is_shop_exists($shop);
 $orders = $Shopify->get_single_order($shop, $shop_info['access_token'],'5966458253');
 $updateorder = $Shopify->updateOrderInfo($shop, $shop_info['access_token'],'5966458253',array("order" =>array("note_attributes" => array("In store pickup status"=>"Success"))));
 $create_fulfillment = $Shopify->create_fulfillment_order($shop, $shop_info['access_token'],'5966458253',array("fulfillment"=>array("id"=>"1022782930","order_id"=>"5966458253","status"=>"success","created_at"=>"2017-02-17T11:27:28-05:00",
 		"service"=>"manual",)));
 echo "<pre>";
 print_r($orders);
 print_r($create_fulfillment);
//print_r($updateorder);
//echo $orders->order->email;
 ?>
