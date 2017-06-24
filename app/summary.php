<?php
 include __DIR__ .'../../includes/utils/Shopify.php';
 include __DIR__ .'../../includes/db/Stores.php';
 $Shopify = new Shopify();
 $Stores = new Stores();
 $shop = $_GET['shop'];
 $shop_info = $Stores->is_shop_exists($shop);
 $orders = $Shopify->get_orders($shop, $shop_info['access_token']);
// echo "<pre>";
// print_r($orders);
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
        <td style="text-align: center">
            <a href="/scan-and-ship/app/order_detail.php/?shop=<?php echo $shop; ?>&&id=<?php echo $order->id; ?>"><?php echo $order->name; ?></a>
        </td>
        <td style="text-align: center">
            <?php echo $order->id; ?>
        </td>
        <?php if($order->fulfillment_status == '' or $order->fulfillment_status == null ) { ?>
        <td style="text-align: center">
        <?php  echo "Unfulfilled"; ?>
        </td>
        <?php } else { ?>
        <td style="text-align: center">
        <?php echo ucfirst($order->fulfillment_status); ?>
        </td>
        <?php } ?>
    <?php } ?>
  
 
<?php include 'footer.php' ?>
<?php //echo $order->line_items[0]->title; ?>