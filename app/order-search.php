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
	$get_order = $Shopify->get_orders($shop,$shop_info['access_token']);
	//$orders = $Shopify->get_single_order($shop, $shop_info['access_token'],$order_id);
	echo "<pre>";
	print_r($get_order);
	foreach($get_order->orders as $order) { 
		if($order_id == $order->name || $order_id == $order->id){
     ?>
      <h2>Order Details</h2>
     <table>
        <tr>
          <th><b>Order No.</b></th>
          <th><?php echo $order->name; ?></th>
        </tr>
         <tr>
          <th><b>Order Id</b></th>
          <th><?php echo $order->id; ?></th>
        </tr>
        <?php if($order->note != '' ){ ?>
        <tr>
          <th><b>Order Notes</b></th>
          <th><?php echo $order->note; ?></th>
        </tr>
        <?php } ?>
        </table>
   
     <?php break;
    	}
    	else {
    		echo "<h2>Invalid Id</h2>";
    		break;
    	}
    	}
   }
?>