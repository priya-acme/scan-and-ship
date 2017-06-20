<?php
 include __DIR__ .'../../includes/utils/Shopify.php';
 include __DIR__ .'../../includes/db/Stores.php';
 $Shopify = new Shopify();
 $Stores = new Stores();
 $shop = $_GET['shop'];
 $shop_info = $Stores->is_shop_exists($shop);
 print_r($shop_info);
 $orders = $Shopify->get_orders($shop, $shop_info[0]['access_token']);
 print_r($orders);
 ?>
<?php include 'header.php' ?>
<div id="content">


<?php include 'footer.php' ?>