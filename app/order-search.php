<h1>Order Lookup page</h1>
<form method="post">
<label for="order_number"></label>
<input type="text" name="order_number" id="order_number" />
<input type="submit" value="Search Order" name="search_order" />
</form>
<?php $shop = $_GET['shop'];
if(isset($_POST['search_order'])){
	$order_id = $_POST['order_number'];
	//echo $order_id;
	$shop_info = $Stores->is_shop_exists($shop);
	$orders = $Shopify->get_single_order($shop, $shop_info['access_token'],$order_id);
	echo $order_id;
	echo $orders->id;
	print_r($orders);
	if($order_id == $orders['id']){
?>
<table>
<tr>
<th><b>Order Id</b></th>
<th><?php echo $orders->id; ?></th>
</tr>
</table>
<?php 
	}
	else{
		echo "Invalid Id";
	}
}
?>