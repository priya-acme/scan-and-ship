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
	$orders = $Shopify->get_single_order($shop, $shop_info['access_token'],$order_id);
	echo "<pre>";
	print_r($get_order);

	if($order_id == $orders->order->id ){
?>
     <h2>Order Details</h2>
     <table>
        <tr>
          <th><b>Order No.</b></th>
          <th><?php echo $orders->order->name; ?></th>
        </tr>
         <tr>
          <th><b>Order Id</b></th>
          <th><?php echo $orders->order->id; ?></th>
        </tr>
        <?php if($orders->order->note != '' ){ ?>
        <tr>
          <th><b>Order Notes</b></th>
          <th><?php echo $orders->order->note; ?></th>
        </tr>
        <?php } ?>
        </table>
        <h2>Shipping Details</h2>
        <table>
        <tr>
	       <th><b>Name</b></th>
	       <th><?php echo $orders->order->shipping_address->first_name." ".$orders->order->shipping_address->last_name; ?></th>
	    </tr>
	    <tr>
	       <th><b>Address</b></th>
	       <th><?php echo $orders->order->shipping_address->address1." ".$orders->order->shipping_address->address2."<br><br>".
	 	       $orders->order->shipping_address->city." ".$orders->order->shipping_address->zip."<br><br>".$orders->order->shipping_address->country; ?></th>
	    </tr>
	    <?php if($orders->order->shipping_address->phone != '' ){ ?>
	     <tr>
	       <th><b>Phone No.</b></th>
	       <th><?php echo $orders->order->shipping_address->phone; ?></th>
	    </tr>
	    <?php } ?>
        </table>
        <h2>Product Details</h2>
        <table>
        <tr>
	       <th><b>Product Title</b></th>
	       <th><b>Quantity</b></th>
	       <th><b>Price</b></th>
	       <th><b>SKU</b></th>
	    </tr>
        <?php  $arrayobj = new ArrayObject($orders->order->line_items);
	       $line_item_count = $arrayobj->count();
	       for($i=0;$i<$line_item_count;$i++)
	       {
	       	?>
	       	<tr>
	       	<th><?php echo $orders->order->line_items[$i]->name; ?></th>
	       	<th><?php echo $orders->order->line_items[$i]->quantity; ?></th>
	       	<th><?php echo $orders->order->line_items[$i]->price; ?></th>
	        <th><?php echo $orders->order->line_items[$i]->sku; ?></th>
	       	</tr>
	       	
	     <?php 
	       }
	     ?>
	  </table>
	  <?php if($orders->order->fulfillments[0]->tracking_company != null or $orders->order->fulfillments[0]->tracking_company != '' ) { ?>
	  <h2>Shipping Method</h2>
      <table>
        <tr>
	       <th><b>Shipping Carrier</b></th>
	       <th><?php echo $orders->order->fulfillments[0]->tracking_company; ?></th>
	    </tr>
	  </table>
	<?php } ?>
<?php 
	}
	else{
		echo "<h2>Invalid Order Id</h2>";
	}
}
?>