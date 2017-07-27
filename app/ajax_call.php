<?php  
include __DIR__ .'../../includes/utils/Shopify.php';
include __DIR__ .'../../includes/db/Stores.php';
$Shopify = new Shopify();
$Stores = new Stores();
$shop =   $_SESSION[$shop];
$shop_info = $Stores->is_shop_exists($shop);
$order_id=$_REQUEST['order_id'];
if(empty($Stores->gett_instore_pickup($_REQUEST['order_id']))){
$Stores->addd_instore_pickup($_REQUEST['order_id'],$_REQUEST['chkbx_val']);
$updateorder = $Shopify->updateOrderInfo($shop, $shop_info['access_token'],$_REQUEST['order_id'],array("order" =>array("note_attributes" => array("In store pickup status"=>"Success"))));
header("location:http://67.207.82.1/scan-and-ship/app/order_detailed_page.php/?id=$order_id");
}
?>

