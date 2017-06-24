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
        <h2>Shipping Details</h2>
        <table>
        <tr>
	       <th><b>Name</b></th>
	       <th><?php echo $order->order->shipping_address->first_name." ".$order->order->shipping_address->last_name; ?></th>
        </tr>
         <tr>
       <th><b>Address</b></th>
       <th><?php echo $order->order->shipping_address->address1." ".$order->order->shipping_address->address2."<br><br>".
         $order->order->shipping_address->city." ".$order->order->shipping_address->zip."<br><br>".$order->order->shipping_address->country; ?></th>
       </tr>
       <?php if($order->order->shipping_address->phone != '' ){ ?>
       <tr>
       <th><b>Phone No.</b></th>
       <th><?php echo $order->order->shipping_address->phone; ?></th>
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