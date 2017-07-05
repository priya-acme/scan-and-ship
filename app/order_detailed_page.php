<?php include 'header.php' ?>
<?php  
include __DIR__ .'../../includes/utils/Shopify.php';
include __DIR__ .'../../includes/db/Stores.php';
$Shopify = new Shopify();
$Stores = new Stores();
$shop =  $_SESSION['shop_name'];
$shop_info = $Stores->is_shop_exists($shop);
$orders = $Shopify->get_single_order($shop, $shop_info['access_token'],$_REQUEST['id']);
if(isset($_POST['submit_barcode'])){
	
}
?>
<?php include 'header.php' ?>
<div class="margtop30">
<div class="container">
<div class="row">
<div class="col-sm-12 col-md-5">
 <div class="role2">BARCODE / PRODUCT CODE  <input type="text" class="txt"> <button type="button" class="serch">
      <span class="glyphicon glyphicon-search"></span>
    </button></div>
 
</div>
<div class="col-sm-12 col-md-7">
<div class="role4">SELECT ROLE</div>
<div class="role3"><a href="">Picker</a></div>
   <div class="role3"><a href="">Shipper</a></div>
    <div class="role3"><a href="">Receiver</a></div>
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
<div class="col-md-5 col-sm-12">
<div class="hdd">ORDER DETAILS</div>
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="table table-bordered table-responsive mytable">
  <tr>
    <td width="29%"><strong>Order No.</strong></td>
    <td width="71%"><?php echo $orders->order->name; ?></td>
  </tr>
  <tr>
    <td><strong>Order ID</strong></td>
    <td><?php echo $orders->order->id; ?></td>
  </tr>
  <tr>
    <td><strong>Dates</strong></td>
    <td><?php echo $orders->updated_at; ?></td>
  </tr>
  <?php if($orders->note != '' ){ ?>
  <tr>
    <td><strong>Internal Notes</strong></td>
   <td><?php echo $orders->note; ?></td>
  </tr>
  <?php } ?>
</table>

</div>
<div class="col-md-5 col-sm-12">
<DIV class="hdd">SHIPPING DETAILS</div>
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="table table-bordered table-responsive mytable">
  <tr>
    <td width="29%"><strong>Name</strong></td>
    <td width="71%"><?php echo $orders->order->shipping_address->first_name." ".$orders->order->shipping_address->last_name; ?></td>
  </tr>
  <tr>
    <td><strong>Order ID</strong></td>
    <td><?php echo $orders->order->id; ?></td>
  </tr>
  <tr>
    <td><strong>Address</strong></td>
    <td><?php echo $orders->order->shipping_address->address1." ".$orders->order->shipping_address->address2."<br><br>".
 	       $orders->order->shipping_address->city." ".$orders->order->shipping_address->zip."<br><br>".$orders->order->shipping_address->country; ?></td>
  </tr>
  <?php if($orders->order->shipping_address->phone != '' ){ ?>
  <tr>
    <td><strong>Phone No.</strong></td>
    <td><?php echo $orders->order->shipping_address->phone; ?></td>
  </tr>
  <?php } ?>
</table>
</div>
<div class="col-md-2 col-sm-12">
<DIV class="hdd">SHIPPING DETAILS</div>
<div class="disable2"><i class="fa fa-ban" aria-hidden="true"></i></div>
</div>
</div>
</div>
</div>


<div class="margtop30">
<div class="container">
<div class="row">
<div class="col-md-12 col-sm-12">
<DIV class="hdd">PRODUCT DETAILS</div>
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="table table-bordered table-responsive mytable">
  <tr>
    <td width="29%" class="hed" style="text-align:left">PRODUCT TITLE</td>
    <td width="8%" class="hed">QUANTITY</td>
    <td width="8%" class="hed">PRICE</td>
    <td width="17%" class="hed">SKU</td>
    <td width="8%" class="hed">PICKED</td>
    <td width="9%" class="hed">SHIPPED</td>
    <td width="21%" class="hed">READY FOR PICKUP</td>
  </tr>
   <?php  $arrayobj = new ArrayObject($orders->order->line_items);
       $line_item_count = $arrayobj->count();
       for($i=0;$i<$line_item_count;$i++)
       {
     ?>
  <tr>
    <td align="left"><?php echo $orders->order->line_items[$i]->name; ?></td>
    <td><?php echo $orders->order->line_items[$i]->quantity; ?></td>
    <td><?php echo $orders->order->line_items[$i]->price; ?></td>
    <?php $variants = $Shopify->get_variants($shop, $shop_info['access_token'],$orders->order->line_items[$i]->variant_id); 
    	if($variants->variant->sku != '' ){ 
    ?>
    <td><?php echo $variants->variant->sku; ?></td>
    <?php } ?>
    <td><div class="disable"><i class="fa fa-ban" aria-hidden="true"></i></div></td>
    <td><div class="disable"><i class="fa fa-ban" aria-hidden="true"></i></div></td>
    <td><div class="disable"><i class="fa fa-ban" aria-hidden="true"></i></div></td>
  </tr>
  <?php } ?>

  
</table>


</div>
</div>
<div class="row">
<div class="col-md-12 col-sm-12">
<textarea  class="txtarea" placeholder="Customer Notes"></textarea>

</div>
<div class="col-md-12 col-sm-12 marbot30">
<a class="order" href="/scan-and-ship/app/summary_page.php/">BACK TO ORDER LOOKUP</a>
</div>
</div>
</div>
</div>
<?php include 'footer.php'; ?>