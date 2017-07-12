<?php 
include __DIR__ .'../../includes/utils/Shopify.php';
 include __DIR__ .'../../includes/db/Stores.php';
 $Shopify = new Shopify();
 $Stores = new Stores();
 $shop = $_SESSION['shop_name'];
 $shop_info = $Stores->is_shop_exists($shop);
 $orders = $Shopify->get_single_order($shop, $shop_info['access_token'],'5814281933');
 //$updateorder = $Shopify->updateOrderInfo($shop, $shop_info['access_token'],'5814281933');
echo $order->order->email;
 ?>
