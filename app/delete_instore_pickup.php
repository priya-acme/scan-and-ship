<?php  
include __DIR__ .'../../includes/utils/Shopify.php';
include __DIR__ .'../../includes/db/Stores.php';
$Shopify = new Shopify();
$Stores = new Stores();
$shop =  $_SESSION['shop_name'];
$shop_info = $Stores->is_shop_exists($shop);
$order_id=$_REQUEST['order_id'];
$Stores->delete_instore_pickup_order($_REQUEST['order_id']);
$updateorder = $Shopify->updateOrderInfo($shop, $shop_info['access_token'],$_REQUEST['order_id'],array("order" =>array("note_attributes" => array("In store pickup status"=>""))));

?>

