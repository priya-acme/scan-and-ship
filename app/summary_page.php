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
<div class="margtop30">
<div class="container">
<div class="row">
<div class="col-md-12">
<div class="tbl">
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="table table-bordered table-responsive mytable">
  <tr>
    <td colspan="3" class="hed">ORDER LOOKUP <input type="text" class="txt"> <button type="button" class="serch">
      <span class="glyphicon glyphicon-search"></span>
    </button></td>
    <td width="6%" class="hed">PICKED</td>
    <td width="7%" class="hed">SHIPPED</td>
    <td width="11%" class="hed">INSTORE PICKUP</td>
    <td width="14%" class="hed">READY FOR PICKUP</td>
    <td width="31%" class="hed">NOTES</td>
  </tr>
  <?php foreach($orders->orders as $order) { ?>
  <tr>
    <td width="7%" valign="middle"><strong><a class="order_detail" href="/scan-and-ship/app/order_detail.php/?id=<?php echo $order->id; ?>"><?php echo $order->name; ?></a></strong></td>
    <td width="12%"><strong><?php echo $order->updated_at; ?></strong></td>
    <td width="12%"><strong><?php echo $order->shipping_address->first_name." ".$order->shipping_address->last_name; ?></strong></td>
    <td><div class="green"><a href=""><i class="fa fa-check" aria-hidden="true"></i></a></div></td>
    <td><div class="disable"><i class="fa fa-ban" aria-hidden="true"></i></div></td>
    <td><div class="disable"><i class="fa fa-ban" aria-hidden="true"></i></div></td>
    <td><div class="disable"><i class="fa fa-ban" aria-hidden="true"></i></div></td>
    <td><div class="last-text"><?php if($order->note != '' ){ echo $order->note; } else { ?> Here is some text about this mobile app. Here is some text about this mobile app.<?php } ?></div></td>
  </tr>
  <?php } ?>
 </table>
</div>
</div>
</div>
</div>
</div>