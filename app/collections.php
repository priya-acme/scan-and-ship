<?php
include __DIR__ .'../../includes/utils/Shopify.php';
include __DIR__ .'../../includes/db/Stores.php';
$Shopify = new Shopify();
$Stores = new Stores();
$shop = $_SESSION['shop_name'];
$shop_info = $Stores->is_shop_exists($shop);
$get_collections = $Shopify->get_collections($shop, $shop_info['access_token']);
print_r($get_collections);
?>