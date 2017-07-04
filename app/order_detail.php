<?php include 'header.php' ?>
<?php  
include __DIR__ .'../../includes/utils/Shopify.php';
include __DIR__ .'../../includes/db/Stores.php';
$Shopify = new Shopify();
$Stores = new Stores();
$shop =  $_SESSION['shop_name'];
$shop_info = $Stores->is_shop_exists($shop);
$orders = $Shopify->get_single_order($shop, $shop_info['access_token'],$_REQUEST['id']);
?>
<div id="content">
     <h2>Barcode / SKU</h2>
     <form method="">
     <table>
     <tr>
     <th><b>Select Role</b></th>
     <td>
     <input type = "radio" value="Picker" name="select_role" <?php if($_SESSION['select_role'] == 'Picker' ){ echo "checked"; }?>>Picker
     <input type = "radio" value="Shipper" name="select_role" <?php if($_SESSION['select_role'] == 'Shipper' ){ echo "checked"; }?>>Shipper
     <input type = "radio" value="Receiver" name="select_role" <?php if($_SESSION['select_role'] == 'Receiver' ){ echo "checked"; }?>>Receiver
     </td>
     </tr>
     </table>
     <table>
     <tr>
     <td><input type="text" name="barcode_sku" placehoder="Enter Barcode or Sku"/></td>
     <td><input type="submit" name="submit" value="Submit" /></td>
     </tr>
     </table>
     </form>
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
          <th><b>Email Id</b></th>
          <th><?php echo $orders->order->email; ?></th>
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
        <table style="float:left">
        <tr>
           <th><b>Product Id</b></th>
	       <th><b>Product Title</b></th>
	       <th><b>Quantity</b></th>
	       <th><b>Price</b></th>
	       <th><b>SKU</b></th>
	       <th><b>Barcode</b></th>
	    </tr>
     <?php  $arrayobj = new ArrayObject($orders->order->line_items);
       $line_item_count = $arrayobj->count();
       for($i=0;$i<$line_item_count;$i++)
       {
     ?>
       	<tr>
       	<th><?php echo $orders->order->line_items[$i]->variant_id; ?></th>
       	<th><?php echo $orders->order->line_items[$i]->name; ?></th>
       	<th><?php echo $orders->order->line_items[$i]->quantity; ?></th>
       	<th><?php echo $orders->order->line_items[$i]->price; ?></th>
       	<?php $variants = $Shopify->get_variants($shop, $shop_info['access_token'],$orders->order->line_items[$i]->variant_id); 
       	//echo "<pre>";
       	//print_r($variants); 
       	if($variants->variant->sku != '' ){ 
       	?>
       	<th><?php echo $variants->variant->sku; ?></th>
        <?php } 
        if($variants->variant->barcode != '' ){ 
       	?>
       	<th><?php echo $variants->variant->barcode; ?></th>
        <?php } ?>
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
  <?php include 'footer.php' ?>