<?php  
include __DIR__ .'../../includes/utils/Shopify.php';
include __DIR__ .'../../includes/db/Stores.php';
$Shopify = new Shopify();
$Stores = new Stores();
$shop =  $_SESSION['shop_name'];
$shop_info = $Stores->is_shop_exists($shop);
$orders = $Shopify->get_single_order($shop, $shop_info['access_token'],$_REQUEST['id']);
//echo $_SESSION['select_role'];
if(isset($_POST['submit_barcode'])){
	$get_order_id = $_REQUEST['id'];
	$barcode_sku = $_POST['barcode_sku'];
	//echo $barcode_sku;
	$select_role = $_SESSION['select_role'];
	$select_role = $_POST['select_role'];
	//echo $select_role;
	if($select_role == 'Picker' || $select_role == 'Shipper' || $select_role == 'Receiver' ){
		$selected_role = $select_role." "."ok";
	}
	$arrayobj = new ArrayObject($orders->order->line_items);
	$line_item_count = $arrayobj->count();
	for($i=0;$i<$line_item_count;$i++)
	{
		$variants = $Shopify->get_variants($shop, $shop_info['access_token'],$orders->order->line_items[$i]->variant_id);
		if($variants->variant->sku == $barcode_sku || $variants->variant->barcode == $barcode_sku)
		{
		   if($selected_role == 'Picker ok' || $select_role1 == 'Picker' ){
			$check_order_veri = $Stores->check_order_veri($variants->variant->sku, $_REQUEST['id'],$selected_role);
			if(empty($check_order_veri)){
			$Stores->order_veri($variants->variant->sku,$variants->variant->barcode,$get_order_id,$selected_role);
	         }
	        }
	        if($selected_role == 'Shipper ok' || $select_role1 == 'Shipper' ){
	        	$s_check_order_veri = $Stores->s_check_order_veri($variants->variant->sku, $_REQUEST['id'],$selected_role);
	        	if(empty($s_check_order_veri)){
	        		$Stores->s_order_veri($variants->variant->sku,$variants->variant->barcode,$get_order_id,$selected_role);
	        	}
	        }
	        if($selected_role == 'Receiver ok' || $select_role1 == 'Receiver' ){
	        	$r_check_order_veri = $Stores->r_check_order_veri($variants->variant->sku, $_REQUEST['id'],$selected_role);
	        	if(empty($r_check_order_veri)){
	        		$Stores->r_order_veri($variants->variant->sku,$variants->variant->barcode,$get_order_id,$selected_role);
	        	}
	        }
		}
		
	}
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Scan and Ship</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="../css/bootstrap.min.css" type="text/css">
  <link rel="stylesheet" href="../css/style.css" type="text/css">
  <link rel="stylesheet" href="../font-awesome/css/font-awesome.css" type="text/css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script src="../js/bootstrap.min.js"></script>
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i,800,800i" rel="stylesheet">
</head>
<body>
<form method="post">
<div class="margtop30">
<div class="container">
<div class="row">
<div class="col-sm-12 col-md-5">
 <div class="role2">BARCODE / PRODUCT CODE  <input type="text" name="barcode_sku" class="txt"> <button type="submit" class="serch" name="submit_barcode">
      <span class="glyphicon glyphicon-search"></span>
    </button></div>
 
</div>
<div class="col-sm-12 col-md-7">
<span class="role2">SELECT ROLE : </span>
<span class="radio radio-primary">
 <input type="radio" name="select_role" id="radio1" value="Picker" <?php if($_SESSION['select_role'] == 'Picker' ){ ?> checked <?php  } else { ?> checked <?php  } ?>>
            <label for="radio1">
                PICKER
            </label>
            <input type="radio" name="select_role" id="radio2" value="Shipper" <?php if($_SESSION['select_role'] == 'Shipper' ){ echo "checked"; }?>>
            <label for="radio2">
                SHIPPER
            </label>
            <input type="radio" name="select_role" id="radio3" value="Receiver" <?php if($_SESSION['select_role'] == 'Receiver' ){ echo "checked"; }?>>
            <label for="radio3">
                RECEIVER
            </label>
 
</span>
<div class="right-icon">
<a href="" class="seting-icon">
<i class="fa fa-cog" aria-hidden="true"></i>
</a>
</div>
</div>
<!-- <div class="col-sm-12 col-md-7"> -->
<!-- <div class="role4">SELECT ROLE</div> -->
<!-- <div class="role3"><input type = "radio" value="Picker" name="select_role" class="select_role" <?php if($_SESSION['select_role'] == 'Picker' ){ echo "checked"; } else { echo "checked"; } ?>>Picker</div>
 <div class="role3"><input type = "radio" value="Shipper" name="select_role" class="select_role" <?php if($_SESSION['select_role'] == 'Shipper' ){ echo "checked"; }?>>Shipper</div>
<div class="role3"><input type = "radio" value="Receiver" name="select_role" class="select_role" <?php if($_SESSION['select_role'] == 'Receiver' ){ echo "checked"; }?>>Receiver</div> 
<!-- <div class="right-icon"> -->
<!-- <a href="" class="seting-icon"> -->
<!-- <i class="fa fa-cog" aria-hidden="true"></i> -->
<!-- </a> -->
<!-- </div> -->
<!-- </div> -->
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
    <td><?php echo $orders->order->updated_at; ?></td>
  </tr>
  <?php if($orders->order->note != '' ){ ?>
  <tr>
    <td><strong>Internal Notes</strong></td>
   <td><?php echo $orders->order->note;  ?></td>
  </tr>
  <?php }  else {
  	?>
  <tr>
    <td><strong>Internal Notes</strong></td>
   <td>Order notes will be here! if notes go longer than specific amount they will be hidden until clicked to go into order page.</td>
  </tr>
  <?php 
  }?>
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
    <td width="9%" class="hed">Receiver</td>
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
    
    
    <?php } 
    
    // Picker verification 
       
        if(isset($_POST['submit_barcode'])){ 
    	$barcode_sku = $_POST['barcode_sku'];
    	$get_order_veri_barcode = $Stores->get_order_veri_barcode($variants->variant->barcode, $_REQUEST['id']);
    	$get_order_veri_sku = $Stores->get_order_veri_sku($variants->variant->sku, $_REQUEST['id']);
    	if($get_order_veri_sku['verification']== 'Picker ok' || $get_order_veri_barcode['verification']== 'Picker ok') { ?>
    	<td><div class="green"><a href=""><i class="fa fa-check" aria-hidden="true"></i></a></div></td>
    	<?php } else { ?>
        <td><div class="red"><a href=""><i class="fa fa-check" aria-hidden="true"></i></a></div></td>
        <?php } ?>
        <?php } else { 
        	$get_order_veri_barcode = $Stores->get_order_veri_barcode($variants->variant->barcode, $_REQUEST['id']);
        	$get_order_veri_sku = $Stores->get_order_veri_sku($variants->variant->sku, $_REQUEST['id']);
        	if($get_order_veri_sku['verification']== 'Picker ok' || $get_order_veri_barcode['verification']== 'Picker ok') { ?>
               <td><div class="green"><a href=""><i class="fa fa-check" aria-hidden="true"></i></a></div></td>
        	<?php } else { ?>
               <td><div class="disable"><i class="fa fa-ban" aria-hidden="true"></i></div></td>
        <?php } } ?>
      
      
      
      <?php
      
      // Shipper verification  
      
        if(isset($_POST['submit_barcode'])){ 
    	$barcode_sku = $_POST['barcode_sku'];
    	$s_get_order_veri_barcode = $Stores->s_get_order_veri_barcode($variants->variant->barcode, $_REQUEST['id']);
    	$s_get_order_veri_sku = $Stores->s_get_order_veri_sku($variants->variant->sku, $_REQUEST['id']);
    	if($s_get_order_veri_sku['verification']== 'Shipper ok' || $s_get_order_veri_barcode['verification']== 'Shipper ok') { ?>
    	<td><div class="green"><a href=""><i class="fa fa-check" aria-hidden="true"></i></a></div></td>
    	<?php } else { ?>
        <td><div class="red"><a href=""><i class="fa fa-check" aria-hidden="true"></i></a></div></td>
        <?php } ?>
        <?php } else { 
        	$s_get_order_veri_barcode = $Stores->s_get_order_veri_barcode($variants->variant->barcode, $_REQUEST['id']);
        	$s_get_order_veri_sku = $Stores->s_get_order_veri_sku($variants->variant->sku, $_REQUEST['id']);
        if($s_get_order_veri_sku['verification']== 'Shipper ok' || $s_get_order_veri_barcode['verification']== 'Shipper ok') { ?>
               <td><div class="green"><a href=""><i class="fa fa-check" aria-hidden="true"></i></a></div></td>
        	<?php } else { ?>
               <td><div class="disable"><i class="fa fa-ban" aria-hidden="true"></i></div></td>
        <?php } } ?>
    
    
     <?php
      
      // Receiver verification  
      
        if(isset($_POST['submit_barcode'])){ 
    	$barcode_sku = $_POST['barcode_sku'];
    	$r_get_order_veri_barcode = $Stores->r_get_order_veri_barcode($variants->variant->barcode, $_REQUEST['id']);
    	$r_get_order_veri_sku = $Stores->r_get_order_veri_sku($variants->variant->sku, $_REQUEST['id']);
    	if($r_get_order_veri_sku['verification']== 'Receiver ok' || $r_get_order_veri_barcode['verification']== 'Receiver ok') { ?>
    	<td><div class="green"><a href=""><i class="fa fa-check" aria-hidden="true"></i></a></div></td>
    	<?php } else { ?>
        <td><div class="red"><a href=""><i class="fa fa-check" aria-hidden="true"></i></a></div></td>
        <?php } ?>
        <?php } else { 
        	$r_get_order_veri_barcode = $Stores->r_get_order_veri_barcode($variants->variant->barcode, $_REQUEST['id']);
        	$r_get_order_veri_sku = $Stores->r_get_order_veri_sku($variants->variant->sku, $_REQUEST['id']);
        if($r_get_order_veri_sku['verification']== 'Receiver ok' || $r_get_order_veri_barcode['verification']== 'Receiver ok') { ?>
               <td><div class="green"><a href=""><i class="fa fa-check" aria-hidden="true"></i></a></div></td>
        	<?php } else { ?>
               <td><div class="disable"><i class="fa fa-ban" aria-hidden="true"></i></div></td>
        <?php } } ?>

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
<a class="order" href="/scan-and-ship/app/summary_page.php">BACK TO ORDER LOOKUP</a>
</div>
</div>
</div>
</div>
</form>
<?php include 'footer.php'; ?>