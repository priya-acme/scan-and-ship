<?php  
include __DIR__ .'../../includes/utils/Shopify.php';
include __DIR__ .'../../includes/db/Stores.php';
$Shopify = new Shopify();
$Stores = new Stores();
$shop =  $_SESSION['shop_name'];
$shop_info = $Stores->is_shop_exists($shop);
$order_id=$_REQUEST['order_id'];
$Stores->delete_picker_order($_REQUEST['dorder'],$_REQUEST['dsku']);
?>

