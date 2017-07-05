<?php
 include __DIR__ .'../../includes/utils/Shopify.php';
 include __DIR__ .'../../includes/db/Stores.php';
 $Shopify = new Shopify();
 $Stores = new Stores();
 $shop =  $_SESSION['shop_name'];
 $shop_info = $Stores->is_shop_exists($shop);
 $orders = $Shopify->get_orders($shop, $shop_info['access_token']);

if(isset($_POST['submit_id'])){
	$order_id = $_POST['order_id'];
	$_SESSION['select_role'] = $_POST['select_role'];
	$shop_info = $Stores->is_shop_exists($shop);
	$get_order = $Shopify->get_orders($shop,$shop_info['access_token']);
	foreach($get_order->orders as $order) {
		if($order_id == $order->name || $order_id == $order->id){
			header("location:/scan-and-ship/app/order_detail.php/?id=$order->id");
		}
	}
}
?>

<?php include 'header.php' ?>
<div id="content">
<form method="post">
<table>
<tr>
<th><b>Select Role</b></th>
<td>
<input type = "radio" value="Picker" name="select_role" class="select_role" checked>Picker
<input type = "radio" value="Shipper" name="select_role" class="select_role">Shipper
<input type = "radio" value="Receiver" name="select_role" class="select_role">Receiver
</td>
</tr>
</table>
<table>
<tr>
<th><b>Order Lookup</b></th>
<td><input type="text" placeholder = "Enter order number or id" name="order_id" /></td>
<td><input type="submit" class="submit_class" name="submit_id" value="Search" /></td>
</tr>
</table>
</form>
<table border="1" width="100%">
    <tr>
        <th><b>Order No</b></th>
        <th><b>Date</b></th>
        <th><b>Name</b></th>
        <th><b>Picked</b></th>
        <th><b>Shipped</b></th>
        <th><b>Receiver</b></th>
        <th><b>Notes</b></th>
    </tr>
   <?php foreach($orders->orders as $order) { ?>
    <tr>
        <td style="text-align: center">
            <a class="order_detail" href="/scan-and-ship/app/order_detail.php/?id=<?php echo $order->id; ?>"><?php echo $order->name; ?></a>
        </td>
        <td style="text-align: center">
            <?php echo $order->updated_at; ?>
        </td>
        <td>
        <?php echo $order->shipping_address->first_name." ".$order->shipping_address->last_name; ?>
        </td>
       <td>
       Yes
       </td>
       <td>
       Yes
       </td>
       <td>
       <input type="checkbox" name="Receiver" value="Receiver"> 
       </td>
       <td>
       <?php echo $order->note; ?>
       </td>
    <?php } ?>
 </tr>
 </table> 
  <?php include 'footer.php' ?>