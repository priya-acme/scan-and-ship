<?php  
include __DIR__ .'../../includes/utils/Shopify.php';
include __DIR__ .'../../includes/db/Stores.php';
$Shopify = new Shopify();
$Stores = new Stores();
$shop =  $_REQUEST['shop'];
$shop_info = $Stores->is_shop_exists($shop);
$order_id=$_REQUEST['order_id'];
$check_order_veri = $Stores->s_check_order_veri($variants->variant->sku, $_REQUEST['id'],$_REQUEST['role']);
if($_REQUEST['role']== 'Shipper ok' || $_REQUEST['role']== 'Shipper' ){
if(empty($check_order_veri)){
	$Stores->s_order_veri($_REQUEST['sku'],"",$order_id,$_REQUEST['role'],$_REQUEST['qty']);
//header("location:http://67.207.82.1/scan-and-ship/app/order_detailed_page.php/?id=$order_id");
}
}
?>


