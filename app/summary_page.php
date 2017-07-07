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
			header("location:/scan-and-ship/app/order_detailed_page.php/?id=$order->id");
		}
	}
}
?>
<?php include 'header.php' ?>
 <form method="post">
<div class="margtop30">
<div class="container">
<div class="row">
<div class="col-sm-12 col-md-6">
 <div class="role2">SELECT ROLE</div>
 <div class="role"><input type = "radio" value="Receiver" name="select_role" class="select_role">Receiver</div>
 <div class="role"><input type = "radio" value="Shipper" name="select_role" class="select_role">Shipper</div>
 <div class="role"><input type = "radio" value="Picker" name="select_role" class="select_role" checked>Picker</div>
</div>
<div class="col-sm-12 col-md-6">
<div class="right-icon">
<a href="" class="seting-icon">
<i class="fa fa-cog" aria-hidden="true"></i>
</a>
</div>
</div>
</div>
</div>
</div>
<div class="margtop30">
<div class="container">
<div class="row">
<div class="col-md-12">
<div class="tbl">
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="table table-bordered table-responsive mytable">
  <tr>
    <td colspan="3" class="hed">ORDER LOOKUP <input type="text" class="txt" name="order_id"> <button type="submit" class="serch" name="submit_id">
      <span class="glyphicon glyphicon-search"></span>
    </button></td>
    <td width="6%" class="hed">PICKED</td>
    <td width="7%" class="hed">SHIPPED</td>
    <td width="11%" class="hed">RECEIVER</td>
    <td width="31%" class="hed">NOTES</td>
    <td>Line items</td>
    <td>Picker Count</td>
    <td>Shipper Count</td>
  </tr>
  <?php foreach($orders->orders as $order) { ?>
  <?php //echo "<pre>";
  //print_r($order);
  ?>
  <tr>
    <td width="7%" valign="middle"><strong><a class="order_detail" href="/scan-and-ship/app/order_detailed_page.php/?id=<?php echo $order->id; ?>"><?php echo $order->name; ?></a></strong></td>
    <td width="12%"><strong><?php echo $order->updated_at; ?></strong></td>
    <td width="12%"><strong><?php echo $order->shipping_address->first_name." ".$order->shipping_address->last_name; ?></strong></td>
    <td><div class="disable"><i class="fa fa-ban" aria-hidden="true"></i></div></td>
    <td><div class="disable"><i class="fa fa-ban" aria-hidden="true"></i></div></td>
    <td><div class="disable"><i class="fa fa-ban" aria-hidden="true"></i></div></td>
    <td><div class="last-text"><?php if($order->note != '' ){ echo $order->note; } else { ?> Here is some text about this mobile app. Here is some text about this mobile app.<?php } ?></div></td>
    <?php  $arrayobj = new ArrayObject($order->line_items);
       $line_item_count = $arrayobj->count();
      echo "<td>".$line_item_count."</td>";
     ?>
     <td><?php print_r($pcount = $Stores->p_count_order($order->id)); echo $pcount['count(*)']; ?></td>
     <td><?php print_r($scount = $Stores->s_count_order($order->id)); echo $scount['count(*)']; ?></td>
  </tr>
  <?php } ?>
 </table>
</div>
</div>
</div>
</div>
</div>
</form>
<?php include 'footer.php' ?>