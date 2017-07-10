<?php  
include __DIR__ .'../../includes/utils/Shopify.php';
include __DIR__ .'../../includes/db/Stores.php';
$Shopify = new Shopify();
$Stores = new Stores();
$shop =  $_SESSION['shop_name'];
$shop_info = $Stores->is_shop_exists($shop);
$Stores->addd_instore_pickup($_REQUEST['order_id'],$_REQUEST['chkbx_val']);
echo "ok";
?>

