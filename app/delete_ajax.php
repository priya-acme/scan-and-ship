<?php  
include __DIR__ .'../../includes/utils/Shopify.php';
include __DIR__ .'../../includes/db/Stores.php';
$Shopify = new Shopify();
$Stores = new Stores();
$shop =  $_REQUEST['shop'];
$shop_info = $Stores->is_shop_exists($shop);
$order_id=$_REQUEST['order_id'];
$Stores->delete_picker_order($_REQUEST['dorder'],$_REQUEST['dsku']);
$Stores->delete_shipper_order($_REQUEST['dsorder'],$_REQUEST['dssku']);
$deleted_data = $Stores->delete_receiver_order($_REQUEST['drorder'],$_REQUEST['drsku']);
print_r($deleted_data);
?>

