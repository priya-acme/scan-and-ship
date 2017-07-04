<?php
 include __DIR__ .'../../includes/utils/Shopify.php';
 include __DIR__ .'../../includes/db/Stores.php';
 $Shopify = new Shopify();
 $Stores = new Stores();
 $shop =  $_SESSION['shop_name'];
 $shop_info = $Stores->is_shop_exists($shop);
 $orders = $Shopify->get_orders($shop, $shop_info['access_token']);
// echo "<pre>";
// print_r($orders);
 ?>
<?php include 'header.php' ?>
<div id="content">
<form method="post">
<table>
<tr>
<th><b>Select Role</b></th>
<td>
<input type = "radio" value="Picker" name="select_role">Picker
<input type = "radio" value="Shipper" name="select_role">Shipper
<input type = "radio" value="Receiver" name="select_role">Receiver
</td>
</tr>
</table>
<table>
<tr>
<th><b>Order Lookup</b></th>
<td><input type="text" placeholder = "Enter order number or id" name="order_id" /></td>
<td><input type="submit" name="submit_id" value="Search" /></td>
</tr>
</table>
</form>
<table border="1" width="100%">
    <tr>
        <th><b>Order No</b></th>
        <th><b>Date</b></th>
        <th><b>Name</b></th>
        <th><b>Order Id</b></th>
        <th><b>Fulfillment Status</b></th>
    </tr>
   <?php foreach($orders->orders as $order) { ?>
    <tr>
        <td style="text-align: center">
            <a href="/scan-and-ship/app/order_detail.php/?id=<?php echo $order->id; ?>"><?php echo $order->name; ?></a>
        </td>
        <td style="text-align: center">
            <?php echo $order->id; ?>
        </td>
        <td style="text-align: center">
            <?php echo $order->updated_at; ?>
        </td>
        <td>
        <?php echo $order->order->shipping_address->first_name." ".$order->order->shipping_address->last_name; ?>
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