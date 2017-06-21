<?php
 include __DIR__ .'../../includes/utils/Shopify.php';
 include __DIR__ .'../../includes/db/Stores.php';
 $Shopify = new Shopify();
 $Stores = new Stores();
 $shop = $_GET['shop'];
 $shop_info = $Stores->is_shop_exists($shop);
 $orders = $Shopify->get_orders($shop, $shop_info['access_token']);
echo "<pre>";
print_r($orders);
 ?>
<?php include 'header.php' ?>
<div id="content">
<table border="1" width="100%">
    <tr>
        <th><b>Order No</b></th>
        <th><b>Order Id</b></th>
        <th><b>Fulfillment Status</b></th>
    </tr>
   <?php foreach($orders->orders as $order) { ?>
    <tr>
        <td>
            <?php echo $order->name; ?>
        </td>
        <td>
            <?php echo $order->id; ?>
        </td>
        <td>
        <?php  echo $order[0]->line_items->title; ?>
        </td>
    <?php } ?>
  
 
<?php include 'footer.php' ?>
