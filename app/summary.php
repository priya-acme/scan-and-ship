<?php
 include __DIR__ .'../../includes/utils/Shopify.php';
 include __DIR__ .'../../includes/db/Stores.php';
 $Shopify = new Shopify();
 $Stores = new Stores();
 $shop = $_GET['shop'];
 $shop_info = $Stores->is_shop_exists($shop);
 $orders = $Shopify->get_orders($shop, $shop_info['access_token']);

 ?>
<?php include 'header.php' ?>
<div id="content">
<table border="1" width="100%">
    <tr>
        <th>Order No</th>
        <th>Order Id</th>
    </tr>
   <?php foreach($orders->orders as $order) { ?>
    <tr>
        <td>
            <?php echo $order->name; ?>
        </td>
        <td>
            <?php echo $order->id; ?>
        </td>
    </tr>
   <?php } ?>
 

<?php include 'footer.php' ?>