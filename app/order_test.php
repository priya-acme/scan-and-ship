<?php 
$shop = $_REQUEST['shop'];
$order_id = $_REQUEST['order_id'];
header("location:/double-check/app/order_detailed_page.php/?shop=$shop&&id=$order_id");
?>