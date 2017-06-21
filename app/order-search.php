<h1>Order Lookup page</h1>
<form method="post">
<label for="order_number"></label>
<input type="text" name="order_number" id="order_number" />
<input type="submit" value="Search Order" name="search_order" />
</form>
<br>
<br>
<?php $shop = $_GET['shop'];
if(isset($_POST['search_order'])){
	$order_id = $_POST['order_number'];
	//echo $order_id;
	$shop_info = $Stores->is_shop_exists($shop);
	$orders = $Shopify->get_single_order($shop, $shop_info['access_token'],$order_id);
	if($order_id == $orders->order->id){
?>
     <h2>Order Details</h2>
     <table>
        <tr>
          <th><b>Order No</b></th>
          <th><?php echo $orders->order->name; ?></th>
        </tr>
         <tr>
          <th><b>Order Id</b></th>
          <th><?php echo $orders->order->id; ?></th>
        </tr>
     </table>
<?php 
	}
	else{
		echo "<h2>Invalid Order Id</h2>";
	}
}
?>